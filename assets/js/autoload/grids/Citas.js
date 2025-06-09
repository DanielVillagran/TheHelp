var id=0;
$(document).ready(function() {
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Departamentos/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Citas/get_Citas",
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
    id = $(this).attr('rel');
    $("#modalNuevoServicio").modal('toggle');
    
});
function agendar_cita(){
    //console.log($datepicker.value());
    //console.log($timepicker.value());
    $.ajax({
        url: "/Citas/agendar_cita",
        type: 'POST',
        data: {
            'id': $.trim(id),
            'fecha':$datepicker.value()+' '+$timepicker.value()+':00',
            'dia':$datepicker.value(),
            'hora':$timepicker.value()
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
            grid_load_data();
            $("#modalNuevoServicio").modal('hide');
        }
    });
}
function descarga_excel(){
    window.open("/Citas/get_citas_csv");
  
  
  }