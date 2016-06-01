<?php
	$context = new ZMQContext();
	$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'pusher');
	$socket->connect("tcp://localhost:5555");

	$socket->send(json_encode(array("category" => "kittensCategory", "data" => "This is a TEST!")));