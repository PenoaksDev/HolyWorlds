<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App\Http\Middleware\Permissions;
use App\Models\Setting;
use App\Helper;
use App\Util;
use Config;
use Log;

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
		$loader->alias('Permissions', Permissions::class);
		$loader->alias('Helper', Helper::class);
		$loader->alias('Util', Util::class);
		$loader->alias('Log', Log::class);
	}
}
