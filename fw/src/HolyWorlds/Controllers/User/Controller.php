<?php

/*
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

namespace HolyWorlds\Controllers\User;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Models\User;
use Milky\Facades\View;
use Milky\Http\Response;

class Controller extends BaseController
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

		return View::render( 'user.show', compact( 'user' ) + [
				'commentPaginator' => $user->profile->comments()->orderBy( 'created_at', 'desc' )->paginate()
			] );
	}
}
