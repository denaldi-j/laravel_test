<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\IndexController;

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

Route::get('/', [IndexController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'category'], function() {
	Route::get('/', [CategoryController::class, 'index']);
	Route::get('/list', [CategoryController::class, 'get']);
	Route::post('/add', [CategoryController::class, 'store']);
	Route::post('/update/{id}', [CategoryController::class, 'update']);
	Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
});

Route::group(['middleware' => 'auth', 'prefix' => 'book'], function() {
    Route::get('/index', function () {
        return view('book');
    });
    Route::get('/', [BookController::class, 'index']);
    Route::post('/add', [BookController::class, 'store']);
    Route::post('/update/{id}', [BookController::class, 'update']);
    Route::delete('/delete/{id}', [BookController::class, 'delete']);
});
