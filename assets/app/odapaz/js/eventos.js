$(document).ready(function () {
    $.ajax({
		url: server+"/WS/get_eventos",
		type: "POST",
		data: {"product": "",tipo:3},
		dataType: "json",
		beforeSend: function() {
		},
		success: function(data) {
				//swal.close();
				////console.log(data);
				//console.log(data);
				$("#div_eventos").empty().append(data);
			}
		});

});