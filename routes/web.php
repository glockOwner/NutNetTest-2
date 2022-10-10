<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PerformerController;
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


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['controller' => PerformerController::class, 'prefix' => '/performers'], function () {
    Route::get('/', 'index')->name('performers.index');
    Route::group(['middleware' => 'is_auth'], function () {
        Route::get('/create', 'create')->name('performers.create');
        Route::post('/store', 'store')->name('performers.store');
        Route::get('/edit/{performerId}', 'edit')->name('performers.edit');
        Route::patch('/update/{performerId}', 'update')->name('performers.update');
        Route::delete('/delete/{performerId}', 'delete')->name('performers.delete');
    });
});

Route::group(['controller' => AlbumController::class, 'prefix' => '/albums'], function () {
    Route::get('/', 'index')->name('albums.index');
    Route::group(['middleware' => 'is_auth'], function () {
        Route::get('/create', 'create')->name('albums.create');
        Route::post('/store', 'store')->name('albums.store');
        Route::get('/edit/{albumId}', 'edit')->name('albums.edit');
        Route::patch('/update/{albumId}', 'update')->name('albums.update');
        Route::delete('/delete/{albumId}', 'delete')->name('albums.delete');
    });
});
