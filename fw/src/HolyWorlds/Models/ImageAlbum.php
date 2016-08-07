<?php namespace HolyWorlds\Models;

use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use HolyWorlds\Support\Traits\Commentable;
use HolyWorlds\Support\Traits\HasOwner;
use HolyWorlds\Tagging\Taggable;
use Milky\Database\Eloquent\Model;
use Milky\Facades\URL;

class ImageAlbum extends Model implements StaplerableInterface
{
	use Commentable, HasOwner, Taggable, EloquentTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'title', 'description'];

	/**
	 * User-friendly model name.
	 *
	 * @return string
	 */
	public $friendlyName = 'Image Album';

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::deleting( function ( $album )
		{
			foreach ( $album->attachments as $attachment )
			{
				$attachment->delete();
			}
			foreach ( $album->comments as $comment )
			{
				$comment->delete();
			}
		} );
	}

	/**
	 * Attribute: has multiple images.
	 *
	 * @return boolean
	 */
	public function getHasMultipleImagesAttribute()
	{
		return $this->attachments->count() > 1;
	}

	/**
	 * Attribute: cover URL.
	 *
	 * @return string
	 */
	public function getCoverUrlAttribute()
	{
		return URL::route( 'imagecache', [
			'template' => 'large',
			'filename' => $this->attachments->first()->item->getRelativePath()
		] );
	}

	/**
	 * Attribute: URL.
	 *
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return URL::route( 'image-album.show', ['id' => $this->id, 'name' => str_slug( $this->title )] );
	}
}
