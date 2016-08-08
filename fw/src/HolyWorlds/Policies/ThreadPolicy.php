<?php namespace HolyWorlds\Policies;

use HolyWorlds\Models\Forum\Thread;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ThreadPolicy extends Policy
{
	protected $prefix = "holyworlds.forum.thread";

	/**
	 * Permission: Delete posts in thread.
	 *
	 * @param  object  $user
	 * @param  Thread  $thread
	 * @return bool
	 *
	 * @PermissionMethod( namespace="delete" )
	 */
	public function deletePosts($user, Thread $thread)
	{
		return true;
	}

	/**
	 * Permission: Rename thread.
	 *
	 * @param  object  $user
	 * @param  Thread  $thread
	 * @return bool
	 *
	 * @PermissionMethod( namespace="rename" )
	 */
	public function rename($user, Thread $thread)
	{
		return $user->getKey() === $thread->author_id;
	}

	/**
	 * Permission: Reply to thread.
	 *
	 * @param  object  $user
	 * @param  Thread  $thread
	 * @return bool
	 *
	 * @PermissionMethod( namespace="reply" )
	 */
	public function reply($user, Thread $thread)
	{
		return !$thread->locked;
	}

	/**
	 * Permission: Delete thread.
	 *
	 * @param  object  $user
	 * @param  Thread  $thread
	 * @return bool
	 *
	 * @PermissionMethod( namespace="delete" )
	 */
	public function delete($user, Thread $thread)
	{
		return Gate::allows('deleteThreads', $thread->category) || $user->getKey() === $thread->author_id;
	}
}
