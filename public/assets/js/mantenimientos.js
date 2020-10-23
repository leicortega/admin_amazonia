function mostrar_imagen(url) {
    $('#modal_ver_documento_content').html(`<img src="/storage/${url}" class="img-fluid" alt="">`);
    $('#modal_ver_documento').modal('show');
}
