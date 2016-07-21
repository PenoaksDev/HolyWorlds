<?php
namespace HolyWorlds\Http\Controllers\Dev;

use React\ZMQ\Context;

class DevController extends \App\Http\Controllers\Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function restartPushServer()
    {
    	echo ("<pre>");

    	$pid = exec( "ps -eo pid,command | grep php.*/bin/PushServer\.php | grep -v grep | awk '{print $1}'" );
    	if ( !empty( $pid ) )
    	{
    		echo ( "Sending exit() commmand to running Push Server. (PID " . $pid . ")\n" );
    	
	    	$context = new \ZMQContext();
			$socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'push service');
			$socket->connect("tcp://localhost:5555");

			$socket->send(json_encode(array("action" => "exit")));
		}
		else
			echo ( "There is currently no running instances of Push Server.\n" );

		echo ( "Starting a new instance of the Push Server.\n\n" );
		exec("bash -c 'php -f " . base_path() . "/bin/PushServer.php' > /dev/null 2>&1 &");

		$pid = exec( "ps -eo pid,command | grep php.*/bin/PushServer\.php | grep -v grep | awk '{print $1}'" );
		echo ( "Holy Worlds Push Server has been restarted successfully! (PID " . $pid . ")" );

		echo ("</pre>");

		die();
    }

    public function broadcast()
    {
		$context = new \ZMQContext();
		$socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'push service');
		$socket->connect("tcp://localhost:5555");

		$socket->send(json_encode(array("channel" => "chatPublic", "data" => "Hello World!")));
    }
}
