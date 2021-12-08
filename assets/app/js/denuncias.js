var lat = 0;
var long = 0;
var imageURI = "";
$(document).ready(function () {
  $.ajax({
    url: server + "/WS/get_tipos_denuncias",
    type: "POST",
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#tipos_denuncia").append(data);
    },
  });
});
$("#enviar_denuncia").submit(function (e) {
  e.preventDefault();
  uploadPhoto();
});
$(document).on("deviceready", function () {
  var onSuccess = function (position) {
    lat = position.coords.latitude;
    long = position.coords.longitude;
  };

  // onError Callback receives a PositionError object
  //
  function onError(error) {
    alert("code: " + error.code + "\n" + "message: " + error.message + "\n");
  }

  navigator.geolocation.getCurrentPosition(onSuccess, onError);
});

function uploadPhoto() {
  var options = new FileUploadOptions();
  options.fileKey = "file";
  options.fileName = imageURI.substr(imageURI.lastIndexOf("/") + 1);
  options.mimeType = "image/jpeg";
  var ft = new FileTransfer();
  ft.upload(
    imageURI,
    server + "/WS/upload_image",
    function (result) {
      var data = $("#enviar_denuncia").serializeArray();
      data.push({ name: "imagen", value: result.response.replace(/"/g,"") });
      data.push({ name: "lat", value: lat });
      data.push({ name: "lon", value: long });
      $.ajax({
        url: server+"/WS/add_denuncia",
        type: "POST",
        data: data,
        dataType: "json",
        beforeSend: function() {
          swal({
            title: "Cargando",
            showConfirmButton: false,
            imageUrl: "img/loader.gif"
        });
        },
        success: function(data) {

                    window.location.replace("denuncia-enviado.html");

          }
        });
      
    },
    function (error) {
      console.log(JSON.stringify(error));
    },
    options
  );
}
function init_camera() {
  navigator.camera.getPicture(onSuccess, onFail, {
    quality: 50,
    destinationType: Camera.DestinationType.FILE_URI,
  });

  function onSuccess(imageUR) {
    $("#imagen").css("background-image", "url(" + imageUR + ")");
    imageURI = imageUR;
    $("#boton_denuncia").show();
  }

  function onFail(message) {
    alert("Failed because: " + message);
  }
}
