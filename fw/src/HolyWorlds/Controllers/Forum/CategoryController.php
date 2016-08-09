<?php namespace HolyWorlds\Controllers\Forum;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Models\Forum\Category;
use Milky\Account\Permissions\PermissionManager;
use Milky\Facades\Config;
use Milky\Facades\Hooks;
use Milky\Facades\Redirect;
use Milky\Facades\URL;
use Milky\Facades\View;
use Milky\Http\JsonResponse;
use Milky\Http\RedirectResponse;
use Milky\Http\Request;
use Milky\Http\Response;

class CategoryController extends BaseController
{
	/**
	 * GET: Return an index of categories view (the forum index).
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function index( Request $request )
	{
		$categories = Category::where( "category_id", 0 )->orderBy( "weight", "asc" );

		$categories = $categories->get()->filter( function ( $category )
		{
			return PermissionManager::checkPermission( $category->permission );
		} );

		Hooks::trigger( 'app.user.viewing.index' );

		return View::render( 'forum.index', compact( 'categories' ) );
	}

	/**
	 * GET: Return a category view.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function show( $id, Request $request )
	{
		$category = Category::find( $id );

		if ( is_null( $category ) || !$category->exists )
			return View::render( "errors.404" );

		if ( PermissionManager::checkPermission( $category->permission ) )
		{
			Hooks::trigger( 'app.user.viewing.category', compact( 'category' ) );

			$categories = [];
			if ( PermissionManager::i()->has( 'forum.moveCategories' ) )
				$categories = Category::where( "category_id", 0 )->get();

			return View::render( 'forum.category.show', compact( 'categories', 'category', 'threads' ) );
		}

		return $this->error( 403, "You do not have permission to view this thread." ); // TODO Redirect to forum index and display message.
	}

	/**
	 * GET: Return a category by ID.
	 *
	 * @param  int $id
	 * @param  Request $request
	 * @return JsonResponse|Response
	 */
	public function fetch( $id, Request $request )
	{
		$category = Category::find( $id );

		if ( is_null( $category ) || !$category->exists )
			return $this->notFoundResponse();

		if ( $category->private )
			$this->authorize( 'view', $category );

		return $this->response( $category );
	}

	/**
	 * POST: Store a new category.
	 *
	 * @param  Request $request
	 * @return RedirectResponse
	 */
	public function store( Request $request )
	{
		$category = $this->api( 'category.store' )->parameters( $request->all() )->post();

		// alert( 'success', 'categories.created' );

		return Redirect::to( URL::route( 'category.show', $category ) );
	}

	/**
	 * PATCH: Update a category.
	 *
	 * @param  Request $request
	 * @return RedirectResponse
	 */
	public function update( Request $request )
	{
		$action = $request->input( 'action' );

		$category = $this->api( "category.{$action}", $request->route( 'category' ) )->parameters( $request->all() )->patch();

		// alert( 'success', 'categories.updated', 1 );

		return Redirect::to( URL::route( 'category.show', $category ) );
	}

	/**
	 * DELETE: Delete a category.
	 *
	 * @param  Request $request
	 * @return RedirectResponse
	 */
	public function destroy( Request $request )
	{
		$this->api( 'category.delete', $request->route( 'category' ) )->parameters( $request->all() )->delete();

		// alert( 'success', 'categories.deleted', 1 );

		return Redirect::to( Config::get( 'forum.routing.root' ) );
	}
}
