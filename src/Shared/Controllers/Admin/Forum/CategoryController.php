<?php
namespace Shared\Http\Controllers\Admin\Forum;

use Http\Controllers\Admin\Controller;
use Models\Forum\Category;
use Penoaks\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display an index of forum categories.
     *
     * @return \Foundation\Http\Response
     */
    public function index()
    {
        return view('admin.forum.category.index', [
            'categories' => Category::orderBy('weight', 'desc')->get()
        ]);
    }
}
