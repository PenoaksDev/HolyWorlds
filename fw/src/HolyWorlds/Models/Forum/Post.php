<?php
namespace HolyWorlds\Models\Forum;

use Illuminate\Database\Eloquent\SoftDeletes;
use HolyWorlds\Support\Traits\HasAuthor;
use HolyWorlds\Support\Traits\CachesData;

class Post extends BaseModel
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
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function thread()
	{
		return $this->belongsTo( Thread::class )->withTrashed();
	}

	/**
	 * Relationship: Parent post.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo( Post::class, 'post_id' );
	}

	/**
	 * Relationship: Child posts.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function children()
	{
		return $this->hasMany( Post::class, 'post_id' )->withTrashed();
	}

	public function getContentAttribute()
	{
		return str_replace( "{SMILIES_PATH}", \URL::asset( 'images/smiles' ), $this->attributes['content'] );
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
			{
				if ( $post->id == $self->id )
				{
					return $index + 1;
				}
			}
		} );
	}
}
