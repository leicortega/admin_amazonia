<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('index');

// APP Routes Utilitaries
Route::post('/app/sistema/get/departamentos', 'AdminController@departamentos');
Route::post('/app/sistema/get/municipios', 'AdminController@municipios');
Route::post('/app/sistema/alertas/documentos', 'AlertasController@alerta_documentos');

// RUTAS APP OBCONSULTORES
Route::get('/app/sistema/get/departamentos', 'AdminController@departamentos');
Route::get('/app/sistema/get/municipios', 'AdminController@municipios');

// Rutas para administrador
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/users', 'AdminController@users')->name('users');
    Route::get('/admin/users/filtro', 'AdminController@filtro')->name('users_filtro');
    Route::post('/admin/users/create', 'AdminController@createUser')->name('user-create');
    Route::get('/admin/users/show/{id}', 'AdminController@showUser')->name('user-show');
    Route::post('/admin/users/update', 'AdminController@updateUser')->name('user-update');
    Route::get('/admin/sistema/vehiculos', 'AdminController@admin_vehiculos')->name('datos-vehiculos');
    Route::get('/admin/sistema/admin_documentos_vehiculos', 'AdminController@admin_documentos_vehiculos')->name('admin_documentos_vehiculos');
    Route::get('/admin/sistema/eliminar_documento_vehiculo/{id}', 'AdminController@eliminar_doc_vehiculo')->name('delete_documentos_vehiculo');
    Route::post('/admin/sistema/agg_documentos_vehiculo', 'AdminController@agg_documentos_vehiculo')->name('agg_documentos_vehiculo');
    Route::post('/admin/sistema/edit_documentos_vehiculo', 'AdminController@edit_documentos_vehiculo')->name('edit_documentos_vehiculo');
    Route::post('/admin/sistema/agg_categoria_documentos_vehiculo', 'AdminController@agg_categoria_documentos_vehiculo')->name('agg_categoria_documentos_vehiculo');
    Route::post('/admin/sistema/vehiculos/agg_datos_vehiculo', 'AdminController@agg_datos_vehiculo');
    // Administrar Cargos
    Route::get('/admin/sistema/cargos', 'AdminController@cargos')->name('cargos');
    Route::post('/admin/sistema/agg_documeto_cargo', 'AdminController@agg_documeto_cargo')->name('agg_documeto_cargo');
    Route::get('/admin/sistema/eliminar_documento_cargos/{id}', 'AdminController@eliminar_documento_cargos');
    Route::post('/admin/sistema/agg_cargo', 'AdminController@agg_cargo');
    // Administrar Inspecciones
    Route::get('/admin/sistema/inspecciones', 'AdminController@inspecciones')->name('inspecciones');
    Route::post('/admin/sistema/inspecciones/agg_admin_inspeccion', 'AdminController@agg_admin_inspeccion');
    //administrar proveedores

    // Admin Roles y permisos
    Route::resource('/admin/sistema/roles','RoleController');
    Route::resource('/admin/sistema/permisos','PermisoController');
});

// Rutas para Correos
Route::group(['middleware' => ['permission:correos|correos.create.edit|correos.destroy|universal']], function () {
    // Rutas para ver Correos
    Route::group(['middleware' => ['permission:correos|universal']], function () {
        Route::get('/correos/nuevos', 'CorreosController@nuevos')->name('correos');
        Route::get('/correos/respondidos', 'CorreosController@respondidos');
        Route::get('/correos/show/{id}', 'CorreosController@show');
    });
    // Rutas para crear Correos
    Route::group(['middleware' => ['permission:correos.create|universal']], function () {
        Route::post('/correos/responder', 'CorreosController@responder');
    });
    // Rutas para editar Correos
    Route::group(['middleware' => ['permission:correos.edit|universal']], function () {
        
    });
    // Rutas para eliminar Correos
    Route::group(['middleware' => ['permission:correos.destroy|universal']], function () {
        
    });
});

