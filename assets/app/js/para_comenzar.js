const server = "https://zumpango.vmcomp.com.mx";
var sharedPreferences = null;
$(document).on("deviceready", function () {
  sharedPreferences=window.plugins.SharedPreferences.getInstance();
});
$(document).ready(function () {
    $.ajax({
		url: server+"/WS/get_colonias",
		type: "POST",
		data: {"product": ""},
		dataType: "json",
		beforeSend: function() {
		},
		success: function(data) {
				//swal.close();
				////console.log(data);
                $("#colonia").append(data.colonias);
                create_custom_dropdowns();
                
			}
		});

});
function save_colonia(){
    var key = "colonia";
    var value = $("#colonia").val();
    sharedPreferences.put(
      key,
      value,
      function () {
        FCMPlugin.subscribeToTopic($("#colonia").val());
        window.location.replace("index.html");
      },
      function (error) {
        console.log(error);
      }
    );
    
}