$(document).ready(function () {
    $.ajax({
		url: server+"/WS/get_intereses",
		type: "POST",
		data: {"product": ""},
		dataType: "json",
		beforeSend: function() {
		},
		success: function(data) {
                $("#intereses").empty().append(data.regreso);
                console.log(data.arreglo);
                for(elemento in data.arreglo){
                  interes=elemento;
                  revisar_cambios(data.arreglo[interes].id)
                  
                }
                
			}
		});

});
function revisar_cambios(id){
  sharedPreferences.get(
    "interes"+id,
    function (value) {
        console.log("#check"+id);
      if (value == "0") {
        console.log("aqui");
        $("#check"+id).prop("checked",false);
      } else {
        $("#check"+id).prop("checked",true);
      }
    },
    function (error) {
      console.log("No hay nada");
    }
  );

}
function cambiar_valor(id){
    var key = "interes"+id;
    var value = $("#check"+id).prop("checked") == true?"1":"0";
    sharedPreferences.put(
      key,
      value,
      function () {
        FCMPlugin.subscribeToTopic(key);
        //window.location.replace("index.html");
      },
      function (error) {
        console.log(error);
      }
    );
    
}