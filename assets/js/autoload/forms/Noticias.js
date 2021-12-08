$("#icon-preview").hide();

var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Noticias($("#id").val());
        get_service_list();
    }

    $(".input-file-icon").change(function () {
        var t = $(this).val();
        var labelText = 'Img: ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    });

    $(".btn-nuevo-servicio").click(function () {
        $("#modalNuevoServicio").modal("show");
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


$(".input-contacto-principal").focusin(function () {
    if ($(".input-contacto-principal").val() == "") {
        $(".floating-label-no-required").addClass("floating-label-active");
    }
});

$(".input-contacto-principal").focusout(function () {
    if ($(".input-contacto-principal").val() == "") {
        $(".floating-label-no-required").removeClass("floating-label-active");
    }
});

function get_info_Noticias(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Noticias/get_info_Noticias",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {
            swal.close();
            for (var key in data) {
                if (key != 'logo') {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
            if (data.logo != null) {
                $(".current_logo").html('<img src="/assets/' + data.logo + '"/>');
            }

        }
    });
}
function save_Noticias() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Noticias_info"));
    $.ajax({
        type: "post",
        url: "/Noticias/save_info",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {

            swal.close();
            if ($("#id").val()!=0) {
                location.href="/Noticias";
            }else{
                location.href="/Noticias/edit/"+data.insert_id;
            }
           
        }
    });
}
function limpiar_form_modal(){
            $("#nombre").val("");
            $("#descripcion").val("");
            $("#pagos").prop('checked', false);
            $("#citas").prop('checked', false);
            $("#id_servicio").val(0);
            $("#eliminar_s").hide();

}
function agregar_servicio() {
    var data = {
        id: $("#id").val(),
        'nombre': $("#nombre").val(),
        'descripcion': $("#descripcion").val(),
        'pagos': $("#pagos").prop("checked"),
        'citas': $("#citas").prop("checked"),
        'id_servicio': $("#id_servicio").val()
    };
    $.ajax({
        type: "post",
        url: "/Noticias/save_servicio",
        data: data,
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {

            swal.close();
            limpiar_form_modal();
            $("#modalNuevoServicio").modal("hide");
            get_service_list();
            //location.href="/Noticias";
        }
    });

}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}
