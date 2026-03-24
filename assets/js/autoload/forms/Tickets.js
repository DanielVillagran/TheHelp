var currentSedeId = "";

$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }
});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Tickets/get_info_Tickets",
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
            var empresaId = $.trim(data.empresa || data.empresa_id || data.empresaId || "");
            currentSedeId = $.trim(data.sede || data.sede_id || data.sedeId || "");
            for (var key in data) {
                if (key != 'evidencia' && key != 'empresa' && key != 'empresa_id' && key != 'empresaId' && key != 'sede' && key != 'sede_id' && key != 'sedeId') {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
            if (data.descripcion && !$('[name="users[descripcion]"]').val()) {
                $('[name="users[descripcion]"]').val($.trim(data.descripcion));
            }
            var tipoServicioId = $.trim(data.tipoServicioId || "");
            if (tipoServicioId !== "") {
                $("#tipoServicioId").val(tipoServicioId);
            }
            if (empresaId !== "") {
                $("#empresa_select").val(empresaId);
                loadSedesByEmpresa(empresaId, currentSedeId);
            }
        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Tickets/save_info",
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

            location.href = "/Tickets";
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
    if ($("#empresa_select").prop("disabled")) {
        return;
    }
    var empresaId = $(this).val();
    currentSedeId = "";
    loadSedesByEmpresa(empresaId, "");
});

function loadSedesByEmpresa(empresaId, selectedSedeId) {
    if (!empresaId) {
        $("#select_sede").empty().append('<option hidden>Seleccionar sede</option>');
        return;
    }

    $.ajax({
        url: "/Empresas/get_Empresas_sedes",
        type: 'POST',
        data: {
            id: empresaId
        },
        dataType: 'json',
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {
            swal.close();
            $("#select_sede").empty().append(data.select);
            if (selectedSedeId) {
                $("#select_sede").val(selectedSedeId);
            }
        }
    });
}
