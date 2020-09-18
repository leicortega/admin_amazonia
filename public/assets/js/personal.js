$(document).ready(function () {
    $('#form_cargos_personal').submit(function () {
        $.ajax({
            url: '/personal/agg_cargo_personal',
            type: 'POST',
            data: $('#form_cargos_personal').serialize(),
            success: function (data) {
                if (data.create === 1) {
                    cargar_cargos_personal(data.personal_id)
                }
            }
        })

        return false;
    })
})

function showPersonal(id) {
    $.ajax({
        url: '/personal/registro/ver/'+id,
        type: 'get',
        success: function (data) {
            $('#modal-title-personal').text('Datos '+data.nombres)
            $('#verPersonal').modal('show')

            $('#tipo_identificacion_update').val(data.tipo_identificacion)
            $('#identificacion_update').val(data.identificacion)
            $('#nombres_update').val(data.nombres)
            $('#primer_apellido_update').val(data.primer_apellido)
            $('#segundo_apellido_update').val(data.segundo_apellido)
            $('#fecha_ingreso_update').val(data.fecha_ingreso)
            $('#direccion_update').val(data.direccion)
            $('#rh_update').val(data.rh)
            $('#tipo_vinculacion_update').val(data.tipo_vinculacion)
            $('#correo_update').val(data.correo)
            $('#telefonos_update').val(data.telefonos)

            $('#personal_id').val(data.id)

            switch (data.sexo) {
                case 'Hombre':
                    $('#custominlineRadio6').attr('checked','checked')
                    break;

                case 'Mujer':
                    $('#custominlineRadio7').attr('checked','checked')
                    break;

                case 'Otro/s':
                    $('#custominlineRadio8').attr('checked','checked')
                    break;
                default:
                    break;
            }

            switch (data.estado) {
                case 'Activo':
                    $('#custominlineRadio9').attr('checked','checked')
                    break;

                case 'Inactivo':
                    $('#custominlineRadio10').attr('checked','checked')
                    break;
                default:
                    break;
            }

            cargar_cargos_personal(data.id)

        }
    });
}

function cargar_cargos_personal(id) {
    $.ajax({
        url: '/personal/cargar_cargos_personal',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $content = `
                <table class="table table-bordered mt-3 mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            `
            data.forEach(cargo => {
                $content += `
                    <tr>
                        <th scope="row">${cargo.nombre}</th>
                        <td><button type="button" class="btn btn-danger" onclick="eliminar_cargo_personal(${cargo.cargos_id}, ${cargo.personal_id})"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `
            });

            $content += `
                    </tbody>
                </table>
            `

            $('#cargos_personal_content').html($content)
        }
    })

    return false;
}