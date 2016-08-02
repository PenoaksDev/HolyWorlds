<?php namespace Milky\Bus;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
trait DispatchesJobs
{
	/**
	 * Dispatch a job to its appropriate handler.
	 *
	 * @param  mixed $job
	 * @return mixed
	 */
	protected function dispatch( $job )
	{
		return Dispatcher::i()->dispatch( $job );
	}

	/**
	 * Dispatch a command to its appropriate handler in the current process.
	 *
	 * @param  mixed $job
	 * @return mixed
	 */
	public function dispatchNow( $job )
	{
		return Dispatcher::i()->dispatchNow( $job );
	}
}
