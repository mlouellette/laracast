<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\PostCommentsController;

use Illuminate\Support\Facades\Route;


Route::get('/', [PostController::class, 'index'])->name('home');

// route binding key to an eloquent model, wild card name has to match the function parameter
// inject models instances into your routes and controllers, automatically find the id specified in route url
// 'post/{post:slug}' if you want to find the slug that match the route parameter Post::where('slug', $post)->firstOrFail()
Route::get('posts/{post:slug}', [PostController::class, 'show']);
Route::post('posts/{post:slug}/comments', [PostCommentsController::class, 'store']);

Route::get('register', [RegisterController::class, 'create'])->middleware('guest');
Route::post('register', [RegisterController::class, 'store'])->middleware('guest');

Route::get('login', [SessionsController::class, 'create'])->middleware('guest');
Route::post('login', [SessionsController::class, 'store'])->middleware('guest');

Route::post('logout', [SessionsController::class, 'destroy'])->middleware('auth');

Route::post('newsletter', function () {
    request()->validate(['email' => 'required|email']);
    $mailchimp = new \MailchimpMarketing\ApiClient();

    $mailchimp->setConfig([
        'apiKey' => \config('services.mailchimp.apiKey'), 
        'server' => 'us21'
    ]);

    // $response = $mailchimp->lists->getListMembersInfo('63bc16be9b');
    try {
        $response = $mailchimp->lists->addListMember('63bc16be9b', [
            'email_address' => request('email'),
            'status' => 'subscribed'
        ]);

    } catch (\Exception $e) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => 'This email could not be added to our newsletter first'
        ]);
    }

    return redirect('/')->with('success', 'You are now signed up for our newsletter!');


});