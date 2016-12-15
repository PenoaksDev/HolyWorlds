<?php namespace HolyWorlds\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use HolyWorlds\Models\User;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class GeneralPolicy extends Policy
{
	protected $prefix = "holyworlds";

	/**
	 * Determine if the given user is allowed to create image albums.
	 *
	 * @param  User $user
	 * @return bool
	 */
	public function createImageAlbums( User $user )
	{
		return !$user->isNew;
	}

	/**
	 * Determine if the given user is allowed to create characters.
	 *
	 * @param  User $user
	 * @return bool
	 */
	public function createCharacters( User $user )
	{
		return !$user->isNew;
	}

	/**
	 * Determine if the given user is allowed to view characters.
	 *
	 * @param  User $user
	 * @return bool
	 */
	public function viewCharacters( User $user )
	{
		return !$user->isNew;
	}
}
