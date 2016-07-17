<?php
namespace Shared\Http\Controllers;

use Penoaks\Http\Request;
use Models\MsgChannel;
use Http\Requests;

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
