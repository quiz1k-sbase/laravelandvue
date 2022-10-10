<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::get('posts', [PostController::class, 'index']);
Route::controller(PostController::class)->group(function () {
    Route::post('store', 'store');
    Route::post('update/{id}', 'update');
    Route::delete('delete/{id}', 'delete');
});

Route::get('comments', [CommentController::class, 'index']);
Route::controller(CommentController::class)->group(function () {
    Route::post('storeComment', 'store');
    Route::post('storeCommentReply', 'storeReply');
    Route::post('updateComment/{id}', 'update');
    Route::delete('deleteComment/{id}', 'delete');
});


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

/*Route::get("{any}", [\App\Http\Controllers\Api\UserController::class, 'index'])->where("any", ".*");*/
