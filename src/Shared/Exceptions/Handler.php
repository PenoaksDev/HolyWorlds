<?php
namespace Shared\Exceptions;

use Exception;
use Penoaks\Auth\Access\AuthorizationException;
use Penoaks\Database\Eloquent\ModelNotFoundException;
use Penoaks\Exceptions\Handler as ExceptionHandler;
use Penoaks\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $e
	 * @return void
	 */
	public function report( Exception $e )
	{
		parent::report( $e );
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Foundation\Http\Request $request
	 * @param  \Exception $e
	 * @return \Foundation\Http\Response
	 */
	public function render( $request, Exception $e )
	{
		return parent::render( $request, $e );
	}
}
