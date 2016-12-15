<?php namespace HolyWorlds\Policies;

use Milky\Account\Permissions\Policy;
use HolyWorlds\Models\Forum\Post;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class PostPolicy extends Policy
{
	protected $prefix = "holyworlds.forum.post";

	/**
	 * Permission: Edit post.
	 *
	 * @param  object $user
	 * @param  Post $post
	 * @return bool
	 *
	 * @PermissionMethod( namespace="edit" )
	 */
	public function edit( $user, Post $post )
	{
		return $user->getKey() === $post->author_id;
	}

	/**
	 * Permission: Delete post.
	 *
	 * @param  object $user
	 * @param  Post $post
	 * @return bool
	 *
	 * @PermissionMethod( namespace="delete" )
	 */
	public function delete( $user, Post $post )
	{
		return Gate::forUser( $user )->allows( 'deletePosts', $post->thread ) || $user->getKey() === $post->user_id;
	}
}
