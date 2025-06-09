$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    $("#modalNuevoServicio").modal("show");
});
function save_modal() {
    event.preventDefault();
    var data = new FormData(document.getElementById("modal_info"));
    var userId = data.get("users[user_id]");
    var empresaId = data.get("users[empresa_id]");

    if (userId != "Seleccionar usuario" && empresaId != "Seleccionar empresa") {
        $.ajax({
            type: "post",
            url: "/Empresas/save_assign",
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
                    const form = document.getElementById("modal_info");
                    form.reset();
                    swal.close();
                    $("#modalNuevoServicio").modal("hide");
                    grid_load_data();
                } else {
                    swal('Error!', "Ya existe la relación seleccionada.", 'error');
                }

            }
        });
    } else {
        swal('Error!', "Debes completar todos los campos.", 'error');
    }
}
function grid_load_data() {
    $.ajax({
        url: "/Empresas/get_Empresas_Assign",
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
            url: "/Empresas/eliminar_assign",
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