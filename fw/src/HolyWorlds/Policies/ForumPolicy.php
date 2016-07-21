<?php
namespace HolyWorlds\Policies;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ForumPolicy
{
	/**
	 * Permission: Create categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function createCategories( $user )
	{
		return true;
	}

	/**
	 * Permission: Manage category.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function manageCategories( $user )
	{
		return $this->moveCategories( $user ) || $this->renameCategories( $user );
	}

	/**
	 * Permission: Move categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function moveCategories( $user )
	{
		return true;
	}

	/**
	 * Permission: Rename categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function renameCategories( $user )
	{
		return true;
	}

	/**
	 * Permission: Mark new/updated threads as read.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function markNewThreadsAsRead( $user )
	{
		return true;
	}

	/**
	 * Permission: View trashed threads.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function viewTrashedThreads( $user )
	{
		return true;
	}

	/**
	 * Permission: View trashed posts.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function viewTrashedPosts( $user )
	{
		return true;
	}
}
