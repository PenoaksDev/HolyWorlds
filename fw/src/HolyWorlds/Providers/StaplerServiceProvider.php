<?php namespace HolyWorlds\Providers;

use Codesleeve\Stapler\Interfaces\Config as ConfigInterface;
use Codesleeve\Stapler\Stapler;
use Milky\Facades\Config;
use Milky\Framework;
use Milky\Providers\ServiceProvider;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class StaplerServiceProvider extends ServiceProvider
{
	public function __construct()
	{
		Stapler::boot();
		Stapler::setConfigInstance( new ConfigWrapper );
	}
}

class ConfigWrapper implements ConfigInterface
{
	public function get( $name )
	{
		return Config::get( $name );
	}

	public function set( $name, $value )
	{
		Config::set( $name, $value );
	}
}
