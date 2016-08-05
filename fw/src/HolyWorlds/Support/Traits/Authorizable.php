<?php namespace HolyWorlds\Support\Traits;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
trait Authorizable
{
	/**
	 * Determine if the entity has a given ability.
	 *
	 * @param  string $ability
	 * @param  array|mixed $arguments
	 * @return bool
	 */
	public function can( $ability, $arguments = [] )
	{
		return true;
		// return app( Gate::class )->forUser( $this )->check( $ability, $arguments );
	}

	/**
	 * Determine if the entity does not have a given ability.
	 *
	 * @param  string $ability
	 * @param  array|mixed $arguments
	 * @return bool
	 */
	public function cant( $ability, $arguments = [] )
	{
		return false;
		// return !$this->can( $ability, $arguments );
	}

	/**
	 * Determine if the entity does not have a given ability.
	 *
	 * @param  string $ability
	 * @param  array|mixed $arguments
	 * @return bool
	 */
	public function cannot( $ability, $arguments = [] )
	{
		return false;
		// return $this->cant( $ability, $arguments );
	}
}
