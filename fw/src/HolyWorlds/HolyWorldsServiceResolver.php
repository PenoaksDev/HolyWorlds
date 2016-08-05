<?php namespace HolyWorlds;

use Milky\Binding\Resolvers\ServiceResolver;

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

	public function __construct()
	{

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
