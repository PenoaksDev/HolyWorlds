<?php namespace HolyWorlds\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use HolyWorlds\Models\User;
use HolyWorlds\Models\UserProfile;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class UserProfilePolicy extends Policy
{
	protected $prefix = 'holyworlds.user.profile';

	/**
	 * Determine if the given user can add a comment on the given user profile.
	 *
	 * @param  User $user
	 * @param  UserProfile $profile
	 * @return bool
	 *
	 * @PermissionMethod(namespace="comment.add")
	 */
	public function addComment( User $user, UserProfile $profile )
	{
		return !$user->isNew;
	}
}
