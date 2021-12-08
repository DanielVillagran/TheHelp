var id=0;
$(document).ready(function() {
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Departamentos/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Notificaciones/get_Notificaciones",
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
            $('#groups_grid thead').empty().append(data.head);
            $('#groups_grid tbody').empty().append(data.table).trigger('footable_redraw');
            $('#groups_grid').show();
        }
    });
}
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
    e.preventDefault();
    id = $(this).attr('rel');
    $("#modalNuevoServicio").modal('toggle');
    
});
