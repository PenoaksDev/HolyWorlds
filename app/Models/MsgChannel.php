<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UuidAsKey;
use App\Models\Setting;
use App\Util;
use Auth;

class MsgChannel extends Model
{
	use UuidAsKey;

	protected $fillable = ['id', 'title', 'perm', 'created_at', 'updated_at'];
	public $incrementing = false;
}
