<?php namespace HolyWorlds\Policies;

use HolyWorlds\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Slynova\Commentable\Models\Comment;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class CommentPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine if the given comment is editable by the given user.
	 *
	 * @param  User $user
	 * @param  Comment $comment
	 * @return bool
	 */
	public function edit( User $user, Comment $comment )
	{
		return $user->can( 'admin' ) || $user->id == $comment->user->id;
	}

	/**
	 * Determine if the given comment is deletable by the given user.
	 *
	 * @param  User $user
	 * @param  Comment $comment
	 * @return bool
	 */
	public function delete( User $user, Comment $comment )
	{
		return $user->can( 'admin' );
	}
}
