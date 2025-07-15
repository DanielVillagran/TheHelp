var tabla = null;
$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    window.location.href = '/Encuestas/RespuestasAdd';
});

function grid_load_data() {
    $.ajax({
        url: "/Encuestas/get_Respuestas_globales",
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
    window.location.href = "/encuestas/respuestaView/" + idemp;
});
$('#groups_grid').footable().on('click', '.row-edit', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/Empresas/edit/' + idemp;
});