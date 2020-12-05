<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('index');

// APP Routes Utilitaries
Route::post('/app/sistema/get/departamentos', 'AdminController@departamentos');
Route::post('/app/sistema/get/municipios', 'AdminController@municipios');
Route::post('/app/sistema/alertas/documentos', 'AlertasController@alerta_documentos');

Route::get('/app/sistema/get/departamentos', 'AdminController@departamentos');
Route::get('/app/sistema/get/municipios', 'AdminController@municipios');

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
    // Administrar Inspecciones
    Route::get('/admin/sistema/inspecciones', 'AdminController@inspecciones')->name('inspecciones');
    Route::post('/admin/sistema/inspecciones/agg_admin_inspeccion', 'AdminController@agg_admin_inspeccion');
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
Route::group(['middleware' => ['permission:vehiculos|universal']], function () {
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

    // RUTAS MANTENIMIENTOS
    Route::get('/vehiculos/mantenimientos', 'MantenimientosController@index');
    Route::get('/vehiculos/{id}/mantenimientos', 'MantenimientosController@mantenimientos_vehiculo');
    Route::get('/vehiculos/ver/mantenimiento/{id}', 'MantenimientosController@ver');
    Route::get('/vehiculos/print/mantenimiento/{id}', 'MantenimientosController@print');
    Route::post('/vehiculos/solicitar_mantenimiento', 'MantenimientosController@solicitar_mantenimiento');
    Route::post('/vehiculos/mantenimiento/agregar_actividad', 'MantenimientosController@agregar_actividad');
    Route::post('/vehiculos/mantenimiento/agregar_detalle_actividad', 'MantenimientosController@agregar_detalle_actividad');
    Route::post('/vehiculos/mantenimiento/agregar_facruta', 'MantenimientosController@agregar_facruta');
    Route::post('/vehiculos/mantenimiento/agregar_firma', 'MantenimientosController@agregar_firma');
    Route::get('/vehiculos/mantenimientos/autorizar/{id}', 'MantenimientosController@autorizar_view');
    Route::post('/vehiculos/mantenimientos/autorizar', 'MantenimientosController@autorizar');
    Route::post('/vehiculos/mantenimientos/autorizar_contabilidad', 'MantenimientosController@autorizar_contabilidad');
    Route::get('/vehiculos/mantenimientos/eliminar_factura/{id}', 'MantenimientosController@eliminar_factura');

    // Rutas para Inspecciones
    Route::get('/vehiculos/inspecciones', 'InspeccionesController@index');
    Route::get('/vehiculos/{id}/inspecciones', 'InspeccionesController@inspecciones_vehiculo');
    Route::get('/vehiculos/inspecciones/agregar', 'InspeccionesController@agregar_view');
    Route::post('/vehiculos/inspecciones/agregar', 'InspeccionesController@agregar');
    Route::get('/vehiculos/inspecciones/ver/{id}', 'InspeccionesController@ver')->name('ver_inspeccion');
    Route::post('/vehiculos/inspecciones/agregar_adjunto', 'InspeccionesController@agregar_adjunto');
    Route::post('/vehiculos/inspecciones/cerrar', 'InspeccionesController@cerrar');
    Route::get('/vehiculos/inspecciones/pdf/{id}', 'InspeccionesController@pdf');
    Route::post('/vehiculos/inspecciones/filter', 'InspeccionesController@filter');
    Route::post('/vehiculos/inspecciones/certificado', 'InspeccionesController@certificado');
});

// Rutas para Contabilidad
Route::group(['middleware' => ['permission:vehiculos|universal']], function () {
    Route::get('/contabilidad', 'ContabilidadController@index')->name('contabilidad');
    Route::post('/contabilidad/create', 'ContabilidadController@create');
});

