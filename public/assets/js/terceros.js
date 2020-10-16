$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('#form_agg_contacto').submit(function () {
        $.ajax({
            url: '/terceros/agg_contacto',
            type: 'POST',
            data: $('#form_agg_contacto').serialize(),
            success: function (data) {
                $('#agg_contacto').modal('hide');
                $('#form_agg_contacto')[0].reset();
                cargar_contactos(data);
            }
        });

        return false;
    });

    $('#agg_documento').submit(function () {
        var form = document.getElementById('agg_documento');
        var formData = new FormData(form);
        $.ajax({
            url: '/terceros/agg_documento',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
	        processData: false,
            success: function (data) {
                console.log(data)
                $('#agg_documento')[0].reset();
                $('#agg_documento_modal').modal('hide');
                cargar_documentos(data.terceros_id);
            }
        });

        return false;
    });

    $('#agg_documento').submit(function () {
        var form = document.getElementById('agg_documento');
        var formData = new FormData(form);
        $.ajax({
            url: '/terceros/agg_documento',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
	        processData: false,
            success: function (data) {
                console.log(data)
                $('#agg_documento')[0].reset();
                $('#agg_documento_modal').modal('hide');
                cargar_documentos(data.terceros_id);
            }
        });

        return false;
    });

    $('#form_crear_cotizacion').submit(function () {
        if ($('#fecha_ida').val() == '' ||
            $('#fecha_regreso').val() == '' ||
            $('#tipo_servicio').val() == '' ||
            $('#tipo_vehiculo').val() == '' ||
            $('#departamento_origen').val() == '' ||
            $('#ciudad_origen').val() == '' ||
            $('#departamento_destino').val() == '' ||
            $('#ciudad_destino').val() == '' ||
            $('#descripcion').val() == '' ||
            $('#cotizacion_por').val() == '' ||
            $('#valor_unitario').val() == '' ||
            $('#cantidad').val() == '') {
            $('#alert_crear_cotizacion').removeClass('d-none');
        } else {
            $('#alert_crear_cotizacion').addClass('d-none');
            $.ajax({
                url: '/terceros/crear_cotizacion',
                type: 'POST',
                data: $('#form_crear_cotizacion').serialize(),
                success: function (data) {
                    window.open('/terceros/print_cotizacion/'+data.id, '_blank');
                    $('#form_crear_cotizacion')[0].reset();
                    $('#modal_crear_cotizacion').modal('hide');
                    cargar_cotizaciones(data.tercero_id);
                    back_cotizacion();
                }
            });
        }

        return false;
    });

    $('#form_eliminar_cotizacion').submit(function () {
        $.ajax({
            url: '/terceros/eliminar_cotizacion',
            type: 'POST',
            data: $('#form_eliminar_cotizacion').serialize(),
            success: function (data) {
                $('#modal_eliminar_cotizacion').modal('hide');
                $('#form_eliminar_cotizacion')[0].reset();
                cargar_cotizaciones(data);
                cargar_contratos(data);
            }
        });

        return false;
    });

    $('#form_generar_contrato').submit(function () {
        $.ajax({
            url: '/terceros/generar_contrato',
            type: 'POST',
            data: $('#form_generar_contrato').serialize(),
            success: function (data) {
                console.log(data.tercero);
                $('#modal-crear-contrato').modal('hide');
                $('#form_generar_contrato')[0].reset();
                cargar_contratos(data.tercero);
                window.open('/terceros/print_contrato/'+data.cotizacion, '_blank');
            }
        });

        return false;
    });
});

