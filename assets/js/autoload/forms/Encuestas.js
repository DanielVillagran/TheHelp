var states = "";
var id_global = 0;
$(document).ready(function () {
    if ($("#id").val() != 0) {
        id_global = $("#id").val();
        get_info_Departamentos($("#id").val());
    }

});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Encuestas/get_info_Encuestas",
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
            grid_load_preguntas();


        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Encuestas/save_info",
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

            location.href = "/Encuestas";


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
$("#btn_add_new").click(function () {
    $("#modalPregunta").modal("show");
});
function save_pregunta() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_pregunta"));
    var nombre = data.get("pregunta[pregunta]")
    var tipo = data.get("pregunta[tipo]");

    if (nombre != "" && tipo != "") {
        $.ajax({
            type: "post",
            url: "/Encuestas/save_pregunta",
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
                if (data) {
                    const form = document.getElementById("modal_pregunta");
                    form.reset();
                    swal.close();
                    $("#modalPregunta").modal("hide");
                    grid_load_preguntas();
                } else {
                    swal('Error!', "Ya existe una pregunta con ese nombre.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function grid_load_preguntas() {
    $.ajax({
        url: "/Encuestas/get_Encuestas_preguntas",
        type: 'POST',
        data: {
            id: id_global
        },
        dataType: 'json',
        beforeSend: function (e) {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {
            swal.close();
            let id = "#preguntas_grid";
            if ($.fn.DataTable.isDataTable(id)) {
                $(id).DataTable().destroy();
            }
            $(id + ' thead').empty().append(data.head);
            $(id + ' tbody').empty().append(data.table);
            $(id).show();
            inicializarDatatable(id);
        }
    });
}