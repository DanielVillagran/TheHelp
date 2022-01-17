var states = "";
let objetoScanner = null;
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    objetoScanner = html5QrcodeScanner
    html5QrcodeScanner.render(onScanSuccess);
    function onScanSuccess(decodedText, decodedResult) {
        //objetoScanner.stop();
        if (decodedText.includes("vehiculos...")) {
            let id = decodedText.split("vehiculos...")[1];
            $('[name="users[vehiculoId]"]').val(id);
            objetoScanner.stop().then(ignore => {
                // QR Code scanning is stopped.
                console.log("QR Code scanning stopped.");
            }).catch(err => {
                // Stop failed, handle it.
                console.log("Unable to stop scanning.");
            });
        } else {
            alert("Pruebas");
            html5QrcodeScanner.clear();
        }

        //console.log(`Code scanned = ${decodedText}`, decodedResult);
    }

});

function get_info_Departamentos(id) {
    console.log(id);
    $.ajax({
        type: "post",
        url: "/Servicios/get_info_Servicios",
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
        url: "/Servicios/save_info",
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

            location.href = "/Servicios";


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