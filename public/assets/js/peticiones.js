$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showUser(id) {
    $.ajax({
        url: '/admin/users/show/'+id,
        type: 'get',
        success: function (data) {
            $('#modal-blade').modal('show');
            $('#modal-blade-title').html(data.user.name);
            
            var content = `
            
                <form action="/admin/users/update" method="POST" >

                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                    <input type="hidden" name="id" value="${data.user.id}">
                
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="name" placeholder="Escriba el nombre" value="${data.user.name}" required />
                        </div>
                    </div>
                            
                    <div class="form-group row">
                        <label for="identificacion" class="col-sm-2 col-form-label">Identificacion</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="number" name="identificacion" placeholder="Escriba la identificacion" value="${data.user.identificacion}" required />
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Correo (opcional)</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" placeholder="Escriba el correo" value="${(data.user.email) ? data.user.email : ''}" />
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                        <div class="col-sm-10">
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="">Seleccione el estado</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="password" placeholder="Escriba la contraseña" />
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                        <div class="col-sm-10">
                            <select name="tipo" id="tipo" id="tipo" class="form-control" onchange="selectTipo(this.value)" required>
                                <option value="">Seleccione el tipo</option>
                                <option value="admin">admin</option>
                                <option value="general">general</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group row ${(data.rol == 'admin') ? 'd-none' : ''} divPermisos">
                        <label for="permiso" class="col-sm-2 col-form-label">Permiso</label>
                        <div class="col-sm-10 mt-2">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck11" name="permisos[]" value="universal" ${data.permisos.includes('universal') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck11">Universal</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck22" name="permisos[]" value="correos" ${data.permisos.includes('correos') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck22">Correos</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck33" name="permisos[]" value="cotizaciones" ${data.permisos.includes('cotizaciones') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck33">Cotizaciones</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck44" name="permisos[]" value="control ingreso" ${data.permisos.includes('control ingreso') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck44">Control ingreso</label>
                            </div>
                        </div>
                    </div>
                
                    <div class="mt-3">
                        <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Actualizar</button>
                    </div> 

                </form>`;

            $('#modal-blade-body').html(content);

            $('#estado').val(data.user.estado);
            $('#tipo').val(data.rol);

        }
    });
}

function showCorreo(id) {
    $.ajax({
        url: '/correos/show/'+id,
        type: 'get',
        success: function (data) {
            $('#modal-responder-correo').modal('show');
            $('#modal-title-correo').html('Correo #'+data.correo.id);
            
            var content = `
                <div class="card-body">

                    <input type="hidden" name="id" value="${data.correo.id}" />
                    
                    <div class="row">
                        <div class="col-10">
                            <h4 class="font-size-16 mb-4">${data.correo.asunto}</h4>
                        </div>
                        <div class="col-2">
                            <p>${data.correo.fecha}</p>
                        </div>
                    </div>
                    

                    <p>${data.correo.nombre+' '+data.correo.apellido},</p>
                    <p>${data.correo.mensaje}</p>
                    <hr>

                </div>
            `;

            $('#modal-content-correo').html(content);

            if (data.correo.id_user_respuesta) {
                var content_respuesta = `
                    <h4 class="header-title mb-4">Respuesta: ${data.user.name} - ${data.correo.fecha_respuesta}</h4>

                    ${data.correo.respuesta}
                `;
                $('#textarea-correo').html(content_respuesta);
                $('#btn-submit-correo').addClass('d-none');
            }

        }
    });
}

