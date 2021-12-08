var server="http://heladoschaps.com/application";
var username="";
var userid="";
var cuenta=0;
var contador=0;
var stock="";
var ticket="";
var total=0;
var dev=0;
var stock_name="";
$(document).ready(function(){
	var url=window.location.href.split("?id=");
	url=url[1].split("&name=");
	username=url[1].split("&stock=")[0];
	stock=url[1].split("&stock=")[1];
	userid=url[0];
	//$("#userinicio").empty().append(username);
	$.ajax({
		url: server+"/webserviceapp/get_sales.php",
		type: "POST",
		data: {
			'stock_id':stock,
			'user_id':userid,
			"product": $("#buscar").val()
		},
		dataType: "json",
		beforeSend: function() {
			swal({
				title: "Cargando",
				showConfirmButton: false,
				imageUrl: "/images/loader.gif"
			});
		},
		success: function(data) {
			swal.close();
				//console.log(data);
				//console.log(data);
				$("#tablamisventas > tbody").empty().append(data.lista);
				total=data.total;
				ticket=data.lista_ticket;
				dev=data.total_dev;
				total-=dev;
				console.log(ticket);
				$("#ptotalventas").empty().append("$"+addCommas(parseFloat(total).toFixed(2)));
			}
		});
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
$("#buscar").keyup(function(event){
	$.ajax({
		url: server+"/webserviceapp/get_sales.php",
		type: "POST",
		data: {
			'stock_id':stock,
			'user_id':userid,
			"product": $("#buscar").val()
		},
		dataType: "json",
		beforeSend: function() {
			swal({
				title: "Cargando",
				showConfirmButton: false,
				imageUrl: "/images/loader.gif"
			});
		},
		success: function(data) {
			swal.close();
				//console.log(data);
				console.log(data);
				$("#tablamisventas > tbody").empty().append(data.lista);
			}
		});

});
function corte(){
	swal({
		title: "<p id='pswalerror'>Corte de caja</p>",
		html: "<p id='psswalerror'>Deseas realizar el corte de caja?</p>",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#0066D1',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: 'Cancelar',
	}).then((result) => {
		if (result.value) {
			var countrows = $('#tablamisventas tr').length;
			var rows = countrows - 1;

			if (total== 0) {

				swal("<p id='pswalerror'>Atención</p>", "<p id='psswalerror'>Para realizar un corte de caja primero debes realizar una venta.</p>", "info");

			} else {
				$.ajax({
					url: server+"/webserviceapp/corte.php",
					type: "POST",
					data: {
						'stock_id':stock,
						'user_id':userid
					},
					dataType: "json",
					success: function(data) {
				swal("<p id='pswal'>Corte Realizado</p>", "<p id='psswal'> La cantidad total vendida es de: <br> <b id='psbswal'>$" + parseFloat(total).toFixed(2) + "</b></p>", "success");

				$("#ptotalventas").empty().append("$"+addCommas(parseFloat(total).toFixed(2)));
				$.ajax({
					url: server+"/webserviceapp/get_sales.php",
					type: "POST",
					data: {
						'stock_id':stock,
						'user_id':userid
					},
					dataType: "json",
					success: function(data) {
				//console.log(data);
				//console.log(data);
				$("#tablamisventas > tbody").empty().append(data.lista);
				var string_total="Total:        $"+addCommas(parseFloat(total).toFixed(2));

				if(dev!=0){
					string_total+="\n"+"Devoluciones: $"+addCommas(parseFloat(dev).toFixed(2));
					string_total+="\n"+"Total Real:   $"+addCommas((parseFloat(total)-parseFloat(dev)).toFixed(2));
				}
				imprimir_ticket2(ticket,string_total,username+"\n"+stock_name);
				total=0;
				$("#ptotalventas").empty().append("$"+addCommas(parseFloat(total).toFixed(2)));
			}
		});
			}
		});
			}
		}
	});


}

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