<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UuidAsKey;
use App\Models\Setting;
use App\Util;
use Auth;

class MsgPost extends Model
{
	use UuidAsKey;

	protected $fillable = ['id', 'parent', 'type', 'user', 'text', 'created_at', 'updated_at'];
	public $incrementing = false;
}
