<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth.token')->prefix('/v1')->group(function () {
    Route::get('books', 'Api\\ApiController@books');
    Route::get('authors', 'Api\\ApiController@authors');
    Route::post('save_book', 'Api\\ApiController@saveBook');

    Route::prefix('author')->group(function () {
        Route::get('/', 'Api\\Author@index');
        Route::post('/', 'Api\\Author@store');
        Route::get('/search', 'Api\\Author@search');
        Route::get('/{id}', 'Api\\Author@edit');
        Route::put('/{id}', 'Api\\Author@update');
        Route::delete('/{id}', 'Api\\Author@destroy');
    });

    Route::prefix('book')->group(function () {
        Route::get('/', 'Api\\Book@index');
        Route::post('/', 'Api\\Book@store');
        Route::get('/search', 'Api\\Book@search');
        Route::get('/{id}', 'Api\\Book@edit');
        Route::put('/{id}', 'Api\\Book@update');
        Route::delete('/{id}', 'Api\\Book@destroy');
    });
});