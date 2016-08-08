<?php namespace HolyWorlds\Providers;

use HolyWorlds\Account\CustomAuth;
use HolyWorlds\Policies\AdminPolicy;
use HolyWorlds\Policies\ForumPolicy;
use HolyWorlds\Policies\UserProfilePolicy;
use Milky\Account\AccountManager;
use Milky\Account\Permissions\PermissionManager;
use Milky\Auth\Access\Gate;
use Milky\Facades\Blade;
use Milky\Filesystem\Filesystem;
use Milky\Providers\ServiceProvider;
use Symfony\Component\Finder\Finder;

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

		$p = PermissionManager::i();

		foreach ( Finder::create()->files()->in( realpath( __DIR__ . '/../Policies' ) )->name( '*Policy.php' ) as $file )
		{
			require_once( $file->getRealPath() );

			$class = '\\HolyWorlds\\Policies\\' . str_replace( '.php', '', $file->getFilename() );
			$p->policy( new $class );
		}
	}
}
