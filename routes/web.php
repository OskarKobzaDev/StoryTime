<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);

    Route::resource('posts', PostController::class)->shallow()->only(['store','create']);
    Route::delete('posts.delete/{post}', [PostController::class, 'destroy'])->name('posts.delete');
    Route::get('posts.edit/{post}', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts.update/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::post('/likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{type}/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');

});

Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name("posts.show");

Route::get('posts/{topic?}', [PostController::class, 'index'])->name("posts.index");

Route::get('/policy', function() {
    return Inertia::render('PrivacyPolicy', [
        'title' => 'Privacy Policy',
    ]);
}
)->name("policy.index");




