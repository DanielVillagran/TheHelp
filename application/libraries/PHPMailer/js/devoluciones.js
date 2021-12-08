var server="http://heladoschaps.com/application";
var username="";
var userid="";
var cuenta=0;
var contador=0;
var stock="";
var stock_name="";
$(document).ready(function(){
	var url=window.location.href.split("?id=");
	url=url[1].split("&name=");
	username=url[1].split("&stock=")[0];
	stock=url[1].split("&stock=")[1];
	userid=url[0];
	$("#userdev").val(username);
	//console.log(userid);
	$.ajax({
		url: server+"/webserviceapp/get_stock_name.php",
		type: "POST",
		data: {id: stock},
		dataType: "json",
		success: function(data) {
			stock_name=data.name;
			}
		});
});
$( "#form_devolucion" ).submit(function( event ) {
	event.preventDefault();
	var url=window.location.href.split("?id=");
	url=url[1].split("&name=");
	username=url[1].split("&stock=")[0];
	stock=url[1].split("&stock=")[1];
	userid=url[0];
	//console.log(userid);
	swal({
		title: "Cargando...",
		showConfirmButton: false,
		imageUrl: "loader.gif"
	});
	$.ajax({
		url: server+"/webserviceapp/devolucion.php",
		type: 'post',
		async: false,
		data: {
			'importe': $("#importedev").val(),
			'concepto':$("#conceptodev").val(),
			'stock_id':stock,
			'user_id':userid
		},
		dataType: 'html',
		beforeSend(){
			swal({
				title: "Cargando...",
				showConfirmButton: false,
				imageUrl: "loader.gif"
			});
		},
		success() {
			swal.close();
			imprimir_ticket3($("#conceptodev").val(),"Total:        $"+addCommas(parseFloat($("#importedev").val()).toFixed(2)),username+"\n"+stock_name);
			swal({

			type: 'success',
			title: "<p id='pswal'>Devolucion realizada</p>",
			html: "<p id='psswal'> El administrador sera notificado en breve.</p>",
			confirmButtonText: 'Aceptar',
			showCancelButton: false,
			cancelButtonText: 'Cancelar',

		}).then((result) => {
			window.location.href= "inicio.html?id="+userid+"&name="+username+"&stock="+stock;
});
			


		}
	});
})
function addCommas(nStr) {
	nStr += '';
	var x = nStr.split('.');
	var x1 = x[0];
	var x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
$('#importedev').on('input', function () { 
	this.value = this.value.replace(/[^0-9]/g,'');
});
function logout(){
	window.location.replace("index.html");
}
function iniciovender() {

	window.location.href= "vender.html?id="+userid+"&name="+username+"&stock="+stock;
}
function inicioventas() {

	window.location.href= "ventas.html?id="+userid+"&name="+username+"&stock="+stock;
}

function iniciodevoluciones() {

	window.location.href= "devoluciones.html?id="+userid+"&name="+username+"&stock="+stock;
}
function inicioinicio(){
	window.location.href= "inicio.html?id="+userid+"&name="+username+"&stock="+stock;
}