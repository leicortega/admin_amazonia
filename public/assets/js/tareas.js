$(document).ready(function () {
    $.ajax({
        url: '/tareas/cargar_calendario',
        type: 'POST',
        data: {list:getParameterByName('list') ?? '0', tipo:$('#tipo').val()},
        success: function (data) {
            switch (data.tipo) {
                case 'Documentos Vehiculos':
                    data.documentos.forEach(tarea => {
                        evento = {id:tarea.id, title: tarea.name+' - '+tarea.placa, 
                        start: tarea.fecha_inicio_vigencia, 
                        end: tarea.fecha_fin_vigencia, 
                        color: '#FFE761'};
                     });
                    break;

                case 'Default':
                    data.documentos.forEach(tarea => {
                        evento = {id:tarea.id, title: tarea.name_tarea, start: tarea.fecha, end: tarea.fecha_limite, color: '#90151c'};
                    });
                    break;

                default:
                    data.forEach(tarea => {
                        evento = {id:tarea.id, title: tarea.name_tarea, start: tarea.fecha, end: tarea.fecha_limite, color: '#90151c'};
                    });
                    break;
            }
            console.log(data)
        }
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ver_adjunto (adjunto) {
    $('#ver_adjunto').attr('src', '../../../storage/'+adjunto);
    $('#modal_ver_adjunto').modal('show');
}

var val=0;

function cambiar_asignador(){
    if(val==0){
        $('#asingado_none').removeClass('d-none');
        val=1;
    }else{
        $('#asignado').val('');
        $('#asingado_none').addClass('d-none');
        val=0;
    }
}

$(function() {
    $('#calendar').fullCalendar({
        header: {
            language: 'es',
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay',
       },
       editable: true,
       eventLimit: true,
       selectable: true,
       selectHelper: true,
       eventClick: function (info){
            vertarea(info.id, info.tipo);
       },
       select: (start, end)=>{ //para cuando se da click en una fecha
            $('#time_fecha').val('');
            $('#time_fecha_final').val('');
            $('#id_editar').val('');
            $('#tarea').val('');
            $('#name_tarea').val('');
            $('#asignado').val('');
            $('#flexCheckChecked').removeAttr('checked');
            $('#asingado_none').addClass('d-none');
            val=0;
            $('#titulo_crear_tarea').val('Crear Tarea');
            $('#fecha').val(moment(start).format('YYYY-MM-DD'));
            $('#fecha_limite').val(moment(end).format('YYYY-MM-DD'));
            $('#modalCrearActivities').modal('show');
       },
        eventDrop: function(event, delta, revertFunc) { // si changement de position
            editar_tarea_calendar(event.id, moment(event.start).format('YYYY-MM-DD'), moment(event.end).format('YYYY-MM-DD'))
        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
            editar_tarea_calendar(event.id, moment(event.start).format('YYYY-MM-DD'), moment(event.end).format('YYYY-MM-DD'))
        },
        events:function(start, end, timezone, callback){
            $.ajax({
                url: '/tareas/cargar_calendario',
                type: 'POST',
                data: {list:getParameterByName('list') ?? '0'},
                success: function (data) {      
                    var eventos=[];
                    switch (data.tipo) {
                        case 'Documentos Vehiculos':
                             data.documentos.forEach(tarea => {
                                eventos.push({
                                    id: tarea.id,
                                    title: tarea.name+' - '+tarea.placa,
                                    start: tarea.fecha_fin_vigencia,
                                    end: tarea.fecha_fin_vigencia,
                                    color: '#FD3636',
                                    tipo: data.tipo
                                });
                            });
                            break;
                        case 'Documentos Administración':
                            data.documentos.forEach(tarea => {
                                eventos.push({
                                    id: tarea.id,
                                    title: tarea.nombre+' - '+tarea.name,
                                    start: tarea.fecha_fin_vigencia,
                                    end: tarea.fecha_fin_vigencia,
                                    color: '#FD3636',
                                    tipo: data.tipo
                                  });
                            });
                            break;
                        default:
                            data.documentos.forEach(tarea => {
                                eventos.push({
                                    id: tarea.id,
                                    title: tarea.name_tarea,
                                    start: tarea.fecha,
                                    end: tarea.fecha_limite,
                                    color: '#2fa97c',
                                    tipo: data.tipo
                                  });
                            });
                            break;
                    }
                    callback(eventos);
                }
            })
        }

    })
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function vertarea(id, tipo){
    $.ajax({
        url: '/tareas/vercalendario_tarea',
        type: 'POST',
        data: {id:id, tipo:tipo},
        success: function (tarea) {
            switch(tipo){
                case 'tarea':
                    contenido=`
                            <h4 class="mb-4">Asignada por: ${tarea.supervisor_id.name}</h4>
                            <table class="table table-bordered">
                            <thead class="table-bg-dark">
                                <tr>
                                    <th colspan="4" class="text-center"><b>DATOS DE TAREA (${tarea.name_tarea})</b></th>
                                </tr>
                                <tr>
                                    <th>Fecha asignada</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Fecha limite</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${tarea.fecha}</td>
                                    <td>${tarea.asignado_id.name}</td>
                                    <td>${tarea.estado}</td>
                                    <td>${tarea.fecha_limite}</td>
                                </tr>
                            </tbody>
                            <thead class="table-bg-dark">
                                <tr>
                                    <th colspan="3" class="text-center">Tarea</th>
                                    <th colspan="1" class="text-center">Adjunto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">${tarea.tarea}</td>
                                    <td colspan="1" class="text-center">`

                                    if(tarea.adjunto){
                                        contenido+=`<button type="button" class="btn btn-success btn-lg"  onclick="ver_documento_legal('${tarea.adjunto}', 'Tarea Adjunto',this)">Ver adjunto</button>`;
                                    }else{
                                        contenido+=`No Hay Adjunto`;
                                    }

                                    contenido += `
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class='row mt-4'>
                            <div class="ml-3 col-sm-9">
                                <button type="button" class="btn btn-primary btn-lg"  onclick="editar_tarea_calendar('${tarea.id}')">Editar</button>
                                <button type="button" class="btn btn-danger btn-lg ml-3"  onclick="eliminar_tarea_calendar('${tarea.id}')">Eliminar</button>
                            </div>
                            <div class="ml-3 col-sm-2">
                                <button type="button" class="btn btn-info btn-lg" data-dismiss="modal" aria-label="Close">Cerrar</button>
                            </div>
                        </div>
                            `;
                        $('#body_ver').html(contenido);
                        
                    break;
                    case 'Documentos Vehiculos':
                        contenido=`
                            <h4 class="mb-4">${tipo}</h4>
                            <table class="table table-bordered">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th class="text-center table-bg-dark">No</th>
                                        <th class="text-center table-bg-dark">Fecha expedición</th>
                                        <th class="text-center table-bg-dark">Fecha Inicio</th>
                                        <th class="text-center table-bg-dark">Fecha Final</th>
                                        <th class="text-center table-bg-dark">Tipo</th>
                                        <th class="text-center table-bg-dark">Estado</th>
                                        <th class="text-center table-bg-dark">Acción</th>
                                    </tr>
                                </thead>
                                <tbody">
                                    <tr>
                                        <td class="text-center">${tarea.consecutivo}</td>
                                        <td class="text-center">${tarea.fecha_expedicion}</td>
                                        <td class="text-center">${tarea.fecha_inicio_vigencia}</td>
                                        <td class="text-center">${tarea.fecha_fin_vigencia}</td>
                                        <td class="text-center">${tarea.tipo.name}</td>
                                        <td class="text-center">${tarea.estado}</td>
                                        <td class="text-center">
                                            <a href="/vehiculos/ver/${tarea.vehiculo.id}" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                           
                        `;
                        $('#body_ver').html(contenido);
                        break;
                    case 'Documentos Administración':
                        contenido=`
                            <h4 class="mb-4">${tipo}</h4>
                            <table class="table table-bordered">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th class="text-center table-bg-dark">No</th>
                                        <th class="text-center table-bg-dark">Fecha expedición</th>
                                        <th class="text-center table-bg-dark">Fecha Inicio</th>
                                        <th class="text-center table-bg-dark">Fecha Final</th>
                                        <th class="text-center table-bg-dark">Tipo</th>
                                        <th class="text-center table-bg-dark">Nombre</th>
                                        <th class="text-center table-bg-dark">Acción</th>
                                    </tr>
                                </thead>
                                <tbody">
                                    <tr>
                                        <td class="text-center">${tarea.id}</td>
                                        <td class="text-center">${tarea.fecha_inicio_vigencia}</td>
                                        <td class="text-center">${tarea.fecha_inicio_vigencia}</td>
                                        <td class="text-center">${tarea.fecha_fin_vigencia}</td>
                                        <td class="text-center">${tarea.documentacion.nombre}</td>
                                        <td class="text-center">${tarea.nombre}</td>
                                        <td class="text-center">
                                            <a href="/informacion/documentacion" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        `;
                        $('#body_ver').html(contenido);
                        break;
            }
            
            $('#modalVerActivities').modal('show');
            
            
        }
    });
}

function eliminar_tarea_calendar(id){
    if(confirm('¿Desea eliminar esta tarea?')){
        window.location = '/tareas/eliminate/'+id;
    }
}

function cambiarurl(list){
    window.location = '/calendario?list='+list;
}

function editar_tarea_calendar(id, fecha_ini_pase, fecha_fin_pase){
    $.ajax({
        url: '/tareas/vercalendario_tarea',
        type: 'POST',
        data: {id:id},
        success: function (tarea) {
            console.log(tarea.id);
            hora=tarea.fecha.substr(11,5);
            fecha=tarea.fecha.substr(0,10);
            hora_limite=tarea.fecha_limite.substr(11,5);
            fecha_limite=tarea.fecha_limite.substr(0,10);
            $('#time_fecha').val(hora);
            if(fecha_ini_pase){
                $('#fecha').val(fecha_ini_pase);
            }else{
                $('#fecha').val(fecha);
            }
            if(fecha_fin_pase){
                $('#fecha_limite').val(fecha_fin_pase);
            }else{
                $('#fecha_limite').val(fecha_limite);
            }
            $('#time_fecha_final').val(hora_limite);
            $('#id_editar').val(tarea.id);
            $('#tarea').val(tarea.tarea);
            $('#name_tarea').val(tarea.name_tarea);
            if(tarea.asignado != tarea.supervisor){
                $('#asignado').val(tarea.asignado);
                $('#flexCheckChecked').attr('checked', true);
                $('#asingado_none').removeClass('d-none');
                val=1;
            }
            $('#titulo_crear_tarea').val('Editar Tarea');
            $('#modalVerActivities').modal('hide');
            $('#modalCrearActivities').modal('show');
        }
    });
}

function ver_documento_legal(documento_file, tipo, btn) {
    $(btn).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', true);
    $('#modal_ver_documento_title').text(tipo);
    $('#modal_ver_documento_content').html(`<iframe src="/storage/${ documento_file }" width="100%" height="810px" frameborder="0"></iframe>`)
    $('#modal_ver_documento').modal('show')
    $(btn).html('Ver adjunto').removeAttr('disabled');
}

function cambiarTipoEventos(tipo) {
    $.ajax({
        url: '/tareas/cambiar_tipo_eventos_calendario',
        type: 'POST',
        data: {list:getParameterByName('list') ?? '0', tipo:$('#tipo').val()},
        success: function (data) {
            window.location.reload();
        }
    });
}



