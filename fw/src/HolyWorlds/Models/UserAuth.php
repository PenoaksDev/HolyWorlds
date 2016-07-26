<?php namespace HolyWorlds\Models;

use HolyWorlds\Models\Traits\HasOwner;
use Milky\Database\Eloquent\Model;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class UserAuth extends Model
{
	use HasOwner;

	protected $fillable = ['user_id', 'provider', 'provider_user_id', 'provider_user_email', 'token'];

	public function scopeForProvider($query, $key)
	{
		return $query->where('provider', $key);
	}

	public static function createFromSocialite(User $user, $provider, SocialiteUser $socialiteUser)
	{
		return static::create([
			'user_id' => $user->id,
			'provider' => $provider,
			'provider_user_id' => $socialiteUser->id,
			'provider_user_email' => $socialiteUser->email,
			'token' => $socialiteUser->token
		]);
	}
}
