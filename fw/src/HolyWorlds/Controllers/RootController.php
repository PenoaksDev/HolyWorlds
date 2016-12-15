<?php namespace HolyWorlds\Controllers;

use HolyWorlds\Models\Article;
use HolyWorlds\Models\Forum\Post;
use HolyWorlds\Models\Forum\Thread;
use HolyWorlds\Models\Session;
use HolyWorlds\Models\User;
use Milky\Facades\View;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class RootController extends BaseController
{
	public function index()
	{
		return View::render( 'index', [
			'newUsers' => User::activated()->orderBy( 'created_at', 'desc' )->limit( 5 )->get(),
			'onlineUsers' => Session::authenticated()->groupBy( 'user_id' )->recent()->limit( 10 )->get(),
			'newThreads' => Thread::with( ['author', 'posts'] )->orderBy( 'created_at', 'desc' )->limit( 5 )->get(),
			'newPosts' => Post::where( 'post_id', '!=', null )->orderBy( 'created_at', 'DESC' )->limit( 5 )->get(),
			'articles' => Article::published()->orderBy( 'published_at', 'desc' )->paginate()
		] );
	}
}
