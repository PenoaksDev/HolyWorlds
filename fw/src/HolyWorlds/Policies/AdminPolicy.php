<?php
namespace HolyWorlds\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use HolyWorlds\Middleware\Permissions;
use HolyWorlds\Models\User;

class AdminPolicy
{
	use HandlesAuthorization;

	public function admin( User $user )
	{
		return Permissions::checkPermission( 'sys.admin', $user ) !== false;
	}
}
