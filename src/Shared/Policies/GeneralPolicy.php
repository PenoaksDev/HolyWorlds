<?php
namespace Shared\Policies;

use Penoaks\Auth\Access\HandlesAuthorization;
use Shared\Models\User;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class GeneralPolicy
{
	use HandlesAuthorization;

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
