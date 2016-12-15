<?php namespace HolyWorlds\Models;

use HolyWorlds\Support\Traits\Commentable;
use Milky\Account\Models\User as BaseUser;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class User extends BaseUser
{
	use Commentable;

	public function auths()
	{
		return $this->hasMany( UserAuth::class );
	}

	public function profile()
	{
		$result = $this->hasOne( UserProfile::class, "id" );
		if ( !$result->exists() )
			$result->create( [] );

		return $result;
	}
}
