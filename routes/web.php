<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);
    Route::resource('posts', PostController::class)->only(['create', 'store']);

});

require __DIR__.'/settings.php';

Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');


