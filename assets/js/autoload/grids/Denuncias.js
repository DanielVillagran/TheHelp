$(document).ready(function() {
    //initMap();
    grid_load_data();

});
$("#btn_add_new").click(function() {
    window.location.href = '/Colonias/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Home/get_denuncias",
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
            //$('#groups_grid thead').empty().append(data.head);
            $('#noa_grid tbody').empty().append(data.table).trigger('footable_redraw');
            //$('#no_atendidas').show();
            $('#atendidas tbody').empty().append(data.table2).trigger('footable_redraw');
            $('#atendidas').show();
        }
    });
}
function initMap(lat,lon) {
    var uluru = {lat: parseFloat( lat), lng: parseFloat(lon)};
    console.log(uluru);
    var map = new google.maps.Map(
        document.getElementById('map'), {zoom: 15, center: uluru});
    var marker = new google.maps.Marker({position: uluru, map: map});
  }
function atender_denuncia(id){
    $.ajax({
        url: "/Home/atender_denuncia",
        type: 'POST',
        data: {
            'id': $.trim(id)
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
        }
    });
}
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/Colonias/edit/' + idemp;
});
function abrir_imagen(imagen){
    $("#modalFoto").modal("toggle");
    $(".modal-foto").css(
        "background-image",
        "url("+imagen+")"
      );
}
