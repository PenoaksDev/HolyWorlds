<?php
namespace HolyWorlds\Http\Controllers\Forum;

use Penoaks\Http\Request;
use Penoaks\Support\Facades\Gate;
use Events\Forum\UserViewingCategory;
use Events\Forum\UserViewingIndex;
use Models\Forum\Category;
use Http\Middleware\Permissions;
use Auth;

class CategoryController extends BaseController
{
	/**
	 * GET: Return an index of categories view (the forum index).
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$categories = Category::where("category_id", 0)->orderBy("weight", "asc");

		$categories = $categories->get()->filter(function ($category)
		{
			return Permissions::checkPermission( $category->permission );
		});

		event( new UserViewingIndex );

		return view( 'forum.index', compact('categories') );
	}

	/**
	 * GET: Return a category view.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, Request $request)
	{
		$category = Category::find( $id );

		if (is_null($category) || !$category->exists)
		{
			return view("errors.404");
		}

		if ( Permissions::checkPermission( $category->permission ) )
		{
			event( new UserViewingCategory( $category ) );

			$categories = [];
			if (Gate::allows('moveCategories')) {
				$categories = Category::where("category_id", 0)->get();
			}

			return view('forum.category.show', compact('categories', 'category', 'threads'));
		}
		return "You do not have permission to view this thread."; // Redirect to forum index and display message.
	}

	/**
	 * GET: Return a category by ID.
	 *
	 * @param  int  $id
	 * @param  Request  $request
	 * @return JsonResponse|Response
	 */
	public function fetch($id, Request $request)
	{
		$category = Category::find($id);

		if (is_null($category) || !$category->exists) {
			return $this->notFoundResponse();
		}

		if ($category->private) {
			$this->authorize('view', $category);
		}

		return $this->response($category);
	}

	/**
	 * POST: Store a new category.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request)
	{
		$category = $this->api('category.store')->parameters($request->all())->post();

		Forum::alert('success', 'categories.created');

		return redirect(route('category.show', $category));
	}

	/**
	 * PATCH: Update a category.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request)
	{
		$action = $request->input('action');

		$category = $this->api("category.{$action}", $request->route('category'))->parameters($request->all())->patch();

		alert( 'success', 'categories.updated', 1 );

		return redirect( route( 'category.show', $category ) );
	}

	/**
	 * DELETE: Delete a category.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Request $request)
	{
		$this->api('category.delete', $request->route('category'))->parameters($request->all())->delete();

		Forum::alert('success', 'categories.deleted', 1);

		return redirect(config('forum.routing.root'));
	}
}
