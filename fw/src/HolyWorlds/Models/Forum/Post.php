<?php namespace HolyWorlds\Models\Forum;

use HolyWorlds\Support\Traits\CachesData;
use HolyWorlds\Support\Traits\HasAuthor;
use Milky\Database\Eloquent\Relations\BelongsTo;
use Milky\Database\Eloquent\Relations\HasMany;
use Milky\Database\Eloquent\RoutableModel;
use Milky\Database\Eloquent\SoftDeletes;
use Milky\Facades\Config;
use Milky\Facades\URL;
use Milky\Helpers\Str;

class Post extends BaseModel implements RoutableModel
{
	use SoftDeletes, CachesData, HasAuthor;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'forum_posts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'thread_id',
		'category_id',
		'author_id',
		'content',
		'created_at',
		'updated_at',
		'deleted_at',
		'post_id'
	];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['author'];

	/**
	 * Create a new post model instance.
	 *
	 * @param  array $attributes
	 */
	public function __construct( array $attributes = [] )
	{
		parent::__construct( $attributes );
		$this->setPerPage( 25 );
	}

	/**
	 * Relationship: Thread.
	 *
	 * @return BelongsTo
	 */
	public function thread()
	{
		return $this->belongsTo( Thread::class )->withTrashed();
	}

	/**
	 * Relationship: Parent post.
	 *
	 * @return BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo( Post::class, 'post_id' );
	}

	/**
	 * Relationship: Child posts.
	 *
	 * @return HasMany
	 */
	public function children()
	{
		return $this->hasMany( Post::class, 'post_id' )->withTrashed();
	}

	public function getContentAttribute()
	{
		return str_replace( "{SMILIES_PATH}", URL::asset( 'images/smiles' ), $this->attributes['content'] );
	}

	/**
	 * Attribute: First post flag.
	 *
	 * @return boolean
	 */
	public function getIsFirstAttribute()
	{
		return $this->id == $this->thread->firstPost->id;
	}

	/**
	 * Attribute: Sequence number in thread.
	 *
	 * @return int
	 */
	public function getSequenceNumberAttribute()
	{
		$self = $this;

		return $this->remember( 'sequenceNumber', function () use ( $self )
		{
			foreach ( $self->thread->posts as $index => $post )
				if ( $post->id == $self->id )
					return $index + 1;
		} );
	}

	public function appendRoute( $route, &$parameters, &$appendedUrl )
	{
		if ( $route == 'forum.thread.show' )
		{
			// The requested route is for a thread; we need to specify the page number and append a hash for the post
			$parameters['page'] = ceil( $this->sequenceNumber / $this->getPerPage() );
			$appendedUrl = "#post-{$this->sequenceNumber}";
		}
		else
			// Other post routes require the post parameter
			$parameters['post'] = $this->id;

		return [
			'category' => $this->thread->category->id,
			'category_slug' => Str::slugify( $this->thread->category->title ),
			'thread' => $this->thread->id,
			'thread_slug' => Str::slugify( $this->thread->title )
		];
	}
}
