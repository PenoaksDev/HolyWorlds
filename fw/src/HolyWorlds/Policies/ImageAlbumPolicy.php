<?php namespace HolyWorlds\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use HolyWorlds\Models\ImageAlbum;
use HolyWorlds\Models\User;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ImageAlbumPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine if the given user can add a comment on the given image album.
	 *
	 * @param  User $user
	 * @param  ImageAlbum $album
	 * @return bool
	 */
	public function addComment( User $user, ImageAlbum $album )
	{
		return !$user->isNew;
	}

	/**
	 * Determine if the given user can edit the given image album.
	 *
	 * @param  User $user
	 * @param  ImageAlbum $album
	 * @return bool
	 */
	public function edit( User $user, ImageAlbum $album )
	{
		return $user->can( 'admin' ) || $user->id == $album->user->id;
	}

	/**
	 * Determine if the given user can delete the given image album.
	 *
	 * @param  User $user
	 * @param  ImageAlbum $album
	 * @return bool
	 */
	public function delete( User $user, ImageAlbum $album )
	{
		return $user->can( 'admin' ) || $user->id == $album->user->id;
	}
}
