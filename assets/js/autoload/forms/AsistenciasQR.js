var states = "";
var COLOR_DL_PENDIENTE = '#fff3cd';
var COLOR_FALTA = '#f4b183';
var qrScanner = null;
var qrScannerActivo = false;
var ultimoQrLeido = null;
var qrBadgeTimeout = null;
var ubicacionGlobal = null;
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }

});

function obtenerUbicacionActual() {
    return new Promise(function (resolve, reject) {
        if (!navigator.geolocation) {
            reject(new Error("Tu dispositivo no soporta geolocalizacion."));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            },
            function () {
                reject(new Error("No fue posible obtener la ubicacion del dispositivo."));
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
}

function mostrarLectorQr() {
    $("#qr_scanner_wrapper").show();
    $("#qr_scanner_status").text("Escanea el QR del colaborador.");
    iniciarLectorQr();
}

function ocultarLectorQr() {
    $("#qr_scanner_wrapper").hide();
    $("#qr_scanner_status").text("Valida la sede para habilitar el lector.");
    $("#colaborador_qr_id").val("");
    ocultarBadgeColaboradorQr();
    detenerLectorQr();
}

function mostrarBadgeColaboradorQr(data, type) {
    if (qrBadgeTimeout) {
        clearTimeout(qrBadgeTimeout);
    }

    var badge = $("#qr_colaborador_badge");
    badge.removeClass("error");
    if (type === "error") {
        badge.addClass("error");
    }

    $("#qr_colaborador_badge_nombre").text(data.nombre || "Colaborador detectado");
    $("#qr_colaborador_badge_codigo").text("Codigo: " + (data.codigo || "-"));
    $("#qr_colaborador_badge_id").text("ID: " + (data.colaborador_id || "-"));
    badge.fadeIn(200);

    qrBadgeTimeout = setTimeout(function () {
        ocultarBadgeColaboradorQr();
    }, 10000);
}

function ocultarBadgeColaboradorQr() {
    if (qrBadgeTimeout) {
        clearTimeout(qrBadgeTimeout);
        qrBadgeTimeout = null;
    }

    $("#qr_colaborador_badge").hide();
    $("#qr_colaborador_badge").removeClass("error");
    $("#qr_colaborador_badge_nombre").text("");
    $("#qr_colaborador_badge_codigo").text("");
    $("#qr_colaborador_badge_id").text("");
}

function iniciarLectorQr() {
    if (qrScannerActivo || typeof Html5Qrcode === "undefined") {
        return;
    }

    qrScanner = new Html5Qrcode("qr-reader");
    qrScanner.start({
            facingMode: "environment"
        }, {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        function (decodedText) {
            procesarQrEscaneado(decodedText);
        },
        function () {}
    ).then(function () {
        qrScannerActivo = true;
    }).catch(function () {
        $("#qr_scanner_status").text("No fue posible iniciar la camara.");
    });
}

function detenerLectorQr() {
    if (!qrScanner || !qrScannerActivo) {
        qrScanner = null;
        qrScannerActivo = false;
        return;
    }

    qrScanner.stop().then(function () {
        qrScanner.clear();
        qrScanner = null;
        qrScannerActivo = false;
    }).catch(function () {
        qrScanner = null;
        qrScannerActivo = false;
    });
}

function procesarQrEscaneado(token) {
    if (!token || token === ultimoQrLeido) {
        return;
    }
    ultimoQrLeido = token;
    $("#qr_scanner_status").text("Validando QR...");
    $.ajax({
        url: "/Asistencias/decode_qr_colaborador",
        type: "POST",
        data: {
            token: token,
            lat: ubicacionGlobal.lat,
            lng: ubicacionGlobal.lng,
            sede_id: $("#select_sede").val()
        },
        dataType: "json",
        success: function (data) {
            if (data && data.status) {
                $("#colaborador_qr_id").val(data.colaborador_id);
                $("#qr_scanner_status").text("Colaborador detectado con ID " + data.colaborador_id + ".");
                mostrarBadgeColaboradorQr(data);
            } else {
                $("#colaborador_qr_id").val("");
                $("#qr_scanner_status").text(data.mensaje || "No fue posible leer el QR.");
                mostrarBadgeColaboradorQr({
                    nombre: "QR invalido",
                    codigo: data.mensaje || "No fue posible leer el QR.",
                    colaborador_id: "-"
                }, "error");
            }

            setTimeout(function () {
                ultimoQrLeido = null;
            }, 2000);
        },
        error: function () {
            $("#colaborador_qr_id").val("");
            $("#qr_scanner_status").text("No fue posible validar el QR.");
            mostrarBadgeColaboradorQr({
                nombre: "Error de lectura",
                codigo: "No fue posible validar el QR.",
                colaborador_id: "-"
            }, "error");
            setTimeout(function () {
                ultimoQrLeido = null;
            }, 2000);
        }
    });
}

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
    ocultarLectorQr();
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
$("#select_sede").change(async function () {
    var sedeId = $("#select_sede").val();
    if (!sedeId || sedeId === "Seleccionar sede") {
        ocultarLectorQr();
        return;
    }

    try {
        swal({
            title: "Obteniendo ubicacion",
            showConfirmButton: false,
            imageUrl: "/assets/images/loader.gif"
        });

        var ubicacion = await obtenerUbicacionActual();
        ubicacionGlobal = ubicacion;

        $.ajax({
            url: "/Geocerca/validar_posicion",
            type: 'POST',
            data: {
                sede_id: sedeId,
                lat: ubicacion.lat,
                lng: ubicacion.lng
            },
            dataType: 'json',
            success: function (data) {
                if (data && data.status) {
                    swal(data.en_rango ? "En rango" : "Fuera de rango", data.mensaje, data.en_rango ? "success" : "warning");
                    if (data.en_rango) {
                        mostrarLectorQr();
                    } else {
                        ocultarLectorQr();
                    }
                } else {
                    ocultarLectorQr();
                    swal("Error", data.mensaje || "No fue posible validar la ubicacion.", "error");
                }
            },
            error: function () {
                ocultarLectorQr();
                swal.close();
                swal("Error", "No fue posible validar la ubicacion.", "error");
            }
        });
    } catch (error) {
        ocultarLectorQr();
        swal.close();
        swal("Ubicacion", error.message, "warning");
    }
});
$("#search").click(function () {
    grid_load_data();
})

function toggleColaboradorSelect($tipoSelect) {
    var selectedValue = String($tipoSelect.val() || '');
    var usarAsistencia = ['1', '7', '9'].indexOf(selectedValue) !== -1;
    var $row = $tipoSelect.closest('tr');
    var $todosWrapper = $row.find('.colaborador-select-todos').closest('.colaborador-select-wrapper');
    var $asistenciaWrapper = $row.find('.colaborador-select-asistencia').closest('.colaborador-select-wrapper');
    var $todosSelect = $row.find('.colaborador-select-todos');
    var $asistenciaSelect = $row.find('.colaborador-select-asistencia');
    var $heInput = $row.find('input[name^="he["]');

    if (usarAsistencia) {
        $todosWrapper.hide();
        $todosSelect.prop('disabled', true).val('');
        $asistenciaWrapper.show();
        $asistenciaSelect.prop('disabled', false);
    } else {
        $asistenciaWrapper.hide();
        $asistenciaSelect.prop('disabled', true).val('');
        $todosWrapper.show();
        $todosSelect.prop('disabled', false);
    }

    if (selectedValue == '7') {
        $heInput.prop('readonly', false);
    } else {
        $heInput.prop('readonly', true).val('');
    }

    syncFaltaRow($row);
}

function syncFaltaRow($row) {
    var esDL = String($row.find('select[name^="tipos["]').val() || '') === '9';
    var esFalta = getTipoAsistenciaPrefijo($row) === 'F';
    $row.children('td').each(function () {
        this.style.removeProperty('background-color');
        if (esDL) {
            this.style.setProperty('background-color', COLOR_DL_PENDIENTE, 'important');
        } else if (esFalta) {
            this.style.setProperty('background-color', COLOR_FALTA, 'important');
        }
    });
}

function getTipoAsistenciaPrefijo($row) {
    var texto = $.trim($row.find('select[name^="tipos["] option:selected').text() || '');
    return texto.split('-')[0].trim();
}

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
            $('select[name^="tipos["]').each(function () {
                toggleColaboradorSelect($(this));
            });
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
function update_extra_horario(select) {
    var $select = $(select);
    $.ajax({
        url: "/Asistencias/update_extra_horario",
        type: 'POST',
        data: {
            id: $select.data('id'),
            horario_id: $select.val()
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
            if (!data || data.status !== 'ok') {
                swal('Error!', (data && data.mensaje) ? data.mensaje : 'No fue posible actualizar el horario.', 'error');
            }
            grid_load_data();
        },
        error: function () {
            swal.close();
            swal('Error!', 'No fue posible actualizar el horario.', 'error');
            grid_load_data();
        }
    });
}
function confirmar_extra_dl(checkbox) {
    var $checkbox = $(checkbox);
    $.ajax({
        url: "/Asistencias/confirmar_extra_dl",
        type: 'POST',
        data: {
            id: $checkbox.data('id'),
            confirmado: $checkbox.is(':checked') ? 1 : 0
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
            if (!data || !data.status) {
                swal('Error!', (data && data.mensaje) ? data.mensaje : 'No fue posible confirmar el extra.', 'error');
            }
            grid_load_data();
        },
        error: function () {
            swal.close();
            swal('Error!', 'No fue posible confirmar el extra.', 'error');
            grid_load_data();
        }
    });
}
$(document).on('change', 'select[name^="tipos["]', function () {
    toggleColaboradorSelect($(this));
});
$(document).on('change', '.extra-horario-select', function () {
    update_extra_horario(this);
});
$(document).on('change', '.confirmar-extra-dl-toggle', function () {
    confirmar_extra_dl(this);
});
