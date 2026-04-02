$(document).ready(function() {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }
});

function get_info_Departamentos(id) {
    $.ajax({
        type: "post",
        url: "/Tipos_Tickets/get_info_Tipos_Tickets",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            swal.close();
            for (var key in data) {
                if (key === 'con_copia_correo') {
                    $('#con_copia_correo').prop('checked', parseInt(data[key], 10) === 1);
                } else {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Tipos_Tickets/save_info",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            swal.close();
            location.href = "/Tipos_Tickets";
        }
    });
}
