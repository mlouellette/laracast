<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $currentCategorySlug = request('category');
        $currentCategory = null;

        if ($currentCategorySlug) {
            $currentCategory = Category::firstWhere('slug', $currentCategorySlug);
        }

        return view('posts.index', [
            'posts' => Post::latest()->filter(request(['search', 'category', 'author']))->paginate(6)->withQueryString(),
            'currentCategory' => $currentCategory
        ]);
    }

    public function show(Post $post)
    {
        return view('posts\show', [
            'post' => $post
        ]);
    }

}

