<?php
namespace Shared\Providers;

use Penoaks\Barebones\ServiceProvider;
use Penoaks\Framework\AliasLoader;
use Penoaks\Support\Facades\Log;
use Shared\Middleware\Permissions;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerAliases();
	}

	/**
	 * Register aliases.
	 *
	 * @return void
	 */
	public function registerAliases()
	{
		$loader = AliasLoader::getInstance();
		$loader->alias('Log', Log::class);
	}
}