// Rutas para Cotizaciones
Route::group(['middleware' => ['permission:cotizaciones|cotizaciones.create|cotizaciones.edit|cotizaciones.destroy|universal']], function () {
    // Rutas para ver las Cotizaciones
    Route::group(['middleware' => ['permission:cotizaciones|universal']], function () {
        Route::get('/cotizaciones/nuevas', 'CotizacionesController@nuevas')->name('cotizaciones');
        Route::get('/cotizaciones/respondidas', 'CotizacionesController@respondidas');
        Route::get('/cotizaciones/show/{id}', 'CotizacionesController@show');
        Route::get('/cotizaciones/aceptadas', 'CotizacionesController@aceptadas')->name('cotizaciones-aceptadas');
        Route::get('/cotizaciones/buscar_tercero/{id}', 'CotizacionesController@buscar_tercero');
    });
    // Rutas para crear las Cotizaciones
    Route::group(['middleware' => ['permission:cotizaciones.create|universal']], function () {
        Route::post('/cotizaciones/responder', 'CotizacionesController@responder');
        Route::post('/cotizaciones/crear-tercero', 'CotizacionesController@crear_tercero');
        Route::post('/cotizaciones/add-tercero', 'CotizacionesController@add_tercero');
        Route::post('/cotizaciones/generar-contrato', 'CotizacionesController@generar_contrato');
    });
    // Rutas para editar las Cotizaciones
    Route::group(['middleware' => ['permission:cotizaciones.edit|universal']], function () {
        
    });
    // Rutas para eliminar las Cotizaciones
    Route::group(['middleware' => ['permission:cotizaciones.destroy|universal']], function () {
        
    });
});

// Rutas para Control de Ingreso
Route::group(['middleware' => ['permission:control ingreso|control ingreso.create|control ingreso.edit|control ingreso.destroy|universal']], function () {
    // Rutas para ver el  Control de Ingreso
    Route::group(['middleware' => ['permission:control ingreso|universal']], function () {
        Route::get('/control_ingreso/funcionarios', 'ControlIngresoController@funcionarios')->name('funcionarios');
        Route::get('/control_ingreso/clientes', 'ControlIngresoController@clientes')->name('clientes');
        Route::post('/control_ingreso/search', 'ControlIngresoController@search');
        Route::get('/control_ingreso/historial/{id}', 'ControlIngresoController@historialIngresos');
        Route::get('/control_ingreso/print/{id}/{fecha}', 'ControlIngresoController@printIngreso');
    });
    // Rutas para crear el  Control de Ingreso
    Route::group(['middleware' => ['permission:control ingreso.create|universal']], function () {
        Route::post('/control_ingreso/create', 'ControlIngresoController@create');
        Route::get('/control_ingreso/create/search/{id}', 'ControlIngresoController@createSearch');
        Route::post('/control_ingreso/registrar', 'ControlIngresoController@registrar');
    });
    // Rutas para editar el  Control de Ingreso
    Route::group(['middleware' => ['permission:control ingreso.edit|universal']], function () {
        
    });
    // Rutas para eliminar el  Control de Ingreso
    Route::group(['middleware' => ['permission:control ingreso.destroy|universal']], function () {
        
    });
    
});

