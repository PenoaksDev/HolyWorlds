<?php namespace HolyWorlds\Tagging\Model;

use HolyWorlds\Tagging\Taggable;
use Milky\Database\Eloquent\Model as Eloquent;
use Milky\Database\Eloquent\Relations\BelongsTo;
use Milky\Database\Eloquent\Relations\MorphTo;

class Tagged extends Eloquent
{
	protected $table = 'tagging_tagged';
	public $timestamps = false;
	protected $fillable = ['tag_name', 'tag_slug'];

	public function __construct( array $attributes = [] )
	{
		parent::__construct( $attributes );
	}

	/**
	 * Morph to the tag
	 *
	 * @return MorphTo
	 */
	public function taggable()
	{
		return $this->morphTo();
	}

	/**
	 * Get instance of tag linked to the tagged value
	 *
	 * @return BelongsTo
	 */
	public function tag()
	{
		$model = Taggable::$taggingUtility->tagModelString();

		return $this->belongsTo( $model, 'tag_slug', 'slug' );
	}

}
