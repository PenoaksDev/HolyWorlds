<?php namespace HolyWorlds\Support\Traits;

use HolyWorlds\Models\User;

/**
 * The MIT License (MIT)
 * Copyright 2017 Penoaks Publishing Ltd. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
trait HasOwner
{
	/**
	 * Relationship: user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( User::class );
	}

	/**
	 * Scope: by user
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @param  User $user
	 * @return \Illuminate\Database\Query\Builder
	 */
	public function scopeByUser( $query, User $user )
	{
		return $query->where( 'id', $user->id );
	}
}
