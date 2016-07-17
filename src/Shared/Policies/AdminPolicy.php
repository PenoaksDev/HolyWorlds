<?php
namespace Shared\Policies;

use Penoaks\Auth\Access\HandlesAuthorization;
use Shared\Middleware\Permissions;
use Shared\Models\User;

class AdminPolicy
{
	use HandlesAuthorization;

	public function admin( User $user )
	{
		return Permissions::checkPermission( 'sys.admin', $user ) !== false;
	}
}
