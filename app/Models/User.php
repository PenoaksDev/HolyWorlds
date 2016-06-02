<?php namespace App\Models;

use App\Models\Setting;
use Fenos\Notifynder\Notifable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifable;

	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
	'name', 'email', 'password', 'activated',
	];

	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
	'password', 'remember_token',
	];

	protected $primaryKey = "userId";

	/**
	* Relationship: profile
	*
	* @return \Illuminate\Database\Eloquent\Relations\HasOne
	*/
	public function profile()
	{
		return $this->hasOne(UserProfile::class);
	}

	/**
	* Relationship: socialite auths
	*
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function auths()
	{
		return $this->hasMany(UserAuth::class);
	}

	/**
	* Relationship: comments
	*
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	/**
	* Relationship: characters
	*
	* @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function characters()
	{
		return $this->hasMany(Character::class);
	}

	/**
	* Scope: activated
	*
	* @param  \Illuminate\Database\Query\Builder
	* @return \Illuminate\Database\Query\Builder
	*/
	public function scopeActivated($query)
	{
		return $query->where('activated', 1);
	}

	/**
	* Attribute: display name
	*
	* @return string
	*/
	public function getDisplayNameAttribute()
	{
		if (!is_null($this->profile->family_name)) {
			return "{$this->name} ({$this->profile->family_name})";
		}

		return $this->name;
	}

	/**
	* Attribute: main character
	*
	* @return string
	*/
	public function getMainCharacterAttribute()
	{
		return $this->characters()->where('main', 1)->first();
	}

	/**
	* Attribute: determine if the user is considered to be new
	*
	* @return bool
	*/
	public function getIsNewAttribute()
	{
		return $this->hasPermission(Setting::get('default_group', 'sys.user'));
	}

	/**
	* Attribute: slugified name
	*
	* @return string
	*/
	public function getSlugAttribute()
	{
		return str_slug($this->name, '-');
	}

	/**
	* Attribute: profile URL
	*
	* @return string
	*/
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

	public function inheritance()
	{
		return $this->hasMany(GroupInheritance::class, "child", "userId");
	}

	public function permissions()
	{
		return $this->hasMany(Permission::class, "name", "userId");
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

	/**
	* Helper: activate the user
	*
	* @return void
	*/
	public function activate()
	{
		if ($this->activated) {
			return;
		}

		$this->activated = 1;
		$this->save();
	}

	/**
	* Helper: deactivate the user
	*
	* @return void
	*/
	public function deactivate()
	{
		if (!$this->activated) {
			return;
		}

		$this->activated = 0;
		$this->save();
	}
}
