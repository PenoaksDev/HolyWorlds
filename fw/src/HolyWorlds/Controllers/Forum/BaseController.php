<?php namespace HolyWorlds\Http\Controllers\Forum;

use Illuminate\Auth\Access\AuthorizesRequests;
use Penoaks\Validation\ValidatesRequests;
use Penoaks\Http\Exception\HttpResponseException;
use Penoaks\Http\Request;
use Penoaks\Http\Response;
use Penoaks\Support\Collection;
use Contracts\API\ReceiverContract;
use Http\Controllers\Controller;

abstract class BaseController extends Controller
{
	private $baseRules = [
		'author_id' => ['integer'],
		'enable_threads' => ['boolean'],
		'category_id' => ['integer'],
		'content' => ['min:5'],
		'locked' => ['boolean'],
		'pinned' => ['boolean'],
		'private' => ['boolean'],
		'description' => ['string'],
		'thread_id' => ['integer', 'exists:forum_threads,id'],
		'title' => ['string', 'min:5'],
		'weight' => ['integer'],
	];

	/**
	 * Create a frontend controller instance.
	 */
	public function __construct()
	{

	}

	/**
	 * Helper: Bulk action response.
	 *
	 * @param  Collection  $models
	 * @param  string  $transKey
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function bulkActionResponse(Collection $models, $transKey)
	{
		if ($models->count())
		{
			alert('success', $transKey, $models->count());
		}
		else
		{
			alert('warning', 'general.invalid_selection');
		}

		return redirect()->back();
	}

	/**
	 * Validate the given request with the given rules.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  array  $rules
	 * @param  array  $messages
	 * @param  array  $customAttributes
	 * @return void
	 *
	 * @throws \Illuminate\Http\Exception\HttpResponseException
	 */
	public function validate(Request $request, array $rules = [], array $messages = [], array $customAttributes = [])
	{
		$rules = array_merge_recursive( $this->baseRules, $rules );

		$validator = $this->getValidationFactory()->make( $request->all(), $rules, $messages, $customAttributes );

		if ( $validator->fails() )
		{
			$this->throwValidationException( $request, $validator );
		}
	}
}
