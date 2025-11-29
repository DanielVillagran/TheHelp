$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    window.location.href = '/Colaboradores/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Colaboradores/get_Colaboradores/activo",
        type: 'POST',
        data: {
            'search': $.trim($('#filter').val())
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
            if ($.fn.DataTable.isDataTable('#groups_grid')) {
                $('#groups_grid').DataTable().destroy();
            }
            $('#groups_grid thead').empty().append(data.head);
            $('#groups_grid tbody').empty().append(data.table);
            $('#groups_grid').show();
            inicializarDatatable('#groups_grid');
        }
    });
}
$('#groups_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Estas seguro que deseas dar de baja este elemento?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas dar de baja este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }, function () {

        $.ajax({
            url: "/Colaboradores/eliminar",
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
});
$('#groups_grid').footable().on('click', '.row-edit', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/Colaboradores/edit/' + idemp;
});
function handleUpload($input, uploadUrl, status) {
    var file = $input[0].files[0];
    if (!file) return;

    if (file.type !== "application/pdf") {
        swal({ title: 'Error!', text: 'Solo se permiten archivos PDF.', type: 'error' })
            .then(() => { $input.val(''); });
        return;
    }

    var formData = new FormData();
    formData.append("file", file);
    formData.append("status", status);

    $.ajax({
        url: uploadUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = (typeof response === 'string') ? JSON.parse(response) : response;
            var operados = (res && res.resultado && Array.isArray(res.resultado.operados)) ? res.resultado.operados : [];
            var rechazados = (res && res.resultado && Array.isArray(res.resultado.rechazados)) ? res.resultado.rechazados : [];

            var listaOperados = operados.length ? operados.map(o => o.split(",")[0].trim()).join("<br>") : "Ninguno";
            var listaRechazados = rechazados.length ? rechazados.map(r => r.split(",")[0].trim()).join("<br>") : "Ninguno";

            var mensaje = `
          <b>Procesados (${operados.length}):</b><br>${listaOperados}<br><br>
          <b>Rechazados (${rechazados.length}):</b><br>${listaRechazados}
        `;

        swal({
            title: 'Listo!',
            text: mensaje,
            type: 'success',
            html: true
          }, function () {
            $input.val('');
            grid_load_data();
          });
        },
        error: function () {
            swal({
                title: 'Error!',
                text: "Ocurrió un error al subir el documento.",
                type: 'success',
                html: true
              }, function () {
                $input.val('');
                grid_load_data();
              });
           
        }
    });
}

$("#carga_masiva_baja").click(function () {
    $("#archivo_excel_baja").trigger("click");
});
$("#archivo_excel_baja").change(function () {
    handleUpload($("#archivo_excel_baja"), "/Colaboradores/leer_acuse", 5);
});

$("#carga_masiva_prebaja").click(function () {
    $("#archivo_excel_prebaja").trigger("click");
});
$("#archivo_excel_prebaja").change(function () {
    handleUpload($("#archivo_excel_prebaja"), "/Colaboradores/leer_acuse", 4);
});
