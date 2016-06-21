<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $fillable = ["id", "name", "description"];
	public $timestamps = false;
	public $incrementing = false;

	public function inheritance()
	{
		// What groups am I a member of?
		return $this->hasMany(GroupInheritance::class, "child");
	}

	public function groups()
	{
		$groups = array();
		foreach( $this->inheritance as $heir )
			$groups[] = $heir->group;
		return $groups;
	}

	public function hasGroups()
	{
		return $this->inheritance()->count() > 0;
	}

	public function addGroup( $parent )
	{
		$parent = ( $parent instanceof Group ) ? $parent->id : $parent;
		if ( $this->id == $parent )
			abort( 500, 'Group can not be a parent of ones self' );
		if ( $this->inheritance()->where("child", "parent")->count() == 0 )
			$this->inheritance()->create(["parent" => $parent, "type" => 0]);
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

	public function hasChild( $child )
	{
		$child = ( $child instanceof Group ) || ( $child instanceof User ) ? $child->id : $child;
		return $this->children()->where("child", $child)->count() > 0;
	}

	public function addChild( $child )
	{
		$child = ( $child instanceof Group ) || ( $child instanceof User ) ? $child->id : $child;
		if ( $this->id == $child )
			abort( 500, 'Group can not be a child of ones self' );
		$this->children()->create(["child" => $child, "type" => 0]);
	}

	public function permissions()
	{
		return $this->hasMany(PermissionAssigned::class, "name");
	}

	public function hasChildren()
	{
		return $this->children()->count() > 0;
	}

	public function children()
	{
		// What groups/users are memebers?
		return $this->hasMany(GroupInheritance::class, "parent");
	}
}
