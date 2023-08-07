<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;


Route::get('/', [PostController::class, 'index'])->name('home');

// route binding key to an eloquent model, wild card name has to match the function parameter
// inject models instances into your routes and controllers, automatically find the id specified in route url
// 'post/{post:slug}' if you want to find the slug that match the route parameter Post::where('slug', $post)->firstOrFail()
Route::get('posts/{post}', [PostController::class, 'show']);

Route::get('register', [RegisterController::class, 'create']);
Route::post('register', [RegisterController::class, 'store']);