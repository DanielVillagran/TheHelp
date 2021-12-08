var usuario="";
function printMyImage3(ticket,total) {
	var image = new Image();
	image.src = 'logoticket.jpeg';
	image.onload = function() {
		var canvas = document.createElement('canvas');
		canvas.height = 200;
		canvas.width = 300;
		var context = canvas.getContext('2d');
		context.drawImage(image, 0, 0);
		var imageData = canvas.toDataURL('image/jpeg').replace(/^data:image\/(png|jpg|jpeg);base64,/, ""); //remove mimetype
        window.DatecsPrinter.printImage(
imageData, //base64
canvas.width,
canvas.height,
1,
function() {
	//alert("Me la pele");
	printSomeTestText3(ticket,total);
	console.log("Problemas");
},
function(Error) {
	alert(JSON.stringify(Error));
}
)
		};
		
	}
	function leadingZero(value) {
  if (value < 10) {
    return "0" + value.toString();
  }
  return value.toString();
}
	function printSomeTestText3(ticket,total) {
		//printMyImage();

		var today = new Date();
		var date = leadingZero(today.getDate())+'-'+leadingZero(today.getMonth()+1)+'-'+today.getFullYear();
		var time = leadingZero(today.getHours()) + ":" + leadingZero(today.getMinutes());
		ticket=" Helados Chap's" + "\n"
		+" Ticket de Devolucion" + "\n\n"
		+usuario+ "\n\n"
		+date+' '+time + "\n\n"
		+ticket+"\n\n";

		ticket+=total  
		+"\n\n"
		+"Gracias por su preferencia."
		+"\n";

		window.DatecsPrinter.printText(ticket , 'utf8', 
			function() {
			//window.alert(window.DatecsPrinter.feedPapper(5));
			printMyBarcode();
		}
		);
	}
	function feedPapper() {
		window. DatecsPrinter.feedPaper(100, 
			function() {
			//window.alert(window.DatecsPrinter.feedPapper(5));
			//alert('Se ha completado la impresión!');
		}
		);
	}

	function printMyBarcode() {
		window.DatecsPrinter.printBarcode(
    75, //here goes the barcode type code
    '13132498746313210584982011487', //your barcode data
    function() {
    	feedPapper();
    	
    	//window.DatecsPrinter.disconnect();
    },
    function() {
    	alert(JSON.stringify(error));
    }
    );
	}
	function imprimir_ticket3(ticket,total,user=""){
		usuario=user;
		console.log(usuario);
		window.DatecsPrinter.listBluetoothDevices(

			function (devices) {
				//alert(JSON.stringify(devices));
				window.DatecsPrinter.connect(devices[0].address, 
					function() {
						printMyImage3(ticket,total);
					},
					function() {
						alert(JSON.stringify(error));
					}
					);
			},
			function (error) {
				alert(JSON.stringify(error));
			}
			);
	}