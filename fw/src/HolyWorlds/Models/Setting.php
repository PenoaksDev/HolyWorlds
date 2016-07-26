<?php namespace HolyWorlds\Models;

use Milky\Database\Eloquent\Model;
use Penoaks\Support\Facades\Auth;
use Penoaks\Support\Facades\Config;
use Penoaks\Support\Facades\DB;
use HolyWorlds\Middleware\Permissions;

class Setting extends Model
{
	private static $cachedSettings;

	protected $fillable = ['key', 'global_perm', 'public_perm', 'global_only', 'type', 'enum', 'value'];
	protected $primaryKey = 'key';
	public $timestamps = false;
	public $incrementing = false;

	public static function boot()
	{
		self::$cachedSettings = Config::get( 'holyworlds.settings' );

		Setting::creating( function ( $setting )
		{
			if ( empty( $setting->type ) )
			{
				$setting->type = gettype( $setting ) == 'boolean' ? 1 : 0;
			}

			if ( !array_key_exists( $setting->key, Setting::$cachedSettings ) )
			{
				abort( 500, 'Key does not exist in the settings facade' );
			}
		} );
	}

	public static function get( $key )
	{
		$setting = static::findOrNew( $key );
		$setting->key = $key; // Why is the key not set?
		return $setting;
	}

	public static function getValue( $key, $user = null )
	{
		$setting = static::findOrNew( $key );
		$setting->key = $key; // Why is the key not set?
		return $setting->value( $user );
	}

	public function setEnumAttribute( $enum )
	{
		$this->type = 2;
		$this->attributes['enum'] = implode( '|', $enum );
	}

	public function setDefAttribute( $value )
	{
		if ( $this->global_perm && Permissions::checkPermission( $this->global_perm ) !== false )
		{
			return;
		}
		if ( $this->type == 2 && !in_array( $value, $this->enumValues ) )
		{
			return;
		}
		$this->attributes['def'] = $value;
	}

	public function set( $value, $user = null )
	{
		if ( $this->global_only == 1 )
		{
			if ( is_null( $value ) )
			{
				return false;
			}
			else
			{
				return $this->setDefault( $value, $user );
			}
		}
		if ( is_null( $user ) )
		{
			$user = Auth::user();
		}
		if ( $this->public_perm && Permissions::checkPermission( $this->public_perm ) !== false )
		{
			return false;
		}
		if ( is_null( $value ) )
		{
			DB::table( 'settings_custom' )->where( ['key' => $this->key, 'owner' => $user->id] )->delete();

			return true;
		}
		if ( $this->type == 2 && !in_array( $value, $this->enumValues ) )
		{
			return false;
		}

		$custom = DB::table( 'settings_custom' )->where( ['key' => $this->key] )->limit( 1 )->first();
		if ( is_null( $custom ) )
		{
			DB::table( 'settings_custom' )->insert( ['key' => $this->key, 'owner' => $user->id, 'value' => $value] );
		}
		else
		{
			DB::table( 'settings_custom' )->where( [
				'key' => $this->key,
				'owner' => $user->id
			] )->update( ['value' => $value] );
		}

		return true;
	}

	public function value( $def = null, $user = null )
	{
		if ( $this->global_only == 1 )
		{
			if ( is_null( $def ) )
			{
				return $this->def;
			}
			else
			{
				if ( is_null( $this->def ) )
				{
					$this->setDefault( $def, $user );
				}

				return $def;
			}
		}
		if ( is_null( $user ) )
		{
			$user = Auth::user();
		}
		$custom = DB::table( 'settings_custom' )->where( 'key', $this->key )->limit( 1 )->first();
		if ( is_null( $custom ) )
		{
			if ( is_null( $def ) )
			{
				return $this->def;
			}
			else
			{
				if ( is_null( $this->def ) )
				{
					$this->setDefault( $def, $user );
				}

				return $def;
			}
		}
		else
		{
			return $custom->value;
		}
	}

	public function typeString()
	{
		switch ( $this->type )
		{
			case 0:
				return "string";
			case 1:
				return "boolean";
			case 2:
				return "enum";
		}
	}

	public function getEnumAttribute()
	{
		return explode( '|', $this->enum );
	}

	public function forget( $user = null )
	{
		$this->set( null, $user );
	}

	public function __toString()
	{
		return $this->value();
	}
}
