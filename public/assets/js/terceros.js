$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    cargarDepartamentos();

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
