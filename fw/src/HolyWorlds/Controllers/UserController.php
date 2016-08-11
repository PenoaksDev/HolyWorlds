<?php namespace HolyWorlds\Controllers;

use HolyWorlds\Models\User;
use HolyWorlds\Models\UserAuth;
use HolyWorlds\Support\Image;
use Milky\Facades\Acct;
use Milky\Facades\Cache;
use Milky\Facades\Config;
use Milky\Facades\Hash;
use Milky\Facades\Mail;
use Milky\Facades\Redirect;
use Milky\Facades\View;
use Milky\Helpers\Str;
use Milky\Http\Request;
use Milky\Http\Response;

class UserController extends BaseController
{
	/**
	 * Create a new account controller instance.
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Show the account settings page.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function getSettings( Request $request )
	{
		$auths = UserAuth::byUser( Acct::acct() )->get();

		return View::render( 'user.account.settings', compact( 'auths' ) );
	}

	/**
	 * Handle an account settings request.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function postSettings( Request $request )
	{
		$this->validate( $request, [
			'current_password' => 'required',
			'password' => 'confirmed|min:6'
		] );

		$user = $request->user();

		if ( !Hash::check( $request->input( 'current_password' ), $user->password ) )
			return Redirect::to( 'account/settings' )->withMessages( ['error' => "Incorrect current password entered. Please try again."] );

		if ( $request->has( 'email' ) )
		{
			$email = $request->input( 'email' );
			Mail::send( 'account.emails.email-changed', compact( 'user', 'email' ), function ( $m ) use ( $user )
			{
				$m->to( $user->email, $user->name )->subject( 'Account email address changed' );
			} );
			$user->email = $email;
			Notification::success( "Email address updated." );
		}

		if ( $request->has( 'password' ) )
		{
			$user->password = bcrypt( $request->input( 'password' ) );
			Notification::success( "Password updated." );
			Mail::send( 'account.emails.password-changed', compact( 'user' ), function ( $m ) use ( $user )
			{
				$m->to( $user->email, $user->name )->subject( 'Account password changed' );
			} );
		}

		$user->save();

		return Redirect::to( 'account/settings' );
	}

	/**
	 * Show the login disconnection page.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function getDisconnectLogin( Request $request )
	{
		$key = $request->route( 'provider' );
		$providers = config( 'auth.login_providers' );

		if ( $provider = $providers[$key] )
			return View::render( 'user.account.disconnect-login', compact( 'key', 'provider' ) );

		return Redirect::to( 'account/settings' );
	}

	/**
	 * Handle a provider login disconnection request.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function postDisconnectLogin( Request $request )
	{
		$key = $request->route( 'provider' );
		$providers = Config::get( 'auth.login_providers' );

		if ( $provider = $providers[$key] )
		{
			$auth = UserAuth::byUser( Acct::acct() )->forProvider( $key )->delete();
			Notification::success( "Your {$provider} login has been disconnected." );
		}

		return Redirect::to( 'account/settings' );
	}

	/**
	 * Show the user's notifications.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function getNotifications( Request $request )
	{
		$notifications = $request->user()->getNotifications();
		$request->user()->readAllNotifications();

		return View::render( 'user.account.notifications', compact( 'notifications' ) );
	}

	/**
	 * Redirect to the user's profile.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function redirectToProfile( Request $request )
	{
		return Redirect::route( 'account.profile', [
			'id' => Acct::acct()->getId(),
			'name' => Str::slugify( Acct::acct()->getDisplayName() )
		] );
	}

	/**
	 * Show the profile edit page.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function getEditProfile( Request $request )
	{
		return View::render( 'user.profile.edit', ['user' => $request->user()] );
	}

	/**
	 * Handle a profile edit request.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function postEditProfile( Request $request )
	{
		$config = Config::get( 'image.avatars' );

		$this->validate( $request, [
			'avatar' => "mimes:jpeg,gif,png|max:{$config['max_size']}"
		] );

		$profile = $request->user()->profile;
		$profile->update( $request->only( 'family_name', 'about', 'signature' ) );

		if ( $request->hasFile( 'avatar' ) && $request->file( 'avatar' )->isValid() )
		{
			$current = $request->user()->profile->avatar;

			if ( !is_null( $current ) )
			{
				$current->delete();
				Cache::forget( "user_{$request->user()->id}_avatar" );
			}

			$file = $request->file( 'avatar' );
			$destination = config( 'filer.path.absolute' );
			$filename = 'avatars/' . Auth::id() . '.' . $file->guessExtension();
			Image::make( $request->file( 'avatar' ) )->fit( $config['dimensions'][0], $config['dimensions'][1] )->save( "{$destination}/{$filename}" );

			$profile->attach( $filename, ['key' => 'avatar'] );
		}

		return Redirect::to( 'account/profile/edit' )->withMessages( ['success' => "Profile updated."] );
	}
}
