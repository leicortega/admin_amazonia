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

    $('#form_eliminar_contrato').submit(function () {
        $.ajax({
            url: '/terceros/eliminar_contrato',
            type: 'POST',
            data: $('#form_eliminar_contrato').serialize(),
            success: function (data) {
                $('#modal_eliminar_contrato').modal('hide');
                $('#form_eliminar_contrato')[0].reset();
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
                $('#modal-crear-contrato').modal('hide');
                $('#form_generar_contrato')[0].reset();
                cargar_contratos(data.tercero);
                window.open('/terceros/print_contrato/'+data.trayecto, '_blank');
            }
        });

        return false;
    });

    $('#form_actualizar_contrato').submit(function () {
        $.ajax({
            url: '/terceros/actualizar_contrato',
            type: 'POST',
            data: $('#form_actualizar_contrato').serialize(),
            success: function (data) {
                console.log(data);
                $('#modal_editar_contrato').modal('hide');
                $('#form_actualizar_contrato')[0].reset();
                cargar_contratos(data.tercero);
                window.open('/terceros/print_contrato/contrato/'+data.contrato, '_blank');
            }
        });

        return false;
    });

    $('#form_agregar_trayecto').submit(function () {
        $.ajax({
            url: '/terceros/agregar_trayecto',
            type: 'POST',
            data: $('#form_agregar_trayecto').serialize(),
            success: function (data) {
                console.log(data);
                $('#modal_agregar_trayecto').modal('hide');
                $('#form_agregar_trayecto')[0].reset();
                ver_trayectos(data.contrato);
                window.open('/terceros/print_contrato/'+data.trayecto, '_blank');
            }
        });

        return false;
    });

    cargarDepartamentos();
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
                        <td>${ contacto.direccion }</td>
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
                    <td>${ cotizacion.nombre }</td>
                    <td>${ cotizacion.tipo_contrato }</td>
                    <td>${ cotizacion.objeto_contrato }</td>
                    <td class="text-center">
                        <button type="button" onclick="editar_contrato(${ cotizacion.id })" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_contrato(${ cotizacion.id })" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <button type="button" onclick="ver_trayectos(${ cotizacion.id })" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-plus"></i></button>
                        <button type="button" onclick="eliminar_contrato(${ cotizacion.id }, 'Contrato')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#content_table_contratos').html(content);

        }
    });
}

