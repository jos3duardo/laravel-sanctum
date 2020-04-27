<?php

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
    return response()->json([
        'message' => 'Welcome Laravel Sanctum',
        'author' => 'jos3daurdo',
        'email' => 'jos3duardolopes@gmail.com',
        'git' => 'https://github.com/jos3duardo'
    ],200);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
