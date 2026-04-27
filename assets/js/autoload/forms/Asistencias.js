var states = "";
var asistenciasFinalizadas = false;
var COLOR_DL_PENDIENTE = '#fff3cd';
var COLOR_FALTA = '#f4b183';
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }

});

function setAsistenciasFinalizadas(finalizadas) {
    asistenciasFinalizadas = !!finalizadas;
    $('#acciones_asistencias').show();
    $('#btn_guardar_asistencias, #btn_guardar_finalizar_asistencias').toggle(!asistenciasFinalizadas);
    $('#asistencias_finalizadas_msg').toggle(asistenciasFinalizadas);
    $('#addExtra').toggle(!asistenciasFinalizadas && $('#asistencias_grid tbody tr').length > 0);
    if (asistenciasFinalizadas) {
        $('#asistencias_grid').find('select, input, button').prop('disabled', true);
    }
}

function limpiarAccionesAsistencias() {
    asistenciasFinalizadas = false;
    $('#acciones_asistencias').hide();
    $('#asistencias_finalizadas_msg').hide();
    $('#btn_guardar_asistencias, #btn_guardar_finalizar_asistencias').show();
    $('#addExtra').hide();
}

function puedeBuscarAsistencias() {
    return $("#fecha").val() &&
        $("#empresa_select").val() &&
        $("#empresa_select").val() !== "Seleccionar empresa" &&
        $("#select_sede").val() &&
        $("#select_sede").val() !== "Seleccionar sede";
}

function buscarAsistenciasSiCompleto() {
    if (puedeBuscarAsistencias()) {
        grid_load_data();
    }
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
            $("#select_horarios").empty().append('<option hidden>Seleccionar horario</option>');
            limpiarAccionesAsistencias();

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
            buscarAsistenciasSiCompleto();

        }
    });
});
$("#fecha").change(function () {
    limpiarAccionesAsistencias();
    buscarAsistenciasSiCompleto();
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

    if (['1', '7'].indexOf(selectedValue) !== -1) {
        $heInput.prop('readonly', false);
    } else {
        $heInput.prop('readonly', true).val('');
    }

    syncConfirmarDLRow($row, selectedValue);
}

function syncConfirmarDLRow($row, selectedValue) {
    var esDL = String(selectedValue || '') === '9';
    var esFalta = getTipoAsistenciaPrefijo($row) === 'F';
    var $checkbox = $row.find('.confirmar-dl-toggle');
    var $hidden = $row.find('.confirmar-dl-value');
    var $cells = $row.children('td');
    var confirmado = $checkbox.length ? $checkbox.is(':checked') : false;

    if (!esDL) {
        $cells.each(function () {
            this.style.removeProperty('background-color');
        });
        if (esFalta) {
            $cells.each(function () {
                this.style.setProperty('background-color', COLOR_FALTA, 'important');
            });
        }
        if ($hidden.length) {
            $hidden.val('0');
        }
        if ($checkbox.length) {
            $checkbox.prop('checked', false);
        }
        return;
    }

    if ($hidden.length) {
        $hidden.val(confirmado ? '1' : '0');
    }
    $cells.each(function () {
        if (confirmado) {
            this.style.removeProperty('background-color');
        } else {
            this.style.setProperty('background-color', COLOR_DL_PENDIENTE, 'important');
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
            setAsistenciasFinalizadas(data.finalizado == 1);
            $("#select_horario").empty().append(data.select_horarios);
            $("#select_colaboradores").empty().append(data.select_colaboradores);
            swal.close();
            //$("#select_sede").empty().append(data.select);

        }
    });
}
$('#btn_guardar_asistencias').on('click', function () {
    $('#finalizar_asistencias').val('0');
});
$('#btn_guardar_finalizar_asistencias').on('click', function () {
    $('#finalizar_asistencias').val('1');
});

function requiereConfirmacionFinalizar() {
    var tieneHorasExtras = false;
    var tieneDL = false;

    $('#asistencias_grid tbody tr').each(function () {
        var $row = $(this);
        var tipo = String($row.find('select[name^="tipos["]').val() || '');
        var horasExtras = $.trim(String($row.find('input[name^="he["]').val() || ''));

        if (horasExtras !== '' && Number(horasExtras) > 0) {
            tieneHorasExtras = true;
        }
        if (tipo === '9') {
            tieneDL = true;
        }
        if ($row.find('.confirmar-extra-dl-toggle').length) {
            tieneDL = true;
        }
    });

    return tieneHorasExtras || tieneDL;
}

function tieneExtrasDLSinConfirmar() {
    return $('#asistencias_grid tbody .confirmar-extra-dl-toggle:not(:checked)').length > 0;
}

function guardarAsistencias(data) {
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
            if (data && data.status === 'error') {
                swal('Error!', data.mensaje || 'No fue posible guardar las asistencias.', 'error');
                return;
            }
            if (data && data.finalizado == 1) {
                setAsistenciasFinalizadas(true);
                swal('Listo!', 'Las asistencias se guardaron y finalizaron.', 'success');
            } else {
                swal('Listo!', 'Las asistencias se guardaron correctamente.', 'success');
            }

        }
    });
}

$('#form-asistencias').on('submit', function (e) {
    e.preventDefault();
    if (asistenciasFinalizadas) {
        swal('Error!', 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.', 'error');
        return;
    }
    if (tieneExtrasDLSinConfirmar()) {
        swal('Error!', 'Debes confirmar los extras DL antes de guardar.', 'error');
        return;
    }
    const data = $(this).serialize();
    if ($('#finalizar_asistencias').val() === '1' && requiereConfirmacionFinalizar()) {
        swal({
            title: "Confirmar finalizacion",
            text: "Hay empleados con horas extras o DL. Si finalizas, ya no se podran modificar estas asistencias.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0066D1",
            cancelButtonColor: "#d33",
            confirmButtonText: "Guardar y finalizar",
            cancelButtonText: "Cancelar"
        }, function (confirmado) {
            if (confirmado) {
                guardarAsistencias(data);
            }
        });
        return;
    }

    guardarAsistencias(data);
});
$("#btn_add_new_extra").click(function () {
    $("#modalExtra").modal("show");
});
function save_extra() {
    event.preventDefault();
    if (asistenciasFinalizadas) {
        swal('Error!', 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.', 'error');
        return;
    }
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
                if (data && data.status === 'error') {
                    swal('Error!', data.mensaje || 'No fue posible guardar el extra.', 'error');
                } else if (data) {
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
    if (asistenciasFinalizadas) {
        swal('Error!', 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.', 'error');
        return;
    }
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
                if (data && data.status === 'error') {
                    swal('Error!', data.mensaje || 'No fue posible eliminar el extra.', 'error');
                    return;
                }
                grid_load_data();
            }
        });

    });
}
function update_extra_horario(select) {
    if (asistenciasFinalizadas) {
        swal('Error!', 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.', 'error');
        grid_load_data();
        return;
    }

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
    if (asistenciasFinalizadas) {
        swal('Error!', 'Las asistencias de esta fecha ya fueron finalizadas y no se pueden modificar.', 'error');
        grid_load_data();
        return;
    }

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
$(document).on('change', '.confirmar-dl-toggle', function () {
    var $row = $(this).closest('tr');
    var $hidden = $row.find('.confirmar-dl-value');
    if ($hidden.length) {
        $hidden.val($(this).is(':checked') ? '1' : '0');
    }
    syncConfirmarDLRow($row, $row.find('select[name^="tipos["]').val());
});
