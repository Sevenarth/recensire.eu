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

Route::get('/', 'HomeController@front');
Route::get('/category/{category_slug}', 'HomeController@front');

Route::name('tests.')->prefix('test')->group(function () {
  Route::get('/{testUnit}', 'TestUnitsController@view')->name('view');
  Route::patch('/{testUnit}', 'TestUnitsController@accept')->name('accept');
  Route::get('/{testUnit}/grazie', 'TestUnitsController@thankYou')->name('thankyou');
});

Route::get('contactus', 'HomeController@contactus')->name('contactus');
Route::post('contactus', 'HomeController@send')->name('postContactus');

Route::get('/go/{testUnit}', 'TestUnitsController@go')->name('tests.go');

Route::prefix('pannello')->group(function () {
    Auth::routes();
});

Route::namespace('Panel')->name('panel.')->middleware('auth')->prefix('pannello')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/upload', 'HomeController@upload')->name('upload');
    Route::post('/upload', 'HomeController@postUpload')->name('postUpload');
    Route::get('/reportistica', 'HomeController@report')->name('report');
    Route::post('/reportistica', 'HomeController@postReport')->name('postReport');

    Route::prefix('venditori')->name('sellers.')->group(function () {
      Route::get('/', 'SellersController@index')->name('home');
      Route::get('/nuovo', 'SellersController@create')->name('create');
      Route::put('/nuovo', 'SellersController@put')->name('put');
      Route::get('/visualizza-{seller}', 'SellersController@view')->name('view');
      Route::get('/modifica-{seller}', 'SellersController@edit')->name('edit');
      Route::patch('/visualizza-{seller}', 'SellersController@update')->name('update');
      Route::delete('/visualizza-{seller}', 'SellersController@delete')->name('delete');
      Route::post('/fetch', 'SellersController@fetch')->name('fetch');
      Route::get('/fetch', 'SellersController@fetch')->name('fetch');
    });
    Route::prefix('negozi')->name('stores.')->group(function () {
      Route::get('/', 'StoresController@index')->name('home');
      Route::get('/nuovo', 'StoresController@create')->name('create');
      Route::put('/nuovo', 'StoresController@put')->name('put');
      Route::get('/visualizza-{store}', 'StoresController@view')->name('view');
      Route::get('/modifica-{store}', 'StoresController@edit')->name('edit');
      Route::patch('/visualizza-{store}', 'StoresController@update')->name('update');
      Route::delete('/visualizza-{store}', 'StoresController@delete')->name('delete');
      Route::get('/reports-{store}', 'StoresController@reports')->name('reports');
      Route::post('/reports-{store}', 'StoresController@reportsUpdate')->name('reportsUpdate');
      Route::get('/prodotti-{store}', 'StoresController@products')->name('products');
      Route::put('/prodotti-{store}', 'StoresController@attachProduct')->name('attachProduct');
      Route::delete('/prodotti-{store}/disassocia-{product}', 'StoresController@detachProduct')->name('detachProduct');
      Route::post('/fetch', 'StoresController@fetch')->name('fetch');
      Route::get('/fetch', 'StoresController@fetch')->name('fetch');
    });
    Route::prefix('prodotti')->name('products.')->group(function () {
      Route::get('/', 'ProductsController@index')->name('home');
      Route::get('/nuovo', 'ProductsController@create')->name('create');
      Route::put('/nuovo', 'ProductsController@put')->name('put');
      Route::get('/tags', 'ProductsController@tags')->name('tags');
      Route::get('/visualizza-{product}', 'ProductsController@view')->name('view');
      Route::get('/modifica-{product}', 'ProductsController@edit')->name('edit');
      Route::patch('/visualizza-{product}', 'ProductsController@update')->name('update');
      Route::delete('/visualizza-{product}', 'ProductsController@delete')->name('delete');
      Route::put('/negozio-{product}', 'ProductsController@attachStore')->name('attachStore');
      Route::delete('/visualizza-{product}/disassocia-{store}', 'ProductsController@detachStore')->name('detachStore');
    });
    Route::prefix('ordini-di-lavoro')->name('testOrders.')->group(function () {
      Route::get('/', 'TestOrdersController@index')->name('home');
      Route::get('/nuovo-{product}-{store}', 'TestOrdersController@create')->name('create');
      Route::put('/nuovo-{product}-{store}', 'TestOrdersController@put')->name('put');
      Route::get('/visualizza-{testOrder}', 'TestOrdersController@view')->name('view');
      Route::get('/modifica-{testOrder}', 'TestOrdersController@edit')->name('edit');
      Route::patch('/visualizza-{testOrder}', 'TestOrdersController@update')->name('update');
      Route::delete('/visualizza-{testOrder}', 'TestOrdersController@delete')->name('delete');
    });
    Route::prefix('tests')->name('testUnits.')->group(function () {
      Route::get('/', 'TestUnitsController@index')->name('home');
      Route::get('/nuovo-{testOrder}', 'TestUnitsController@create')->name('create');
      Route::put('/nuovo-{testOrder}', 'TestUnitsController@put')->name('put');
      Route::get('/nuovi-{testOrder}', 'TestUnitsController@massCreate')->name('massCreate');
      Route::put('/nuovi-{testOrder}', 'TestUnitsController@massPut')->name('massPut');
      Route::get('/visualizza-{testUnit}', 'TestUnitsController@view')->name('view');
      Route::get('/modifica-{testUnit}', 'TestUnitsController@edit')->name('edit');
      Route::patch('/visualizza-{testUnit}', 'TestUnitsController@update')->name('update');
      Route::delete('/visualizza-{testUnit}', 'TestUnitsController@delete')->name('delete');
      Route::get('/rinnova-{testUnit}', 'TestUnitsController@renew')->name('renew');
      Route::get('/duplica-{testUnit}', 'TestUnitsController@duplicate')->name('duplicate');
    });
    Route::prefix('testers')->name('testers.')->group(function () {
      Route::get('/', 'TestersController@index')->name('home');
      Route::post('/fetch', 'TestersController@fetch')->name('fetch');
      Route::get('/nuovo', 'TestersController@create')->name('create');
      Route::put('/nuovo', 'TestersController@put')->name('put');
      Route::get('/visualizza-{tester}', 'TestersController@view')->name('view');
      Route::get('/modifica-{tester}', 'TestersController@edit')->name('edit');
      Route::patch('/visualizza-{tester}', 'TestersController@update')->name('update');
      Route::delete('/visualizza-{tester}', 'TestersController@delete')->name('delete');
      Route::put('/importa', 'TestersController@import')->name('import');
      Route::get('/esporta', 'TestersController@export')->name('export');
    });
    Route::prefix('categorie')->name('categories.')->group(function () {
      Route::get('/', 'CategoriesController@index')->name('home');
      Route::get('/nuovo', 'CategoriesController@create')->name('create');
      Route::put('/nuovo', 'CategoriesController@put')->name('put');
      Route::get('/modifica-{cat}', 'CategoriesController@edit')->name('edit');
      Route::patch('/modifica-{cat}', 'CategoriesController@update')->name('update');
      Route::delete('/elimina-{cat}', 'CategoriesController@delete')->name('delete');
    });

    Route::get('/opzioni', 'OptionsController@index')->name('options');
    Route::post('/opzioni', 'OptionsController@update')->name('options.update');

    Route::get('/shortcodes', 'OptionsController@shortcodes')->name('shortcodes');
    Route::post('/shortcodes', 'OptionsController@shortcodesUpdate')->name('shortcodesUpdate');

    Route::get('/reports', 'OptionsController@reports')->name('reports');
    Route::post('/reports', 'OptionsController@reportsUpdate')->name('reportsUpdate');

    Route::get('/banlist', 'HomeController@banlist')->name('banlist');
    Route::get('/banlist/esporta', 'HomeController@banlistExport')->name('banlist.export');
    Route::post('/banlist', 'HomeController@banlistUpdate')->name('banlistUpdate');
});
