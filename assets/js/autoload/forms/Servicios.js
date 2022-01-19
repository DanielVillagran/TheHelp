var states = "";
let objetoScanner = null;
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }
    $(".input-file-icon").change(function () {
        var t = $(this).val();
        var labelText = 'Img: ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    });

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    objetoScanner = html5QrcodeScanner
    html5QrcodeScanner.render(onScanSuccess);
    function onScanSuccess(decodedText, decodedResult) {
        //objetoScanner.stop();
        if (decodedText.includes("vehiculos...")) {
            let id = decodedText.split("vehiculos...")[1];
            $('[name="users[vehiculoId]"]').val(id);
            html5QrcodeScanner.clear();
        }
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
                if (key != 'evidencia') {
                    $('[name="users[' + key + ']"]').val($.trim(data[key]));
                } 
                if (data.evidencia != null) {
                    $(".current_logo").html('<img src="/assets/' + data.evidencia + '"/>');
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
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(".current_logo").html('<img src="/assets/' + e.target.result + '"/>');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".input-file-icon").change(function () {

    readURL(this);
    $("#icon-preview").show();
});
