<?php namespace Milky\Validation;

use Milky\Binding\UniversalBuilder;
use Milky\Exceptions\Validation\ValidationException;
use Milky\Http\HttpFactory;
use Milky\Http\JsonResponse;
use Milky\Http\Request;
use Milky\Http\Routing\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
trait ValidatesRequests
{
	/**
	 * The default error bag.
	 *
	 * @var string
	 */
	protected $validatesRequestErrorBag;

	/**
	 * Run the validation routine against the given validator.
	 *
	 * @param  Validator|array $validator
	 * @param  Request|null $request
	 * @return void
	 */
	public function validateWith( $validator, Request $request = null )
	{
		$request = $request ?: HttpFactory::i()->request();
		if ( is_array( $validator ) )
		{
			$validator = $this->getValidationFactory()->make( $request->all(), $validator );
		}
		if ( $validator->fails() )
		{
			$this->throwValidationException( $request, $validator );
		}
	}

	/**
	 * Validate the given request with the given rules.
	 *
	 * @param  Request $request
	 * @param  array $rules
	 * @param  array $messages
	 * @param  array $customAttributes
	 * @return void
	 */
	public function validate( Request $request, array $rules, array $messages = [], array $customAttributes = [] )
	{
		$validator = $this->getValidationFactory()->make( $request->all(), $rules, $messages, $customAttributes );
		if ( $validator->fails() )
		{
			$this->throwValidationException( $request, $validator );
		}
	}

	/**
	 * Validate the given request with the given rules.
	 *
	 * @param  string $errorBag
	 * @param  Request $request
	 * @param  array $rules
	 * @param  array $messages
	 * @param  array $customAttributes
	 * @return void
	 *
	 * @throws ValidationException
	 */
	public function validateWithBag( $errorBag, Request $request, array $rules, array $messages = [], array $customAttributes = [] )
	{
		$this->withErrorBag( $errorBag, function () use ( $request, $rules, $messages, $customAttributes )
		{
			$this->validate( $request, $rules, $messages, $customAttributes );
		} );
	}

	/**
	 * Throw the failed validation exception.
	 *
	 * @param  Request $request
	 * @param  Validator $validator
	 * @return void
	 *
	 * @throws ValidationException
	 */
	protected function throwValidationException( Request $request, $validator )
	{
		throw new ValidationException( $validator, $this->buildFailedValidationResponse( $request, $this->formatValidationErrors( $validator ) ) );
	}

	/**
	 * Create the response for when a request fails validation.
	 *
	 * @param  Request $request
	 * @param  array $errors
	 * @return Response
	 */
	protected function buildFailedValidationResponse( Request $request, array $errors )
	{
		if ( ( $request->ajax() && !$request->pjax() ) || $request->wantsJson() )
		{
			return new JsonResponse( $errors, 422 );
		}

		return redirect()->to( $this->getRedirectUrl() )->withInput( $request->input() )->withErrors( $errors, $this->errorBag() );
	}

	/**
	 * Format the validation errors to be returned.
	 *
	 * @param  Validator $validator
	 * @return array
	 */
	protected function formatValidationErrors( Validator $validator )
	{
		return $validator->errors()->getMessages();
	}

	/**
	 * Get the URL we should redirect to.
	 *
	 * @return string
	 */
	protected function getRedirectUrl()
	{
		return UniversalBuilder::resolveClass( UrlGenerator::class )->previous();
	}

	/**
	 * Get a validation factory instance.
	 *
	 * @return Factory
	 */
	protected function getValidationFactory()
	{
		return UniversalBuilder::resolveClass( Factory::class );
	}

	/**
	 * Execute a Closure within with a given error bag set as the default bag.
	 *
	 * @param  string $errorBag
	 * @param  callable $callback
	 * @return void
	 */
	protected function withErrorBag( $errorBag, callable $callback )
	{
		$this->validatesRequestErrorBag = $errorBag;
		call_user_func( $callback );
		$this->validatesRequestErrorBag = null;
	}

	/**
	 * Get the key to be used for the view error bag.
	 *
	 * @return string
	 */
	protected function errorBag()
	{
		return $this->validatesRequestErrorBag ?: 'default';
	}
}
