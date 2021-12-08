$(document).ready(function() {
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Departamentos/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Departamentos/get_Departamentos",
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
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/Departamentos/edit/' + idemp;
});