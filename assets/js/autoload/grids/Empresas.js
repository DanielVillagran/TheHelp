var tabla = null;
$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    window.location.href = '/Empresas/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Empresas/get_Empresas",
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
$('#filter').on('keyup change', function () {
    tabla.search(this.value).draw();
});
$('#groups_grid').footable().on('click', '.row-delete', function (e) {
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
            url: "/Empresas/eliminar",
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
    window.location.href = '/Empresas/edit/' + idemp;
});
$("#carga_masiva").click(function () {
    $("#archivo_excel").trigger("click");
});
$("#archivo_excel").change(function (e) {
    var file = e.target.files[0];

    if (!file) return;

    var reader = new FileReader();

    reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, { type: "array" });

        var firstSheetName = workbook.SheetNames[0];
        var worksheet = workbook.Sheets[firstSheetName];
        var jsonData = XLSX.utils.sheet_to_json(worksheet);
        $.ajax({
            url: "/Empresas/carga_masiva",
            type: "POST",
            data: JSON.stringify({ usuarios: jsonData }),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                swal('Listo!', "Se ha procesado el documento con exito.", 'success');
                $("#archivo_excel").val('');
                grid_load_data();
            },
            error: function (xhr) {
                swal('Error!', "Ocurrio un error al cargar el documento.", 'error');
                $("#archivo_excel").val('');
            }
        });

    };

    reader.readAsArrayBuffer(file);
});