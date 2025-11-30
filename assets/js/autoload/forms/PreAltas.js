var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    } else {
        let hace5 = new Date();
        hace5.setDate(hace5.getDate() - 5);
        let hace5Str = hace5.toISOString().split("T")[0];
        $("input[name='users[fecha_alta]']").attr("min", hace5Str);
    }

});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Colaboradores/get_info",
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
            if (data.cliente) {
                $("#empresa_select").val(data.cliente);
                get_empresas_sedes(data.cliente, function () {
                    if (data.sede) {
                        $("#sede_select").val(data.sede);
                        get_puestos_by_sede(data.sede, function () {
                            if (data.puesto) {
                                $("#puesto_select").val(data.puesto);
                                get_horarios_by_puesto(data.sede, data.puesto, function () {
                                    if (data.horario_id) {
                                        $("#horario_select").val(data.horario_id);
                                        get_datos_sueldo(data.sede, data.puesto, data.horario_id);
                                    }
                                });
                            }
                        });
                    }
                });
            }
            for (var key in data) {
                if (key != 'logo') {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }

        }
    });
}
function validarFormulario() {
    const rfc = document.getElementById("rfc");
    const curp = document.getElementById("curp");
    const nss = document.getElementById("nss");

    const rfcRegex = /^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/;
    const curpRegex = /^[A-Z]{4}\d{6}[A-Z0-9]{8}$/;
    const nssRegex = /^\d{11}$/;

    let errores = [];
    let primerCampoInvalido = null;

    if (!rfcRegex.test(rfc.value)) {
        errores.push("• El RFC debe tener 13 caracteres: 4 letras, 6 números y 3 alfanuméricos.");
        if (!primerCampoInvalido) primerCampoInvalido = rfc;
    }

    if (!curpRegex.test(curp.value)) {
        errores.push("• El CURP debe tener 18 caracteres: 4 letras, 6 números y 8 alfanuméricos.");
        if (!primerCampoInvalido) primerCampoInvalido = curp;
    }

    if (!nssRegex.test(nss.value)) {
        errores.push("• El NSS debe tener exactamente 11 dígitos numéricos.");
        if (!primerCampoInvalido) primerCampoInvalido = nss;
    }

    if (errores.length > 0) {
        Swal.fire({
            title: "Errores en el formulario",
            html: errores.join("<br>"),
            icon: "error",
            confirmButtonText: "Aceptar"
        }).then(() => {
            primerCampoInvalido.focus();
        });
        return false;
    }

    return true;
}

function save_Departamentos() {
    event.preventDefault();
    if (validarFormulario()) {
        var data = new FormData(document.getElementById("Departamentos_info"));
        $.ajax({
            type: "post",
            url: "/PreAltas/save_info",
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

                location.href = "/PreAltas";


            }
        });
    }
}
$("#empresa_select").change(function () {
    get_empresas_sedes($("#empresa_select").val());
});

$("#sede_select").change(function () {
    get_puestos_by_sede($("#sede_select").val());
})

$("#puesto_select").change(function () {
    get_horarios_by_puesto($("#sede_select").val(), $("#puesto_select").val())

})

$("#horario_select").change(function () {
    get_datos_sueldo($("#sede_select").val(), $("#puesto_select").val(), $("#horario_select").val());
})

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}

function get_empresas_sedes(clienteId, callback) {
    $.ajax({
        type: "post",
        url: "/Empresas/get_Empresas_sedes",
        data: { id: clienteId },
        dataType: "json",
        success: function (data) {
            $("#sede_select").empty().append(data.select);
            if (callback) callback();
        }
    });
}

function get_puestos_by_sede(sedeId, callback) {
    $.ajax({
        type: "post",
        url: "/Empresas/get_puestos_by_sede",
        data: { id: sedeId },
        dataType: "json",
        success: function (data) {
            $("#puesto_select").empty().append(data.select);
            if (callback) callback();
        }
    });
}

function get_horarios_by_puesto(sedeId, puestoId, callback) {
    $.ajax({
        type: "post",
        url: "/Empresas/get_horarios_by_puesto",
        data: { sede_id: sedeId, id: puestoId },
        dataType: "json",
        success: function (data) {
            $("#horario_select").empty().append(data.select);
            if (callback) callback();
        }
    });
}

function get_datos_sueldo(sedeId, puestoId, horarioId) {
    $.ajax({
        type: "post",
        url: "/Empresas/get_datos_sueldo",
        data: { sede_id: sedeId, puesto_id: puestoId, horario_id: horarioId },
        dataType: "json",
        success: function (data) {
            $("#sd_input").val(data.select.salario_diario);
            $("#sueldo_input").val(data.select.sueldo_neto_semanal);
        }
    });
}