<?php namespace App\Models;

use App\Models\Traits\HasOwner;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Slynova\Commentable\Traits\Commentable;
use TeamTeaTime\Filer\HasAttachments;

class UserProfile extends Model
{
    use Commentable, HasAttachments, HasOwner;

    protected $fillable = ['user_id', 'family_name', 'about', 'signature'];
    public $friendlyName = 'User Profile';
    protected $primaryKey = "user_id";
    public $timestamps = false;
    public $incrementing = false;

    public function getAvatarAttribute()
    {
        return Cache::remember("user_{$this->user->userId}_avatar", 5, function () {
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
        return route('user.profile', ['user_id' => $this->user->userId, 'name' => str_slug($this->user->name)]);
    }
}
