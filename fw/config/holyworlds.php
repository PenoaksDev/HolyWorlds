<?php

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */

return [
	/*
	|--------------------------------------------------------------------------
	| Cache lifetimes
	|--------------------------------------------------------------------------
	|
	| Here we specify cache lifetimes (in minutes) for various model data. Any
	| falsey values set here will cause the cache to use the default lifetime
	| for corresponding models/attributes.
	|
	*/

	'cache_lifetimes' => [
		'default' => 5,
		'Category' => [
			'threadCount' => 5,
			'postCount' => 5,
			'deepestChild' => 720,
			'depth' => 720
		],
		'Post' => [
			'sequenceNumber' => 5
		]
	],
];
