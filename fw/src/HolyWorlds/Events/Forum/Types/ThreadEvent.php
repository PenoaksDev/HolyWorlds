<?php namespace HolyWorlds\Events\Forum\Types;

use Models\Forum\Thread;

class ThreadEvent
{
    /**
     * @var Thread
     */
    public $thread;

    /**
     * Create a new event instance.
     *
     * @param  Thread  $thread
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }
}
