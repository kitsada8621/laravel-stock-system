<?php

use GuzzleHttp\Psr7\Request;
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
*/Route::get('/','HomeController@home');

Auth::routes();

Route::resource('department', 'User\DepartmentController')->middleware('admin');
Route::resource('user','User\UserController');
Route::resource('type','Product\TypeController')->middleware('admin');
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('unit','Product\UnitController')->middleware('admin');
Route::resource('stock','Stock\StockController')->middleware('admin');

Route::group(['prefix' => 'product'], function () {
    Route::get('/','Product\ProductController@index');
    Route::post('/','Product\ProductController@create');
    Route::delete('/{id}','Product\ProductController@destroy');
});

Route::group(['prefix' => 'sale'], function () {
    Route::get('/','Stock\SaleController@index')->name('sale.index');
    Route::put('/{id}','Stock\SaleController@cutStock');
});

Route::group(['prefix' => 'autocomplete'], function () {
    Route::get('/user/name','Autocomple\AutocompleController@autoUsernamecomple');
});

Route::group(['middleware'=>['admin'],'prefix' => 'import'], function () {
    Route::get('/','Stock\ImportController@index')->name('import.index');
    Route::post('/{id}','Stock\ImportController@importProduct');

    Route::get('/data','Stock\ImportController@historyImport')->name('import.history');
    Route::put('/data/{id}','Stock\ImportController@updateImport');
    Route::delete('/data/{id}','Stock\ImportController@destroy');
});

Route::group(['middleware'=>['admin'],'prefix' => 'product/return'], function () {
    Route::get('/','Stock\ReturnController@index')->name('product.return.index');
    Route::post('/{id}','Stock\ReturnController@returnProduct');
});

Route::group(['prefix' => 'setting'], function () {
    Route::get('/','User\UserController@setting');
    Route::post('/{id}','User\UserController@confirmSetting')->name('setting.submit');

    Route::get('/pass','User\UserController@passwordIndex');
    Route::patch('/pass/{id}','User\UserController@updatePassword');
});

Route::group(['prefix' => 'report'], function () {
    Route::get('/list','Stock\SaleController@reports');
    Route::get('/sale/{id}','Product\ReportController@printSale');

    Route::get('/return','Stock\ReturnController@reportIndex');
    Route::post('/return/print','Stock\ReturnController@reportPrint');
});

Route::group(['prefix' => 'order'], function () {
    Route::get('/','HomeController@orderList');
    route::patch('/{stockId}','Stock\SaleController@confrimSale');
    route::patch('/unconfrim/{id}','Stock\SaleController@unconfirmSale');
    Route::delete('/{id}','Stock\SaleController@removeRequestSale');
});

Route::group(['prefix' => 'count'], function () {
    Route::get('/all','HomeController@showCount');
    Route::get('/confrim','HomeController@showCountconfirm');
    Route::get('/wait/confrim','HomeController@showCountWaitConfirm');
    Route::get('/remove','HomeController@showCountRemove');
    Route::get('/return','Stock\ReturnController@returnLenght');
});


/** error pages */
Route::get('/401', function () {
    return view('pages.errors.401');
});
Route::get('/user/valid','AuthController@habdleValid');