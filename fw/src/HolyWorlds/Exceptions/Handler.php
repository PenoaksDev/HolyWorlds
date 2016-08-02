<?php namespace HolyWorlds\Exceptions;

use Milky\Auth\Access\AuthorizationException;
use Milky\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
	public function __construct()
	{
		parent::__construct();

		$this->dontReport( [
			AuthorizationException::class,
		] );
	}
}
