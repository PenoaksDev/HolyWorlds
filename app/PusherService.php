<?php
namespace App;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class PusherService implements WampServerInterface
{
	protected $subscribedTopics = array();

	public function onSubscribe(ConnectionInterface $conn, $topic)
	{
		$this->subscribedTopics[$topic->getId()] = $topic;
	}

	public function onUnSubscribe(ConnectionInterface $conn, $topic)
	{

	}

	public function onOpen(ConnectionInterface $conn)
	{
		echo("Connection Open");
	}

	public function onClose(ConnectionInterface $conn)
	{

	}

	public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
	{
		$conn->callError($id, $topic, 'You are not allowed to make calls')->close();
	}

	public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
	{
		$conn->close();
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{

	}

	/**
	* @param string JSON'ified string we'll receive from ZeroMQ
	*/
	public function onMessage($entry)
	{
		echo "Got message: " . $entry;

		$entryData = json_decode($entry, true);

		// If the lookup topic object isn't set there is no one to publish to
		if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
			return;
		}

		$topic = $this->subscribedTopics[$entryData['category']];

		// re-send the data to all the clients subscribed to that category
		$topic->broadcast($entryData);
	}
}