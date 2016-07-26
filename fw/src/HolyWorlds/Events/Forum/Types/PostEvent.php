<?php namespace HolyWorlds\Events\Forum\Types;

use Models\Forum\Post;

class PostEvent
{
    /**
     * @var Post
     */
    public $post;

    /**
     * Create a new event instance.
     *
     * @param  Post  $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
