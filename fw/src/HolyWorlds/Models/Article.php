<?php namespace HolyWorlds\Models;

use Carbon\Carbon;
use HolyWorlds\Support\Helper;
use HolyWorlds\Support\Traits\Commentable;
use HolyWorlds\Support\Traits\HasOwner;
use HolyWorlds\Tagging\Taggable;
use Milky\Database\Eloquent\Model;
use Milky\Database\Eloquent\RoutableModel;
use Milky\Helpers\Str;

class Article extends Model implements RoutableModel
{
	use Commentable, HasOwner, Taggable;

	protected $fillable = ['id', 'user_id', 'slug', 'title', 'body', 'published_at', 'created_at', 'updated_at'];
	protected $dates = ['published_at'];
	public $friendlyName = 'Article';

	public function scopePublished( $query )
	{
		return $this->where( 'published_at', '<=', Carbon::now() );
	}

	public function latestRevision()
	{
		return $this->revisions()->orderBy( 'created_at', 'desc' )->first();
	}

	public function getUrlAttribute()
	{
		return Helper::route( 'article.show', $this );
	}

	public function revisions()
	{
		return $this->hasMany( ArticleRevision::class );
	}

	public function appendRoute( $route, &$parameters, &$appendedUrl )
	{
		return [
			'article' => $this->id,
			'title' => ( empty( $this->slug ) ? Str::slugify( $this->title ) : $this->slug )
		];
	}
}
