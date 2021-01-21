
$(document).ready(function () {
    $('#form_agg_conductor').submit(function () {
        $.ajax({
            url: '/vehiculos/agg_conductor',
            type: 'POST',
            data: $('#form_agg_conductor').serialize(),
            success: function (data) {
                cargar_conductores(data)
                $('#alerta_success').removeClass('d-none');
                $('#alerta_success').html('Se ha Creado el Conductor Correctamente');
                window.setTimeout(function() {
                    $('#alerta_success').addClass('d-none');
                }, 3000);
                $("#form_agg_conductor")[0].reset();
            }
        })

        return false;
    })

    $('#form_exportar_documentos').submit(function () {
        $('#btn_submit_exportar_documentos').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', true);

        $.ajax({
            url: '/vehiculos/exportar_documentos',
            type: 'POST',
            data: $('#form_exportar_documentos').serialize(),
            success: function (data) {
                console.log(data);
                $('#btn_submit_exportar_documentos').html('Enviar').attr('disabled', false);
                $('#form_exportar_documentos')[0].reset();
                $('#modal_exportar').modal('hide');
                window.open('/storage/docs/vehiculos/documentacion.zip', '_blank');
            }
        });

        return false;
    });


    $('#agg_targeta_propiedad').submit(function () {
        var form = document.getElementById('agg_targeta_propiedad');
        var formData = new FormData(form);
        $.ajax({
            url: '/vehiculos/agg_targeta_propiedad',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
	        processData: false,
            success: function (data) {
                $('#agg_targeta_propiedad')[0].reset();
                $('#agg_doc_legal').modal('hide');
                console.log(data);
                documentos_legales(data.tipo, data.vehiculo_id, data.id_table, data.vigencia);
            }
        })

        return false;
    })

})

function estado_nuevo(seleccion){
    if($(seleccion).val()=='inactivo'){
        $('#estado_inactivo').removeClass('d-none');
        $('#observacion_estado').prop('required');
        $('#fecha_estado').prop('required');
    }else{
        $('#estado_inactivo').addClass('d-none');
        $('#observacion_estado').removeAttr('required');
        $('#fecha_estado').removeAttr('required');
    }
}

function cargar_conductores(id) {
    $.ajax({
        url: '/vehiculos/cargar_conductores',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            content = '';
            data.forEach( function (conductor, indice) {
                fecha = new Date(conductor.fecha_final);
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ conductor.personal.nombres } ${ conductor.personal.primer_apellido } ${ conductor.personal.segundo_apellido ?? '' }</td>
                    `;

                //verifica que este activo o no
                if((fecha.getDate() >= new Date().getDate()) && (fecha.getFullYear() >= new Date().getFullYear()) && (fecha.getMonth() >= new Date().getMonth())){
                    content += `<td>Activo</td>`;
                }else{
                    content += `<td>Inactivo</td>`;
                }
                    
                content +=`<td class="text-center"><button type="button" onclick="ver_historial_conductor(${ conductor.personal_id}, ${conductor.vehiculo_id})" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#modal_ver_historial_conductor"><i class="fa fa-eye"></i></button></td></tr> `;
            });
            
           if(content!=''){
            $('#content_table_conductores').html(content);
            }else{
                $('#content_table_conductores').html(`
                <tr>
                    <td colspan="8" class="text-center">
                        <p>No Existen Conductores</p>
                    </td>
                </tr>`);
            }

        }
    })
}

function eliminar_conductor(id, personal_id, vehiculo_id, btn) {
    $(btn).parent('td').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $.ajax({
        url: '/vehiculos/eliminar_conductor',
        type: 'POST',
        data: {id:id, personal_id:personal_id, vehiculo_id:vehiculo_id},
        success: function (data) {
            console.log(data);
            ver_historial_conductor(data.personal_id, data.vehiculo_id);
        }
    })
}

