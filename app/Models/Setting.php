<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Middleware\Permissions;
use Auth;
use DB;

class Setting extends Model
{
	protected $primaryKey = 'key';

	public $timestamps = false;

	protected $fillable = ['key', 'global_perm', 'public_perm', 'global_only', 'type', 'enum', 'value'];

	public static function boot()
	{
		Setting::creating( function ( $setting )
		{
			if ( empty( $setting->type ) )
				$setting->type = gettype( $setting ) == 'boolean' ? 1 : 0;
		} );
	}

	public static function get($key, $def = null, $user = null)
	{
		return static::findOrNew( $key )->value( $def, $user );
	}

	public function setDefault( $def, $user = null )
	{
		if ( is_null( $user ) )
			$user = Auth::user();
		if ( $this->global_perm && Permissions::checkPermission( $this->global_perm ) !== false )
			return false;
		if ( $this->type == 2 && !in_array( $value, $this->enumValues ) )
			return false;
		$this->def = $def;
		return $this->save();
	}

	public function set( $value, $user = null )
	{
		if ( $this->global_only == 1 )
			if ( is_null( $value ) )
				return false;
			else
				return $this->setDefault( $value, $user );
		if ( is_null( $user ) )
			$user = Auth::user();
		if ( $this->public_perm && Permissions::checkPermission( $this->public_perm ) !== false )
			return false;
		if ( is_null( $value ) )
		{
			DB::table( 'settings_custom' )->where( [ 'key' => $this->key, 'owner' => $user->id ] )->delete();
			return true;
		}
		if ( $this->type == 2 && !in_array( $value, $this->enumValues ) )
			return false;

		$custom = DB::table( 'settings_custom' )->where( [ 'key' => $this->key ] )->limit( 1 )->first();
		if ( is_null( $custom ) )
			DB::table( 'settings_custom' )->insert( [ 'key' => $this->key, 'owner' => $user->id, 'value' => $value ] );
		else
			DB::table( 'settings_custom' )->where( [ 'key' => $this->key, 'owner' => $user->id ] )->update( [ 'value' => $value ] );
		return true;
	}

	public function value( $def = null, $user = null )
	{
		if ( $this->global_only == 1 )
		{
			if ( is_null( $def ) )
				return $this->def;
			else
			{
				if ( is_null( $this->def ) )
					$this->setDefault( $def, $user );
				return $def;
			}
		}
		if ( is_null( $user ) )
			$user = Auth::user();
		$custom = DB::table( 'settings_custom' )->where( 'key', $this->key )->limit( 1 )->first();
		if ( is_null( $custom ) )
			if ( is_null( $def ) )
				return $this->def;
			else
			{
				if ( is_null( $this->def ) )
					$this->setDefault( $def, $user );
				return $def;
			}
		else
			return $custom->value;
	}

	public function type()
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
		return explode('|', $this->enum);
	}

	public function forget( $user = null )
	{
		$this->set( null, $user );
	}
}
