<?php
namespace App\Events\Forum\Types;

use App\Models\Forum\Category;

class CategoryEvent
{
    /**
     * @var Category
     */
    public $category;

    /**
     * Create a new event instance.
     *
     * @param  Category  $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}