function ver_trayectos(id) {
    $.ajax({
        url: '/terceros/ver_trayectos',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            console.log(data);
            content = `<button type="button" class="btn btn-lg btn-primary mb-3" onclick="agregar_trayecto(${id})">Agregar Trayecto</button>

                        <table class="table table-bordered">
                            <thead class="thead-inverse">
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Trayecto</th>
                                    <th scope="col">Servicio</th>
                                    <th scope="col">Vehiculo</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            data.forEach( function (trayecto, indice) {
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ new Date(trayecto.fecha).toLocaleDateString() }</td>
                    <td>${ trayecto.ciudad_origen } - ${ trayecto.ciudad_destino }</td>
                    <td>${ trayecto.tipo_servicio }</td>
                    <td>${ trayecto.tipo_vehiculo }</td>
                    <td class="text-center">
                        <button type="button" onclick="editar_trayecto(${ trayecto.id })" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_trayecto(${ trayecto.id })" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <button type="button" onclick="eliminar_trayecto(${ trayecto.id }, ${ id })" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            content += `</tbody>
            </table>`;

            $('#content_ver_trayectos').html(content);
            $('#modal-ver-trayectos').modal('show');

        }
    });
}

function agregar_trayecto(id) {
    $('#form_agregar_trayecto')[0].reset();
    $('#modal_agregar_trayecto').modal('show');
    $('#contratos_id').val(id);
    $('#trayecto_creado').val(null);
    $('#vehiculo_id_trayecto option[value=""]').attr("selected", true);
    $('#conductor_uno_id_trayecto option[value=""]').attr("selected", true);
    $('#conductor_dos_id_trayecto option[value=""]').attr("selected", true);
    $('#conductor_tres_id_trayecto option[value=""]').attr("selected", true);
    $('#tipo_servicio_trayecto option[value=""]').attr("selected", true);
    $('#tipo_vehiculo_trayecto option[value=""]').attr("selected", true);
    $('#departamento_origen_trayecto option[value=""]').attr("selected", true);
    $('#departamento_destino_trayecto option[value=""]').attr("selected", true);
    $('#ciudad_origen_trayecto').html('<option value="">Seleccione el departamento</option>');
    $('#ciudad_destino_trayecto').html('<option value="">Seleccione el departamento</option>');
    $('#ciudad_origen_trayecto option[value=""]').attr("selected", true);
    $('#ciudad_destino_trayecto option[value=""]').attr("selected", true);
}

function total_cotizacion_trayecto() {
    let valor = $('#valor_unitario_trayecto').val()
    let cantidad = $('#cantidad_trayecto').val()

    $('#total_trayecto').val(valor*cantidad)
}

function total_cotizacion() {
    let valor = $('#valor_unitario').val()
    let cantidad = $('#cantidad').val()

    $('#total').val(valor*cantidad)
}

function cargarDepartamentos() {
    let html = '<option value="">Seleccione el departamento</option>';
	$.ajax({
		url: '/app/sistema/get/departamentos',
        type: 'POST',
		success: function (data) {
			data.forEach(dpt => {
				html += '<option value="'+dpt.nombre+'">'+dpt.nombre+'</option>';
			});
			$('#departamento').html(html);
			$('#departamento_origen').html(html);
            $('#departamento_destino').html(html);
            $('#departamento_origen_trayecto').html(html);
			$('#departamento_destino_trayecto').html(html);
		}
    })
}

function dptOrigen(dpt) {
    var html = '<option value="">Seleccione</option>';
    $.ajax({
        url: '/app/sistema/get/municipios',
        type: 'POST',
        data: { dpt:dpt },
        success: function (data) {
            data.municipios.forEach(dpt => {
                html += '<option value="'+dpt.nombre+'">'+dpt.nombre+'</option>';
            });
            $('.ciudad_origen').html(html)
        }
    })
}

function dptDestino(dpt) {
    var html = '<option value="">Seleccione</option>';
    $.ajax({
        url: '/app/sistema/get/municipios',
        type: 'POST',
        data: { dpt:dpt },
        success: function (data) {
            data.municipios.forEach(dpt => {
                html += '<option value="'+dpt.nombre+'">'+dpt.nombre+'</option>';
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
    window.open('/terceros/print_contrato/contrato/' + id, '_blank');
}

function ver_trayecto(id) {
    window.open('/terceros/print_contrato/' + id, '_blank');
}

function eliminar_cotizacion(id, title) {
    $('#modal_eliminar_cotizacion').modal('show');
    $('#cotizacion_id').val(id);
    $('#modal_eliminar_cotizacion_tilte').text('Eliminar ' + title);
    $('#modal_eliminar_cotizacion_content').html(`
        <h2>¿Seguro desea eliminar ${ title == 'Contrato' ? 'el contrato' : 'la cotizacion' }?</h2>
    `);
}

function eliminar_contrato(id, title) {
    $('#modal_eliminar_contrato').modal('show');
    $('#contrato_id_delete').val(id);
    $('#modal_eliminar_contrato_tilte').text('Eliminar ' + title);
    $('#modal_eliminar_contrato_content').html(`
        <h2>¿Seguro desea eliminar ${ title == 'Contrato' ? 'el contrato' : 'la cotizacion' }?</h2>
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
            $('#modal_editar_contrato').modal('show');
            $('#select_responsable_update option[value="'+ data.responsable.identificacion + '"]').attr("selected", true);
            $('#identificacion_responsable_update').val(data.responsable.identificacion);
            $('#nombre_responsable_update').val(data.responsable.nombre);
            $('#direccion_responsable_update').val(data.responsable.direccion);
            $('#telefono_responsable_update').val(data.responsable.telefono);
            $('#tipo_contrato_update option[value="'+ data.contrato.tipo_contrato + '"]').attr("selected", true);
            $('#objeto_contrato_update').val(data.contrato.objeto_contrato);
            $('#contrato_parte_uno_update').val(data.contrato.contrato_parte_uno);
            $('#contrato_parte_dos_update').val(data.contrato.contrato_parte_dos);

            $('#contrato_id').val(data.contrato.id);
        }
    });
}

