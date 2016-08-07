<?php
namespace App\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use App\Events\Forum\UserCreatingThread;
use App\Events\Forum\UserMarkingNew;
use App\Events\Forum\UserViewingNew;
use App\Events\Forum\UserViewingThread;
use App\Models\Forum\Thread;
use App\Models\Forum\Category;

class ThreadController extends BaseController
{
	/**
	 * @var Thread
	 */
	protected $threads;

	/**
	 * @var Post
	 */
	protected $posts;

	/**
	 * GET: Return an index of new/updated threads for the current user, optionally filtered by category ID.
	 *
	 * @param  Request  $request
	 * @return JsonResponse|Response
	 */
	public function indexNew(Request $request)
	{
		$this->validate($request);

		$threads = Thread::recent();

		if ($request->has('category_id')) {
			$threads = $threads->where('category_id', $request->input('category_id'));
		}

		$threads = $threads->get();

		// If the user is logged in, filter the threads according to read status
		if (auth()->check()) {
			$threads = $threads->filter(function ($thread)
			{
				return $thread->userReadStatus;
			});
		}

		// Filter the threads according to the user's permissions
		$threads = $threads->filter(function ($thread)
		{
			return (!$thread->category->private || Gate::allows('view', $thread->category));
		});

		event(new UserViewingNew($threads));

		return view('forum.thread.index-new', compact('threads'));
	}

	/**
	 * GET: return an index of threads by category ID.
	 *
	 * @param  Request  $request
	 * @return JsonResponse|Response
	 */
	public function index(Request $request)
	{
		$this->validate($request, ['category_id' => ['required']]);

		$threads = Thread::withRequestScopes($request)
			->where('category_id', $request->input('category_id'))
			->get();

		return $this->response($threads);
	}

	/**
	 * PATCH: Mark new/updated threads as read for the current user.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function markNew(Request $request)
	{
		$threads = $this->api('thread.mark-new')->parameters($request->only('category_id'))->patch();

		event(new UserMarkingNew);

		if ($request->has('category_id')) {
			$category = $this->api('category.fetch', $request->input('category_id'))->get();

			if ($category) {
				alert('success', 'categories.marked_read', 0, ['category' => $category->title]);
				return redirect(route('category.show', $category));
			}
		}

		alert('success', 'threads.marked_read');
		return redirect(config('forum.routing.root'));
	}

	/**
	 * GET: Return a thread view.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, Request $request)
	{
		// $thread = $request->input('include_deleted') ? Thread::withTrashed()->find($id) : Thread::find($id);
		$thread = Thread::find($id);

		if (is_null($thread) || !$thread->exists) {
			return "Thread not found";
		}

		if ($thread->trashed()) {
			$this->authorize('delete', $thread);
		}

		if ($thread->category->private) {
			$this->authorize('view', $thread->category);
		}

		event(new UserViewingThread($thread));

		$category = $thread->category;

		$categories = [];
		if (Gate::allows('moveThreadsFrom', $category)) {
			$categories = Category::where( [ 'category_id' => 0, 'enable_threads' => 1 ] )->get();
		}

		return view('forum.thread.show', compact('categories', 'category', 'thread'));
	}

	/**
	 * GET: Return a 'create thread' view.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$category = $this->api('category.fetch', $request->route('category'))->get();

		if (!$category->threadsEnabled) {
			alert('warning', 'categories.threads_disabled');

			return redirect(route('category.show', $category));
		}

		event(new UserCreatingThread($category));

		return view('forum.thread.create', compact('category'));
	}

	/**
	 * POST: Store a new thread.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request)
	{
		$category = $this->api('category.fetch', $request->route('category'))->get();

		if (!$category->threadsEnabled) {
			alert('warning', 'categories.threads_disabled');

			return redirect(route('category.show', $category));
		}

		$thread = [
			'author_id'	 => auth()->user()->getKey(),
			'category_id'   => $category->id,
			'title'		 => $request->input('title'),
			'content'	   => $request->input('content')
		];

		$thread = $this->api('thread.store')->parameters($thread)->post();

		alert('success', 'threads.created');

		return redirect(route('thread.show', $thread));
	}

	/**
	 * PATCH: Update a thread.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request)
	{
		$action = $request->input('action');

		$thread = $this->api("thread.{$action}", $request->route('thread'))->parameters($request->all())->patch();

		alert('success', 'threads.updated', 1);

		return redirect(route('thread.show', $thread));
	}

	/**
	 * DELETE: Delete a thread.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Request $request)
	{
		$this->validate($request, ['action' => 'in:delete,permadelete']);

		$permanent = !config('forum.preferences.soft_deletes') || ($request->input('action') == 'permadelete');

		$parameters = $request->all();

		if ($permanent) {
			$parameters += ['force' => 1];
		}

		$thread = $this->api('thread.delete', $request->route('thread'))->parameters($parameters)->delete();

		alert('success', 'threads.deleted', 1);

		return redirect($permanent ? route('category.show', $thread->category) : route('thread.show', $thread));
	}

	/**
	 * DELETE: Delete threads in bulk.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function bulkDestroy(Request $request)
	{
		$this->validate($request, ['action' => 'in:delete,permadelete']);

		$parameters = $request->all();

		if (!config('forum.preferences.soft_deletes') || ($request->input('action') == 'permadelete')) {
			$parameters += ['force' => 1];
		}

		$threads = $this->api('bulk.thread.delete')->parameters($parameters)->delete();

		return $this->bulkActionResponse($threads, 'threads.deleted');
	}

	/**
	 * PATCH: Update threads in bulk.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function bulkUpdate(Request $request)
	{
		$this->validate($request, ['action' => 'in:restore,move,pin,unpin,lock,unlock']);

		$action = $request->input('action');

		$threads = $this->api("bulk.thread.{$action}")->parameters($request->all())->patch();

		return $this->bulkActionResponse($threads, 'threads.updated');
	}
}
