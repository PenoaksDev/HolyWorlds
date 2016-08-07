<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Notification;

class GroupController extends Controller
{
	public function index()
	{
		return view('admin.groups.index', ['groups' => Group::orderBy('id', 'desc')->get()]);
	}

	public function show( $id )
	{
		$group = Group::find( $id );
		return view('admin.groups.inheritance', compact('group') );
	}

	public function inheritance( $id )
	{
		$group = Group::find( $id );
		return view('admin.groups.inheritance', compact('group') );
	}

	protected function recursiveSave( $data )
	{
		$existing = [];

		foreach ( $data as $e )
		{
			$nodes = explode("/", $e['id']);

			if ( count( $nodes ) < 2 )
				abort( 500, "Invalid node path." );

			// Handle the group inheritances
			$parent = $nodes[count( $nodes ) - 1]; // staff
			$child = $nodes[count( $nodes ) - 2]; // moderator

			// TODO Ignore recursive loop warning messages
			if ( !array_key_exists( $child, $existing ) )
			{
				// On first encounter, collect the current batch of parents/inheritances.
				$existing[$child] = [];
				foreach ( Group::find( $child )->inheritance as $i )
					$existing[$child][] = $i->parent;
			}

			// If does not exist, insert new. Otherwise, remove from array because it needs to exist.
			$key = array_search( $parent, $existing[$child] );
			if ( $key === false )
				Group::find( $child )->addGroup( $parent );
			else
				unset( $existing[$child][$key] );

			// Handle children elements
			if ( array_key_exists( 'is_open', $e ) && $e['is_open'] )
			{
				// Tree element was opened, this means there could be groups. None if children does not exists.

				if ( array_key_exists( 'children', $e ) )
					$this->recursiveSave( $e['children'] );
				else
				{
					Group::find( $parent )->inheritance()->delete();
					// No children exists -- remove all
				}
			}
		}

		// Remove extra groups
		foreach ( $existing as $existingChild => $existingGroups )
			foreach ( $existingGroups as $existingGroup )
				Group::find( $existingChild )->inheritance()->where("parent", $existingGroup)->delete();
	}

	public function ajax( Request $request )
	{
		if ( $request->has('save') )
		{
			$data = json_decode( $request->input('save'), true );
			$this->recursiveSave( $data );
			return ["error" => null, "response" => "successful"];
		}

		if ( !$request->has('node') )
			return $this->error( 404, "Missing arguments" );

		$nodePath = explode( "/", $request->input('node') );

		$group = Group::find( $nodePath[count($nodePath) - 1] );

		if ( $group == null )
			return $this->error();

		if ( $request->has('add') )
		{
			$child = Group::find( $request->input('add') );

			if ( $child == null )
				return $this->error( 500, "Group not found" );

			// TODO Improve the add logic by doing some prechecks.
			$group->addGroup( $child );
			return ["error" => null, "response" => "successful"];
		}

		$data = [];
		foreach ( $group->groups() as $member )
		{
			if ( in_array( $member->id, $nodePath ) )
				$data[] = ["label" => $member->name . " ***illegal recursive leak***", "id" => implode( "/", $nodePath ) . "/" . $member->id, "load_on_demand" => false];
			else
				$data[] = ["label" => $member->name, "id" => implode( "/", $nodePath ) . "/" . $member->id, "load_on_demand" => $member->hasGroups()];
		}

		return $data;
	}

	public function edit( $id )
	{
		return view('admin.users.edit');
	}

	public function create()
	{
		return view('admin.groups.create');
	}

	public function delete( $id )
	{
		return view('admin.users.delete');
	}
}