// Rutas para Vehiculos
Route::group(['middleware' => ['permission:vehiculos|vehiculos.create|vehiculos.edit|vehiculos.destroy|universal']], function () {
    //rutas de Vehiculos que solo pueden ver
    Route::group(['middleware' => ['permission:vehiculos|universal']], function () {
        Route::get('/vehiculos', 'VehiculoController@index')->name('vehiculos');
        Route::post('/vehiculos/carga_entidades', 'VehiculoController@carga_entidades')->name('carga_entidades');
        Route::get('/vehiculos/filtro', 'VehiculoController@filtrar')->name('vehiculos_filtro');
        Route::get('/vehiculos/ver/{id}', 'VehiculoController@ver')->name('ver-vehiculo');
        Route::post('/vehiculos/ver_conductor_historial', 'VehiculoController@ver_conductor_historial');
        Route::post('/vehiculos/cargar_conductores', 'VehiculoController@cargar_conductores');
        Route::post('/vehiculos/cargar_documentos_all', 'VehiculoController@cargar_documentos_all');
        Route::post('/vehiculos/exportar_documentos', 'VehiculoController@exportar_documentos');
        Route::post('/vehiculos/cargar_tarjeta_propiedad', 'VehiculoController@cargar_tarjeta_propiedad');
        Route::post('/vehiculos/get_documento_legal', 'VehiculoController@get_documento_legal');
        Route::post('/vehiculos/get_vehiculo_categoria', 'VehiculoController@get_vehiculo_categoria');
        Route::post('/vehiculos/cargar_procesos', 'VehiculoController@cargar_procesos');
        Route::post('/vehiculos/cargar_terceros', 'VehiculoController@cargar_terceros');
        Route::get('/vehiculos/cagar_compraventas', 'VehiculoController@cagar_compraventas');
        Route::get('/vehiculos/trazabilidad_inspecciones/{id}', 'VehiculoController@trazabilidad_inspecciones');
        // RUTAS MANTENIMIENTOS
        Route::get('/vehiculos/mantenimientos', 'MantenimientosController@index')->name('mantenimientos');
        Route::get('/vehiculos/mantenimientos/filtro', 'MantenimientosController@filtrar')->name('mantenimientos_filtro');
        Route::get('/vehiculos/ver/mantenimiento/{id}', 'MantenimientosController@ver');
        Route::get('/vehiculos/print/mantenimiento/{id}', 'MantenimientosController@print');
        // Rutas para Inspecciones
        Route::get('/vehiculos/inspecciones', 'InspeccionesController@index')->name('inspecciones');
        Route::get('/vehiculos/inspecciones/filtro', 'InspeccionesController@filtro')->name('inspecciones_filtro');
        Route::get('/vehiculos/inspecciones/ver/{id}', 'InspeccionesController@ver')->name('ver_inspeccion');
        Route::get('/vehiculos/inspecciones/pdf/{id}', 'InspeccionesController@pdf');
        Route::post('/vehiculos/inspecciones/certificado', 'InspeccionesController@certificado');
        Route::get('/vehiculos/inspecciones/certificado/{id}', 'InspeccionesController@certificado_view');
    });
    //rutas de Vehiculos que solo pueden crear
    Route::group(['middleware' => ['permission:vehiculos.create|universal']], function () {
        Route::get('/vehiculos/agregar', 'VehiculoController@agregar_vehiculo')->name('agregar_vehiculo');
        Route::post('/vehiculos/create', 'VehiculoController@create');
        Route::post('/vehiculos/agg_conductor', 'VehiculoController@agg_conductor');
        Route::post('/vehiculos/agg_targeta_propiedad', 'VehiculoController@agg_targeta_propiedad');
        Route::post('/vehiculos/agg_compraventa', 'VehiculoController@agg_compraventa');
        // RUTAS MANTENIMIENTOS
        Route::get('/vehiculos/{id}/mantenimientos', 'MantenimientosController@mantenimientos_vehiculo');
        Route::post('/vehiculos/solicitar_mantenimiento', 'MantenimientosController@solicitar_mantenimiento');
        Route::post('/vehiculos/mantenimiento/agregar_actividad', 'MantenimientosController@agregar_actividad');
        Route::post('/vehiculos/mantenimiento/agregar_detalle_actividad', 'MantenimientosController@agregar_detalle_actividad');
        Route::post('/vehiculos/mantenimiento/agregar_facruta', 'MantenimientosController@agregar_facruta');
        Route::post('/vehiculos/mantenimiento/agregar_firma', 'MantenimientosController@agregar_firma');
        Route::get('/vehiculos/mantenimientos/autorizar/{id}', 'MantenimientosController@autorizar_view');
        Route::post('/vehiculos/mantenimientos/autorizar', 'MantenimientosController@autorizar');
        Route::post('/vehiculos/mantenimientos/autorizar_contabilidad', 'MantenimientosController@autorizar_contabilidad');
        // Rutas para Inspecciones
        Route::get('/vehiculos/inspecciones/agregar', 'InspeccionesController@agregar_view');
        Route::post('/vehiculos/inspecciones/agregar', 'InspeccionesController@agregar');
        Route::post('/vehiculos/inspecciones/agregar_adjunto', 'InspeccionesController@agregar_adjunto');
    });
    //rutas de Vehiculos que solo pueden editar
    Route::group(['middleware' => ['permission:vehiculos.edit|universal']], function () {
        Route::post('/vehiculos/update', 'VehiculoController@update');
        // RUTAS MANTENIMIENTOS
        // Rutas para Inspecciones
    });
    //rutas de Vehiculos que solo pueden eliminar
    Route::group(['middleware' => ['permission:vehiculos.destroy|universal']], function () {
        Route::post('/vehiculos/eliminar_conductor', 'VehiculoController@eliminar_conductor');
        Route::post('/vehiculos/eliminar_documento_legal', 'VehiculoController@eliminar_documento_legal');
        // RUTAS MANTENIMIENTOS
        Route::get('/vehiculos/mantenimientos/eliminar_factura/{id}', 'MantenimientosController@eliminar_factura');
        // Rutas para Inspecciones
        Route::post('/vehiculos/inspecciones/cerrar', 'InspeccionesController@cerrar');
    });
    
    // Route::get('/vehiculos/{id}/inspecciones', 'InspeccionesController@inspecciones_vehiculo');
    // Route::post('/vehiculos/inspecciones/filter', 'InspeccionesController@filter');

});

