var titulo;
var mensaje;
$(document).ready(function () {
   

    $(".input-file-icon").change(function () {
        var t = $(this).val();
        var labelText = 'Img: ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    });

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#icon-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".input-file-icon").change(function () {

    readURL(this);
    $("#icon-preview").show();
});


function enviar() {
    titulo = $('#titulo').val();
    mensaje = $('#mensaje').val();

    $('#rtitulo').text(titulo);
    $('#rmensaje').text(mensaje);
    //final();
}

function final() {
    var data = new FormData(document.getElementById("notificacion_info"));
    $.ajax({
        url: '/notificaciones/enviar_notificacion',
        data: data,
        type: 'POST',
        processData: false,
        contentType: false,
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            $("#exampleModalCenter").modal("hide");
            $('#titulo').val("");
            $('#mensaje').val("");
            location.reload();
            swal.close();
        }
    });
    //location.reload();
}