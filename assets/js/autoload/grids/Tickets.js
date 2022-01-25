$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    window.location.href = '/Tickets/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Tickets/get_Tickets",
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
            $('#groups_grid thead').empty().append(data.head);
            $('#groups_grid tbody').empty().append(data.table).trigger('footable_redraw');
            $('#groups_grid').show();
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
            url: "/Tickets/eliminar",
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
    var tipo = $(this).attr('tipo');
    var Nombre = $(this).attr('nom');
    if (tipo) {

        swal({
            title: "<p id='pswalerror'>Estas seguro que deseas Completar este elemento?</p>",
            html: "<p id='psswalerror'>Estas seguro que deseas Completar este elemento?</p>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0066D1",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }, function () {

            $.ajax({
                url: "/Tickets/completar",
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
    } else {
        window.location.href = '/Tickets/edit/' + idemp;
    }
});