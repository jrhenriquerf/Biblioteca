<?php

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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/book/search', 'BookController@search')->name('book.search');
Route::get('/author/search', 'AuthorController@search')->name('author.search');
Route::resource('book', 'BookController');
Route::resource('lending', 'LendingController');
Route::resource('author', 'AuthorController');