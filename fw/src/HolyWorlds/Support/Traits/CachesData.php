<?php namespace HolyWorlds\Support\Traits;

use Illuminate\Support\Facades\Cache;
use Penoaks\Facades\Config;
use ReflectionClass;

trait CachesData
{
	/**
	 * Cache a value from the given callback based on the current class name, the given key and the configured cache
	 * lifetime.
	 *
	 * @param  string $key
	 * @param  callable $function
	 * @return string
	 */
	protected function remember( $key, callable $function )
	{
		$class = new ReflectionClass( $this );
		$class = $class->getShortName();

		$lifetimes = Config::get( 'forum.preferences.cache_lifetimes' );
		$lifetime = isset( $lifetimes[$class][$key] ) ? $lifetimes[$class][$key] : $lifetimes['default'];

		return Cache::remember( $class . $this->id . $key, $lifetime, $function );
	}
}
