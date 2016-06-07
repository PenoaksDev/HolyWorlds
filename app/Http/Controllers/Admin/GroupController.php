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
		return view('admin.groups.show', compact('group') );
	}

	public function ajax( Request $request )
	{
		if ( $request->has('save') )
		{
			return $request->input('save');
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
			return ["error" => null, "command" => "successful"];
		}

		$data = [];
		foreach ( $group->groups() as $member )
		{
			if ( in_array( $member->id, $nodePath ) )
				$data[] = ["label" => $member->name . " ***illegal recursive loop***", "id" => implode( "/", $nodePath ) . "/" . $member->id, "load_on_demand" => false];
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
		return view('admin.users.edit');
	}

	public function delete( $id )
	{
		return view('admin.users.delete');
	}
}
