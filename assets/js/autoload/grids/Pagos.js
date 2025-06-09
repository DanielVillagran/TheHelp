var id=0;
$(document).ready(function() {
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Departamentos/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Pagos/get_Pagos",
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
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Ya se ha validado el pago?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
      }, function() {
        
            $.ajax({
                url: "/Pagos/update_pago",
                type: 'POST',
                data: {
                    'id': idemp,
                    'status':1
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
$('#groups_grid').footable().on('click', '.row-delete', function(e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Se ha completado el proceso?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
      }, function() {
        
            $.ajax({
                url: "/Pagos/update_pago",
                type: 'POST',
                data: {
                    'id': idemp,
                    'status':2
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

