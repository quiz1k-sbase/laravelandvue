<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\XMLParserController;
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
    Route::get('userDataExport/{user}', 'userDataExport');
    Route::get('index', 'index');
    Route::get('logout', 'logout');
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
    Route::post('getUsername/{id}', 'getUsername');
});

Route::get('userdata', [AuthController::class, 'index']);
Route::controller(AuthController::class)->group(function () {
    Route::post('changeName', 'changeName');
    Route::post('changeEmail', 'changeEmail');
    Route::post('changePhone', 'changePhone');
    Route::post('changePassword', 'changePassword');
    Route::post('addPhoto', 'addPhoto');
});

/*Route::middleware("auth")->group(function () {
    Route::get('plansData', [StripeController::class, 'index']);
    Route::get('plansData/{plan}', [StripeController::class, 'show'])->name("plans.show");
    Route::post('subscriptionCreate', [StripeController::class, 'subscription'])->name("subscription.create");
    Route::get('getToken', [StripeController::class, 'getToken'])->name("getToken");
    Route::post('donate', [StripeController::class, 'donate'])->name("donate");
});*/

Route::middleware("auth")->group(function () {
    Route::get('getToken', [BalanceController::class, 'index']);
    Route::post('donate', [BalanceController::class, 'store'])->name("donate");
});

Route::get('parse', [XMLParserController::class, 'parse']);

Route::get('auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('callback', [GoogleAuthController::class, 'callbackGoogle']);

Route::post('store/photo', [PhotoController::class, 'store']);
Route::get('get/photos/{id}', [PhotoController::class, 'getPhotos']);
Route::post('update/photo/title', [PhotoController::class, 'updateTitle']);
Route::post('update/photo/description', [PhotoController::class, 'updateDescription']);
Route::post('update/photo/image', [PhotoController::class, 'updateImage']);

Route::post('store/video', [VideoController::class, 'store']);
Route::get('get/videos/{id}', [VideoController::class, 'getVideos']);
Route::post('update/video/title', [VideoController::class, 'updateTitle']);
Route::post('update/video/description', [VideoController::class, 'updateDescription']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

/*Route::get("{any}", [\App\Http\Controllers\Api\UserController::class, 'index'])->where("any", ".*");*/
