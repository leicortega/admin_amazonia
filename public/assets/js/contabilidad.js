function ver_anexo(anexo) {
    $('#content_modal_anexo').html(`
        <img src="storage/${anexo}" alt="Anexo">
    `);

    $('#modal_ver_anexo').modal('show');
}
