<?php namespace App\Policies;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user has admin rights.
     *
     * @param  User  $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->hasPermission(Setting::get('admin_group', 'sys.admin'));
    }
}
