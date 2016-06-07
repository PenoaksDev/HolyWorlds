<?php
namespace App\Models;

use App\Util;
use Carbon\Carbon;
use App\Models\Traits\UuidAsKey;
use App\Models\Setting;
use Fenos\Notifynder\Notifable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifable, UuidAsKey;

	protected $fillable = ['name', 'email', 'password', 'activated'];
	protected $hidden = ["password", "remember_token", "activation_token"];
	public $incrementing = false;

	public function profile()
	{
		$result = $this->hasOne(UserProfile::class, "id");
		if ( !$result->exists() )
			$result->create([]);
		return $result;
	}

	public function auths()
	{
		return $this->hasMany(UserAuth::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function characters()
	{
		return $this->hasMany(Character::class);
	}

	public function getDisplayNameAttribute()
	{
		if (!is_null($this->profile->family_name)) {
			return "{$this->name} ({$this->profile->family_name})";
		}

		return $this->name;
	}

	public function getMainCharacterAttribute()
	{
		return $this->characters()->where('main', 1)->first();
	}

	public function getIsNewAttribute()
	{
		return $this->hasPermission(Setting::get('default_group', 'sys.user'));
	}

	public function getSlugAttribute()
	{
		return str_slug($this->name, '-');
	}

	public function getProfileUrlAttribute()
	{
		return url("user/{$this->id}-{$this->slug}");
	}

	public function groups()
	{
		$groups = array();
		foreach( $this->inheritance as $heir )
			$groups[] = $heir->group;
		return $groups;
	}

	public function addGroup( $parent )
	{
		$this->inheritance()->create(["parent" => ( $parent instanceof Group ) ? $parent->id : $parent, "type" => 1]);
	}

	public function inheritance()
	{
		return $this->hasMany(GroupInheritance::class, "child");
	}

	public function permissions()
	{
		return $this->hasMany(Permission::class, "name");
	}

	public static function stats()
	{
		$result = self::get();

		$activated = 0;
		$registered = 0;

		foreach ( $result as $user )
		{
			if ( $user->isActivated() )
				$activated++;
			else
				$registered++;
		}

		$stats = [];
		$stats[] = ["label" => "Registered", "data" => $activated];
		$stats[] = ["label" => "Unactivated", "data" => $registered];
		$stats[] = ["label" => "Online", "data" => 0];
		return $stats;
	}

	public function hasPermission( $node )
	{
		$node = strtolower( $node );

		foreach( $this->permissions as $p )
		{
			if ( empty( $p->permission ) )
				continue; // Ignore empty permission nodes

			if ( strtolower( $p->permission ) == $node )
				return true;

			try
			{
				if ( preg_match( "/" . strtolower( $p->permission ) . "/", $node ) )
					return true;
			}
			catch ( Exception $e )
			{
			// Ignore preg_match() exceptions
			}
		}

		// Directly assigned permissions do not match, check with the Groups next

		foreach ( $this->groups() as $group )
		{
			$result = $group->hasPermission( $node );
			if ( $result )
				return true;
		}

		return false;
	}

	public function isActivated()
	{
		return empty( $this->activation_token );
	}

	public function activate()
	{
		if ( $this->isActivated() )
			return;

		$this->activation_token = null;
		$this->activation_updated = new Carbon();
		$this->save();
	}

	public function scopeActivated($query)
	{
		return $query->where("activation_token", null);
	}

	public function scopeForToken($query, $token)
	{
		return $query->where('activation_token', $token);
	}

	public function activationToken()
	{
		if ( $this->isActivated() )
			return null;
		return $this->activation_token;
	}

	public function deactivate()
	{
		if ( !$this->isActivated() )
			return;

		$token = null;
		do
		{
			$token = str_random(32);
		}
		while (static::where("activation_token", $token)->first() instanceof User);

		$this->activation_token = $token;
		$this->activation_updated = new Carbon();
		$this->save();

		return $token;
	}

	public static function boot()
	{
		parent::boot();

		static::creating(function($user)
		{
			// Set a activation token for every new User
			$user->deactivate();
			$user->id = strtolower( Util::rand(2, FALSE, TRUE) ) . Util::rand(3, TRUE, FALSE) . Util::rand(1, FALSE, TRUE);
		});
	}
}
