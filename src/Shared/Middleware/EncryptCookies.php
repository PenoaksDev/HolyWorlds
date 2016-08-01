<?php
namespace Shared\Middleware;

use Penoaks\Cookie\Middleware\EncryptCookies as BaseEncrypter;

/*
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class EncryptCookies extends BaseEncrypter
{
	/**
	 * The names of the cookies that should not be encrypted.
	 *
	 * @var array
	 */
	protected $except = [
		//
	];
}
