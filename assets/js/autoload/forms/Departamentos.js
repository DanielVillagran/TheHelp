$("#icon-preview").hide();

var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
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
function editar_servicio(id){
    $.ajax({
        type: "post",
        url: "/Departamentos/get_info_Service",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            $("#modalNuevoServicio").modal("toggle");
            $("#nombre").val(data.nombre);
            $("#descripcion").val(data.descripcion);
            $("#c_pago").val(data.c_pago);
            if(data.pagos=="1"){
                $("#pagos").prop('checked', 'checked');
            }
            if(data.citas=="1"){
                $("#citas").prop('checked', 'checked');
            }
            $("#id_servicio").val(id);
            $("#eliminar_s").show();


        }
    });
}
function eliminar_servicio() {
    $.ajax({
        type: "post",
        url: "/Departamentos/delete_service",
        data: {
            id: $("#id_servicio").val()
        },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            limpiar_form_modal();
            $("#modalNuevoServicio").modal("hide");
            get_service_list();


        }
    });
}

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Departamentos/get_info_Departamentos",
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

function get_service_list() {
    $.ajax({
        type: "post",
        url: "/Departamentos/get_service_list",
        data: {
            id: $("#id").val()
        },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            swal.close();
            $("#details_grid > tbody").empty().append(data.table);


        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Departamentos/save_info",
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
                location.href="/Departamentos";
            }else{
                location.href="/Departamentos/edit/"+data.insert_id;
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
        'c_pago': $("#c_pago").val(),
        'descripcion': $("#descripcion").val(),
        'pagos': $("#pagos").prop("checked"),
        'citas': $("#citas").prop("checked"),
        'id_servicio': $("#id_servicio").val()
    };
    $.ajax({
        type: "post",
        url: "/Departamentos/save_servicio",
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
            //location.href="/Departamentos";
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
