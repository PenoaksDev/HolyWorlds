<?php
namespace Shared\Policies\Forum;

use Penoaks\Support\Facades\Gate;
use Shared\Models\Forum\Thread;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ThreadPolicy extends \Shared\Policies\ThreadPolicy
{
	/**
	 * Permission: Delete posts in thread.
	 *
	 * @param  object $user
	 * @param  Thread $thread
	 * @return bool
	 */
	public function deletePosts( $user, Thread $thread )
	{
		return $user->can( 'admin' );
	}

	/**
	 * Permission: Rename thread.
	 *
	 * @param  object $user
	 * @param  Thread $thread
	 * @return bool
	 */
	public function rename( $user, Thread $thread )
	{
		return $user->can( 'admin' );
	}

	/**
	 * Permission: Reply to thread.
	 *
	 * @param  object $user
	 * @param  Thread $thread
	 * @return bool
	 */
	public function reply( $user, Thread $thread )
	{
		return !$user->isNew;
	}

	/**
	 * Permission: Delete thread.
	 *
	 * @param  object $user
	 * @param  Thread $thread
	 * @return bool
	 */
	public function delete( $user, Thread $thread )
	{
		return Gate::allows( 'deleteThreads', $thread->category );
	}
}
