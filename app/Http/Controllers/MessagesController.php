<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsgChannel;
use App\Http\Requests;

class MessagesController extends Controller
{
	public function __construct()
	{
		$this->middleware("perms:sys.user");
	}

	public function index()
	{
		return view("messages.channel.show", ['channel' => MsgChannel::find('public')]);
	}

	public function show( $id )
	{
		$channel = MsgChannel::find( $id );
		if ( !$channel )
			return $this->error();
		return view("messages.private.show", compact('channel'));
	}

	public function channelShow( $id )
	{
		$channel = MsgChannel::find( $id );
		if ( !$channel )
			return $this->error();
		return view("messages.channel.show", compact('channel'));
	}

	public function chatroom() {
		return view('messages/chatroom', [
			'floatingChat' => 'none'
		]);
	}
}
