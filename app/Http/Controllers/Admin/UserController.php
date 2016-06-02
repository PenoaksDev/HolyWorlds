<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Notification;

class UserController extends Controller
{
	public function index()
	{
		return view('admin.users.index', [
			'users' => User::orderBy('userId', 'desc')->get()
			]);
	}

	public function listGroups( $userId )
	{
		$user = User::find($userId);
		return view('admin.users.groups', ["user" => $user] );
	}

	public function edit( $userId )
	{
		return view('admin.users.edit');
	}

	public function create()
	{
		return view('admin.users.edit');
	}

	public function delete( $userId )
	{
		return view('admin.users.delete');
	}
}