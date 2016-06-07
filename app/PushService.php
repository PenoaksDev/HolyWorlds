<?php
namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class PushService implements WampServerInterface
{
	protected $subscribed = array();

	/**
	 * On client subscribe to a specified channel of communication
	 */
	public function onSubscribe(ConnectionInterface $conn, $channel)
	{
		$this->subscribed[ $channel->getId() ] = $channel;
	}

	/**
	 * On client unsubscribe from a specified channel of communication
	 */
	public function onUnSubscribe(ConnectionInterface $conn, $channel)
	{
		
	}

	public function onOpen(ConnectionInterface $conn)
	{
	}

	public function onClose(ConnectionInterface $conn)
	{

	}

	public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
	{
		// $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
	}

	public function onPublish(ConnectionInterface $conn, $channel, $event, array $exclude, array $eligible)
	{
		if ( $channel->getId() == "chatPublic" )
			$this->broadcast( $channel->getId(), $event["msg"] );
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		broadcast( "dev", "Exception has been encountered on the Push Server: " . $e->getMessage() );
	}

	public function broadcast( $channel, $data )
	{
		echo "Broadcasting '" . $data . "' on channel '" . $channel . "'\n";

		if ( !array_key_exists( $channel, $this->subscribed ) )
			return;

		$channel = $this->subscribed[ $channel ];
		$channel->broadcast( $data );	
		$channel->broadcast('test');
	}

	/**
	 * Handle incoming messages from the ZeroMQ channel, i.e., internal API and AJAX requests.
	 */
	public function onMessage($e)
	{
		$d = json_decode($e, true);

		if ( !empty( $d["action"] ) )
		{
			if ( $d["action"] == "exit" )
				exit(1);
		}
		else
		{
			$this->broadcast( $d["channel"], $d["data"] . 'test');
		}
	}
}