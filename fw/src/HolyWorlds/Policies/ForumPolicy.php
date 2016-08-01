<?php namespace HolyWorlds\Policies;

use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ForumPolicy extends Policy
{
	protected $prefix = 'holyworlds.forum';

	/**
	 * Defines the permission checking methods.
	 * $this->prefix + arrayKey = arrayValue
	 * e.g., com.example + . + users.edit = editUsers()
	 *
	 * @var array
	 */
	protected $nodes = [
		'categories.create' => 'createCategories',
		'categories.manage' => 'manageCategories',
		'categories.move' => 'moveCategories',
		'categories.rename' => 'renameCategories',
		'posts.trashed.view' => 'viewTrashedPosts',
		'threads.trashed.view' => 'viewTrashedThreads',
	];

	/**
	 * Permission: Create categories.
	 *
	 * @param  object $acct
	 * @return bool
	 *
	 * @PermissionMethod(prefix="categories.create")
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
	 * @PermissionMethod(prefix="categories.manage")
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
	 * @PermissionMethod(prefix="categories.move")
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
	 * @PermissionMethod(prefix="categories.rename")
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
	 * @PermissionMethod(prefix="posts.trashed.view")
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
	 * @PermissionMethod(prefix="threads.trashed.view")
	 */
	public function viewTrashedPosts( $acct )
	{
		return true;
	}
}
