<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () { return view('welcome'); });
});

// Rutas para administrador
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/users', 'AdminController@users')->name('users');
    Route::post('/admin/users/create', 'AdminController@createUser')->name('user-create');
    Route::get('/admin/users/show/{id}', 'AdminController@showUser')->name('user-show');
    Route::post('/admin/users/update', 'AdminController@updateUser')->name('user-update');
});

// Rutas para Correos
Route::group(['middleware' => ['permission:correos|universal']], function () {
    Route::get('/correos/nuevos', 'CorreosController@nuevos')->name('correos');
    Route::get('/correos/respondidos', 'CorreosController@respondidos');
    Route::get('/correos/show/{id}', 'CorreosController@show');
    Route::post('/correos/responder', 'CorreosController@responder');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
