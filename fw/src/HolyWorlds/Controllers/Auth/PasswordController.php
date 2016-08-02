<?php namespace HolyWorlds\Http\Controllers\Auth;

use HolyWorlds\Controllers\Controller;
use Milky\Auth\ResetsPasswords;

class PasswordController extends Controller
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
		$this->middleware( 'guest' );
	}
}
