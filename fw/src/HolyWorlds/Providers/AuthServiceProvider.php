<?php namespace HolyWorlds\Providers;

use HolyWorlds\Auth\CustomUserProvider;
use HolyWorlds\Models\User;
use Milky\Auth\Access\Gate;
use Milky\Facades\Auth;
use Milky\Framework;
use Milky\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		\HolyWorlds\Models\Article::class => \HolyWorlds\Policies\ArticlePolicy::class,
		\HolyWorlds\Models\Character::class => \HolyWorlds\Policies\CharacterPolicy::class,
		\HolyWorlds\Models\Event::class => \HolyWorlds\Policies\EventPolicy::class,
		\HolyWorlds\Models\ImageAlbum::class => \HolyWorlds\Policies\ImageAlbumPolicy::class,
		\HolyWorlds\Models\UserProfile::class => \HolyWorlds\Policies\UserProfilePolicy::class,
		\Slynova\Commentable\Models\Comment::class => \HolyWorlds\Policies\CommentPolicy::class,
	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  Gate $gate
	 * @return void
	 */
	public function boot( Gate $gate )
	{
		/*
		Blade::directive( 'has', function ( $permission )
		{
			return "<?php if ( \\Shared\\Http\\Middleware\\Permissions::checkPermission( $permission ) !== false ) { ?>";
		} );

		Blade::directive( 'endhas', function ()
		{
			return "<?php } ?>";
		} );
		*/

		Auth::provider( 'custom', function ()
		{
			return new CustomUserProvider( Framework::get( 'hash' ), User::class );
		} );

		$policies = [
			'AdminPolicy',
			'GeneralPolicy',
			'AdminPolicy',
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

		foreach ( $policies as $policy )
		{
			$gate = $this->defineFromClass( $gate, "HolyWorlds\\Policies\\{$policy}" );
		}

		$this->registerPolicies( $gate );
	}

	/**
	 * Define policy methods in the given gate using the given policy class.
	 *
	 * @param  Gate $gate
	 * @param  string $className
	 * @return Gate
	 */
	private function defineFromClass( Gate $gate, $className )
	{
		$class = "\\{$className}";
		foreach ( get_class_methods( new $class ) as $method )
			$gate->define( $method, "{$className}@{$method}" );

		return $gate;
	}
}
