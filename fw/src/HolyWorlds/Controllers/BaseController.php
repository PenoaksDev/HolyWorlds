<?php namespace HolyWorlds\Controllers;

use Milky\Auth\Access\AuthorizesRequests;
use Milky\Auth\Access\AuthorizesResources;
use Milky\Bus\DispatchesJobs;
use Milky\Database\Eloquent\Collection;
use Milky\Facades\Redirect;
use Milky\Http\RedirectResponse;
use Milky\Http\Request;
use Milky\Http\Routing\Controller;
use Milky\Validation\ValidatesRequests;

class BaseController extends Controller
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

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
	 * Helper: Bulk action response.
	 *
	 * @param  Collection $models
	 * @param  string $transKey
	 * @return RedirectResponse
	 */
	protected function bulkActionResponse( Collection $models, $transKey )
	{
		if ( $models->count() )
			alert( 'success', $transKey, $models->count() );
		else
			alert( 'warning', 'general.invalid_selection' );

		return Redirect::back();
	}

	/**
	 * Validate the given request with the given rules.
	 *
	 * @param  Request $request
	 * @param  array $rules
	 * @param  array $messages
	 * @param  array $customAttributes
	 * @return void
	 */
	public function validate( Request $request, array $rules = [], array $messages = [], array $customAttributes = [] )
	{
		$rules = array_merge_recursive( $this->baseRules, $rules );

		$validator = $this->getValidationFactory()->make( $request->all(), $rules, $messages, $customAttributes );

		if ( $validator->fails() )
			$this->throwValidationException( $request, $validator );
	}
}