function cargar_responsable_contrato(responsable) {
    let tercero = $('#terceros_id').val();
    $.ajax({
        url: '/terceros/cargar_responsable_contrato',
        type: 'post',
        data: {responsable:responsable, tercero:tercero},
        success: function (data) {
            if ( data ) {
                $('#identificacion_responsable').val(data.identificacion).attr('readonly', true);
                $('#nombre_responsable').val(data.nombre).attr('readonly', true);
                $('#direccion_responsable').val(data.direccion).attr('readonly', true);
                $('#telefono_responsable').val(data.telefono).attr('readonly', true);
            }
        }
    });

    if (responsable == 'Nuevo') {
        $('#identificacion_responsable').val('').attr('readonly', false);
        $('#nombre_responsable').val('').attr('readonly', false);
        $('#direccion_responsable').val('').attr('readonly', false);
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

function editar_tercero(id) {
    $.ajax({
        url: '/terceros/get_tercero',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $('#tipo_identificacion').val(data.tipo_identificacion);
            $('#identificacion').val(data.identificacion);
            $('#nombre').val(data.nombre);
            $('#tipo_tercero').val(data.tipo_tercero);
            $('#regimen').val(data.regimen);
            $('#departamento').val(data.departamento);
            $('#municipio').val(data.municipio);
            $('#direccion').val(data.direccion);
            $('#correo').val(data.correo);
            $('#telefono').val(data.telefono);

            $('#modal-editar-tercero').modal('show');
        }
    });
}

function eliminar_trayecto(id, contrato) {
    if (window.confirm("¿Seguro desea eliminar el trayecto?")) {
        $.ajax({
            url: '/terceros/eliminar_trayecto',
            type: 'post',
            data: {id:id, contrato:contrato},
            success: function (data) {
                ver_trayectos(data);
            }
        });
    }
}

function editar_trayecto(id) {
    $.ajax({
        url: '/terceros/editar_trayecto',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $('#ciudad_origen_trayecto').html('<option value="'+data.ciudad_origen+'">'+data.ciudad_origen+'</option>');
            $('#ciudad_destino_trayecto').html('<option value="'+data.ciudad_destino+'">'+data.ciudad_destino+'</option>');

            $('#modal_agregar_trayecto').modal('show');
            $('#fecha_ida_trayecto').val(data.fecha_ida);
            $('#fecha_regreso_trayecto').val(data.fecha_regreso);
            $('#tipo_servicio_trayecto option[value="'+ data.tipo_servicio + '"]').attr("selected", true);
            $('#tipo_vehiculo_trayecto option[value="'+ data.tipo_vehiculo + '"]').attr("selected", true);
            $('#departamento_origen_trayecto option[value="'+ data.departamento_origen + '"]').attr("selected", true);
            $('#departamento_destino_trayecto option[value="'+ data.departamento_destino + '"]').attr("selected", true);
            $('#ciudad_origen_trayecto option[value="'+ data.ciudad_origen + '"]').attr("selected", true);
            $('#ciudad_destino_trayecto option[value="'+ data.ciudad_destino + '"]').attr("selected", true);
            $('#descripcion_trayecto').val(data.descripcion);
            $('#observaciones_trayecto').val(data.observaciones);
            $('input[name=combustible_trayecto][value="'+data.combustible+'"]').attr('checked', true);
            $('input[name=conductor_trayecto][value="'+data.conductor+'"]').attr('checked', true);
            $('input[name=peajes_trayecto][value="'+data.peajes+'"]').attr('checked', true);
            $('input[name=cotizacion_por_trayecto][value="'+data.cotizacion_por+'"]').attr('checked', true);
            $('input[name=recorrido_trayecto][value="'+data.recorrido+'"]').attr('checked', true);
            $('#valor_unitario_trayecto').val(data.valor_unitario);
            $('#cantidad_trayecto').val(data.cantidad);
            $('#total_trayecto').val(data.total);
            $('#trayecto_dos_trayecto').val(data.trayecto_dos);
            $('#vehiculo_id_trayecto option[value="'+ data.vehiculo_id + '"]').attr("selected", true);
            $('#conductor_uno_id_trayecto option[value="'+ data.conductor_uno_id + '"]').attr("selected", true);
            $('#conductor_dos_id_trayecto option[value="'+ data.conductor_dos_id + '"]').attr("selected", true);
            $('#conductor_tres_id_trayecto option[value="'+ data.conductor_tres_id + '"]').attr("selected", true);

            $('#contratos_id').val(data.contratos.id);
            $('#trayecto_creado').val(data.id);
        }
    });
}














