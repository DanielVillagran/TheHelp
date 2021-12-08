function send_image(id, archivo) {
  var file = $(archivo)[0].files[0];
  var fileName = file.name;
  var fileSize = file.size;
  var fileExtension = fileName.substring(fileName.lastIndexOf(".") + 1);
  var formData = new FormData();
  formData.append("document", $(archivo)[0].files[0]);
  formData.append("id", id);
  $.ajax({
    url: "/Logos/file_upload",
    type: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function (e) {
      swal({
        title: "Cargando",
        showConfirmButton: false,
        imageUrl: "/assets/images/loader.gif",
      });
    },
    success: function (data) {
      if (data.result) {
        $("#ant_account_logo").val("");

        $("#notaa").val("");
        $("#prepend-small-btn").val("");
        swal("Exito", "La imagen ha sido cargado con exito", "success");
      } else {
        swal("Error", data.message, "error");
      }
    },
  });
}
