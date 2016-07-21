<?php
namespace HolyWorlds\Models;

use Illuminate\Database\Eloquent\Model;
// use HolyWorlds\Models\Traits\HasOwner;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ArticleRevision extends Model
{
	// use HasOwner;

	protected $fillable = ['id', 'article_id', 'user_id', 'title', 'body', 'created_at', 'updated_at'];
	public $friendlyName = 'Article Revision';
}