function cargar_contactos(id) {
    $.ajax({
        url: '/terceros/cargar_contactos',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            let content = '';
            data.forEach(contacto => {
                content += `
                    <tr>
                        <td scope="row">${ contacto.identificacion }</td>
                        <td>${ contacto.nombre }</td>
                        <td>${ contacto.telefono }</td>
                        <td>${ contacto.correo }</td>
                        <td class="text-center"><button type="button" onclick="eliminar_contacto(${ contacto.id })" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
            });
            $('#content_table_contactos').html(content);
        }
    });
}

function cargar_documentos(terceros_id) {
    $.ajax({
        url: '/terceros/cargar_documentos',
        type: 'POST',
        data: {terceros_id:terceros_id},
        success: function (data) {
            content = '';
            data.forEach( function (documento, indice) {
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ documento.tipo }</td>
                    <td>${ documento.descripcion }</td>
                    <td class="text-center">
                        <button type="button" onclick="editar_documento(${ documento.id })" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_documento('${ documento.adjunto_file }', '${ documento.tipo }')" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <a href="/storage/${ documento.adjunto_file }" download class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <button type="button" onclick="eliminar_documento(${ documento.id }, ${ documento.terceros_id })" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#content_table_documentos_adjuntos').html(content);

        }
    });
}

function eliminar_documento(id, terceros_id) {
    $.ajax({
        url: '/terceros/delete_documento',
        type: 'POST',
        data: { id:id, terceros_id:terceros_id },
        success: function (data) {
            cargar_documentos(data);
        }
    });
}

function ver_documento(adjunto, tipo) {
    $('#modal_ver_documento_title').text(tipo)
    $('#modal_ver_documento_content').html(`<iframe src="/storage/${ adjunto }" width="100%" height="810px" frameborder="0"></iframe>`)
    $('#modal_ver_documento').modal('show');
}

function editar_documento(id) {
    $.ajax({
        url: '/terceros/editar_documento',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            $('#tipo').val(data.tipo);
            $('#descripcion_documento').val(data.descripcion);

            $('#id').val(data.id);

            $('#adjunto_file').removeAttr('required');

            $('#agg_documento_modal').modal('show');
            $('#agg_documento_title').text('Editar ' + data.tipo);
        }
    });
}

function cargar_cotizaciones(terceros_id) {
    $.ajax({
        url: '/terceros/cargar_cotizaciones',
        type: 'POST',
        data: {terceros_id:terceros_id},
        success: function (data) {
            content = '';
            data.forEach( function (cotizacion, indice) {
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ new Date(cotizacion.fecha).toLocaleDateString() }</td>
                    <td>${ cotizacion.tipo_servicio }</td>
                    <td>${ cotizacion.tipo_vehiculo }</td>
                    <td>${ cotizacion.ciudad_origen } - ${ cotizacion.ciudad_destino }</td>
                    <td class="text-center">
                        <a href="javascript:generar_contrato(${ cotizacion.id })" class="btn btn-sm btn-primary waves-effect waves-light" title="Generar Contrato"><i class="fa fa-check"></i></a>
                        <button type="button" onclick="editar_cotizacion(${ cotizacion.id })" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_cotizacion(${ cotizacion.id })" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <button type="button" onclick="eliminar_cotizacion(${ cotizacion.id }, 'Cotizacion')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#content_table_cotizaciones').html(content);

        }
    });
}

function cargar_contratos(terceros_id) {
    $.ajax({
        url: '/terceros/cargar_contratos',
        type: 'POST',
        data: {terceros_id:terceros_id},
        success: function (data) {
            content = '';
            data.forEach( function (cotizacion, indice) {
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ new Date(cotizacion.fecha).toLocaleDateString() }</td>
                    <td>${ cotizacion.tipo_servicio }</td>
                    <td>${ cotizacion.tipo_vehiculo }</td>
                    <td>${ cotizacion.ciudad_origen } - ${ cotizacion.ciudad_destino }</td>
                    <td class="text-center">
                        <button type="button" onclick="editar_contrato(${ cotizacion.id })" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_contrato(${ cotizacion.id })" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <button type="button" onclick="eliminar_cotizacion(${ cotizacion.id }, 'Contrato')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#content_table_contratos').html(content);

        }
    });
}

function total_cotizacion() {
    let valor = $('#valor_unitario').val()
    let cantidad = $('#cantidad').val()

    $('#total').val(valor*cantidad)
}

function cargarDepartamentos() {
    let html = '<option value="">Seleccione el departamento</option>';
	$.ajax({
		url: 'https://www.datos.gov.co/resource/xdk5-pm3f.json?$select=departamento&$group=departamento',
		type: 'GET',
		success: function (data) {
			data.forEach(dpt => {
				html += '<option value="'+dpt.departamento+'">'+dpt.departamento+'</option>';
			});
			$('.departamento_origen').html(html);
            $('.departamento_destino').html(html);
		}
    })
}

function dptOrigen(dpt) {
    var html = '<option value="">Seleccione</option>';
    $.ajax({
        url: 'https://www.datos.gov.co/resource/xdk5-pm3f.json?departamento='+dpt,
        type: 'GET',
        success: function (data) {
            data.forEach(dpt => {
                html += '<option value="'+dpt.municipio+'">'+dpt.municipio+'</option>';
            });
            $('.ciudad_origen').html(html)
        }
    })
}

function dptDestino(dpt) {
    var html = '<option value="">Seleccione</option>';
    $.ajax({
        url: 'https://www.datos.gov.co/resource/xdk5-pm3f.json?departamento='+dpt,
        type: 'GET',
        success: function (data) {
            data.forEach(dpt => {
                html += '<option value="'+dpt.municipio+'">'+dpt.municipio+'</option>';
            });
            $('.ciudad_destino').html(html)
        }
    })
}

function submit_cotizacion() {
    $('#fecha_ida_preview').html($('#fecha_ida').val());
    $('#fecha_regreso_preview').html($('#fecha_regreso').val());

    let descripcion = 'Recorrido 1: ' + $('#ciudad_origen').val() + ' ' + $('#descripcion').val() + ' ' + $('#ciudad_destino').val();

    $('input:radio[name=recorrido]:checked').val() == 'Ida y vuelta' ? descripcion += 'con retorno a '+ $('#ciudad_origen').val() +' por el mismo corredor vial,' : '';

    if ($('input:radio[name=conductor]:checked').val() == 'Si' && $('input:radio[name=combustible]:checked').val() == 'Si' && $('input:radio[name=peajes]:checked').val() == 'Si') {
        descripcion += ' incluye conductor, combustible y peajes,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'Si' && $('input:radio[name=combustible]:checked').val() == 'Si' && $('input:radio[name=peajes]:checked').val() == 'No') {
        descripcion += ' incluye conductor y combustible,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'Si' && $('input:radio[name=combustible]:checked').val() == 'No' && $('input:radio[name=peajes]:checked').val() == 'Si') {
        descripcion += ' incluye conductor y peajes,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'No' && $('input:radio[name=combustible]:checked').val() == 'Si' && $('input:radio[name=peajes]:checked').val() == 'Si') {
        descripcion += ' incluye combustible y peajes,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'Si' && $('input:radio[name=combustible]:checked').val() == 'No' && $('input:radio[name=peajes]:checked').val() == 'No') {
        descripcion += ' incluye conductor,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'No' && $('input:radio[name=combustible]:checked').val() == 'Si' && $('input:radio[name=peajes]:checked').val() == 'No') {
        descripcion += ' incluye combustible,';
    }

    if ($('input:radio[name=conductor]:checked').val() == 'No' && $('input:radio[name=combustible]:checked').val() == 'No' && $('input:radio[name=peajes]:checked').val() == 'Si') {
        descripcion += ' incluye peajes,';
    }

    descripcion += 'el tipo de servicio es ' + $('#tipo_servicio').val() + ' el cual se prestara en un(a) ' + $('#tipo_vehiculo').val() + ' y el cobro se calcula por ' + $('input:radio[name=cotizacion_por]:checked').val() + '. ' + $('#observaciones').val() + '<br><br>';

    descripcion += ($('#trayecto_dos').val()) ? 'Recorrido 2: ' + $('#trayecto_dos').val() : 'Recorrido 2: N/A';

    $('#descripcion_preview').html(descripcion);

    $('#valor_unitario_preview').html('$' + $('#valor_unitario').val());
    $('#cantidad_preview').html($('#cantidad').val());
    $('#total_preview').html('$' + $('#total').val());

    $('#form_part_one').addClass('d-none');
    $('#form_part_two').removeClass('d-none');

    $('#btn_next_cotizacion').addClass('d-none');
    $('#btn_submit_cotizacion').removeClass('d-none');
    $('#btn_back_cotizacion').removeClass('d-none');
}

function back_cotizacion() {
    $('#form_part_one').removeClass('d-none');
    $('#form_part_two').addClass('d-none');

    $('#btn_next_cotizacion').removeClass('d-none');
    $('#btn_submit_cotizacion').addClass('d-none');
    $('#btn_back_cotizacion').addClass('d-none');
}

function generar_contrato(id) {
    $('#modal-crear-contrato').modal('show');
    $('#cotizacion_id_contrato').val(id);
}

function ver_cotizacion(id) {
    window.open('/terceros/print_cotizacion/' + id, '_blank');
}

function ver_contrato(id) {
    window.open('/terceros/print_contrato/' + id, '_blank');
}

function eliminar_cotizacion(id, title) {
    $('#modal_eliminar_cotizacion').modal('show');
    $('#cotizacion_id').val(id);
    $('#modal_eliminar_cotizacion_tilte').text('Eliminar ' + title);
    $('#modal_eliminar_cotizacion_content').html(`
        <h2>¿Seguro desea eliminar ${ title == 'Contrato' ? 'el contrato' : 'la cotizacion' }?</h2>
        <br>
        <h5>Se eliminara ${ title == 'Contrato' ? 'la cotizacion' : 'el contrato si lo hay' }</h5>
    `);
}

function editar_cotizacion(id) {
    $.ajax({
        url: '/terceros/editar_cotizacion',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $('#ciudad_origen').html('<option value="'+data.ciudad_origen+'">'+data.ciudad_origen+'</option>');
            $('#ciudad_destino').html('<option value="'+data.ciudad_destino+'">'+data.ciudad_destino+'</option>');

            $('#modal_crear_cotizacion').modal('show');
            $('#fecha_ida').val(data.fecha_ida);
            $('#fecha_regreso').val(data.fecha_regreso);
            $('#tipo_servicio option[value="'+ data.tipo_servicio + '"]').attr("selected", true);
            $('#tipo_vehiculo option[value="'+ data.tipo_vehiculo + '"]').attr("selected", true);
            $('#departamento_origen option[value="'+ data.departamento_origen + '"]').attr("selected", true);
            $('#departamento_destino option[value="'+ data.departamento_destino + '"]').attr("selected", true);
            $('#ciudad_origen option[value="'+ data.ciudad_origen + '"]').attr("selected", true);
            $('#ciudad_destino option[value="'+ data.ciudad_destino + '"]').attr("selected", true);
            $('#descripcion').val(data.descripcion);
            $('#observaciones').val(data.observaciones);
            $('input[name=combustible][value="'+data.combustible+'"]').attr('checked', true);
            $('input[name=conductor][value="'+data.conductor+'"]').attr('checked', true);
            $('input[name=peajes][value="'+data.peajes+'"]').attr('checked', true);
            $('input[name=cotizacion_por][value="'+data.cotizacion_por+'"]').attr('checked', true);
            $('input[name=recorrido][value="'+data.recorrido+'"]').attr('checked', true);
            $('#valor_unitario').val(data.valor_unitario);
            $('#cantidad').val(data.cantidad);
            $('#total').val(data.total);
            $('#trayecto_dos').val(data.trayecto_dos);
            $('#cotizacion_parte_uno').val(data.cotizacion_parte_uno);
            $('#cotizacion_parte_dos').val(data.cotizacion_parte_dos);

            $('#cotizacion_creada').val(data.id);
        }
    });
}

function editar_contrato(id) {
    $.ajax({
        url: '/terceros/editar_contrato',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            console.log(data);
            $('#modal-crear-contrato').modal('show');
            $('#select_responsable option[value="'+ data.responsable.identificacion + '"]').attr("selected", true);
            $('#identificacion_responsable').val(data.responsable.identificacion);
            $('#nombre_responsable').val(data.responsable.nombre);
            $('#correo_responsable').val(data.responsable.correo);
            $('#telefono_responsable').val(data.responsable.telefono);
            $('#tipo_contrato option[value="'+ data.cotizacion.tipo_contrato + '"]').attr("selected", true);
            $('#objeto_contrato').val(data.cotizacion.objeto_contrato);
            $('#vehiculo_id option[value="'+ data.cotizacion.vehiculo_id + '"]').attr("selected", true);
            $('#conductor_id option[value="'+ data.cotizacion.conductor_id + '"]').attr("selected", true);
            $('#contrato_parte_uno').val(data.cotizacion.contrato_parte_uno);
            $('#contrato_parte_dos').val(data.cotizacion.contrato_parte_dos);

            $('#cotizacion_id_contrato').val(data.cotizacion.id);
        }
    });
}

function cargar_responsable_contrato(responsable) {
    $.ajax({
        url: '/terceros/cargar_responsable_contrato',
        type: 'post',
        data: {responsable:responsable},
        success: function (data) {
            if ( data ) {
                $('#identificacion_responsable').val(data.identificacion).attr('readonly', true);
                $('#nombre_responsable').val(data.nombre).attr('readonly', true);
                $('#correo_responsable').val(data.correo).attr('readonly', true);
                $('#telefono_responsable').val(data.telefono).attr('readonly', true);
            }
        }
    });

    if (responsable == 'Nuevo') {
        $('#identificacion_responsable').val('').attr('readonly', false);
        $('#nombre_responsable').val('').attr('readonly', false);
        $('#correo_responsable').val('').attr('readonly', false);
        $('#telefono_responsable').val('').attr('readonly', false);
    }
}

function eliminar_contacto(id) {
    $.ajax({
        url: '/terceros/eliminar_contacto',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            cargar_contactos(data);
        }
    });
}
