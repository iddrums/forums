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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/threads', [App\Http\Controllers\ThreadsController::class, 'index'])->name('threads');
Route::get('/threads/create', [App\Http\Controllers\ThreadsController::class, 'create'])->name('create');
Route::get('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadsController::class, 'show']);
Route::delete('/threads/{channel}/{thread}', [App\Http\Controllers\ThreadsController::class, 'destroy']);
Route::post('/threads', [App\Http\Controllers\ThreadsController::class, 'store'])->name('store');
Route::get('/threads/{channel}', [App\Http\Controllers\ThreadsController::class, 'index'])->name('threads');
Route::post('/threads/{channel}/{thread}/replies', [App\Http\Controllers\RepliesController::class, 'store'])->name('store');
Route::delete('/replies/{reply}', [App\Http\Controllers\RepliesController::class, 'destroy']);
Route::post('/replies/{reply}/favorites', [App\Http\Controllers\FavoritesController::class, 'store'])->name('store');


Route::get('/profiles/{user}', [App\Http\Controllers\ProfilesController::class, 'show'])->name('profiles.user');

