<?php

/*
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

namespace HolyWorlds\Controllers\Group;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Models\Group;
use Milky\Facades\View;
use Milky\Http\Response;

class Controller extends BaseController
{
	/**
	 * Show a group by group ID.
	 *
	 * @param  int $id
	 * @param  string $name
	 * @return Response
	 */
	public function show( $id, $name )
	{
		$group = Group::findOrFail( $id );

		return View::render( 'group.show', compact( 'group' ) );
	}
}
