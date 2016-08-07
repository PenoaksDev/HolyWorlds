<?php namespace HolyWorlds\Commands;

use Penoaks\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use HolyWorlds\Support\PushService;
use ZMQ;

class PushServer extends Command
{
	protected $signature = 'run:pushServer';

	protected $description = 'Starts the Holy Worlds Websocket Server';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		ini_set( 'memory_limit', '4G' );
		set_time_limit( 0 );

		$loop = Factory::create();
		$pusher = new PushService;

		// Listen for the web server to make a ZeroMQ push
		$context = new Context( $loop );
		$pull = $context->getSocket( ZMQ::SOCKET_PULL );
		$pull->bind( 'tcp://127.0.0.1:5555' );
		$pull->on( 'message', [$pusher, 'onMessage'] );

		// Set up our WebSocket server for clients wanting real-time updates
		$webSock = new Server( $loop );
		$webSock->listen( 6564, '127.0.0.1' );
		$webServer = new IoServer( new HttpServer( new WsServer( new WampServer( $pusher ) ) ), $webSock );
		$loop->run();
	}
}