// Rutas para Contabilidad
Route::group(['middleware' => ['permission:vehiculos|vehiculos.create|vehiculos.edit|vehiculos.destroy|universal']], function () {
    //rutas de Contabilidad que solo pueden ver
    Route::group(['middleware' => ['permission:vehiculos|universal']], function () {
        Route::get('/contabilidad', 'ContabilidadController@index')->name('contabilidad');
        Route::get('/contabilidad/filtro', 'ContabilidadController@filtrar')->name('contabilidad_filtro');
        Route::get('/contabilidad/ver/{id}', 'ContabilidadController@ver');
        //Rutas para solicitud de dinero
        Route::get('/solicitud-dinero', 'SolicituddineroController@index')->name('solicitud_dinero');
        Route::get('/solicitud-dinero/{id}', 'SolicituddineroController@ver')->name('solicitud_dinero_ver');
        Route::post('/solicitud-dinero/versoporte', 'SolicituddineroController@ver_soporte')->name('solicitud_ver_soporte');
        Route::post('/solicitud-dinero/verestado', 'SolicituddineroController@ver_estado')->name('solicitud_ver_estado');
        Route::get('/solicitud-dinero/pdf/{id}', 'SolicituddineroController@print')->name('solicitud_pdf');
        Route::get('/solicitud-dinero/filtro/filtrar', 'SolicituddineroController@filtro')->name('solicitud_filtro');
    });
    //rutas de Contabilidad que solo pueden crear
    Route::group(['middleware' => ['permission:vehiculos.create|universal']], function () {
        Route::post('/contabilidad/create', 'ContabilidadController@create');
        //Rutas para solicitud de dinero
        Route::post('/solicitud-dinero/soporte', 'SolicituddineroController@add_soporte')->name('solicitud_add_soporte');
        Route::post('/solicitud-dinero/estados', 'SolicituddineroController@add_estado')->name('solicitud_add_estado');
        Route::post('/solicitud-dinero', 'SolicituddineroController@create')->name('solicitud_dinero_create');
    });
    //rutas de Contabilidad que solo pueden editar
    Route::group(['middleware' => ['permission:vehiculos.edit|universal']], function () {
        
    });
    //rutas de Contabilidad que solo pueden eliminar
    Route::group(['middleware' => ['permission:vehiculos.destroy|universal']], function () {

    });
});

