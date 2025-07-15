var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }

});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Colaboradores/get_info_Colaboradores",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function () {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {
            swal.close();
            for (var key in data) {
                if (key != 'logo') {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                }
            }
            let horario_id= data.horario_id;
            if ($("#empresa_select").val()) {
                $.ajax({
                    type: "post",
                    url: "/Empresas/get_Empresas_horarios_select",
                    data: { id: $("#empresa_select").val() },
                    dataType: "json",
                    beforeSend: function () {

                    },
                    success: function (data) {
                        $("#horario_select").empty().append(data.select);
                        $("#horario_select").val(horario_id);
                    }
                });
            }



        }
    });
}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Colaboradores/save_info",
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

            swal.close();

            location.href = "/Colaboradores";


        }
    });
}
$("#empresa_select").change(function () {
    $.ajax({
        type: "post",
        url: "/Empresas/get_Empresas_horarios_select",
        data: { id: $("#empresa_select").val() },
        dataType: "json",
        beforeSend: function () {

        },
        success: function (data) {
            $("#horario_select").empty().append(data.select);
            $("#horario_select").val("1");
        }
    });
})
function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}