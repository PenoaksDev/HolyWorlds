<?php
namespace HolyWorlds\Policies\Forum;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ForumPolicy extends \Shared\Policies\ForumPolicy
{
	/**
	 * Permission: Create categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function createCategories( $user )
	{
		return $user->can( 'admin' );
	}

	/**
	 * Permission: Move categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function moveCategories( $user )
	{
		return $user->can( 'admin' );
	}

	/**
	 * Permission: Rename categories.
	 *
	 * @param  object $user
	 * @return bool
	 */
	public function renameCategories( $user )
	{
		return $user->can( 'admin' );
	}
}
