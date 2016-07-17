<?php
namespace Shared\Providers;

use Penoaks\Contracts\Auth\Access\Gate as GateContract;
use Penoaks\Support\Facades\Blade;
use Penoaks\Providers\AuthServiceProvider as ServiceProvider;
use Shared\Auth\CustomUserProvider;
use Shared\Models\User;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		\Shared\Models\Article::class => \Shared\Policies\ArticlePolicy::class,
		\Shared\Models\Character::class => \Shared\Policies\CharacterPolicy::class,
		\Shared\Models\Event::class => \Shared\Policies\EventPolicy::class,
		\Shared\Models\ImageAlbum::class => \Shared\Policies\ImageAlbumPolicy::class,
		\Shared\Models\UserProfile::class => \Shared\Policies\UserProfilePolicy::class,
		\Slynova\Commentable\Models\Comment::class => \Shared\Policies\CommentPolicy::class,
	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Foundation\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		Blade::directive('has', function( $permission ) {
			return "<?php if ( \\Shared\\Http\\Middleware\\Permissions::checkPermission( $permission ) !== false ) { ?>";
		});

		Blade::directive('endhas', function() {
			return "<?php } ?>";
		});

		Auth::provider('custom', function( $provider ) {
			return new CustomUserProvider( $this->fw['hash'], User::class );
		});

		foreach(['AdminPolicy', 'GeneralPolicy', 'AdminPolicy', 'ArticlePolicy', 'CategoryPolicy', 'CommentPolicy', 'EventPolicy', 'ForumPolicy', 'ImageAlbumPolicy', 'PostPolicy', 'ThreadPolicy', 'UserProfilePolicy'] as $policy) {
			$gate = $this->defineFromClass($gate, "App\\Policies\\{$policy}");
		}

		$this->registerPolicies($gate);
	}

	/**
	 * Define policy methods in the given gate using the given policy class.
	 *
	 * @param  GateContract  $gate
	 * @param  string  $className
	 * @return GateContract
	 */
	private function defineFromClass(GateContract $gate, $className)
	{
		$class = "\\{$className}";
		foreach (get_class_methods(new $class) as $method) {
			$gate->define($method, "{$className}@{$method}");
		}

		return $gate;
	}
}
