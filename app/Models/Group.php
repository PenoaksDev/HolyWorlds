<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $fillable = ["id", "displayName"];
	public $timestamps = false;
	public $incrementing = false;

	public function groups()
	{
		$groups = array();
		foreach( $this->inheritance as $heir )
			$groups[] = $heir->group;
		return $groups;
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

	public function inheritance()
	{
		return $this->hasMany(GroupInheritance::class, "child");
	}

	public function permissions()
	{
		return $this->hasMany(Permission::class, "name");
	}

	public function children()
	{
		return $this->hasMany(GroupInheritance::class, "parent");
	}
}