<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App\Http\Middleware\Permissions;
use App\Helper;
use App\Util;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

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
	}
}
