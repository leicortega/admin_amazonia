$(window).ready(function () {
    $('input[type=radio][name=galeria]').change(function() {
        if (this.value == 'Si') {
            $('#input_galeria').removeClass('d-none');
        }
        else if (this.value == 'No') {
            $('#input_galeria').addClass('d-none');
        }
    });

    // $('#form_crear_post').submit(function () {
    //    $
    // });
})

