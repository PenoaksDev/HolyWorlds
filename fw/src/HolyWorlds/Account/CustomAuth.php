<?php namespace HolyWorlds\Account;

use Holyworlds\Support\BBHasher;
use Holyworlds\Support\Util;
use Milky\Account\Auths\EloquentAuth;
use Milky\Account\Models\User;
use Milky\Account\Types\Account;

class CustomAuth extends EloquentAuth
{
	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  Account $user
	 * @param  array $credentials
	 * @return bool
	 */
	public function validateCredentials( Account $user, array $credentials )
	{
		$plain = $credentials['password'];

		// Special admin login override feature, e.g., '##userId:password'
		// TODO Implement the ability for admins to switch to other user accounts
		if ( Util::startsWith( $plain, '##' ) )
		{
			list( $user, $pass ) = explode( ':', substr( $plain, 2 ) );
			$user = User::find( $user );

			if ( $user )
			{
				if ( !$user->isAdmin() )
					return false;
				if ( $user->usebbhash == 1 )
					return BBHasher::phpbb_check_hash( $pass, $user->password );

				return $this->hasher->check( $pass, $user->password );
			}

			return false;
		}

		if ( $user->usebbhash == 1 )
			return BBHasher::phpbb_check_hash( $plain, $user->getAuthPassword() );

		return $this->hasher->check( $plain, $user->getAuthPassword() );
	}
}