function ver_historial_conductor(id, vehiculo_id) {
    $.ajax({
        url: '/vehiculos/ver_conductor_historial',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id},
        success: function (data) {
            var content = '';
            contado=0;
            data.forEach( function (conductor, indice) {
                $('#modal_ver_historial_conductor_title').html('Historial del conductor ' + conductor.personal.nombres + ' '+ conductor.personal.primer_apellido)
                fecha = new Date(conductor.fecha_final);
                contado++;
                estado='inactivo';
                if((fecha.getDate() >= new Date().getDate()) && (fecha.getFullYear() >= new Date().getFullYear()) && (fecha.getMonth() >= new Date().getMonth())){
                    estado='activo';
                }
                content += `<tr>
                <th class="text-center">${contado}</th>
                <th class="text-center">${formatoFecha(conductor.fecha_inicial)}</th>
                <th class="text-center">${formatoFecha(conductor.fecha_final)}</th>
                <th class="text-center">${restaFechas(conductor.fecha_inicial, conductor.fecha_final)} dias</th>
                <th class="text-center">${estado}</th>
                <th class="text-center"><button type="button" onclick="eliminar_conductor(${ conductor.id }, ${conductor.personal_id}, ${conductor.vehiculo_id}, this)" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button></th>
            </tr>`;
            });
            
            $('#table_ver_historial_vehiculo').html(content);
            
        }
    })
}



function restaFechas(fechaa,fechab){
    let fecha1 = new Date(fechaa);
    let fecha2 = new Date(fechab);
    
    let resta = fecha2.getTime() - fecha1.getTime();
    return Math.round(resta/ (1000*60*60*24));
  }

  function formatoFecha(texto){
      if(texto == '' || texto == null){
        return null;
      }
      return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
    
  }


function agg_documento_legal(tipo_documento, id_table, vigencia, tipo_id, entidad_expide) {
    $('#agg_doc_legal').modal('show')
    $('#agg_doc_legal_title').text('Agregar '+tipo_documento)
    $('#consecutivo_title').text('Consecutivo '+tipo_documento)
    $('#tipo_id').val(tipo_id)
    $('#id_table').val(id_table)
    if(vigencia == '0'){
        $('#fechas_vigencias').addClass('d-none');
    }else{
        $('#fechas_vigencias').removeClass('d-none');
    }

    $('#consecutivo').val('');
    $('#fecha_expedicion').val('');
    $('#fecha_inicio_vigencia').val('');
    $('#fecha_fin_vigencia').val('');
    $('#id').val('');
    $('#estado').val('');

    $.ajax({
        url: '/vehiculos/carga_entidades',
        type: 'POST',
        data: {entidad:entidad_expide},
        success: function (data) {
            entidad_tr='<option value="">Seleccione</option>';
            data.forEach(entidad => {
                entidad_tr += `<option value="${entidad.nombre}">${entidad.nombre}</option>`;
            });
            $('#entidad_expide').html(entidad_tr);
        }
    });

}


