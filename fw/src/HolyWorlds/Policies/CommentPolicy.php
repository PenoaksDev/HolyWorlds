<?php namespace HolyWorlds\Policies;

use HolyWorlds\Models\User;
use HolyWorlds\Support\Models\Comment;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class CommentPolicy extends Policy
{
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
