<?php
namespace Shared\Providers;

use Penoaks\Barebones\ServiceProvider;
use Penoaks\Facades\Log;
use Penoaks\Framework\AliasLoader;
use Shared\Middleware\Permissions;
use Shared\Support\Helper;
use Shared\Support\Util;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ProjectServiceProvider extends ServiceProvider
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
		$loader->alias('Permissions', Permissions::class);
		$loader->alias('Helper', Helper::class);
		$loader->alias('Util', Util::class);
		$loader->alias('Logging', Log::class);
	}
}
