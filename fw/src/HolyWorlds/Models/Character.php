<?php namespace HolyWorlds\Models;

use Codesleeve\Stapler\Attachment;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use HolyWorlds\Support\Traits\Commentable;
use HolyWorlds\Support\Traits\HasOwner;
use Milky\Database\Eloquent\Model;
use Milky\Database\Eloquent\Relations\BelongsTo;

class Character extends Model implements StaplerableInterface
{
	use Commentable, HasOwner, EloquentTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'class_id', 'name', 'age', 'occupation', 'description', 'main'];

	/**
	 * User-friendly model name.
	 *
	 * @return string
	 */
	public $friendlyName = 'Character';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	public static function boot()
	{
		parent::boot();

		static::deleting( function ( $character )
		{
			foreach ( $character->attachments as $attachment )
			{
				$attachment->delete();
			}
			foreach ( $character->comments as $comment )
			{
				$comment->delete();
			}
		} );
	}

	/**
	 * Relationship: class
	 *
	 * @return BelongsTo
	 */
	public function gameClass()
	{
		return $this->belongsTo( CharacterClass::class, 'class_id' );
	}

	/**
	 * Attribute: portrait.
	 *
	 * @return Attachment
	 */
	public function getPortraitAttribute()
	{
		return $this->findAttachmentByKey( 'portrait' );
	}

	/**
	 * Attribute: portrait URL.
	 *
	 * @return string
	 */
	public function getPortraitUrlAttribute()
	{
		return !is_null( $this->portrait ) ? $this->portrait->getUrl() : url( 'images/game/class/' . strtolower( $this->gameClass->name ) . '.jpg' );
	}

	/**
	 * Attribute: URL.
	 *
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return route( 'character.show', ['character' => $this->id, 'name' => str_slug( $this->name, '-' )] );
	}
}
