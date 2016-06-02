<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MessageController extends Controller
{
	public function __construct()
	{
		$this->middleware("perms:org.holyworlds.user");
	}

	public function index()
	{
		return view("messages/index");
	}
}
