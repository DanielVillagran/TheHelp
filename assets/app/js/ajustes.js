$(document).ready(function () {
  $.ajax({
    url: server + "/WS/get_colonias",
    type: "POST",
    data: { product: "" },
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      //swal.close();
      ////console.log(data);
      $("#colonia").append(data.colonias);
      sharedPreferences.get(
        "colonia",
        function (value) {
          console.log(value);
          $("#colonia").val(value);
        },
        function (error) {}
      );
      sharedPreferences.get(
        "notificaciones",
        function (value) {
            console.log(value);
          if (value == "0") {
            $("#notis").bootstrapToggle('off');
          } else {
            $("#notis").bootstrapToggle('on');
          }
        },
        function (error) {
          sharedPreferences.put(
            "notificaciones",
            "1",
            function () {},
            function (error) {
              console.log(error);
            }
          );
        }
      );
      create_custom_dropdowns();
    },
  });
});
function cambio_notis() {
  if ($("#notis").prop("checked") == true) {
    sharedPreferences.put(
        "notificaciones",
        "1",
        function () {},
        function (error) {
          console.log(error);
        }
      );
  } else {
    sharedPreferences.put(
        "notificaciones",
        "0",
        function () {},
        function (error) {
          console.log(error);
        }
      );
  }
}
function save_colonia() {
  var key = "colonia";
  var value = $("#colonia").val();
  sharedPreferences.put(
    key,
    value,
    function () {
      sharedPreferences.get(
        "colonia",
        function (value) {
          FCMPlugin.unsubscribeFromTopic(value);
        },
        function (error) {}
      );
      
      FCMPlugin.subscribeToTopic($("#colonia").val());
      window.location.replace("index.html");
    },
    function (error) {
      console.log(error);
    }
  );
}