// Rutas para Personal
Route::group(['middleware' => ['permission:personal|personal.create|personal.edit|personal.destroy|universal']], function () {
    //rutas de personal que solo pueden crear
    Route::group(['middleware' => ['permission:personal.create|universal']], function () {
        Route::post('/personal/create', 'PersonalController@create');
        Route::get('/personal/ver/{id}/crearclave', 'PersonalController@createclave')->name('persona.createclave');
        Route::post('/personal/agg_cargo_personal', 'PersonalController@agg_cargo_personal');
        Route::post('/personal/crear_contrato', 'PersonalController@crear_contrato');
        Route::post('/personal/agg_otro_si', 'PersonalController@agg_otro_si');
        Route::post('/personal/agg_documento', 'PersonalController@agg_documento');
        Route::post('/personal/crear_clave', 'PersonalController@crear_clave');
    });
    //rutas de personal que solo pueden editar
    Route::group(['middleware' => ['permission:personal.edit|universal']], function () {
        Route::post('/personal/update', 'PersonalController@update');
        Route::get('/personal/ver/{id}/editar', 'PersonalController@edit')->name('persona.edit');
        Route::post('/personal/editar_contrato', 'PersonalController@editar_contrato');
        Route::post('/personal/editar_documento', 'PersonalController@editar_documento');
        Route::post('/personal/update_clave', 'PersonalController@update_clave');
    });
    //rutas de personal que solo pueden ver
    Route::group(['middleware' => ['permission:personal|universal']], function () { 
        Route::get('/personal/ver/{id}', 'PersonalController@ver')->name('persona.ver');
        Route::get('/personal/datos-personal', 'PersonalController@registro')->name('personal');
        Route::get('/personal/datos-personal/filtro', 'PersonalController@filtro')->name('personal_filtro');
        Route::get('/personal/ver/{id}', 'PersonalController@ver');
        Route::get('/personal/registro/ver/{id}', 'PersonalController@ver_ajax');
        Route::post('/personal/cargar_cargos_personal', 'PersonalController@cargar_cargos_personal');
        Route::post('/personal/cargar_contratos', 'PersonalController@cargar_contratos');
        Route::post('/personal/cargar_documentos', 'PersonalController@cargar_documentos');
        Route::get('/personal/otro_si/print/{id}', 'PersonalController@print_otrosi');
        Route::get('/personal/contrato/print/{id}', 'PersonalController@print_contrato');
        Route::get('/personal/certificado-laboral/print/{id}', 'PersonalController@print_certificado');
        Route::post('/personal/buscar_usuario', 'PersonalController@buscar_usuario');
    });
    //rutas de personal que solo pueden eliminar
    Route::group(['middleware' => ['permission:personal.destroy|universal']], function () {
        Route::get('/personal/delete_cargo_personal/{id}', 'PersonalController@delete_cargo_personal');
        Route::post('/personal/eliminar_documento', 'PersonalController@eliminar_documento');
        Route::post('/personal/eliminar_contrato', 'PersonalController@eliminar_contrato');
    });
    
});

