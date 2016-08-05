<?php namespace HolyWorlds\Models;

use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use HolyWorlds\Support\Traits\Commentable;
use HolyWorlds\Support\Traits\HasOwner;
use Milky\Database\Eloquent\Model;
use Milky\Facades\Cache;
use Milky\Facades\Config;
use Milky\Facades\URL;

class UserProfile extends Model implements StaplerableInterface
{
	use Commentable, HasOwner, EloquentTrait;

	protected $fillable = [
		'id',
		'family_name',
		'user_avatar',
		'location',
		'website',
		'interests',
		'occupation',
		'about',
		'signature',
		'signature_bbcode',
		'post_count',
		'timezone',
		'dst'
	];
	public $friendlyName = 'User Profile';
	public $timestamps = false;
	public $incrementing = false;

	public function getAvatarAttribute()
	{
		if ( $this->user == null )
			return null;

		return Cache::remember( "user_{$this->user->id}_avatar", 5, function ()
		{
			return $this->findAttachmentByKey( 'avatar' );
		} );
	}

	public function getAvatarUrlAttribute()
	{
		return ( is_null( $this->avatar ) ) ? Config::get( 'user.default_avatar_path' ) : $this->avatar->getUrl();
	}

	public function getUrlAttribute()
	{
		return $this->user == null ? null : URL::route( 'user.profile', [
			'id' => $this->user->id,
			'name' => str_slug( $this->user->name )
		] );
	}
}
