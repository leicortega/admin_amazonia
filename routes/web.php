<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('index');

// Rutas para administrador
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/users', 'AdminController@users')->name('users');
    Route::post('/admin/users/create', 'AdminController@createUser')->name('user-create');
    Route::get('/admin/users/show/{id}', 'AdminController@showUser')->name('user-show');
    Route::post('/admin/users/update', 'AdminController@updateUser')->name('user-update');
    Route::get('/admin/sistema/vehiculos', 'AdminController@admin_vehiculos')->name('datos-vehiculos');
    Route::post('/admin/sistema/vehiculos/agg_datos_vehiculo', 'AdminController@agg_datos_vehiculo');
    // Administrar Cargos
    Route::get('/admin/sistema/cargos', 'AdminController@cargos')->name('cargos');
    Route::post('/admin/sistema/agg_cargo', 'AdminController@agg_cargo');
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
    Route::post('/cotizaciones/generar-contrato', 'CotizacionesController@generar_contrato');
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

// Rutas para Vehiculos
Route::group(['middleware' => ['permission:vehiculo|universal']], function () {
    Route::get('/vehiculos', 'VehiculoController@index')->name('vehiculos');
    Route::post('/vehiculos/create', 'VehiculoController@create');
    Route::post('/vehiculos/update', 'VehiculoController@update');
    Route::get('/vehiculos/ver/{id}', 'VehiculoController@ver')->name('ver-vehiculo');
    Route::post('/vehiculos/agg_conductor', 'VehiculoController@agg_conductor');
    Route::post('/vehiculos/cargar_conductores', 'VehiculoController@cargar_conductores');
    Route::post('/vehiculos/eliminar_conductor', 'VehiculoController@eliminar_conductor');
    Route::post('/vehiculos/agg_targeta_propiedad', 'VehiculoController@agg_targeta_propiedad');
    Route::post('/vehiculos/cargar_tarjeta_propiedad', 'VehiculoController@cargar_tarjeta_propiedad');
    Route::post('/vehiculos/eliminar_documento_legal', 'VehiculoController@eliminar_documento_legal');
    Route::post('/vehiculos/get_documento_legal', 'VehiculoController@get_documento_legal');
});

// Rutas para Personal
Route::group(['middleware' => ['permission:personal|universal']], function () {
    Route::get('/personal/registro', 'PersonalController@registro')->name('personal');
    Route::post('/personal/create', 'PersonalController@create');
    Route::get('/personal/ver/{id}', 'PersonalController@ver');
    Route::get('/personal/registro/ver/{id}', 'PersonalController@ver_ajax');
    Route::post('/personal/agg_cargo_personal', 'PersonalController@agg_cargo_personal');
    Route::post('/personal/cargar_cargos_personal', 'PersonalController@cargar_cargos_personal');
});

// Rutas para las Notificaciones
Route::get('/notificaciones/ver/{id}', 'NotificationController@ver');

Route::get('/informacion/covid', function () { return view('covid'); });

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/mail/template', function () { return view('mails.template'); });



