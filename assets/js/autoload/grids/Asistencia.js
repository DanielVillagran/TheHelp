$(document).ready(function() {
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Asistencias/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Asistencias/get_info_asistencia/"+$("#id").val(),
        type: 'POST',
        data: {
            'search': $.trim($('#filter').val())
        },
        dataType: 'json',
        beforeSend: function(e) {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
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
$('#groups_grid').footable().on('click', '.row-delete', function(e) {
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
      }, function() {
        
            $.ajax({
                url: "/Asistencias/eliminar",
                type: 'POST',
                data: {
                    'id': idemp
                },
                dataType: 'json',
                beforeSend: function(e) {
                    
                },
                success: function(data) {
                    //swal('Listo!',"El elemento ha sido eliminado con exito.",'success');
                    grid_load_data();
                }
            });
        
    });
});
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/Asistencias/edit/' + idemp;
});
$('#groups_grid').footable().on('change', '.confirmar-dl', function(e) {
    var idDetalle = $(this).data('id');
    var confirmado = $(this).is(':checked') ? 1 : 0;
    $.ajax({
        url: "/Asistencias/confirmar_dl",
        type: 'POST',
        data: {
            id: idDetalle,
            confirmado: confirmado
        },
        dataType: 'json',
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            swal.close();
            if (data && data.status) {
                grid_load_data();
            } else {
                swal('Error!', (data && data.mensaje) ? data.mensaje : 'No fue posible actualizar el detalle.', 'error');
                grid_load_data();
            }
        },
        error: function() {
            swal.close();
            swal('Error!', 'No fue posible actualizar el detalle.', 'error');
            grid_load_data();
        }
    });
});