function showCotizacion(id) {
    $.ajax({
        url: '/cotizaciones/show/'+id,
        type: 'get',
        success: function (data) {
            
            $('#modal-responder-cotizacion').modal('show');
            $('#modal-title-cotizacion').html('Cotizacion #'+data.cotizacion.num_cotizacion);
            
            var content = `
                <div class="card-body">

                    <input type="hidden" name="id" value="${data.cotizacion.id}" />
                    
                    <div class="form-group row">
                        <div class="col-sm-6 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Fechas<hr class="m-0"></h5>
                                
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Fecha Inicio</label>
                                    <input class="form-control" type="text" name="fecha_ida" value="${data.cotizacion.fecha_ida}" required />
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Fecha Final</label>
                                    <input class="form-control" type="text" name="fecha_regreso" value="${data.cotizacion.fecha_regreso}" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Servicio<hr class="m-0"></h5>
                                
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Tipo Servicio</label>
                                    <input class="form-control" type="text" name="tipo_servicio" value="${data.cotizacion.tipo_servicio}" required />
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-12 col-form-label">Tipo Vehiculo</label>
                                    <input class="form-control" type="text" name="tipo_vehiculo" value="${data.cotizacion.tipo_vehiculo}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Origen<hr class="m-0"></h5>
                                
                                <div class="col-sm-6">
                                    <label class="col-form-label">Departamento</label>
                                    <input class="form-control" type="text" name="departamento_origen" value="${data.cotizacion.departamento_origen}" required />
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Municipio</label>
                                    <input class="form-control" type="text" name="ciudad_origen" value="${data.cotizacion.ciudad_origen}" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Destino<hr class="m-0"></h5>
                                
                                <div class="col-sm-6">
                                    <label class="col-form-label">Departamento</label>
                                    <input class="form-control" type="text" name="departamento_destino" value="${data.cotizacion.departamento_destino}" required />
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Municipio</label>
                                    <input class="form-control" type="text" name="ciudad_destino" value="${data.cotizacion.ciudad_destino}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 d-flex">
                            <div class="form-group row col-sm-12">
                                <h5 class="col-form-label col-sm-12">Descripción del Trayecto<hr class="m-0"></h5>
                                
                                <div class="col-sm-12">
                                    <textarea class="form-control" type="text" name="descripcion" placeholder="Describa los municipios intermedios entre el origen y el destino." required ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 d-flex">
                            <div class="form-group row col-sm-12">
                                <h5 class="col-form-label col-sm-12">Observaciones del Trayecto<hr class="m-0"></h5>
                                
                                <div class="col-sm-12">
                                    <textarea class="form-control" type="text" name="observaciones" placeholder="Ejemplo 'El recorrido inicia en la calle 0 No 0-00 a las 05:00AM.' " ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Incluye<hr class="m-0"></h5>
                                
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Conbustible</label>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="combustible" name="combustible" value="Si" class="custom-control-input">
                                        <label class="custom-control-label" for="combustible">Si</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="combustible2" name="combustible" value="No" class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="combustible2">No</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Conductor</label>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="conductor" name="conductor" value="Si" class="custom-control-input">
                                        <label class="custom-control-label" for="conductor">Si</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="conductor2" name="conductor" value="No" class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="conductor2">No</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-12 col-form-label">Peajes</label>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="peajes" name="peajes" value="Si" class="custom-control-input">
                                        <label class="custom-control-label" for="peajes">Si</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="peajes2" name="peajes" value="No" class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="peajes2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Cotización por<hr class="m-0"></h5>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="cotizacion_por" name="cotizacion_por" value="Dias" class="custom-control-input" required/>
                                    <label class="custom-control-label" for="cotizacion_por">Dia(s)</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="cotizacion_por2" name="cotizacion_por" value="Trayecto" class="custom-control-input" required/>
                                    <label class="custom-control-label" for="cotizacion_por2">Trayecto(s)</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="cotizacion_por3" name="cotizacion_por" value="Mensual" class="custom-control-input" required/>
                                    <label class="custom-control-label" for="cotizacion_por3">Mensual</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex">
                            <div class="form-group row">
                                <h5 class="col-sm-12 col-form-label">Trayecto(s)<hr class="m-0"></h5>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="recorrido" name="recorrido"  value="Solo ida" class="custom-control-input" ${(data.cotizacion.recorrido == 'Solo ida') ? 'checked=""' : ''}/>
                                    <label class="custom-control-label" for="recorrido">Solo ida</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="recorrido2" name="recorrido"  value="Ida y vuelta" class="custom-control-input" ${(data.cotizacion.recorrido == 'Ida y vuelta') ? 'checked=""' : ''}/>
                                    <label class="custom-control-label" for="recorrido2">Ida y vuelta</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 d-flex">
                            <div class="form-group row col-sm-12">
                                <h5 class="col-form-label col-sm-12">Costos<hr class="m-0"></h5>
                                
                                <div class="col-sm-4">
                                    <label class="col-form-label">Valor Unitario</label>
                                    <input class="form-control" type="number" name="valor_unitario" id="valor_unitario" onchange="total_cotizacion()" placeholder="Escriba el valor unitario" required />
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">Cantidad</label>
                                    <input class="form-control" type="number" name="cantidad" id="cantidad" onchange="total_cotizacion()" placeholder="Escriba la cantidad" required />
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">Total</label>
                                    <input class="form-control" type="number" name="total" id="total" value="0" required disabled/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 d-flex">
                            <div class="form-group row col-sm-12">
                                <h5 class="col-form-label col-sm-12">Trayecto 2<hr class="m-0"></h5>
                                
                                <div class="col-sm-12">
                                    <textarea class="form-control" type="number" name="trayecto_dos" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            `;

            $('#modal-content-cotizacion').html(content);

            if (data.cotizacion.responsable_id) {
                var content_respuesta = `
                    <h4 class="header-title mb-4">Respuesta: ${data.user.name} - ${data.cotizacion.fecha_respuesta}</h4>

                    ${data.cotizacion.respuesta}
                `;
                $('#textarea-cotizacion').html(content_respuesta);
                $('#btn-submit-cotizacion').addClass('d-none');
            }

        }
    });
}

