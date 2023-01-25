<?php

use App\Http\Controllers\LineCallbackGet;
use App\Http\Controllers\LineLoginGet;
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

Route::get('/', function () {
    return view('welcome');
})->name('default');

// Line
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@main')->name('login');
Route::prefix('/line')->group(function () {
    Route::get('/login', LineLoginGet::class)->name('line_login');
    Route::get('/login/callback', LineCallbackGet::class)->name('line_callback');
});
Route::get('/register', 'App\Http\Controllers\Auth\LoginController@signup')->name('register');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@destroy')->name('logout');

// hydra
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('auth_login');
Route::post('/register', 'App\Http\Controllers\Auth\LoginController@register')->name('auth_register');
Route::get('/login/callback', 'App\Http\Controllers\Auth\LoginController@test')->name('hydra_callback');
