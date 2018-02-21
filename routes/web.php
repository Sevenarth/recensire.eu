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

Route::namespace('Panel')->name('panel.')->middleware('auth')->prefix('pannello')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/upload', 'HomeController@upload')->name('upload');
    Route::post('/upload', 'HomeController@postUpload')->name('postUpload');

    Route::prefix('venditori')->name('sellers.')->group(function () {
      Route::get('/', 'SellersController@index')->name('home');
      Route::get('/nuovo', 'SellersController@create')->name('create');
      Route::put('/nuovo', 'SellersController@put')->name('put');
      Route::get('/visualizza-{seller}', 'SellersController@view')->name('view');
      Route::get('/modifica-{seller}', 'SellersController@edit')->name('edit');
      Route::patch('/visualizza-{seller}', 'SellersController@update')->name('update');
      Route::delete('/visualizza-{seller}', 'SellersController@delete')->name('delete');
      Route::post('/fetch', 'SellersController@fetch')->name('fetch');
    });
    Route::prefix('negozi')->name('stores.')->group(function () {
      Route::get('/', 'StoresController@index')->name('home');
      Route::get('/nuovo', 'StoresController@create')->name('create');
      Route::put('/nuovo', 'StoresController@put')->name('put');
      Route::get('/visualizza-{store}', 'StoresController@view')->name('view');
      Route::get('/modifica-{store}', 'StoresController@edit')->name('edit');
      Route::patch('/visualizza-{store}', 'StoresController@update')->name('update');
      Route::delete('/visualizza-{store}', 'StoresController@delete')->name('delete');
    });
    Route::prefix('prodotti')->name('products.')->group(function () {
      Route::get('/', 'ProductsController@index')->name('home');
      Route::get('/nuovo', 'ProductsController@create')->name('create');
    });
    Route::prefix('ordini-di-lavoro')->name('testOrders.')->group(function () {
      Route::get('/', 'TestOrdersController@index')->name('home');
    });
    Route::prefix('testers')->name('testers.')->group(function () {
      Route::get('/', 'TestersController@index')->name('home');
    });
});