// Rutas para Terceros
Route::group(['middleware' => ['permission:terceros|terceros.create|terceros.edit|terceros.destroy|universal']], function () {
    //Ruta para poder ver en terceros
    Route::group(['middleware' => ['permission:terceros|universal']], function () {
        Route::get('/terceros', 'TercerosController@index')->name('terceros');
        Route::get('/terceros/filtro', 'TercerosController@filtrar')->name('terceros_filtro');
        Route::get('/terceros/ver/{id}', 'TercerosController@ver')->name('ver-tercero');
        Route::post('/terceros/cargar_contactos', 'TercerosController@cargar_contactos');
        Route::post('/terceros/cargar_responsable_contrato', 'TercerosController@cargar_responsable_contrato');
        Route::post('/terceros/cargar_documentos', 'TercerosController@cargar_documentos');
        Route::post('/terceros/cargar_cotizaciones', 'TercerosController@cargar_cotizaciones');
        Route::get('/terceros/print_cotizacion/{id}', 'TercerosController@print_cotizacion');
        Route::post('/terceros/enviar_cotizacion', 'TercerosController@enviar_cotizacion');
        Route::get('/terceros/print_contrato/{id}', 'TercerosController@print_contrato');
        Route::get('/terceros/print_contrato/contrato/{id}', 'TercerosController@print_contrato_contrato');
        Route::post('/terceros/cargar_contratos', 'TercerosController@cargar_contratos');
        Route::post('/terceros/generar_contrato', 'TercerosController@generar_contrato');
        Route::post('/terceros/get_tercero', 'TercerosController@get_tercero');
        Route::post('/terceros/ver_trayectos', 'TercerosController@ver_trayectos');
        Route::post('/terceros/ver_trayectos_cotizacion', 'TercerosController@ver_trayectos_cotizacion');
        Route::get('/terceros/correspondencia/{id}', 'TercerosController@correspondencia')->name('correspondencia_index');
        Route::get('/terceros/correspondencia/ver/{id}', 'TercerosController@correspondencia_ver')->name('correspondencia_ver');
    });
    //Ruta para poder crear en terceros
    Route::group(['middleware' => ['permission:terceros.create|universal']], function () {
        Route::post('/terceros/create', 'TercerosController@create');
        Route::post('/terceros/agg_contacto', 'TercerosController@agg_contacto');
        Route::post('/terceros/agg_perfil_tercero', 'TercerosController@agg_perfil_tercero');
        Route::post('/terceros/agg_documento', 'TercerosController@agg_documento');
        Route::post('/terceros/crear_cotizacion', 'TercerosController@crear_cotizacion');
        Route::post('/terceros/generar_vehiculos_contratos', 'TercerosController@generar_vehiculos_contratos');
        Route::post('/terceros/agregar_trayecto', 'TercerosController@agregar_trayecto');
        Route::post('/terceros/agregar_trayecto_cotizacion', 'TercerosController@agregar_trayecto_cotizacion');
        Route::post('/terceros/correspondencia/create', 'TercerosController@correspondencia_create');
        Route::post('/terceros/correspondencia/respuesta/create', 'TercerosController@correspondencia_respuesta_create');
    });
    //Ruta para poder editar en terceros
    Route::group(['middleware' => ['permission:terceros.edit|universal']], function () {
        Route::post('/terceros/update', 'TercerosController@update');
        Route::post('/terceros/editar_documento', 'TercerosController@editar_documento');
        Route::post('/terceros/editar_cotizacion', 'TercerosController@editar_cotizacion');
        Route::post('/terceros/editar_contrato', 'TercerosController@editar_contrato');
        Route::post('/terceros/editar_trayecto', 'TercerosController@editar_trayecto');
        Route::post('/terceros/editar_trayecto_cotizacion', 'TercerosController@editar_trayecto_cotizacion');
        Route::post('/terceros/actualizar_contrato', 'TercerosController@actualizar_contrato');
    });
    //Ruta para poder eliminar en terceros
    Route::group(['middleware' => ['permission:terceros.destroy|universal']], function () {
        Route::post('/terceros/eliminar_contacto', 'TercerosController@eliminar_contacto');
        Route::get('/terceros/delete_perfil_tercero/{id}', 'TercerosController@delete_perfil_tercero');
        Route::post('/terceros/delete_documento', 'TercerosController@delete_documento');
        Route::post('/terceros/eliminar_cotizacion', 'TercerosController@eliminar_cotizacion');
        Route::post('/terceros/eliminar_contrato', 'TercerosController@eliminar_contrato');
        Route::post('/terceros/eliminar_trayecto', 'TercerosController@eliminar_trayecto');
        Route::post('/terceros/eliminar_trayecto_cotizacion', 'TercerosController@eliminar_trayecto_cotizacion');
    });
});

// Rutas para BLOG
Route::group(['middleware' => ['permission:blog|blog.create|blog.edit|blog.destroy|universal']], function () {
    //Ruta para poder ver en blogs
    Route::group(['middleware' => ['permission:blog|universal']], function () {
        Route::get('/blog', 'BlogController@index')->name('blog');
        Route::get('/blog/post/ver/{id}', 'BlogController@ver');
    });
    //Ruta para poder crear en blogs
    Route::group(['middleware' => ['permission:blog.create|universal']], function () {
        Route::get('/blog/post/crear', 'BlogController@crear');
        Route::post('/blog/post/crear', 'BlogController@crear_post');
    });
    //Ruta para poder editar en blogs
    Route::group(['middleware' => ['permission:blog.edit|universal']], function () {
        
    });
    //Ruta para poder eliminar en blogs
    Route::group(['middleware' => ['permission:blog.destroy|universal']], function () {
        
    });
});

