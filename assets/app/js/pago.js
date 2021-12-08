var id=0;
var precio=0;
var description="";
$(document).ready(function () {
    var urlParams = new URLSearchParams(window.location.search);
	id=urlParams.get('id'); 
    OpenPay.setId("m70gtw7bctazhllgfezs");
    OpenPay.setApiKey("pk_8dea63374710413680eac60a52434a44");
    OpenPay.setSandboxMode(true);
    //Se genera el id de dispositivo
    deviceSessionId = OpenPay.deviceData.setup(
      "card_payment",
      "deviceIdHiddenFieldName"
    );
    $.ajax({
        url: server + "/WS/get_service",
        type: "POST",
        data: { id: id },
        dataType: "json",
        beforeSend: function () {},
        success: function (data) {
          $("#servicio").empty().append(data.nombre);
          $(".monto").empty().append("$" + parseFloat(data.c_pago).toFixed(2));
          precio=data.c_pago;
          description="Pago de servicio: "+data.nombre;
          if(data.c_pago!=""&&data.c_pago!=0){
            $("#botones_pagar").show();
          }else{
            $("#botones_pagar").hide();
          }
         
        },
      });
  });
  $("#card_payment").submit(function (event) {
    event.preventDefault();
    cantidad_tarjeta = $("#cantidad_tarjeta").val();
    numero_tarjeta = $("#numero_tarjeta").val();
    numero_tarjeta = numero_tarjeta.substr(-4);
    OpenPay.token.extractFormAndCreate(
      "card_payment",
      sucess_callbak,
      error_callbak
    );
  });
  var sucess_callbak = function (response) {
    var token_id = response.data.id;
    var email=$("#email").val();
    var nombre=$("#nombre").val();
    $("#token_id").val(token_id);
    $.ajax({
      url: server + "/WS/proccess_card_pay",
      type: "post",
      data: {
        nombre: $("#nombre_tarjeta").val(),
        token_id: token_id,
        amount: precio,
        nombre_real:nombre,
        email:email,
        servicio:id,
        description: description,
        deviceIdHiddenFieldName: deviceSessionId,
      },
      dataType: "json",
      beforeSend() {
        swal({
          title: "Cargando",
          showConfirmButton: false,
          imageUrl: "../img/loader.gif"
      });
        
      },
      success(data) {

        $("#card_payment")[0].reset();
        $("#modalTarjeta").modal("hide");
        window.location.replace("pagado.html?tipo=1");
      },
      error(error) {

      },
    });
  };
  
  var error_callbak = function (response) {
    var desc =
      response.data.description != undefined
        ? response.data.description
        : response.message;
        console.log("Pedos");
    $("#pay-button").prop("disabled", false);
  };
  function create_oxxo_pay(){
    var email=$("#email").val();
    var nombre=$("#nombre").val();
    $.ajax({
        url: server + "/WS/proccess_oxxo_pay",
        type: "post",
        data: {
          amount: precio,
          type:1,
          nombre:nombre,
          email:email,
          servicio:id,
          description: description
        },
        dataType: "json",
        beforeSend() {
          swal({
            title: "Cargando",
            showConfirmButton: false,
            imageUrl: "img/loader.gif"
        });
          
        },
        success(data) {
            window.open(data.url_recibo);
            window.location.replace("pagado.html?tipo=2");
        },
        error(error) {
  
        },
      });

  }