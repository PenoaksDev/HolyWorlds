<?php namespace HolyWorlds\Providers;

use HolyWorlds\Account\CustomAuth;
use HolyWorlds\Policies\AdminPolicy;
use HolyWorlds\Policies\ForumPolicy;
use Milky\Account\AccountManager;
use Milky\Account\Permissions\PermissionManager;
use Milky\Auth\Access\Gate;
use Milky\Facades\Blade;
use Milky\Providers\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  Gate $gate
	 * @return void
	 */
	public function boot()
	{
		Blade::directive( 'has', function ( $permission )
		{
			return "<?php if( true ): ?>";
			// return "<?php if ( \\Shared\\Http\\Middleware\\Permissions::checkPermission( $permission ) !== false ) { >";
		} );

		Blade::directive( 'endhas', function ()
		{
			return "<?php endif; ?> ";
			// return "<?php } >";
		} );

		PermissionManager::policy( new AdminPolicy() );
		PermissionManager::policy( new ForumPolicy() );

		$policies = [
			'AdminPolicy',
			'GeneralPolicy',
			'ArticlePolicy',
			'CategoryPolicy',
			'CommentPolicy',
			'EventPolicy',
			'ForumPolicy',
			'ImageAlbumPolicy',
			'PostPolicy',
			'ThreadPolicy',
			'UserProfilePolicy'
		];

		$policies = [
			\HolyWorlds\Models\Article::class => \HolyWorlds\Policies\ArticlePolicy::class,
			\HolyWorlds\Models\Character::class => \HolyWorlds\Policies\CharacterPolicy::class,
			\HolyWorlds\Models\Event::class => \HolyWorlds\Policies\EventPolicy::class,
			\HolyWorlds\Models\ImageAlbum::class => \HolyWorlds\Policies\ImageAlbumPolicy::class,
			\HolyWorlds\Models\UserProfile::class => \HolyWorlds\Policies\UserProfilePolicy::class,
			\Slynova\Commentable\Models\Comment::class => \HolyWorlds\Policies\CommentPolicy::class,
		];
	}
}