// Rutas para Personal
Route::group(['middleware' => ['permission:personal|universal']], function () {
    Route::get('/personal/datos-personal', 'PersonalController@registro')->name('personal');
    Route::post('/personal/create', 'PersonalController@create');
    Route::post('/personal/update', 'PersonalController@update');
    Route::get('/personal/ver/{id}', 'PersonalController@ver');
    Route::get('/personal/registro/ver/{id}', 'PersonalController@ver_ajax');
    Route::post('/personal/agg_cargo_personal', 'PersonalController@agg_cargo_personal');
    Route::get('/personal/delete_cargo_personal/{id}', 'PersonalController@delete_cargo_personal');
    Route::post('/personal/cargar_cargos_personal', 'PersonalController@cargar_cargos_personal');
    Route::post('/personal/crear_contrato', 'PersonalController@crear_contrato');
    Route::post('/personal/cargar_contratos', 'PersonalController@cargar_contratos');
    Route::post('/personal/agg_otro_si', 'PersonalController@agg_otro_si');
    Route::post('/personal/editar_contrato', 'PersonalController@editar_contrato');
    Route::post('/personal/agg_documento', 'PersonalController@agg_documento');
    Route::post('/personal/cargar_documentos', 'PersonalController@cargar_documentos');
    Route::post('/personal/editar_documento', 'PersonalController@editar_documento');
    Route::post('/personal/eliminar_documento', 'PersonalController@eliminar_documento');
    Route::get('/personal/otro_si/print/{id}', 'PersonalController@print_otrosi');
    Route::get('/personal/contrato/print/{id}', 'PersonalController@print_contrato');
    Route::get('/personal/certificado-laboral/print/{id}', 'PersonalController@print_certificado');
    Route::post('/personal/buscar_usuario', 'PersonalController@buscar_usuario');
    Route::post('/personal/crear_clave', 'PersonalController@crear_clave');
    Route::post('/personal/update_clave', 'PersonalController@update_clave');
});

// Rutas para Terceros
Route::group(['middleware' => ['permission:terceros|universal']], function () {
    Route::get('/terceros', 'TercerosController@index')->name('terceros');
    Route::get('/terceros/ver/{id}', 'TercerosController@ver')->name('ver-tercero');
    Route::post('/terceros/create', 'TercerosController@create');
    Route::post('/terceros/update', 'TercerosController@update');
    Route::post('/terceros/agg_contacto', 'TercerosController@agg_contacto');
    Route::post('/terceros/cargar_contactos', 'TercerosController@cargar_contactos');
    Route::post('/terceros/eliminar_contacto', 'TercerosController@eliminar_contacto');
    Route::post('/terceros/cargar_responsable_contrato', 'TercerosController@cargar_responsable_contrato');
    Route::post('/terceros/agg_perfil_tercero', 'TercerosController@agg_perfil_tercero');
    Route::get('/terceros/delete_perfil_tercero/{id}', 'TercerosController@delete_perfil_tercero');
    Route::post('/terceros/cargar_documentos', 'TercerosController@cargar_documentos');
    Route::post('/terceros/agg_documento', 'TercerosController@agg_documento');
    Route::post('/terceros/delete_documento', 'TercerosController@delete_documento');
    Route::post('/terceros/cargar_cotizaciones', 'TercerosController@cargar_cotizaciones');
    Route::post('/terceros/editar_documento', 'TercerosController@editar_documento');
    Route::post('/terceros/crear_cotizacion', 'TercerosController@crear_cotizacion');
    Route::get('/terceros/print_cotizacion/{id}', 'TercerosController@print_cotizacion');
    Route::get('/terceros/print_contrato/{id}', 'TercerosController@print_contrato');
    Route::post('/terceros/eliminar_cotizacion', 'TercerosController@eliminar_cotizacion');
    Route::post('/terceros/editar_cotizacion', 'TercerosController@editar_cotizacion');
    Route::post('/terceros/editar_contrato', 'TercerosController@editar_contrato');
    Route::post('/terceros/cargar_contratos', 'TercerosController@cargar_contratos');
    Route::post('/terceros/generar_contrato', 'TercerosController@generar_contrato');
    Route::post('/terceros/get_tercero', 'TercerosController@get_tercero');
});

// Rutas para BLOG
Route::group(['middleware' => ['permission:blog|universal']], function () {
    Route::get('/blog', 'BlogController@index')->name('blog');
    Route::get('/blog/post/crear', 'BlogController@crear');
    Route::post('/blog/post/crear', 'BlogController@crear_post');
    Route::get('/blog/post/ver/{id}', 'BlogController@ver');
});

// Rutas para BLOG
Route::group(['middleware' => ['permission:tareas|universal']], function () {
    Route::get('/tareas', 'TareasController@index')->name('tareas');
    Route::get('/tareas/asignadas', 'TareasController@asignadas')->name('asignadas');
    Route::get('/tareas/completadas', 'TareasController@completadas')->name('completadas');
    Route::post('/tareas/agregar', 'TareasController@agregar');
    Route::post('/tareas/agregar_estado', 'TareasController@agregar_estado');
    Route::get('/tareas/ver/{id}', 'TareasController@ver');
});

// Rutas para las Notificaciones
Route::get('/notificaciones/ver/{id}', 'NotificationController@ver');

Route::get('/informacion/covid', function () { return view('covid'); });

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/mail/template', function () { return view('mails.template'); });



