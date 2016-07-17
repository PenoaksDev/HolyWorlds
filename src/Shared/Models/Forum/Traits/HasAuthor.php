<?php
namespace Shared\Models\Forum\Traits;

use Shared\Models\User;

trait HasAuthor
{
	/**
	 * Relationship: Author.
	 *
	 * @return \Foundation\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo( User::class, 'author_id' );
	}

	/**
	 * Attribute: Author name.
	 *
	 * @return mixed
	 */
	public function getAuthorNameAttribute()
	{
		if (!is_null($this->author)) {
			return $this->author->name;
		}

		return null;
	}
}