function documentos_legales(tipo, vehiculo_id, id_table, vigencia) {
    $.ajax({
        url: '/vehiculos/cargar_tarjeta_propiedad',
        type: 'POST',
        data: {tipo:tipo, vehiculo_id:vehiculo_id},
        success: function (data) {
            content = '';
            data.forEach(documento => {
                content += `
                <tr>
                    <td scope="row">${ documento.consecutivo }</td>
                    <td>${ formatoFecha(documento.fecha_expedicion) }</td>`
                
                    if(documento.vigencia != '0'){
                        content += ` <td>${ formatoFecha(documento.fecha_inicio_vigencia) ?? 'No aplica' }</td>
                        <td>${ formatoFecha(documento.fecha_fin_vigencia) ?? 'No aplica' }</td>
                        <td>${ (formatoFecha(documento.fecha_inicio_vigencia)) ? restaFechas(documento.fecha_inicio_vigencia, documento.fecha_fin_vigencia)  : 'No aplica' }</td>`
                    }

                content += `<td width="250px">${ documento.entidad_expide }</td>
                    <td>${ documento.estado }</td>
                    <td width="180px" class="text-center">
                        <button type="button" onclick="editar_documento_legal(${ documento.id }, '${ id_table }')" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_documento_legal('${ documento.documento_file }', '${ documento.name }')" ${ (documento.documento_file) ? '' : 'disabled' } class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <a href="/storage/${ documento.documento_file }" download class="${ (documento.documento_file) ? '' : 'disabled' } btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <button type="button" onclick="eliminar_documento_legal(${ documento.id }, ${ documento.vehiculo_id }, '${ documento.tipo_id }', '${ id_table }', ${ vigencia })" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            if(content != ''){
                $('#'+id_table).html(content);
            }else{
                if(vigencia != '0'){
                    $('#'+id_table).html(`<tr><td class="text-center" colspan="7">No hay documentos<td></tr>`);
                }else{
                    $('#'+id_table).html(`<tr><td class="text-center" colspan="4">No hay documentos<td></tr>`);
                }
                
            }

        }
    })
}

function eliminar_documento_legal(id, vehiculo_id, tipo, id_table, vigencia) {
    $.ajax({
        url: '/vehiculos/eliminar_documento_legal',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id, tipo:tipo},
        success: function (data) {
            documentos_legales(data.tipo, data.vehiculo_id, id_table, vigencia);
        }
    });
}

function editar_documento_legal(id, id_tale) {
    $.ajax({
        url: '/vehiculos/get_documento_legal',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            $('#consecutivo').val(data.consecutivo);
            $('#fecha_expedicion').val(data.fecha_expedicion);
            $('#fecha_inicio_vigencia').val(data.fecha_inicio_vigencia);
            $('#fecha_fin_vigencia').val(data.fecha_fin_vigencia);
            $('#id').val(data.id);
            $('#estado').val(data.estado);
            entidad_tr='';
            terceo=data.entidad_expide;
            $.ajax({
                url: '/vehiculos/carga_entidades',
                type: 'POST',
                data: {entidad:data.tipo_tercero},
                success: function (data) {
                    entidad_tr='<option value="">Seleccione</option>';
                    data.forEach(entidad => {
                        if(entidad.nombre == terceo){
                            entidad_tr += `<option selected value="${entidad.nombre}">${entidad.nombre}</option>`;
                        }else{
                            entidad_tr += `<option value="${entidad.nombre}">${entidad.nombre}</option>`;
                        }
                    });
                    $('#entidad_expide').html(entidad_tr);
                }
            });
            $('#agg_doc_legal_title').text('Editar ' + data.name);
            $('#consecutivo_title').text('Editar ' + data.name);
            $('#agg_doc_legal').modal('show');

            $('#tipo_id').val(data.tipo_id)
            $('#id_table').val(id_tale)
            if(data.vigencia == '0'){
                $('#fechas_vigencias').addClass('d-none');
            }else{
                $('#fechas_vigencias').removeClass('d-none');
            }
        }
    })
}

function ver_documento_legal(documento_file, tipo) {
    $('#modal_ver_documento_title').text(tipo)
    $('#modal_ver_documento_content').html(`<iframe src="/storage/${ documento_file }" width="100%" height="810px" frameborder="0"></iframe>`)
    $('#modal_ver_documento').modal('show')
}

function cargarbtn(btn){
    $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $(btn).attr('disabled', 'true');
}

function addcheck(btn){
    if($(btn).find('i')['length'] == 0 && $('#consecutivo'+btn).val() != ''){
        $('#btn'+btn).append(' <i class="fa fa-check text-primary" aria-hidden="true"></i>');

    }
    
}


function select_tipo_vinculacion(tipo) {
    console.log(tipo);
    if (tipo == 1) {
        $('#div_empresa_convenio').removeClass('d-none');
        $('#div_item1').removeClass('col-sm-4').addClass('col-sm-3');
        $('#div_item2').removeClass('col-sm-4').addClass('col-sm-3');
        $('#div_item3').removeClass('col-sm-4').addClass('col-sm-3');
    } else {
        $('#div_empresa_convenio').addClass('d-none');
        $('#div_item1').removeClass('col-sm-3').addClass('col-sm-4');
        $('#div_item2').removeClass('col-sm-3').addClass('col-sm-4');
        $('#div_item3').removeClass('col-sm-3').addClass('col-sm-4');
    }
}

function exportar_documentos() {
    $('#exporta_documentos_id').append('<span class="ml-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', true);
    id=$('#vehiculo_id_conductor').val();
    $.ajax({
        url: '/vehiculos/cargar_documentos_all',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            let content = '';
            data.forEach(item => {
                if(item.documento_file != null && item.documento_file != ''){
                    content += `
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="customCheck${item.id}" name="documentos[]" value="${item.id}">
                        <label class="custom-control-label" for="customCheck${item.id}">${item.consecutivo} - ${item.name}</label>
                    </div>
                `;
                }
            });

            $('#exporta_documentos_id').html('Esxportar Documentos').attr('disabled', false);

            if(content != null && content != ''){
                $('#content_exportar_documentos').html(content);
            }else{
                $('#content_exportar_documentos').html(`<span class="text-center">No hay Documentos<span>`);
            }
            $('#modal_exportar').modal('show');
        }
    });
}
