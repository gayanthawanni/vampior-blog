<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::post('/users/{user}/role', [AdminController::class, 'updateRole'])
            ->name('users.role');

        Route::post('/posts/{post}/approve', [AdminController::class, 'approvePost'])
            ->name('posts.approve');

        Route::post('/posts/{post}/reject', [AdminController::class, 'rejectPost'])
            ->name('posts.reject');

        Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])
            ->name('posts.delete');
});

/*
|--------------------------------------------------------------------------
| Comments
|--------------------------------------------------------------------------
*/
Route::post('/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

/*
|--------------------------------------------------------------------------
| Likes & Saves
|--------------------------------------------------------------------------
*/
Route::post('/posts/{post}/like', function (Post $post) {
    auth()->user()->likes()->toggle($post->id);
    return back();
})->middleware('auth')->name('posts.like');

Route::delete('/posts/{post}/unlike', function (Post $post) {
        auth()->user()->likes()->detach($post->id);
        return back();
    })->name('posts.unlike');

     Route::post('/posts/{post}/save', function (Post $post) {
        auth()->user()->bookmarks()->toggle($post->id);
        return back();
    })->name('posts.save');

    Route::delete('/posts/{post}/unsave', function (Post $post) {
        auth()->user()->bookmarks()->detach($post->id);
        return back();
    })->name('posts.unsave');

Route::post('/posts/{post}/save', function (Post $post) {
    auth()->user()->bookmarks()->toggle($post->id);
    return back();
})->middleware('auth')->name('posts.save');

/*
|--------------------------------------------------------------------------
| Blog View
|--------------------------------------------------------------------------
*/
Route::get('/blog/{slug}', function ($slug) {
    $post = Post::where('slug', $slug)->firstOrFail();
    return view('posts.show', compact('post'));
})->name('blog.show');

/*
|--------------------------------------------------------------------------
| Posts (Admin & Editor)
|--------------------------------------------------------------------------
*/
Route::resource('posts', PostController::class)
    ->middleware(['auth', 'role:admin|editor']);

/*
|--------------------------------------------------------------------------
| General Pages
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
