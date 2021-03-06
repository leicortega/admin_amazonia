function cargarbtn(btn){
    $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $(btn).attr('disabled', 'true');
}

function eliminar_documentos_vehiculo(id, btn){
    if (window.confirm('¿Seguro desea eliminar el documento?')) {
        $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', 'true');
        window.location.href = '/admin/sistema/eliminar_documento_vehiculo/'+id;
    }
}

function eliminar_ddocumento_vehiculo(id, name, btn){
    if (window.confirm('¿Seguro desea eliminar el documento " '+name+' "?')) {
        $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', 'true');
        window.location.href = '/admin/sistema/eliminar_documento_cargos/'+id;
    }
}

function editar_documento_vehiculo(id, name, vigencia, btn) {
    $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', 'true');
    $('#name_doc').val(name);
    $('#vigencia_doc').val(vigencia);
    $('#id_doc').val(id);
    $('#crear_datos_documentos').html('Editar');
    $('#modal_agregar_documentos-title').html('Editar Documento')
    $('#modal_agregar_documentos').modal('show');
    $(btn).html('<i class="fas fa-edit"></i>').removeAttr('disabled');
}


function agregar_documento_vehiculo() {
    $('#name_doc').val('');
    $('#vigencia_doc').val('');
    $('#id_doc').val('');
    $('#modal_agregar_documentos-title').html('Crear Documento');
    $('#form_crear_doc')[0].reset();
    $('#crear_datos_documentos').html('Agregar');
    $('#modal_agregar_documentos').modal('show');
}



function editar_datos_vehiculo(cargo){
    console.log(cargo);
    $('#nombre_cargo').val(cargo.nombre);
    $('#funciones_cargo').val(cargo.funciones);
    $('#obligaciones_cargo').val(cargo.obligaciones);
    $('#id_cargo').val(cargo.id);
    cargo.documentos.forEach(doc => {
        $('#documentos_cargo'+ doc.id).attr('checked', true);
    });
    $('#modal-create-datos-vehiculo-title').html('Editar Cargo');
    $('#crear_datos_vehivulo').html('Editar');
    $('#modal-create-datos-vehiculo').modal('show');
}

function agregar_datos_vehiculo(){
    $('#nombre_cargo').val('');
    $('#funciones_cargo').val('');
    $('#obligaciones_cargo').val('');
    $('#id_cargo').val('');
    $('.documentos_cargos').removeAttr('checked');
    $('#modal-create-datos-vehiculo-title').html('Agregar Cargo');
    $('#crear_datos_vehivulo').html('Agregar');
    $('#form_agg_cargo')[0].reset();
    $('#modal-create-datos-vehiculo').modal('show');
}