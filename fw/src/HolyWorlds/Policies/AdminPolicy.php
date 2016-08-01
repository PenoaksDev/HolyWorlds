<?php namespace HolyWorlds\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use HolyWorlds\Middleware\Permissions;
use HolyWorlds\Models\User;
use Milky\Account\Permissions\Policy;
use Milky\Account\Types\Account;

class AdminPolicy extends Policy
{
	protected $prefix = 'holyworlds.admin';

	protected $nodes = [
		0 => 'admin',
	];

	public function admin( Account $acct )
	{
		return $acct->getId() == 'cg092m';
	}
}
