<?php namespace HolyWorlds\Providers;

use HolyWorlds\HolyWorldsServiceResolver;
use Milky\Binding\UniversalBuilder;
use Milky\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		UniversalBuilder::registerResolver( new HolyWorldsServiceResolver() );

		$this->registerAliases();
	}

	/**
	 * Register aliases.
	 *
	 * @return void
	 */
	public function registerAliases()
	{
		/*$loader = AliasLoader::getInstance();
		$loader->alias('Permissions', Permissions::class);
		$loader->alias('Helper', Helper::class);
		$loader->alias('Util', Util::class);
		$loader->alias('Logging', Logging::class);*/
	}

	/**
	 * Boot the service provider
	 */
	public function boot()
	{

	}
}
