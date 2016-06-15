<?php
namespace App\Models;

use App\Models\Traits\HasOwner;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Slynova\Commentable\Traits\Commentable;
use TeamTeaTime\Filer\HasAttachments;

class UserProfile extends Model
{
	use Commentable, HasAttachments, HasOwner;

	protected $fillable = ['id', 'family_name', 'user_avatar', 'location', 'website', 'interests', 'occupation', 'about', 'signature'];
	public $friendlyName = 'User Profile';
	public $timestamps = false;
	public $incrementing = false;

	public function getAvatarAttribute()
	{
		if ( $this->user == null )
			return null;
		return Cache::remember("user_{$this->user->id}_avatar", 5, function () {
			return $this->findAttachmentByKey('avatar');
		});
	}

	public function getAvatarUrlAttribute()
	{
		return (is_null($this->avatar))
		? config('user.default_avatar_path')
		: $this->avatar->getUrl();
	}

	public function getUrlAttribute()
	{
		return $this->user == null ? null : route('user.profile', ['id' => $this->user->id, 'name' => str_slug($this->user->name)]);
	}
}
