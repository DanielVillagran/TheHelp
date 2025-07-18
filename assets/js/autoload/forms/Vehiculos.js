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
        url: "/Vehiculos/get_info_Vehiculos",
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

            new QRCode(document.getElementById("qrcode"), {
                text: "vehiculos..." + data['id'],
                width: 300,
                height: 300,
                correctLevel: QRCode.CorrectLevel.H
            });
            $("#qrCodeDiv").show();
            $("#tableServiciosDiv").show();
            $('#groups_grid thead').empty().append(data.head);
            $('#groups_grid tbody').empty().append(data.tableData).trigger('footable_redraw');
            $("#tableTicketsDiv").show();
            $('#tickets_grid thead').empty().append(data.head2);
            $('#tickets_grid tbody').empty().append(data.tableData2).trigger('footable_redraw');


        }
    });
}
function downloadAsImage() {
    html2canvas($("#qrcode")[0]).then((canvas) => {
        var a = document.createElement('a');
        a.href = canvas.toDataURL("image/png");
        a.download = 'image.png';
        a.click();
    });

}

function save_Departamentos() {
    event.preventDefault();
    var data = new FormData(document.getElementById("Departamentos_info"));
    $.ajax({
        type: "post",
        url: "/Vehiculos/save_info",
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
            if (data.insert_id) {
                location.href = "/Vehiculos/edit/" + data.insert_id;
            } else {
                location.href = "/Vehiculos";
            }


        }
    });
}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}