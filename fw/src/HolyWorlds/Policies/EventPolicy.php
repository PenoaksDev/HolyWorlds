<?php
namespace HolyWorlds\Policies;

use HolyWorlds\Models\Event;
use HolyWorlds\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class EventPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine if the given user can add a comment on the given event.
	 *
	 * @param  User  $user
	 * @param  Event  $event
	 * @return bool
	 */
	public function addComment(User $user, Event $event)
	{
		return !$user->isNew;
	}

	/**
	 * Determine if the given user can view the given event.
	 *
	 * @param  User  $user
	 * @param  Event  $event
	 * @return bool
	 */
	public function view(User $user, Event $event)
	{
		return !$user->isNew;
	}
}
