<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controller\ThreadsController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::view('scan', 'scan');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/threads', [App\Http\Controllers\ThreadsController::class, 'index'])->name('threads');
Route::get('/threads/create', [App\Http\Controllers\ThreadsController::class, 'create'])->name('create');
Route::get('/threads/search', [App\Http\Controllers\SearchController::class, 'show']);
Route::get('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadsController::class, 'show']);
Route::patch('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadsController::class, 'update']);
Route::delete('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadsController::class, 'destroy']);
Route::post('/threads', [App\Http\Controllers\ThreadsController::class, 'store'])->middleware('must-be-confirmed');
Route::get('/threads/{channel}', [App\Http\Controllers\ThreadsController::class, 'index'])->name('threads');


Route::post('/locked-threads/{thread}', [App\Http\Controllers\LockedThreadsController::class, 'store'])->name('locked-threads.store')->middleware('admin');
Route::delete('/locked-threads/{thread}', [App\Http\Controllers\LockedThreadsController::class, 'destroy'])->name('locked-threads.destroy')->middleware('admin');


Route::get('/threads/{channel}/{thread}/replies', [App\Http\Controllers\RepliesController::class, 'index']);
Route::post('/threads/{channel}/{thread}/replies', [App\Http\Controllers\RepliesController::class, 'store'])->name('store');
Route::patch('/replies/{reply}', [App\Http\Controllers\RepliesController::class, 'update']);
Route::delete('/replies/{reply}', [App\Http\Controllers\RepliesController::class, 'destroy'])->name('replies.destroy');

Route::post('/replies/{reply}/best', [App\Http\Controllers\BestRepliesController::class, 'store'])->name('best-replies.store');


Route::post('/threads/{channel}/{thread}/subscriptions', [App\Http\Controllers\ThreadSubscriptionsController::class, 'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [App\Http\Controllers\ThreadSubscriptionsController::class, 'destroy'])->middleware('auth');



Route::post('/replies/{reply}/favorites', [App\Http\Controllers\FavoritesController::class, 'store']);
Route::delete('/replies/{reply}/favorites', [App\Http\Controllers\FavoritesController::class, 'store']);

Route::get('/register/confirm', [App\Http\Controllers\Api\RegisterConfirmationController::class, 'index'])->name('register.confirm');


Route::get('/profiles/{user}', [App\Http\Controllers\ProfilesController::class, 'show'])->name('profiles.user');
Route::get('/profiles/{user}/notifications', [App\Http\Controllers\UserNotificationsController::class, 'index']);
Route::delete('/profiles/{user}/notifications/{notification}', [App\Http\Controllers\UserNotificationsController::class, 'destroy']);

Route::get('api/users', [App\Http\Controllers\Api\UsersController::class, 'index']);
Route::post('api/users/{user}/avatar', [App\Http\Controllers\Api\UserAvatarController::class, 'store'])->middleware('auth')->name('avatar');



