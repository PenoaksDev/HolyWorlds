<?php namespace HolyWorlds\Policies\Forum;

use Penoaks\Support\Facades\Gate;
use HolyWorlds\Models\Forum\Post;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class PostPolicy extends \Shared\Policies\PostPolicy
{
	/**
	 * Permission: Edit post.
	 *
	 * @param  object $user
	 * @param  Post $post
	 * @return bool
	 */
	public function edit( $user, Post $post )
	{
		return $user->id == $post->author_id;
	}

	/**
	 * Permission: Delete post.
	 *
	 * @param  object $user
	 * @param  Post $post
	 * @return bool
	 */
	public function delete( $user, Post $post )
	{
		return Gate::forUser( $user )->allows( 'deletePosts', $post->thread );
	}
}
