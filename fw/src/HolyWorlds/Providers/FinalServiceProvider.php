<?php namespace HolyWorlds\Providers;

use HolyWorlds\Models\Setting;
use HolyWorlds\Support\Util;
use Milky\Facades\Config;
use Milky\Providers\ServiceProvider;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class FinalServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->checkSettings();
	}

	public function checkSettings()
	{
		$settings = Config::get('holyworlds.settings', []);

		foreach ( $settings as $key => $values )
		{
			if ( empty( $key ) || is_numeric( $key ) )
				abort( 500, "Setting key can't be empty nor numeric." );

			$setting = Setting::find( $key );
			if ( $setting == null )
			{
				$setting = new Setting;
				$setting->key = $key;
				foreach ( Util::filter( $values, ['title', 'desc', 'global_perm', 'public_perm', 'global_only', 'type', 'enum', 'def'] ) as $option => $value )
					$setting->$option = $value;
				$setting->save();
			}
		}
	}
}
