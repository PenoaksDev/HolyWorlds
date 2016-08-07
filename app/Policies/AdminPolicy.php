<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Setting;
use App\Http\Middleware\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
	use HandlesAuthorization;

	public function admin( User $user )
	{
		return Permissions::checkPermission( 'sys.admin', $user ) !== false;
	}
}
