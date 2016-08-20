<?php

use HolyWorlds\Models\Article;
use HolyWorlds\Models\Forum\Post;
use HolyWorlds\Models\Forum\Thread;
use HolyWorlds\Models\ImageAlbum;
use HolyWorlds\Models\Session;
use HolyWorlds\Support\Models\Comment;
use Milky\Account\Models\User;
use Milky\Facades\View;
use Milky\Http\Routing\Router;

function loadRoutes( Router $r )
{
	$r->group( ['namespace' => 'HolyWorlds\Controllers', 'middleware' => 'web', 'crumbs' => 'Home|root'], function ( Router $r )
	{
		$r->get( '/', ['as' => 'root', 'uses' => 'RootController@index', 'crumbs' => 'Index'] );

		$r->group( ["prefix" => "messages", "as" => "messages."], function ( Router $r )
		{
			$r->get( "/", ['as' => 'index', 'uses' => 'MessagesController@index'] );
			$r->get( '{id}', ['as' => 'show', 'uses' => 'MessagesController@show'] );

			$r->group( ["prefix" => "channel", "as" => "channel."], function ( Router $r )
			{
				$r->get( '{id}', ['as' => 'show', 'uses' => 'MessagesController@channelShow'] );
			} );
		} );

		$r->group( ['prefix' => 'auth', 'as' => 'auth.'], function ( Router $r )
		{
			// Login
			$r->get( 'login', ['as' => 'login', 'uses' => 'Account\AuthController@getLogin'] );
			$r->post( 'login', 'Account\AuthController@postLogin' );
			$r->get( 'logout', ['as' => 'logout', 'uses' => 'Account\AuthController@getLogout'] );

			// Registration
			$r->get( 'register', ['as' => 'register', 'uses' => 'Account\AuthController@getRegister'] );
			$r->post( 'register', 'Account\AuthController@postRegister' );

			// Activation
			$r->get( 'activate/{token}', [
				'as' => 'auth.get.activation',
				'uses' => 'Account\AuthController@getActivation'
			] );
			$r->post( 'activate', ['as' => 'auth.post.activation', 'uses' => 'Account\AuthController@postActivation'] );

			// Password reset
			$r->get( 'password/reset/{token?}', 'Account\PasswordController@showResetForm' );
			$r->post( 'password/email', 'Account\PasswordController@sendResetLinkEmail' );
			$r->post( 'password/reset', 'Account\PasswordController@reset' );

			// Socialite
			$r->get( '{provider}', 'Account\AuthController@redirectToProvider' );
			$r->get( '{provider}/callback', 'Account\AuthController@handleProviderCallback' );
		});

		// Account
		$r->group( ['prefix' => 'user', 'as' => 'user.'], function ( Router $r )
		{
			// Profile
			$r->get( '{id}-{slug}', ['as' => 'show', 'uses' => 'User\Controller@show'] );

			// Settings & logins
			$r->get( 'settings', ['as' => 'settings', 'uses' => 'Account\AccountController@getSettings'] );
			$r->post( 'settings', 'Account\AccountController@postSettings' );
			$r->get( '{provider}/disconnect', [
				'as' => 'disconnect-login',
				'uses' => 'Account\AccountController@getDisconnectLogin'
			] );
			$r->post( '{provider}/disconnect', 'Account\AccountController@postDisconnectLogin' );

			// Notifications
			$r->get( 'notifications', ['as' => 'notifications', 'uses' => 'Account\AccountController@getNotifications'] );

			$r->get( 'profile', 'Account\AccountController@redirectToProfile' );
			$r->get( 'profile/edit', ['as' => 'profile.edit', 'uses' => 'Account\AccountController@getEditProfile'] );
			$r->post( 'profile/edit', 'Account\AccountController@postEditProfile' );
		} );

		// Groups
		$r->group( ['prefix' => 'group', 'as' => 'group.'], function ( Router $r )
		{
			$r->get( '{id}-{slug}', ['as' => 'show', 'uses' => 'Group\Controller@show'] );
		} );

		// Articles
		$r->group( ['prefix' => 'articles', 'as' => 'article.'], function ( Router $r )
		{
			$r->get( '{article}-{title}', ['as' => 'show', 'uses' => 'ArticleController@show'] );
		} );

		/*
		$r->group(['prefix' => 'events', 'as' => 'event.'], function ($r) {
		$r->get('/', 'EventController@index');
		$r->get(
		'{event}-{name}',
		['as' => 'show', 'uses' => 'EventController@show']
		);
		});
		*/

		// Characters
		$r->group( ['prefix' => 'characters', 'as' => 'character.'], function ( Router $r )
		{
			$r->get( '/', 'CharacterController@index' );
			$r->get( '{character}-{name}', ['as' => 'show', 'uses' => 'CharacterController@show'] );
			$r->get( 'create', ['as' => 'create', 'uses' => 'CharacterController@create'] );
			$r->post( 'create', ['as' => 'store', 'uses' => 'CharacterController@store'] );
			$r->get( '{character}/edit', ['as' => 'edit', 'uses' => 'CharacterController@edit'] );
			$r->patch( '{character}', ['as' => 'update', 'uses' => 'CharacterController@update'] );
			$r->delete( '{character}', ['as' => 'delete', 'uses' => 'CharacterController@delete'] );
		} );

		// Image gallery
		$r->group( ['prefix' => 'gallery', 'as' => 'image-album.'], function ( Router $r )
		{
			$r->get( '/', 'ImageAlbumController@index' );
			$r->get( '{album}-{title}', ['as' => 'show', 'uses' => 'ImageAlbumController@show'] );
			$r->get( 'create', ['as' => 'create', 'uses' => 'ImageAlbumController@create'] );
			$r->post( 'create', ['as' => 'store', 'uses' => 'ImageAlbumController@store'] );
			$r->get( '{album}/edit', ['as' => 'edit', 'uses' => 'ImageAlbumController@edit'] );
			$r->patch( '{album}', ['as' => 'update', 'uses' => 'ImageAlbumController@update'] );
			$r->delete( '{album}', ['as' => 'delete', 'uses' => 'ImageAlbumController@delete'] );
		} );

		// Comments
		$r->group( ['prefix' => 'comments', 'as' => 'comment.'], function ( Router $r )
		{
			$r->post( '{model}/{id}', ['as' => 'store', 'uses' => 'CommentController@store'] );
			$r->get( '{comment}/edit', ['as' => 'edit', 'uses' => 'CommentController@edit'] );
			$r->patch( '{comment}', ['as' => 'update', 'uses' => 'CommentController@update'] );
			$r->delete( '{comment}', ['as' => 'delete', 'uses' => 'CommentController@delete'] );
		} );

		// Tags
		$r->get( 'tagged/{tag}', 'TagController@show' );

		$r->group( ["prefix" => "dev", "namespace" => "Dev"], function ( Router $r )
		{
			$r->get( "restartPushServer", "DevController@restartPushServer" );
			$r->get( "broadcast", "DevController@broadcast" );
		} );

		// Admin
		$r->group( ['prefix' => 'admin', 'namespace' => 'Admin'], function ( Router $r )
		{
			// Dashboard
			$r->get( '/', 'AdminController@getDashboard' );

			$r->get( "groups/ajax", ["as" => "admin.groups.ajax", "uses" => "GroupController@ajax"] );
			$r->get( "groups/{groupId}/inheritance", [
				"as" => "admin.groups.inheritance",
				"uses" => "GroupController@inheritance"
			] );

			// $r->resource('groups', 'GroupController');
			// $r->resource('users', 'UserController');

			// $r->get("groups/{groupId}", ["as" => "admin.groups.tree", "uses" => "GroupController@groupTree"] );
			$r->get( "users/{userId}/groups", ["as" => "admin.users.groups", "uses" => "UserController@listGroups"] );

			// Articles
			// $r->resource('article', 'ArticleController');

			// Events
			// $r->resource('event', 'EventController');

			// Forum
			$r->group( ['prefix' => 'forum', 'namespace' => 'Forum'], function ( Router $r )
			{
				// $r->resource('category', 'CategoryController');
			} );

			// Resource deletion
			$r->get( '{model}/{id}/delete', [
				'as' => 'admin.resource.delete',
				'uses' => 'AdminController@getDeleteResource'
			] );
			$r->delete( '{model}/{id}', 'AdminController@postDeleteResource' );
		} );

		// Model binding
		$r->model( 'album', ImageAlbum::class );
		$r->model( 'article', Article::class );
		$r->model( 'comment', Comment::class );

		$r->group( ["prefix" => "forum", "namespace" => "Forum", "as" => "forum.", 'crumbs' => 'Forum|forum.index'], function ( Router $r )
		{
			$r->get( '/', ['as' => 'index', 'uses' => "CategoryController@index"] );

			$r->get( 'new', ['as' => 'index-new', 'uses' => "CategoryController@indexNew"] );
			$r->patch( 'new', ['as' => 'mark-new', 'uses' => "CategoryController@markNew"] );

			// Categories
			$r->post( 'category/create', ['as' => 'category.store', 'uses' => "CategoryController@store"] );

			$r->group( ['prefix' => '{category}-{category_slug}'], function ( Router $r )
			{
				$r->get( '/', [
					'as' => 'category.show',
					'uses' => "CategoryController@show"
				] );
				$r->patch( '/', [
					'as' => 'category.update',
					'uses' => "CategoryController@update"
				] );
				$r->delete( '/', [
					'as' => 'category.delete',
					'uses' => "CategoryController@destroy"
				] );

				// Threads
				$r->get( '{thread}-{thread_slug}', ['as' => 'thread.show', 'uses' => "ThreadController@show"] );
				$r->get( 'thread/create', ['as' => 'thread.create', 'uses' => "ThreadController@create"] );
				$r->post( 'thread/create', ['as' => 'thread.store', 'uses' => "ThreadController@store"] );
				$r->patch( '{thread}-{thread_slug}', ['as' => 'thread.update', 'uses' => "ThreadController@update"] );
				$r->delete( '{thread}-{thread_slug}', ['as' => 'thread.delete', 'uses' => "ThreadController@destroy"] );

				// Posts
				$r->get( '{thread}-{thread_slug}/post/{post}', ['as' => 'post.show', 'uses' => "PostController@show"] );
				$r->get( '{thread}-{thread_slug}/reply', ['as' => 'post.create', 'uses' => "PostController@create"] );
				$r->post( '{thread}-{thread_slug}/reply', ['as' => 'post.store', 'uses' => "PostController@store"] );
				$r->get( '{thread}-{thread_slug}/post/{post}/edit', [
					'as' => 'post.edit',
					'uses' => "PostController@edit"
				] );
				$r->patch( '{thread}-{thread_slug}/{post}', [
					'as' => 'post.update',
					'uses' => "PostController@update"
				] );
				$r->delete( '{thread}-{thread_slug}/{post}', [
					'as' => 'post.delete',
					'uses' => "PostController@destroy"
				] );
			} );

			// Bulk actions
			$r->group( ['prefix' => 'bulk', 'as' => 'bulk.'], function ( Router $r )
			{
				$r->patch( 'thread', ['as' => 'thread.update', 'uses' => "ThreadController@bulkUpdate"] );
				$r->delete( 'thread', ['as' => 'thread.delete', 'uses' => "ThreadController@bulkDestroy"] );
				$r->patch( 'post', ['as' => 'post.update', 'uses' => "PostController@bulkUpdate"] );
				$r->delete( 'post', ['as' => 'post.delete', 'uses' => "PostController@bulkDestroy"] );
			} );

			// Categories
			$r->group( ['prefix' => 'category', 'as' => 'category.'], function ( Router $r )
			{
				$r->get( '/', ['as' => 'index', 'uses' => 'CategoryController@index'] );
				$r->post( '/', ['as' => 'store', 'uses' => 'CategoryController@store'] );
				$r->get( '{id}', ['as' => 'fetch', 'uses' => 'CategoryController@fetch'] );
				$r->delete( '{id}', ['as' => 'delete', 'uses' => 'CategoryController@destroy'] );
				$r->patch( '{id}/enable-threads', [
					'as' => 'enable-threads',
					'uses' => 'CategoryController@enableThreads'
				] );
				$r->patch( '{id}/disable-threads', [
					'as' => 'disable-threads',
					'uses' => 'CategoryController@disableThreads'
				] );
				$r->patch( '{id}/make-public', ['as' => 'make-public', 'uses' => 'CategoryController@makePublic'] );
				$r->patch( '{id}/make-private', ['as' => 'make-private', 'uses' => 'CategoryController@makePrivate'] );
				$r->patch( '{id}/move', ['as' => 'move', 'uses' => 'CategoryController@move'] );
				$r->patch( '{id}/rename', ['as' => 'rename', 'uses' => 'CategoryController@rename'] );
				$r->patch( '{id}/reorder', ['as' => 'reorder', 'uses' => 'CategoryController@reorder'] );
			} );

			// Threads
			$r->group( ['prefix' => 'thread', 'as' => 'thread.'], function ( Router $r )
			{
				$r->get( '/', ['as' => 'index', 'uses' => 'ThreadController@index'] );
				$r->get( 'new', ['as' => 'index-new', 'uses' => 'ThreadController@indexNew'] );
				$r->patch( 'new', ['as' => 'mark-new', 'uses' => 'ThreadController@markNew'] );
				$r->post( '/', ['as' => 'create', 'uses' => 'ThreadController@create'] );
				$r->get( '{id}', ['as' => 'show', 'uses' => 'ThreadController@show'] );
				$r->delete( '{id}', ['as' => 'delete', 'uses' => 'ThreadController@destroy'] );
				$r->patch( '{id}/restore', ['as' => 'restore', 'uses' => 'ThreadController@restore'] );
				$r->patch( '{id}/move', ['as' => 'move', 'uses' => 'ThreadController@move'] );
				$r->patch( '{id}/lock', ['as' => 'lock', 'uses' => 'ThreadController@lock'] );
				$r->patch( '{id}/unlock', ['as' => 'unlock', 'uses' => 'ThreadController@unlock'] );
				$r->patch( '{id}/pin', ['as' => 'pin', 'uses' => 'ThreadController@pin'] );
				$r->patch( '{id}/unpin', ['as' => 'unpin', 'uses' => 'ThreadController@unpin'] );
				$r->patch( '{id}/rename', ['as' => 'rename', 'uses' => 'ThreadController@rename'] );
			} );

			// Posts
			$r->group( ['prefix' => 'post', 'as' => 'post.'], function ( Router $r )
			{
				$r->get( '/', ['as' => 'index', 'uses' => 'PostController@index'] );
				$r->post( '/', ['as' => 'create', 'uses' => 'PostController@create'] );
				$r->get( '{id}', ['as' => 'show', 'uses' => 'PostController@show'] );
				$r->delete( '{id}', ['as' => 'delete', 'uses' => 'PostController@destroy'] );
				$r->patch( '{id}/restore', ['as' => 'restore', 'uses' => 'PostController@restore'] );
				$r->patch( '{id}', ['as' => 'update', 'uses' => 'PostController@update'] );
			} );

			// Bulk actions
			$r->group( ['prefix' => 'bulk', 'as' => 'bulk.'], function ( Router $r )
			{
				// Threads
				$r->group( ['prefix' => 'thread', 'as' => 'thread.'], function ( Router $r )
				{
					$r->delete( '/', ['as' => 'delete', 'uses' => 'ThreadController@bulkDestroy'] );
					$r->patch( 'restore', ['as' => 'restore', 'uses' => 'ThreadController@bulkRestore'] );
					$r->patch( 'move', ['as' => 'move', 'uses' => 'ThreadController@bulkMove'] );
					$r->patch( 'lock', ['as' => 'lock', 'uses' => 'ThreadController@bulkLock'] );
					$r->patch( 'unlock', ['as' => 'unlock', 'uses' => 'ThreadController@bulkUnlock'] );
					$r->patch( 'pin', ['as' => 'pin', 'uses' => 'ThreadController@bulkPin'] );
					$r->patch( 'unpin', ['as' => 'unpin', 'uses' => 'ThreadController@bulkUnpin'] );
				} );

				// Posts
				$r->group( ['prefix' => 'post', 'as' => 'post.'], function ( Router $r )
				{
					$r->patch( '/', ['as' => 'update', 'uses' => 'PostController@bulkUpdate'] );
					$r->delete( '/', ['as' => 'delete', 'uses' => 'PostController@bulkDestroy'] );
					$r->patch( 'restore', ['as' => 'restore', 'uses' => 'PostController@bulkRestore'] );
				} );
			} );
		} );
	} );
}
