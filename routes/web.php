<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('index');

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

// Rutas para Cotizaciones
Route::group(['middleware' => ['permission:cotizaciones|universal']], function () {
    Route::get('/cotizaciones/nuevas', 'CotizacionesController@nuevas')->name('cotizaciones');
    Route::get('/cotizaciones/respondidas', 'CotizacionesController@respondidas');
    Route::post('/cotizaciones/responder', 'CotizacionesController@responder');
    Route::get('/cotizaciones/show/{id}', 'CotizacionesController@show');
    Route::get('/cotizaciones/aceptadas', 'CotizacionesController@aceptadas')->name('cotizaciones-aceptadas');
    Route::get('/cotizaciones/buscar_tercero/{id}', 'CotizacionesController@buscar_tercero');
    Route::post('/cotizaciones/crear-tercero', 'CotizacionesController@crear_tercero');
    Route::post('/cotizaciones/add-tercero', 'CotizacionesController@add_tercero');
});

// Rutas para Control de Ingreso
Route::group(['middleware' => ['permission:control ingreso|universal']], function () {
    Route::get('/control_ingreso/funcionarios', 'ControlIngresoController@funcionarios')->name('funcionarios');
    Route::get('/control_ingreso/clientes', 'ControlIngresoController@clientes')->name('clientes');
    Route::post('/control_ingreso/create', 'ControlIngresoController@create');
    Route::get('/control_ingreso/create/search/{id}', 'ControlIngresoController@createSearch');
    Route::post('/control_ingreso/registrar', 'ControlIngresoController@registrar');
    Route::post('/control_ingreso/search', 'ControlIngresoController@search');
    Route::get('/control_ingreso/historial/{id}', 'ControlIngresoController@historialIngresos');
    Route::get('/control_ingreso/print/{id}/{fecha}', 'ControlIngresoController@printIngreso');
});

// Rutas para las Notificaciones
Route::get('/notificaciones/ver/{id}', 'NotificationController@ver');

Route::get('/informacion/covid', function () { return view('covid'); });

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/mail/template', function () { return view('mails.template'); });



