<?php
namespace Shared\Support;

use App;
use Auth;
use Config;
use Crypt;
use Carbon\Carbon;
use Models\User;
use Penoaks\Session\SessionManager;
use Penoaks\View\View;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class PushService implements WampServerInterface
{
	protected $subscribed = [];
	protected $cookie_name;

	public function __construct()
	{
		$this->cookie_name = Config::get('session.cookie');
	}

	public function onSubscribe(ConnectionInterface $conn, $channel)
	{
		$this->subscribed[ $channel->getId() ] = $channel;

		$this->broadcast($conn, 'chatPublic', '<b>' . $conn->user->name . ' has joined ' . $channel . '.</b>');
	}

	public function onUnSubscribe(ConnectionInterface $conn, $channel)
	{
		$this->broadcast($conn, 'chatPublic', '<b>' . $conn->user->name . ' has left ' . $channel . '.</b>');
	}

	public function onOpen(ConnectionInterface $conn)
	{
		$session = (new SessionManager(App::getInstance()))->driver();
		$cookies = $conn->WebSocket->request->getCookies();

		if ( !array_key_exists( $this->cookie_name, $cookies ) )
		{
			$conn->close();
			echo "Missing cookie " . $this->cookie_name . "\n";
			return;
		}

		$laravelCookie = urldecode( $cookies[ $this->cookie_name ] );
		$idSession = Crypt::decrypt( $laravelCookie );
		$session->setId( $idSession );
		$conn->session = $session;

		$conn->session->start();

		$idUser = $conn->session->get( Auth::getName() );
		if ( !isset( $idUser ) )
		{
			$conn->close();
			echo "No user loggin present!\n";
			return;
		}

		$conn->user = User::find( $idUser );

		// or you can save data to the session
		// $from->session->put('foo', 'bar');

		// and at the end. save the session state to the store
		// $from->session->save();
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
			$this->broadcast( $conn, $channel->getId(), $event["msg"] );
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		echo $e->getTraceAsString() . "\n";
		// $this->broadcast( "dev", "Exception has been encountered on the Push Server: " . $e->getMessage() );
	}

	public function broadcast( $from, $channel, $data )
	{
		echo "Broadcasting '" . $data . "' on channel '" . $channel . "'\n";

		if ( !array_key_exists( $channel, $this->subscribed ) )
			return;

		$channel = $this->subscribed[ $channel ];
		$channel->broadcast( view('messages.post', ['id' => 'broadcast', 'user' => $from == null ? User::first() : $from->user, 'date' => Carbon::now(), 'text' => $data])->render() );
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
			$this->broadcast( null, $d["channel"], $d["data"] );
		}
	}
}
