<?php namespace HolyWorlds\Models;

use Milky\Database\Eloquent\Model;
// use HolyWorlds\Models\Traits\UuidAsKey;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class MsgPost extends Model
{
	// use UuidAsKey;

	protected $fillable = ['id', 'parent', 'type', 'user', 'text', 'created_at', 'updated_at'];
	public $incrementing = false;
}
