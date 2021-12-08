$(document).ready(function () {
    $.ajax({
		url: server+"/WS/get_departamentos",
		type: "POST",
		data: {"product": "",tipo:4},
		dataType: "json",
		beforeSend: function() {
		},
		success: function(data) {
				//swal.close();
				////console.log(data);
				//console.log(data);
				$("#div_directorio").empty().append(data);
			}
		});

});