$("#enviar_cita").submit(function(e){
    e.preventDefault();
    var urlParams = new URLSearchParams(window.location.search);
	id=urlParams.get('id'); 
    var data=$("#enviar_cita").serializeArray();
    data.push({name: "departamento_servicio_id", value:id });
    $.ajax({
		url: server+"/WS/add_cita",
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
				//swal.close();
				////console.log(data);
                //console.log(data);
                window.location.replace("agendar-cita-enviado.html?email="+$("#email").val());
				//$("#div_eventos").empty().append(data);
			}
		});

});