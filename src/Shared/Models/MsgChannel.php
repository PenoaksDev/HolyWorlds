<?php
namespace Shared\Models;

use Penoaks\Database\Eloquent\Model;
use Shared\Models\Traits\UuidAsKey;

class MsgChannel extends Model
{
	use UuidAsKey;

	protected $fillable = ['id', 'title', 'perm', 'created_at', 'updated_at'];
	public $incrementing = false;
}
