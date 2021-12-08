var id=0;
var precio=0;
var description="";
$(document).ready(function () {
    var urlParams = new URLSearchParams(window.location.search);
    id=urlParams.get('tipo'); 
    if(id!=1){
      $("#titulo").empty().append("Pendiente de pago");
      $("#texto").empty().append("En espera de que el pago sea procesado en alguna tienda de autoservicio.");
    }
    
  });
 