// Rutas para Tareas
Route::group(['middleware' => ['permission:tareas|tareas.create|tareas.edit|tareas.destroy|universal']], function () {
    //Ruta para poder ver en tareas
    Route::group(['middleware' => ['permission:tareas|universal']], function () {
        Route::get('/tareas', 'TareasController@index')->name('tareas');
        Route::get('/tareas/asignadas', 'TareasController@asignadas')->name('asignadas');
        Route::get('/tareas/completadas', 'TareasController@completadas')->name('completadas');
        Route::post('/tareas/cargar_calendario', 'TareasController@cargar_calendario');
        Route::post('/tareas/vercalendario_tarea', 'TareasController@vercalendario_tarea');
        Route::get('/tareas/ver/{id}', 'TareasController@ver');
        Route::get('/calendario', 'TareasController@calendario');
    });
    //Ruta para poder crear en tareas
    Route::group(['middleware' => ['permission:tareas.create|universal']], function () {
        Route::post('/tareas/agregar', 'TareasController@agregar');
        Route::post('/tareas/agregar_estado', 'TareasController@agregar_estado');
    });
    //Ruta para poder editar en tareas
    Route::group(['middleware' => ['permission:tareas.edit|universal']], function () {
        Route::post('/tareas/cambiar_tipo_eventos_calendario', 'TareasController@cambiar_tipo_eventos_calendario');
    });
    //Ruta para poder eliminar en tareas
    Route::group(['middleware' => ['permission:tareas.destroy|universal']], function () {
        Route::get('/tareas/eliminate/{id}', 'TareasController@eliminate_tarea');
    });
});

// RUTAS PARA GOOGLE DRIVE
Route::group(['middleware' => ['permission:hseq|hseq.create.edit|hseq.destroy|universal']], function () {
    // RUTAS PARA VER GOOGLE DRIVE
    Route::group(['middleware' => ['permission:hseq|universal']], function () {
        Route::get('/hseq/list', 'HseqController@list');
        Route::get('/hseq/list/{path}/{folder}', 'HseqController@list_folder');
        Route::get('/hseq/list/{path}', 'HseqController@list_folder_return');
        Route::get('/hseq/descargar', 'HseqController@descargar');
    });
    // RUTAS PARA CREAR GOOGLE DRIVE
    Route::group(['middleware' => ['permission:hseq.create|universal']], function () {
        Route::post('/hseq/create-dir', 'HseqController@create_dir');
        Route::post('/hseq/subir_archivo', 'HseqController@subir_archivo');
    });
    // RUTAS PARA EDITAR GOOGLE DRIVE
    Route::group(['middleware' => ['permission:hseq.edit|universal']], function () {

    });
    // RUTAS PARA ELIMINAR GOOGLE DRIVE
    Route::group(['middleware' => ['permission:hseq.destroy|universal']], function () {
        Route::get('/hseq/eliminar_archivo', 'HseqController@eliminar_archivo');
        Route::get('/hseq/eliminar_carpeta', 'HseqController@eliminar_carpeta');
    });
});
// Rutas para las Notificaciones
Route::get('/notificaciones/ver/{id}', 'NotificationController@ver');

Route::get('/informacion/covid', function () { return view('covid'); });

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/mail/template', function () { return view('mails.template'); });

// Rutas para modulo Documentacíon
Route::get('/informacion/documentacion', 'DocumentacionController@index');
Route::post('/informacion/documentacion/create_modulo', 'DocumentacionController@create_modulo');
Route::post('/informacion/documentacion/delete_modulo', 'DocumentacionController@delete_modulo');
Route::post('/informacion/documentacion/agregar_documento', 'DocumentacionController@agregar_documento');
Route::post('/informacion/documentacion/cargar_documentos', 'DocumentacionController@cargar_documentos');
Route::post('/informacion/documentacion/delete_documento', 'DocumentacionController@delete_documento');
Route::post('/informacion/documentacion/cargar_documentos_all', 'DocumentacionController@cargar_documentos_all');
Route::post('/informacion/documentacion/exportar_documentos', 'DocumentacionController@exportar_documentos');


//informacion personal
Route::get('/informacion/personal','InformacionPersonalController@index');
Route::get('/informacion/personal/{id}','InformacionPersonalController@show');
