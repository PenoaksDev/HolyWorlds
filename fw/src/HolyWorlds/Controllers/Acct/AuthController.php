<?php namespace HolyWorlds\Controllers\Acct;

use HolyWorlds\Controllers\BaseController;
use HolyWorlds\Models\UserAuth;
use Milky\Account\Middleware\RedirectIfAuthenticated;
use Milky\Account\Models\User;
use Milky\Account\Traits\AuthenticatesUsers;
use Milky\Account\Traits\ThrottlesLogins;
use Milky\Account\Types\Account;
use Milky\Facades\Acct;
use Milky\Facades\Config;
use Milky\Facades\Log;
use Milky\Facades\Redirect;
use Milky\Facades\Session;
use Milky\Facades\URL;
use Milky\Facades\View;
use Milky\Http\RedirectResponse;
use Milky\Http\Request;
use Milky\Http\Response;

class AuthController extends BaseController
{
	use AuthenticatesUsers, ThrottlesLogins;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( RedirectIfAuthenticated::class, [
			'except' => ['redirectToProvider', 'handleProviderCallback', 'getLogout']
		] );
	}

	/**
	 * Redirect the user to a given provider's authentication page.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function redirectToProvider( Request $request )
	{
		$provider = $request->route( 'provider' );

		if ( !isset( Config::get( 'auth.login_providers' )[$provider] ) )
			return Redirect::to( 'auth/login' );

		return Socialite::driver( $provider )->redirect();
	}

	/**
	 * Obtain the user information from GitHub.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function handleProviderCallback( Request $request )
	{
		if ( !$request->has( 'code' ) || $request->has( 'denied' ) )
			return Redirect::to( 'auth/login' );

		$provider = $request->route( 'provider' );

		if ( !isset( Config::get( 'auth.login_providers' )[$provider] ) )
			return Redirect::to( 'auth/login' );

		$socialiteUser = Socialite::driver( $provider )->user();

		$auth = UserAuth::where( [
			'provider' => $provider,
			'provider_user_id' => $socialiteUser->id
		] )->first();

		if ( is_null( $auth ) )
		{
			if ( Acct::isGuest() )
			{
				Session::flash( 'pending_user_auth', $socialiteUser );
				Session::flash( 'pending_user_auth_provider', $provider );

				return Redirect::to( 'auth/register' );
			}
			else
			{
				UserAuth::createFromSocialite( Acct::acct(), $provider, $socialiteUser );
				Notification::success( "Your {$provider} account is now connected and you can log in with it from now on." );

				return Redirect::to( 'account/settings' );
			}
		}
		else
		{
			Acct::login( $auth->user );
			Notification::success( "Welcome back, {$auth->user->name}!" );

			return Redirect::to( '/' );
		}
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function postLogin( Request $request )
	{
		$this->validate( $request, ['username' => 'required'] );

		$field = filter_var( $request->input( 'username' ), FILTER_VALIDATE_EMAIL ) ? 'email' : 'name';
		$request->merge( [$field => $request->input( 'username' )] );
		$this->username = $field;

		return self::login( $request );
	}

	/**
	 * Send the response after the user was authenticated.
	 *
	 * @param  Request $request
	 * @param  Account $user
	 * @return Response
	 */
	protected function authenticated( Request $request, Account $user )
	{
		if ( !$user->isActivated() )
		{
			Acct::logout();

			// TODO Give the option to redispatch the activation e-mail
			return Redirect::back()->withErrors( 'warning', "Your account is not activated. :(" );
		}

		return Redirect::intended( '/' )->withErrors( 'success', "Welcome, {$user->getDisplayName()}!" );
	}

	/**
	 * Logging the user out of the application.
	 *
	 * @return Response
	 */
	public function getLogout()
	{
		if ( !empty( URL::previous() ) && !str_contains( URL::previous(), 'auth/' ) )
			$this->redirectAfterLogout = URL::previous();

		return $this->logout();
	}

	/**
	 * Show the application registration form.
	 *
	 * @return Response
	 */
	public function getRegister()
	{
		Session::keep( ['pending_user_auth', 'pending_user_auth_provider'] );

		return View::render( 'auth.register' );
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  Request $request
	 * @return RedirectResponse
	 */
	public function postRegister( Request $request )
	{
		Session::keep( ['pending_user_auth', 'pending_user_auth_provider'] );

		$this->validate( $request, [
			'name' => 'required|max:255|unique:users',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
		] );

		// Create the user
		$user = User::create( [
			'name' => $request->input( 'name' ),
			'email' => $request->input( 'email' ),
			'password' => bcrypt( $request->input( 'password' ) )
		] );

		// Given them the default group
		$user->addGroup( Setting::get( 'default_group' ) );

		// Give the user a profile
		UserProfile::create( ['id' => $user->id] );

		$activation = $user->activationToken();

		// Send it with the activation email
		Mail::send( 'auth.emails.activation', compact( 'user', 'activation' ), function ( $m ) use ( $user )
		{
			$m->to( $user->email, $user->name )->subject( 'Holy Worlds account activation' );
		} );

		Notification::success( "Thanks for registering, {$user->name}! An account activation link has been sent to {$user->email}." );

		// If there's a pending user auth, create it
		if ( Session::has( 'pending_user_auth' ) )
		{
			$socialiteUser = Session::pull( 'pending_user_auth' );
			$provider = Session::pull( 'pending_user_auth_provider' );
			$auth = UserAcct::createFromSocialite( $user, $provider, $socialiteUser );
			Notification::success( "Your account has been linked to {$provider}." );
		}

		return redirect( '/' );
	}

	/**
	 * Show the account activation form.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function getActivation( Request $request )
	{
		$user = User::forToken( $request->route( 'token' ) )->first();

		if ( is_null( $user ) )
		{
			Notification::info( "Invalid token. Maybe the link you followed is old?" );

			return redirect( '/' );
		}

		return View::make( 'auth.activation', compact( 'user' ) );
	}

	/**
	 * Handle an account activation request.
	 *
	 * @param  Request $request
	 * @return RedirectResponse
	 */
	public function postActivation( Request $request )
	{
		$user = User::forToken( $request->input( 'token' ) )->first();

		if ( is_null( $user ) )
		{
			Notification::info( "Invalid token. Maybe the link you followed is old?" );

			return redirect( '/' );
		}

		$user->activate();

		Notification::success( "Account {$user->name}/{$user->email} successfully activated. You are now logged in. :D" );
		Acct::login( $user );

		return redirect( '/' );
	}
}
