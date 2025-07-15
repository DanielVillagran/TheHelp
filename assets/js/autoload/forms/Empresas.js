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
            grid_load_encuestas();
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
    $('[name="puesto[id]"]').val(0);
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

function grid_load_encuestas() {
    $.ajax({
        url: "/Empresas/get_Empresas_encuestas",
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
            let id = "#encuestas_grid";
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
$('#encuestas_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    window.location.href = "/encuestas/respuestaView/" + idemp;
});
$('#puestos_grid').footable().on('click', '.row-edit', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    $.ajax({
        url: "/Empresas/get_Empresas_puesto_id",
        type: 'POST',
        data: {
            id: idemp
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
            for (var key in data) {
                if (key != 'logo') {
                    $('[name="puesto[' + key + ']"]').val($.trim(data[key]));
                }
            }
            $("#modalPuesto").modal("show");
        }
    });
});
$('#puestos_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }, function () {

        $.ajax({
            url: "/Empresas/eliminar_Empresas_puesto_id",
            type: 'POST',
            data: {
                'id': idemp
            },
            dataType: 'json',
            beforeSend: function (e) {

            },
            success: function (data) {
                grid_load_puestos();
            }
        });

    });
});
$('#horarios_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }, function () {

        $.ajax({
            url: "/Empresas/eliminar_Empresas_horario_id",
            type: 'POST',
            data: {
                'id': idemp
            },
            dataType: 'json',
            beforeSend: function (e) {

            },
            success: function (data) {
                grid_load_horarios();
            }
        });

    });
});
$('#sedes_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }, function () {

        $.ajax({
            url: "/Empresas/eliminar_Empresas_sede_id",
            type: 'POST',
            data: {
                'id': idemp
            },
            dataType: 'json',
            beforeSend: function (e) {

            },
            success: function (data) {
                grid_load_sedes();
            }
        });

    });
});
function actualizarCostos() {
    const valor = parseFloat($("#costo_unitario").val());
    if (!isNaN(valor)) {
        const costo_por_dia = parseFloat((valor / 30.4).toFixed(2));
        $('[name="puesto[costo_por_dia]"]').val(costo_por_dia);
        const costo_descanso = parseFloat((costo_por_dia * 2).toFixed(2));
        $('[name="puesto[costo_descanso_laborado]"]').val(costo_descanso);
        const costo_festivo = parseFloat((costo_por_dia * 3).toFixed(2));
        $('[name="puesto[costo_dia_festivo]"]').val(costo_festivo);
        const costo_extra = parseFloat((costo_por_dia / 4).toFixed(2));
        $('[name="puesto[costo_hora_extra]"]').val(costo_extra);
    }
}

let debounceTimer;
$("#costo_unitario").on('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(actualizarCostos, 2000);
});

$("#costo_unitario").change(actualizarCostos);