function selectTipo(tipo) {
    if (tipo == 'general') {
        $('.divPermisos').removeClass('d-none')
    } else {
        $('.divPermisos').addClass('d-none')
    }
}

function registrarIngreso(id, name) {
    $('#registrarIngreso-title').html('Registrar ingreso de ' + name)
    $('#control_ingreso_id').val(id)

    $('#registrarIngreso').modal('show')
}

$('#identificacion').blur(function () {
    var id = $('#identificacion').val()
    $.ajax({
        url: '/control_ingreso/create/search/'+id,
        type: 'get',
        success: function (data) {
            if (data) {
                registrarIngreso(data.id, data.name)
                $('#identificacion').val('')
                $('#crearFuncionario').modal('hide')
            }
        }
    });
    return false;
})

function total_cotizacion() {
    let valor = $('#valor_unitario').val()
    let cantidad = $('#cantidad').val()

    $('#total').val(valor*cantidad)
}

function historialIngresos(id, name) {
    $.ajax({
        url: '/control_ingreso/historial/'+id,
        type: 'get',
        success: function (data) {
            // console.log(data.historial)
            $('#historialIngresos').modal('show');
            $('#modal-historial-title').html('Historial de ingresos de '+name);
            
            var content = `
                <table class="table table-centered table-hover table-bordered mb-0 mt-0">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Temperatura</th>
                            <th scope="col">Sintomas</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Fiebre</th>
                            <th scope="col">Tos</th>
                            <th scope="col">Gripa</th>
                            <th scope="col">Malestar</th>
                            <th scope="col">Dolor</th>
                            <th><i class="mdi mdi-settings"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                `
            data.historial.forEach(ingreso => {
                content += `
                    <tr>
                        <td>${ingreso.fecha}</td>
                        <td>${ingreso.temperatura}</td>
                        <td>${ingreso.pregunta_one}</td>
                        <td>${ingreso.pregunta_three}</td>
                        <td>${ingreso.fiebre}</td>
                        <td>${ingreso.tos}</td>
                        <td>${ingreso.gripa}</td>
                        <td>${ingreso.malestar}</td>
                        <td>${ingreso.dolor_cabeza}</td>
                        <td>
                            <a href="/control_ingreso/print/${ingreso.control_ingreso_id}/${ingreso.fecha}" target="_blank"><button type="button" class="btn btn-outline-primary btn-sm <?php echo $hoy > $ultimoIngreso ? 'disabled' : '' ?>"><i class="mdi mdi-file"></i></button></a>
                        </td>
                    </tr>
                `
            });

            content += `
                    </tbody>
                </table>
            `;

            $('#modal-historial-body').html(content);

        }
    });
}