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
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home')->middleware('can:admin,employee');
Route::resource('/users', 'UserController',['only'=>['index','update']])->middleware('can:admin,employee');
Route::resource('/car', 'CarController',['only'=>['index','create']])->middleware('can:admin,employee');
Route::resource('/order', 'OrderController',['only'=>['index','create', 'show', 'edit', 'update']])->middleware('can:admin,employee');
Route::resource('/repair', 'RepairController',['only'=>['index','create','update', 'destroy']])->middleware('can:admin,employee');
Route::resource('/client', 'ClientController',['only'=>['index']]);