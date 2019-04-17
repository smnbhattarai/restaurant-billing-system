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

Route::prefix('restro')->middleware('auth')->group(function() {
    Route::resource('menu', 'MenuController');
    Route::resource('bill', 'BillController');
    Route::post('menu/suggestion', "MenuController@getSuggestion")->name("menu.suggestion");
    Route::post('bill/suggestion', "BillController@getSuggestion")->name("bill.suggestion");
});
