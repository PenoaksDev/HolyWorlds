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
		$settings = Config::get('holyworlds.settings');

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
