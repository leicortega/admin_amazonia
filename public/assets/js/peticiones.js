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
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck22" name="permisos[]" value="entradas" ${data.permisos.includes('entradas') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck22">Entradas</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck33" name="permisos[]" value="salidas" ${data.permisos.includes('salidas') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck33">Salidas</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck44" name="permisos[]" value="productos" ${data.permisos.includes('productos') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck44">Productos</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="custominlineCheck55" name="permisos[]" value="proveedores" ${data.permisos.includes('proveedores') ? 'checked=""' : ''}>
                                <label class="custom-control-label" for="custominlineCheck55">Proveedores</label>
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

function selectTipo(tipo) {
    if (tipo == 'general') {
        $('.divPermisos').removeClass('d-none')
    } else {
        $('.divPermisos').addClass('d-none')
    }
}