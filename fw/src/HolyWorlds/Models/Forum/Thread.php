<?php namespace HolyWorlds\Models\Forum;

use Carbon\Carbon;
use Milky\Database\Eloquent\Relations\BelongsTo;
use Milky\Database\Eloquent\Relations\BelongsToMany;
use Milky\Database\Eloquent\Relations\HasMany;
use Milky\Database\Eloquent\RoutableModel;
use Milky\Database\Eloquent\SoftDeletes;
use HolyWorlds\Support\Traits\HasAuthor;
use HolyWorlds\Models\Setting;
use HolyWorlds\Models\User;
use Milky\Database\Query\Builder;
use Milky\Helpers\Str;
use Milky\Pagination\LengthAwarePaginator;

class Thread extends BaseModel implements RoutableModel
{
	use SoftDeletes, HasAuthor;

	/**
	 * Eloquent attributes
	 */
	protected $table = 'forum_threads';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'category_id',
		'author_id',
		'title',
		'type',
		'locked',
		'last_post',
		'last_thread',
		'views',
		'posts',
		'created_at',
		'updated_at',
		'deleted_at',
		'poll_title',
		'poll_start',
		'poll_length',
		'poll_max_options',
		'poll_last_vote',
		'poll_vote_change'
	];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['author'];

	/**
	 * @var string
	 */
	const STATUS_UNREAD = 'unread';

	/**
	 * @var string
	 */
	const STATUS_UPDATED = 'updated';

	/**
	 * Create a new thread model instance.
	 *
	 * @param  array $attributes
	 */
	public function __construct( array $attributes = [] )
	{
		parent::__construct( $attributes );
		$this->perPage = 25;
	}

	/**
	 * Relationship: Category.
	 *
	 * @return BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo( Category::class );
	}

	/**
	 * Relationship: Readers.
	 *
	 * @return BelongsToMany
	 */
	public function readers()
	{
		return $this->belongsToMany( User::class, 'forum_threads_read', 'thread_id', 'user_id' )->withTimestamps();
	}

	/**
	 * Relationship: Posts.
	 *
	 * @return HasMany
	 */
	public function posts()
	{
		$withTrashed = false; //config('forum.preferences.display_trashed_posts') || Gate::allows('viewTrashedPosts');
		$query = $this->hasMany( Post::class );

		return $withTrashed ? $query->withTrashed() : $query;
	}

	/**
	 * Scope: Recent threads.
	 *
	 * @param  Builder $query
	 * @return Builder
	 */
	public function scopeRecent( $query )
	{
		$time = time();
		$age = strtotime( '7 days' ); //config('forum.preferences.old_thread_threshold'), 0);
		$cutoff = $time - $age;

		return $query->where( 'updated_at', '>', date( 'Y-m-d H:i:s', $cutoff ) )->orderBy( 'updated_at', 'desc' );
	}

	/**
	 * Attribute: Paginated posts.
	 *
	 * @return LengthAwarePaginator
	 */
	public function getPostsPaginatedAttribute()
	{
		return $this->posts()->paginate( Setting::findOrNew( 'forum_posts_per_page' )->value( 15 ) );
	}

	/**
	 * Attribute: The last page number of the thread.
	 *
	 * @return int
	 */
	public function getLastPageAttribute()
	{
		return $this->postsPaginated->lastPage();
	}

	/**
	 * Attribute: The first post in the thread.
	 *
	 * @return Post
	 */
	public function getFirstPostAttribute()
	{
		return $this->posts()->orderBy( 'created_at', 'asc' )->limit( 1 )->first();
	}

	/**
	 * Attribute: The last post in the thread.
	 *
	 * @return Post
	 */
	public function getLastPostAttribute()
	{
		return Post::find( $this->attributes['last_post'] );
	}

	/**
	 * Attribute: Creation time of the last post in the thread.
	 *
	 * @return Carbon
	 */
	public function getLastPostTimeAttribute()
	{
		return $this->lastPost->created_at;
	}

	/**
	 * Attribute: 'Old' flag.
	 *
	 * @return boolean
	 */
	public function getOldAttribute()
	{
		$age = Setting::get( 'forum_trash_threshold' );

		return ( !$age || $this->updated_at->timestamp < ( time() - strtotime( $age, 0 ) ) );
	}

	/**
	 * Attribute: Currently authenticated reader.
	 *
	 * @return mixed
	 */
	public function getReaderAttribute()
	{
		if ( auth()->check() )
		{
			$reader = $this->readers()->where( 'user_id', auth()->user()->getKey() )->first();

			return ( !is_null( $reader ) ) ? $reader->pivot : null;
		}

		return null;
	}

	/**
	 * Attribute: Read/unread/updated status for current reader.
	 *
	 * @return mixed
	 */
	public function getUserReadStatusAttribute()
	{
		if ( !$this->old && auth()->check() )
		{
			if ( is_null( $this->reader ) )
				return self::STATUS_UNREAD;

			return ( $this->updatedSince( $this->reader ) ) ? self::STATUS_UPDATED : false;
		}

		return false;
	}

	/**
	 * Helper: Mark this thread as read for the given user ID.
	 *
	 * @param  int $userID
	 * @return $this
	 */
	public function markAsRead( $userID )
	{
		if ( !$this->old )
		{
			if ( is_null( $this->reader ) )
				$this->readers()->attach( $userID );
			elseif ( $this->updatedSince( $this->reader ) )
				$this->reader->touch();
		}

		return $this;
	}

	public function appendRoute( $route, &$parameters, &$appendedUrl )
	{
		return [
			'category' => $this->category->id,
			'category_slug' => Str::slugify( $this->category->title ),
			'thread' => $this->id,
			'thread_slug' => Str::slugify( $this->title )
		];
	}
}
