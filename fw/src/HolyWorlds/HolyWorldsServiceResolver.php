<?php namespace HolyWorlds;

use HolyWorlds\Account\CustomAuth;
use HolyWorlds\Models\Group;
use HolyWorlds\Models\User;
use Milky\Account\Middleware\RedirectIfAuthenticated;
use Milky\Binding\ServiceResolver;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class HolyWorldsServiceResolver extends ServiceResolver
{
	protected $middlewareGuestInstance;
	protected $auth;

	public function __construct()
	{
		$this->auth = new CustomAuth( User::class, Group::class );
	}

	public function middlewareGuest()
	{
		return $this->middlewareGuestInstance ?: $this->middlewareGuestInstance = new RedirectIfAuthenticated();
	}

	public function key()
	{
		return 'holyworlds';
	}
}
