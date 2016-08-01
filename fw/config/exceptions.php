<?php

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

return [
	/*
    |--------------------------------------------------------------------------
    | Exception Transformers
    |--------------------------------------------------------------------------
    |
    | Here are each of the exception transformers setup for your application.
    |
    | This allows you to turn your exceptions into other exceptions such as
    | http exceptions for perfect results when passed to the displayers. Note
    | that this list is processed in order and subsequent transformers can
    | still modify the results of previous ones if required.
    |
    */

	'transformers' => [
		'Milky\Exceptions\Transformers\AuthTransformer',
		'Milky\Exceptions\Transformers\CsrfTransformer',
		'Milky\Exceptions\Transformers\ModelTransformer',
	],

	/*
	|--------------------------------------------------------------------------
	| Exception Displayers
	|--------------------------------------------------------------------------
	|
	| Here are each of the exception displayers setup for your application.
	|
	| These displayers are sorted by priority. Note that when we are in debug
	| mode, we will select the first valid displayer from the list, and when we
	| are not in debug mode, we'll filter out all verbose displayers, then
	| select the first valid displayer from the new list.
	|
	*/

	'displayers' => [
		'Milky\Exceptions\Displayers\DebugDisplayer',
		'Milky\Exceptions\Displayers\ViewDisplayer',
		'Milky\Exceptions\Displayers\HtmlDisplayer',
		'Milky\Exceptions\Displayers\JsonDisplayer',
		'Milky\Exceptions\Displayers\JsonApiDisplayer',
	],

	/*
	|--------------------------------------------------------------------------
	| Displayer Filters
	|--------------------------------------------------------------------------
	|
	| Here are each of the filters for the displayers.
	|
	| This allows you to apply filters to your displayers in order to work out
	| which displayer to use for each exception. This includes things like
	| content type negotiation.
	|
	*/

	'filters' => [
		'Milky\Exceptions\Filters\VerboseFilter',
		'Milky\Exceptions\Filters\CanDisplayFilter',
		'Milky\Exceptions\Filters\ContentTypeFilter',
	],

	/*
	|--------------------------------------------------------------------------
	| Default Displayer
	|--------------------------------------------------------------------------
	|
	| Here you may define the default displayer for your application.
	|
	| This displayer will be used if your filters have filtered out all the
	| displayers, otherwise leaving us unable to displayer the exception.
	|
	*/

	'default' => 'Milky\Exceptions\Displayers\HtmlDisplayer',

	/*
	|--------------------------------------------------------------------------
	| Exception Levels
	|--------------------------------------------------------------------------
	|
	| Here are each of the log levels for the each exception.
	|
	| If an exception passes an instance of test for each key, then the log
	| level used is the value associated with each key.
	|
	*/

	'levels' => [
		'Milky\Database\Eloquent\ModelNotFoundException' => 'warning',
		'Milky\Http\Session\TokenMismatchException' => 'notice',
		'Symfony\Component\HttpKernel\Exception\HttpExceptionInterface' => 'warning',
		'Symfony\Component\Debug\Exception\FatalErrorException' => 'critical',
		'Exception' => 'error',
	],
];
