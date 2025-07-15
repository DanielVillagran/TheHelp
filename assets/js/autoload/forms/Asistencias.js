var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }

});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Asistencias/get_info_Asistencias",
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


        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Asistencias/save_info",
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

            location.href = "/Asistencias";


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
$("#empresa_select").change(function () {
    $.ajax({
        url: "/Empresas/get_Empresas_sedes",
        type: 'POST',
        data: {
            id: $("#empresa_select").val()
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

        }
    });
});
$("#select_sede").change(function () {
    $.ajax({
        url: "/Empresas/get_Sedes_horarios",
        type: 'POST',
        data: {
            id: $("#select_sede").val()
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
            $("#select_horarios").empty().append(data.select);

        }
    });
});
$("#search").click(function () {
    grid_load_data();
})
function grid_load_data() {
    $.ajax({
        url: "/Asistencias/get_asistencias_by_empresa",
        type: 'POST',
        data: {
            empresa_id: $("#empresa_select").val(),
            horario_id: $("#select_horarios").val(),
            sede_id: $("#select_sede").val(),
            fecha: $("#fecha").val()
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
            $('#asistencias_grid thead').empty().append(data.head);
            $('#asistencias_grid tbody').empty().append(data.table).trigger('footable_redraw');
            $('#asistencias_grid').show();
            $("#addExtra").show();
            $("#select_horario").empty().append(data.select_horarios);
            $("#select_colaboradores").empty().append(data.select_colaboradores);
            swal.close();
            //$("#select_sede").empty().append(data.select);

        }
    });
}
$('#form-asistencias').on('submit', function (e) {
    e.preventDefault();
    const data = $(this).serialize();
    $.ajax({
        url: "/Asistencias/save_puestos_cubiertos",
        type: 'POST',
        data: data,
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
            //$("#select_sede").empty().append(data.select);

        }
    });
});
$("#btn_add_new_extra").click(function () {
    $("#modalExtra").modal("show");
});
function save_extra() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_extra"));
    var colaborador_id = data.get("puesto[colaborador_id]");
    var horario = data.get("puesto[horario_id]");
    var puesto = data.get("puesto[puesto_id]");
    data.append("puesto[empresa_id]", $("#empresa_select").val());
    data.append("puesto[sede_id]", $("#select_sede").val());
    data.append("puesto[fecha]", $("#fecha").val());

    if (
        colaborador_id && colaborador_id.trim() !== "" &&
        horario && horario.trim() !== "" &&
        puesto && puesto.trim() !== ""
    ) {
        $.ajax({
            type: "post",
            url: "/Asistencias/save_extra",
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
                    const form = document.getElementById("modal_extra");
                    form.reset();
                    swal.close();
                    $("#modalExtra").modal("hide");
                    grid_load_data();
                } else {
                    swal('Error!', "Ya existe una asignación con la misma información.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function delete_extra(idemp) {
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
            url: "/Asistencias/eliminar_extra",
            type: 'POST',
            data: {
                'id': idemp
            },
            dataType: 'json',
            beforeSend: function (e) {

            },
            success: function (data) {
                //swal('Listo!',"El elemento ha sido eliminado con exito.",'success');
                grid_load_data();
            }
        });

    });
}
$(document).on('change', 'select[name^="tipos["]', function () {
    var selectedValue = $(this).val();
    var $row = $(this).closest('tr');
    var $heInput = $row.find('input[name^="he["]');

    if (selectedValue == '7') {
        $heInput.prop('readonly', false);
    } else {
        $heInput.prop('readonly', true).val('');
    }
});
