function ver_documento(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://docs.google.com/document/d/${path}/edit" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}

function ver_imagen(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://drive.google.com/file/d/0B3Hbt0U4MbEZcTNiMElYZUFzQ1k" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}
