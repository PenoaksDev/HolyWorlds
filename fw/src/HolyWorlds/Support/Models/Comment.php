<?php namespace HolyWorlds\Support\Models;

use Milky\Database\Eloquent\Collection;
use Milky\Database\Eloquent\Nested\NestedModel;
use Milky\Database\Eloquent\Relations\BelongsTo;
use Milky\Database\Eloquent\Relations\MorphTo;

class Comment extends NestedModel
{
	/**
	 * The attributes that are fillable via mass assignment.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'body'];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['user'];

	/**
	 * Determine if the comment has children.
	 *
	 * @return bool
	 */
	public function hasChildren()
	{
		return ( ( $this->rgt - $this->lft - 1 ) / 2 ) > 0;
	}

	/**
	 * Get all of comment's children.
	 *
	 * @param  array $columns
	 * @return Collection
	 */
	public function getChildren( $columns = ['*'] )
	{
		return $this->children()->get( $columns );
	}

	/**
	 * Get all of the owning commentable models.
	 *
	 * @return MorphTo
	 */
	public function commentable()
	{
		return $this->morphTo();
	}

	/**
	 * Get the user that creates the comment.
	 *
	 * @param  $configKey  string
	 * @return BelongsTo
	 */
	public function user( $configKey = 'auth.providers.users.model' )
	{
		return $this->belongsTo( config()->get( $configKey ) );
	}
}
