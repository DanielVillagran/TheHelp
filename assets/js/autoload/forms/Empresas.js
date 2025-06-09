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
        url: "/Empresas/get_info_Empresas",
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
            grid_load_sedes();
            grid_load_horarios();
            grid_load_puestos();
            grid_load_asistencias();
        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Empresas/save_info",
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

            location.href = "/Empresas";


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
    $("#modalSede").modal("show");
});
function save_sede() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_sede"));
    var nombre = data.get("sede[nombre]");

    if (nombre != "") {
        $.ajax({
            type: "post",
            url: "/Empresas/save_sede",
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
                    const form = document.getElementById("modal_sede");
                    form.reset();
                    swal.close();
                    $("#modalSede").modal("hide");
                    grid_load_sedes();
                } else {
                    swal('Error!', "Ya existe una sede con ese nombre.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function grid_load_sedes() {
    $.ajax({
        url: "/Empresas/get_Empresas_sedes",
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
            $("#select_sede").empty().append(data.select);
            let id = "#sedes_grid";
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

$("#btn_add_new_horario").click(function () {
    $("#modalHorario").modal("show");
});
function save_horario() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_horario"));
    var nombre = data.get("horario[nombre]");

    if (nombre != "") {
        $.ajax({
            type: "post",
            url: "/Empresas/save_horario",
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
                    const form = document.getElementById("modal_horario");
                    form.reset();
                    swal.close();
                    $("#modalHorario").modal("hide");
                    grid_load_horarios();
                } else {
                    swal('Error!', "Ya existe un horario con ese nombre.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function grid_load_horarios() {
    $.ajax({
        url: "/Empresas/get_Empresas_horarios",
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
            $("#select_horario").empty().append(data.select);
            let id = "#horarios_grid";
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

$("#btn_add_new_puesto").click(function () {
    $("#modalPuesto").modal("show");
});
function save_puesto() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_puesto"));
    var sede = data.get("puesto[sede_id]");
    var horario = data.get("puesto[horario_id]");
    var puesto = data.get("puesto[puesto_id]");
    var cantidad = data.get("puesto[cantidad]");
    if (
        sede && sede.trim() !== "" &&
        horario && horario.trim() !== "" &&
        puesto && puesto.trim() !== "" &&
        cantidad && cantidad.trim() !== ""
    ) {
        $.ajax({
            type: "post",
            url: "/Empresas/save_puesto",
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
                    const form = document.getElementById("modal_puesto");
                    form.reset();
                    swal.close();
                    $("#modalPuesto").modal("hide");
                    grid_load_puestos();
                } else {
                    swal('Error!', "Ya existe un horario con ese nombre.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function grid_load_puestos() {
    $.ajax({
        url: "/Empresas/get_Empresas_puestos",
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
            let id = "#puestos_grid";
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
function grid_load_asistencias() {
    $.ajax({
        url: "/Empresas/get_Empresas_asistencias",
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
            let id = "#asistencias_grid";
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
$('#asistencias_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    window.location.href = "/asistencias/view/" + idemp;
});