<?php namespace HolyWorlds\Policies;

use HolyWorlds\Models\ImageAlbum;
use HolyWorlds\Models\User;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ImageAlbumPolicy extends Policy
{
	protected $prefix = 'holyworlds.album';

	/**
	 * Determine if the given user can add a comment on the given image album.
	 *
	 * @param  User $user
	 * @param  ImageAlbum $album
	 * @return bool
	 *
	 * @PermissionMethod( namespace="comment" )
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
	 *
	 * @PermissionMethod( namespace="edit" )
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
	 *
	 * @PermissionMethod( namespace="delete" )
	 */
	public function delete( User $user, ImageAlbum $album )
	{
		return $user->can( 'admin' ) || $user->id == $album->user->id;
	}
}
