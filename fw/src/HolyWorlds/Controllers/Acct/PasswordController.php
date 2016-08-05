<?php namespace HolyWorlds\Controllers\Acct;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Middleware\RedirectIfAuthenticated;
use Milky\Auth\ResetsPasswords;

class PasswordController extends BaseController
{
	use ResetsPasswords;

	/**
	 * @var string
	 */
	protected $redirectPath = '/';

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'holyworlds.middleware.guest' );
	}
}
