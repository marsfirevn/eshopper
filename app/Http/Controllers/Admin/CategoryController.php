<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = factory(Category::class, 5)->create();
    }
}
