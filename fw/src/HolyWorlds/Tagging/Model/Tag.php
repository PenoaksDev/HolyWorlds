<?php namespace HolyWorlds\Tagging\Model;

use Milky\Database\Eloquent\Model as Eloquent;
use Milky\Database\Query\Builder;
use Milky\Facades\Config;
use Milky\Framework;

/**
 * Copyright (C) 2014 Robert Conner
 */
class Tag extends Eloquent
{
	protected $table = 'tagging_tags';
	public $timestamps = false;
	protected $softDelete = false;
	public $fillable = ['name'];

	/**
	 * @param array $attributes
	 */
	public function __construct( array $attributes = [] )
	{
		parent::__construct( $attributes );

		if ( $connection = Config::get( 'tagging.connection' ) )
			$this->connection = $connection;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Illuminate\Database\Eloquent\Model::save()
	 */
	public function save( array $options = [] )
	{
		$validator = Framework::get( 'validator' )->make( ['name' => $this->name], ['name' => 'required|min:1'] );

		if ( $validator->passes() )
		{
			$normalizer = config( 'tagging.normalizer' );
			$normalizer = $normalizer ?: [$this->taggingUtility, 'slug'];

			$this->slug = call_user_func( $normalizer, $this->name );

			return parent::save( $options );
		}
		else
		{
			throw new \Exception( 'Tag Name is required' );
		}
	}

	/**
	 * Get suggested tags
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeSuggested( $query )
	{
		return $query->where( 'suggest', true );
	}

	/**
	 * Set the name of the tag : $tag->name = 'myname';
	 *
	 * @param string $value
	 */
	public function setNameAttribute( $value )
	{
		$displayer = config( 'tagging.displayer' );
		$displayer = empty( $displayer ) ? '\Illuminate\Support\Str::title' : $displayer;

		$this->attributes['name'] = call_user_func( $displayer, $value );
	}

	/**
	 * Look at the tags table and delete any tags that are no londer in use by any taggable database rows.
	 * Does not delete tags where 'suggest'value is true
	 *
	 * @return int
	 */
	public static function deleteUnused()
	{
		return ( new static )->newQuery()->where( 'count', '=', 0 )->where( 'suggest', false )->delete();
	}

}
