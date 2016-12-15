<?php namespace HolyWorlds\Policies;

use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ForumPolicy extends Policy
{
	/**
	 * Defines the permission namespace prefix.
	 * Prefix will be prefixed to the namespace for the below methods.
	 *
	 * @var string
	 */
	protected $prefix = 'holyworlds.forum';

	/**
	 * Permission: Create categories.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="categories.create")
	 */
	public function createCategories( $acct )
	{
		return true;
	}

	/**
	 * Permission: Manage category.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="categories.manage")
	 */
	public function manageCategories( $acct )
	{
		return $this->moveCategories( $acct ) || $this->renameCategories( $acct );
	}

	/**
	 * Permission: Move categories.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="categories.move")
	 */
	public function moveCategories( $acct )
	{
		return true;
	}

	/**
	 * Permission: Rename categories.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="categories.rename")
	 */
	public function renameCategories( $acct )
	{
		return true;
	}

	/**
	 * Permission: View trashed threads.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="posts.trashed.view")
	 */
	public function viewTrashedThreads( $acct )
	{
		return true;
	}

	/**
	 * Permission: View trashed posts.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(namespace="threads.trashed.view")
	 */
	public function viewTrashedPosts( $acct )
	{
		return true;
	}
}
