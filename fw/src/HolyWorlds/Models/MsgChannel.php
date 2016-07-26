<?php namespace HolyWorlds\Models;

use Milky\Database\Eloquent\Model;
use HolyWorlds\Models\Traits\UuidAsKey;

class MsgChannel extends Model
{
	use UuidAsKey;

	protected $fillable = ['id', 'title', 'perm', 'created_at', 'updated_at'];
	public $incrementing = false;
}
