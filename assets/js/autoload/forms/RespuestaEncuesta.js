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
        url: "/Encuestas/get_info_Encuestas",
        data: {
            id: id,
        },
        dataType: "json",
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif",
            });
        },
        success: function (data) {
            swal.close();
            for (var key in data) {
                if (key != "logo") {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
        },
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
                imageUrl: "/assets/images/loader.gif",
            });
        },
        success: function (data) {
            swal.close();

            location.href = "/Encuestas";
        },
    });
}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split("T")[0].split("-");
    var array_hour = date.split("T")[1].split(":");
    formated_date +=
        array_date[2] +
        "-" +
        array_date[1] +
        "-" +
        array_date[0] +
        " " +
        array_hour[0] +
        ":" +
        array_hour[1] +
        ":00";
    return formated_date;
}
$("#empresa_select").change(function () {
    $.ajax({
        url: "/Empresas/get_Empresas_sedes",
        type: "POST",
        data: {
            id: $("#empresa_select").val(),
        },
        dataType: "json",
        beforeSend: function (e) {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif",
            });
        },
        success: function (data) {
            swal.close();
            $("#select_sede").empty().append(data.select);
        },
    });
});
$("#search").click(function () {
    grid_load_data();
});
function grid_load_data() {
    $.ajax({
        url: "/Encuestas/get_respuestas_responder",
        type: "POST",
        data: {
            empresa_id: $("#empresa_select").val(),
            encuesta_id: $("#encuesta_select").val(),
            fecha: $("#fecha").val(),
        },
        dataType: "json",
        beforeSend: function (e) {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif",
            });
        },
        success: function (data) {
            $("#respuestas_grid thead").empty().append(data.head);
            $("#respuestas_grid tbody")
                .empty()
                .append(data.table)
                .trigger("footable_redraw");
            $("#respuestas_grid").show();
            $("#introduccion").empty().append(data.introduccion);
            swal.close();
            //$("#select_sede").empty().append(data.select);
        },
    });
}
$('#form-respuestas').on('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Por favor, firma para continuar',
        html: `
            <canvas id="canvasFirma" width="400" height="200"
                style="border:1px solid #ccc; border-radius:6px; width:100%;"></canvas>
            <div style="margin-top:10px;">
                <button id="btnClearFirma" class="swal2-cancel swal2-styled" type="button">Limpiar</button>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            const canvas = document.getElementById('canvasFirma');
            const signaturePad = new SignaturePad(canvas);
            document.getElementById('btnClearFirma').addEventListener('click', () => signaturePad.clear());
            Swal.signaturePad = signaturePad;
        },
        preConfirm: () => {
            if (Swal.signaturePad.isEmpty()) {
                Swal.showValidationMessage('Por favor, ingresa tu firma antes de continuar');
                return false;
            }
            const base64 = Swal.signaturePad.toDataURL();
            return dataURLtoBlob(base64); // Devuelve el archivo Blob
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const firmaBlob = result.value;
            const form = $('#form-respuestas')[0];
            const formData = new FormData(form);
            formData.append('firma', firmaBlob, 'firma.png');

            $.ajax({
                url: "/Encuestas/save_respuestas_encuestas",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    swal({
                        title: "Cargando",
                        showConfirmButton: false,
                        imageUrl: "/assets/images/loader.gif"
                    });
                },
                success: function (data) {
                    swal('Listo!', "Se ha guardado la información con éxito.", 'success');
                    $('#respuestas_grid').hide();
                },
                error: function () {
                    swal('Error', "Ocurrió un problema al guardar la información.", 'error');
                }
            });
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
        colaborador_id &&
        colaborador_id.trim() !== "" &&
        horario &&
        horario.trim() !== "" &&
        puesto &&
        puesto.trim() !== ""
    ) {
        $.ajax({
            type: "post",
            url: "/Encuestas/save_extra",
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function () {
                swal({
                    title: "Cargando",
                    showConfirmButton: false,
                    imageUrl: "/assets/images/loader.gif",
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
                    swal(
                        "Error!",
                        "Ya existe una asignación con la misma información.",
                        "error"
                    );
                }
            },
        });
    } else {
        swal("Error!", "Debes completar todos los campos.", "error");
    }
}
function dataURLtoBlob(dataURL) {
    const parts = dataURL.split(';base64,');
    const contentType = parts[0].split(':')[1];
    const byteCharacters = atob(parts[1]);
    const byteArrays = [];
    for (let i = 0; i < byteCharacters.length; i++) {
        byteArrays.push(byteCharacters.charCodeAt(i));
    }
    return new Blob([new Uint8Array(byteArrays)], { type: contentType });
}
function delete_extra(idemp) {
    swal(
        {
            title:
                "<p id='pswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
            html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0066D1",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
        },
        function () {
            $.ajax({
                url: "/Encuestas/eliminar_extra",
                type: "POST",
                data: {
                    id: idemp,
                },
                dataType: "json",
                beforeSend: function (e) { },
                success: function (data) {
                    //swal('Listo!',"El elemento ha sido eliminado con exito.",'success');
                    grid_load_data();
                },
            });
        }
    );
}
