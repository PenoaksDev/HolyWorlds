<?php
namespace Shared\Support;

use Models\Article;
use Models\Forum\Category;
use Models\Forum\Thread;
use Models\Forum\Post;
use Util;
use Session;

class Helper
{
	/**
	 * Generate a URL to a named forum route.
	 *
	 * @param  string $route
	 * @param  null|\Foundation\Database\Eloquent\Model $model
	 * @return string
	 */
	public static function route( $route, $model = null )
	{
		if ( !starts_with( $route, config( 'forum.routing.as' ) ) )
		{
			$route = config( 'forum.routing.as' ) . $route;
		}
		$params = [];
		$append = '';
		if ( $model )
		{
			switch ( true )
			{
				case $model instanceof Article:
					$params = [
						'article' => $model->id,
						'title' => ( empty( $model->slug ) ? Util::slugify( $model->title ) : $model->slug )
					];
					break;
				case $model instanceof Category:
					$params = [
						'category' => $model->id,
						'category_slug' => Util::slugify( $model->title )
					];
					break;
				case $model instanceof Thread:
					$params = [
						'category' => $model->category->id,
						'category_slug' => Util::slugify( $model->category->title ),
						'thread' => $model->id,
						'thread_slug' => Util::slugify( $model->title )
					];
					break;
				case $model instanceof Post:
					$params = [
						'category' => $model->thread->category->id,
						'category_slug' => Util::slugify( $model->thread->category->title ),
						'thread' => $model->thread->id,
						'thread_slug' => Util::slugify( $model->thread->title )
					];
					if ( $route == config( 'forum.routing.as' ) . 'thread.show' )
					{
						// The requested route is for a thread; we need to specify the page number and append a hash for
						// the post
						$params['page'] = ceil( $model->sequenceNumber / $model->getPerPage() );
						$append = "#post-{$model->sequenceNumber}";
					}
					else
					{
						// Other post routes require the post parameter
						$params['post'] = $model->id;
					}
					break;
			}
		}

		return route( $route, $params ) . $append;
	}
}
