<?php
namespace Shared\Providers;

use Penoaks\Contracts\Auth\Access\Gate as GateContract;
use Penoaks\Support\Facades\Blade;
use Penoaks\Providers\AuthServiceProvider as ServiceProvider;
use Shared\Auth\CustomUserProvider;
use Shared\Models\User;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		//
	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Foundation\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		$this->registerPolicies($gate);
	}
}
