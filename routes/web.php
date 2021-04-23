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

Route::get('/home', function () {
    return view('home')->with('movies', \App\Models\Movie::all())->with('categories', \App\Models\Category::all());
});

Route::get('/movies', ['App\Http\Controllers\MovieController', 'index'])->name('movies');
Route::get('/categories/{category}', ['App\Http\Controllers\MovieController', 'show'])->name('categories');

