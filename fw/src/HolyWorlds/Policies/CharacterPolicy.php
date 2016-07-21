<?php
namespace HolyWorlds\Policies;

use HolyWorlds\Models\User;
use HolyWorlds\Models\Character;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class CharacterPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine if the given user can add a comment on the given character.
	 *
	 * @param  User $user
	 * @param  Character $character
	 * @return bool
	 */
	public function addComment( User $user, Character $character )
	{
		return !$user->isNew;
	}

	/**
	 * Determine if the given user can edit the given character.
	 *
	 * @param  User $user
	 * @param  Character $character
	 * @return bool
	 */
	public function edit( User $user, Character $character )
	{
		return $user->can( 'admin' ) || $user->id == $character->user->id;
	}

	/**
	 * Determine if the given user can delete the given image album.
	 *
	 * @param  User $user
	 * @param  Character $character
	 * @return bool
	 */
	public function delete( User $user, Character $character )
	{
		return $user->can( 'admin' ) || $user->id == $character->user->id;
	}
}
