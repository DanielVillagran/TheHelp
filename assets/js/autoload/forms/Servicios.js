var states = "";
$(document).ready(function () {
    if ($("#id").val() != 0) {
        get_info_Departamentos($("#id").val());
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var arg = {
        resultFunction: function (result) {
            /*
                result.format: code format,
                result.code: decoded string,
                result.imgData: decoded image data
            */
            var aChild = document.createElement('li');
            aChild[txt] = result.format + ': ' + result.code;
            document.querySelector('body').appendChild(aChild);
        }
    };
    /* -------------------------------------- Available parameters --------------------------------------*/
    var options = {
        DecodeQRCodeRate: 5,                    // null to disable OR int > 0 !
        DecodeBarCodeRate: 5,                   // null to disable OR int > 0 !
        successTimeout: 500,                    // delay time when decoding is succeed
        codeRepetition: true,                   // accept code repetition true or false
        tryVertical: true,                      // try decoding vertically positioned barcode true or false
        frameRate: 15,                          // 1 - 25
        width: 320,                             // canvas width
        height: 240,                            // canvas height
        constraints: {                          // default constraints
            video: {
                mandatory: {
                    maxWidth: 1280,
                    maxHeight: 720
                },
                optional: [{
                    sourceId: true
                }]
            },
            audio: false
        },
        flipVertical: false,                    // boolean
        flipHorizontal: false,                  // boolean
        zoom: -1,                               // if zoom = -1, auto zoom for optimal resolution else int
        beep: 'audio/beep.mp3',                 // string, audio file location
        decoderWorker: 'js/DecoderWorker.js',   // string, DecoderWorker file location
        brightness: 0,                          // int
        autoBrightnessValue: false,             // functional when value autoBrightnessValue is int
        grayScale: false,                       // boolean
        contrast: 0,                            // int
        threshold: 0,                           // int 
        sharpness: [],      // to On declare matrix, example for sharpness ->  [0, -1, 0, -1, 5, -1, 0, -1, 0]
        resultFunction: function (result) {
            /*
                result.format: code format,
                result.code: decoded string,
                result.imgData: decoded image data
            */
            alert(result.code);
        },
        cameraSuccess: function (stream) {           //callback funtion to camera success
            console.log('cameraSuccess');
        },
        canPlayFunction: function () {               //callback funtion to can play
            console.log('canPlayFunction');
        },
        getDevicesError: function (error) {          //callback funtion to get Devices error
            console.log(error);
        },
        getUserMediaError: function (error) {        //callback funtion to get usermedia error
            console.log(error);
        },
        cameraError: function (error) {              //callback funtion to camera error  
            console.log(error);
        }
    };

    /*---------------------------- Example initializations Javascript version ----------------------------*/
    new WebCodeCamJS("canvas").init(arg);
    /* OR */
    var canvas = document.querySelector('#webcodecam-canvas');
    new WebCodeCamJS(canvas).init();
    /* OR */
    new WebCodeCamJS('#webcodecam-canvas').init();

    /*------------------------ Example initializations jquery & Javascript version ------------------------*/
    var decoder = new WebCodeCamJS('#webcodecam-canvas');

    var decoder = $("#webcodecam-canvas").WebCodeCamJQuery(args).data().plugin_WebCodeCamJQuery;

    decoder.buildSelectMenu('#camera-select', sel); //sel : default camera optional
    /* Chrome & MS Edge: build select menu
    *  Firefox: the default camera initializes, return decoder object 
    */
    //simple initialization
    decoder.init();
    /* Select environment camera if available */
    decoder.buildSelectMenu('#camera-select', 'environment|back').init(args);
    /* Select user camera if available */
    decoder.buildSelectMenu('#camera-select', 'user|front').init(args);
    /* Select camera by name */
    decoder.buildSelectMenu('#camera-select', 'facecam').init(args);
    /* Select first camera */
    decoder.buildSelectMenu('#camera-select', 0).init(args);
    /* Select environment camera if available, without visible select menu*/
    decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init().play();

    /* --------------------------------------- Available Functions: ----------------------------------------*/
    /* camera stop & delete stream */
    decoder.stop();
    /* camera play, restore process */
    decoder.play();
    /* get current image from camera */
    decoder.getLastImageSrc();
    /* decode local image */
    /* if url is defined download image before staring open process */
    decoder.decodeLocalImage(url);
    /* get optimal zoom */
    decoder.getOptimalZoom();
    /* Configurable options */
    decoder.options['parameter'];
    /* Example: 
    ** decoder.options.brightness = 20;         - set brightness to 20
    ** decoder.options.DecodeQRCodeRate = null; - disable qrcode decoder
    */

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

            new QRCode(document.getElementById("qrcode"), {
                text: "vehiculos..." + data['id'],
                width: 300,
                height: 300,
                correctLevel: QRCode.CorrectLevel.H
            });
            $("#qrCodeDiv").show();


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