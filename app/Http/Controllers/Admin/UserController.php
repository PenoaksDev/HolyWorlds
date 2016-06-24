<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Notification;

class UserController extends Controller
{
	public function index()
	{
		return view('admin.users.index', ['users' => User::orderBy('id', 'desc')->get()]);
	}

	public function listGroups( $id )
	{
		$user = User::find($id);
		return view('admin.users.groups', ["user" => $user] );
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
