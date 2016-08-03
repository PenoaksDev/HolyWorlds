<?php namespace HolyWorlds\Models;

use Milky\Database\Eloquent\Builder;
use Milky\Database\Eloquent\Model;
use Milky\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['last_activity'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Create a new Eloquent model instance.
	 *
	 * @param  array $attributes
	 * @return void
	 */
	public function __construct( array $attributes = [] )
	{
		$this->setDateFormat( 'U' );
		parent::__construct( $attributes );
	}

	/**
	 * Relationship: user
	 *
	 * @return BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( User::class, 'user_id' );
	}

	/**
	 * Scope: authenticated
	 *
	 * @param  Builder $query
	 * @return Builder
	 */
	public function scopeAuthenticated( $query )
	{
		return $query->with( ['user'] )->whereNotNull( 'user_id' );
	}

	/**
	 * Scope: recent
	 *
	 * @param  Builder $query
	 * @param  int $minutes
	 * @return Builder
	 */
	public function scopeRecent( $query, $minutes = 5 )
	{
		return $query->where( 'last_activity', '>=', time() - ( $minutes * 60 ) );
	}
}
