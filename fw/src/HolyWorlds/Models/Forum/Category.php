<?php namespace HolyWorlds\Models\Forum;

use HolyWorlds\Support\Traits\CachesData;
use Milky\Account\Permissions\PermissionManager;
use Milky\Database\Eloquent\Relations\BelongsTo;
use Milky\Database\Eloquent\Relations\HasMany;
use Milky\Database\Eloquent\RoutableModel;
use Milky\Facades\Config;
use Milky\Helpers\Str;
use Milky\Pagination\LengthAwarePaginator;

class Category extends BaseModel implements RoutableModel
{
	use CachesData;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'forum_categories';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'category_id',
		'title',
		'description',
		'weight',
		'enable_threads',
		'permission',
		'created_at',
		'updated_at'
	];

	/**
	 * Create a new category model instance.
	 *
	 * @param  array $attributes
	 */
	public function __construct( array $attributes = [] )
	{
		parent::__construct( $attributes );

		$this->perPage = Config::get( 'forum.preferences.pagination.categories' );
	}

	/**
	 * Relationship: Parent category.
	 *
	 * @return BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo( Category::class, 'category_id' )->orderBy( 'weight' );
	}

	/**
	 * Relationship: Child categories.
	 *
	 * @return HasMany
	 */
	public function children()
	{
		return $this->hasMany( Category::class, 'category_id' )->orderBy( 'weight' );
	}

	/**
	 * Relationship: Threads.
	 *
	 * @return HasMany
	 */
	public function threads()
	{
		$withTrashed = PermissionManager::i()->has( 'forum.viewTrashedThreads' );
		$query = $this->hasMany( Thread::class );

		return $withTrashed ? $query->withTrashed() : $query;
	}

	/**
	 * Attribute: Child categories.
	 *
	 * @return Collection
	 */
	public function getChildrenAttribute()
	{
		$children = $this->children()->get();

		$children = $children->filter( function ( $category )
		{
			if ( $category->private )
			{
				return Gate::allows( 'view', $category );
			}

			return true;
		} );

		return $children;
	}

	/**
	 * Attribute: Paginated threads.
	 *
	 * @return LengthAwarePaginator
	 */
	public function getThreadsPaginatedAttribute()
	{
		// TODO ->orderBy('pinned', 'desc')
		return $this->threads()->orderBy( 'updated_at', 'desc' )->paginate( Config::get( 'forum.preferences.pagination.threads' ) );
	}

	/**
	 * Attribute: Newest thread.
	 *
	 * @return Thread
	 */
	public function getNewestThreadAttribute()
	{
		return $this->threads()->orderBy( 'created_at', 'desc' )->first();
	}

	/**
	 * Attribute: Latest active thread.
	 *
	 * @return Thread
	 */
	public function getLatestActiveThreadAttribute()
	{
		return $this->threads()->orderBy( 'updated_at', 'desc' )->first();
	}

	/**
	 * Attribute: New threads enabled.
	 *
	 * @return bool
	 */
	public function getThreadsEnabledAttribute()
	{
		return $this->enable_threads;
	}

	/**
	 * Attribute: Thread count.
	 *
	 * @return int
	 */
	public function getThreadCountAttribute()
	{
		return $this->remember( 'threadCount', function ()
		{
			return $this->threads->count();
		} );
	}

	/**
	 * Attribute: Deepest child.
	 *
	 * @return Category
	 */
	public function getDeepestChildAttribute()
	{
		$category = $this;

		return $this->remember( 'deepestChild', function () use ( $category )
		{
			while ( $category->parent )
			{
				$category = $category->parent;
			}

			return $category;
		} );
	}

	/**
	 * Attribute: Depth.
	 *
	 * @return int
	 */
	public function getDepthAttribute()
	{
		$category = $this;

		return $this->remember( 'depth', function () use ( $category )
		{
			$depth = 0;

			while ( $category->parent )
			{
				$depth++;
				$category = $category->parent;
			}

			return $depth;
		} );
	}

	/**
	 * @param $parameters
	 * @param $appendedUrl
	 *
	 * @return array
	 */
	public function appendRoute( $route, &$parameters, &$appendedUrl )
	{
		return [
			'category' => $this->id,
			'category_slug' => Str::slugify( $this->title )
		];
	}
}
