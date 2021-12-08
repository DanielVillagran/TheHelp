var server="";
$(document).ready(function(){
	

	$("#login_form").submit(function(event){
		event.preventDefault();
		swal({
			title: "Cargando...",
			showConfirmButton: false,
			imageUrl: "loader.gif"
		});
		$.ajax({
			url: server+"webserviceapp/login_process.php",
			type: "POST",
			data: {
				username:$("#userlogin").val(),
				password:$("#passlogin").val()
			},
			dataType: "json",
			beforeSend: function() {
				swal({
					title: "Cargando...",
					showConfirmButton: false,
					imageUrl: "resources/loader.gif"
				});
			},
			success: function(data) {
				swal.close();
				//console.log(data);
				if(data==false){
					swal("Error de credenciales.","El usuario no existe o la contraseña es incorrecta, por favor revisa tus credenciales." ,"warning");
				}else{
					////console.log(data.name);
					if(data.stock_id==undefined){
						data.stock_id="admin";

					}
					if(data.rol==3){
						window.location.replace("pos.php");
					}else{
						window.location.replace("dashboard.php");
					}

				}
			}
		});
	});


});