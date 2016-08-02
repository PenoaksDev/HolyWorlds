<?php namespace HolyWorlds\Support\Traits;

use HolyWorlds\Support\Models\Comment;
use Milky\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
	/**
	 * Get all of the model's comments.
	 *
	 * @return MorphMany
	 */
	public function comments()
	{
		return $this->morphMany( Comment::class, 'commentable' );
	}
}
