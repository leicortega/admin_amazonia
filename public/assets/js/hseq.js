function ver_documento(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://docs.google.com/document/d/${path}/edit" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}

function ver_imagen(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://drive.google.com/file/d/${path}/preview?usp=drivesdk" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}

function ver_pdf(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://drive.google.com/file/d/${path}/preview?usp=drivesdk" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}

function ver_excel(path) {
    $('#modal_ver_documento').modal('show');
    $('#content_modal_ver_documento').html(`
        <iframe style="width: 100%;" src="https://docs.google.com/spreadsheets/d/${path}" width="100%" height="720" frameborder="0" allowfullscreen target="_parent" scrolling="no"></iframe>
    `);
}

function descargar(file, path, mimetype) {
    window.open('/hseq/descargar?file='+file+'&path='+path+'&mimetype='+mimetype, '_blank');
}

function eliminar_archivo(path,basename) {
    var button=document.getElementById(("eliminar_archivo."+basename));
    if (confirm('¿Seguro desea eliminar el archivo?')) {
        button.disabled = true;
        $.ajax({
            url: '/hseq/eliminar_archivo?path='+path,
            type: 'GET',
            success: function (data) {
                if (data) {
                    //recargar pagina
                    // alert('Archvio eliminado correctamente');
                    location.reload();
                } else {
                    //mostrar error al guardar datos
                    confirm('Error al eliminar el archvio');
                    button.disabled=false;
                }
            }

        });

    }
}

function eliminar_carpeta(path,basename) {
    var button=document.getElementById(("eliminar_carpeta."+basename));
    if (confirm('¿Seguro desea eliminar la carpeta?')) {
        button.disabled = true;
        $.ajax({
            url: '/hseq/eliminar_carpeta?path='+path,
            type: 'GET',
            success: function (data) {
                if (data) {
                    //recargar pagina
                    // alert('Carpeta eliminada correctamente');
                    location.reload();
                } else {
                    //mostrar error al guardar datos
                    confirm('Error al eliminar el archvio');
                    button.disabled=false;
                }

            }
        });

    }
    console.log(button.disabled);
}


$("#button_create_carpeta").click(function(event){
    var button=document.getElementById("button_create_carpeta");
    var formData = new FormData(document.getElementById("form_create_carpeta"));
    button.disabled = true;
    $.ajax({
        url: '/hseq/create-dir',
        type: 'POST',
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data) {
                //recargar pagina
                // alert('Carpeta creada correctamente');
                location.reload();
            } else {
                //mostrar error al guardar datos
                alert('Error al crear la carpeta');
                button.disabled=false;
            }

        }
    });
});

$("#button_create_archivo").click(function(event){
    var button=document.getElementById("button_create_archivo");
    var formData = new FormData(document.getElementById("form_subir_archivo"));
    button.disabled = true;
    $.ajax({
        url: '/hseq/subir_archivo',
        type: 'POST',
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data) {
                //recargar pagina
                // alert('Archivo subido correctamente');
                location.reload();
            } else {
                //mostrar error al guardar datos
                alert('Error al crear la carpeta');
                button.disabled=false;
            }

        }
    });
});
