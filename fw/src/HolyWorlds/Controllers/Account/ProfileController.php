<?php namespace HolyWorlds\Controllers\Account;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Models\User;
use Milky\Facades\View;
use Milky\Http\Response;

class ProfileController extends BaseController
{
	/**
	 * Show a user profile by user ID.
	 *
	 * @param  int $id
	 * @param  string $name
	 * @return Response
	 */
	public function show( $id, $name )
	{
		$user = User::findOrFail( $id );

		return View::render( 'user.profile.show', compact( 'user' ) + [
				'commentPaginator' => $user->profile->comments()->orderBy( 'created_at', 'desc' )->paginate()
			] );
	}
}
