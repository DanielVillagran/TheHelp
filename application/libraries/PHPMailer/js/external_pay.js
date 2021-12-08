var server = "";
var username = "";
var userid = "";
var cuenta = 0;
var contador = 0;
var productos = 0;
var cantidades = 0;
var type = 0;
var descuento = 0;
var arreglo = [];
var arreglo2 = [];
var p_arre=[];
var paquetes = null;
var total_a = 0;
var kits = [];
var pagos = [];
var clientes = [];
var conta_kits = 0;
var deviceSessionId = "";
var tipo_clientes=0; 
var total_google=0;
$(document).ready(function() {
    $.ajax({
        url: server + "webserviceapp/get_info_sale.php",
        type: "POST",
        dataType: "json",
        data:{'venta_id':$("#venta_id").val()},
        beforeSend: function() {},
        success: function(data) {
            if(data!=null){
                if(data.is_payed==0){
                    $("#totalcarrito")
      .empty()
      .append("$" + parseFloat(data.total).toFixed(2));
                total_a=data.total;
            sale_modal();
                }else{
                    swal(
                        "Este venta ya ha sido pagada.",
                        "",
                        "error"
                      );
                }

            }else{
                swal(
                    "Este venta no ha sido encontrada.",
                    "",
                    "error"
                  );
            }

        }
    });
});



function get_actual_date() {
  var today = new Date();
  var dd = today.getDate();

  var mm = today.getMonth() + 1;
  var yyyy = today.getFullYear();
  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }
  var time =
    today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  today = dd + "/" + mm + "/" + yyyy;
  return today + " " + time;
}
function create_ticket(folio, cliente = null, total, recibido, cambio, tabla,referencia=null) {
  $("#fecha_ticket")
    .empty()
    .append(get_actual_date());
  $("#folio_ticket")
    .empty()
    .append(folio);
    console.log(referencia);
    if (referencia == null) {
      $("#referencia_ticket").hide();
    } else {
      $("#referencia_ticket").show();
      $("#referencia_cliente")
        .empty()
        .append(referencia);
    }
  if (cliente == null) {
    $("#cliente_ticket").hide();
  } else {
    $("#cliente_ticket").show();
    $("#nombre_cliente")
      .empty()
      .append(cliente.nombre);
    $("#telefono_cliente")
      .empty()
      .append(cliente.telefono);
  }
  $("#productos_ticket")
    .empty()
    .append(cantidades);
  $("#subtotal_ticket")
    .empty()
    .append("$" + addCommas(parseFloat(total * 0.84).toFixed(2)));
  $("#iva_ticket")
    .empty()
    .append("$" + addCommas(parseFloat(total * 0.16).toFixed(2)));
  $("#total_ticket")
    .empty()
    .append("$" + addCommas(parseFloat(total).toFixed(2)));
  $("#recibido_ticket")
    .empty()
    .append("$" + addCommas(parseFloat(recibido).toFixed(2)));
  $("#cambio_ticket")
    .empty()
    .append("$" + addCommas(parseFloat(cambio).toFixed(2)));
  $("#tabla_ticket > tbody")
    .empty()
    .append(tabla);
  var tipo_venta = $("#tipo_venta").val();
  if (tipo_venta != "" && tipo_venta != "0") {
    $("#row_precio_table").hide();
    $("#row_total_table").hide();
  } else {
    $("#row_precio_table").show();
    $("#row_total_table").show();
  }
  $("#sec-ticket").show();
  var htmlsource = $("#sec-ticket")[0];
  html2canvas(htmlsource, {
    onrendered: function(canvas) {
      var img = canvas.toDataURL("image/png");
      var doc = new jsPDF();
      doc.addImage(img, "JPEG", -35, -10);
      var pdf = doc.output("blob");
      $("#sec-ticket").hide();
      var data = new FormData();
      data.append("data", pdf);

      data.append("correo", cliente.correo);
      data.append("nombre", cliente.nombre + " " + cliente.apellidos);
      $.ajax({
        url: server + "webserviceapp/send_email.php",
        type: "post",
        data: data,
        contentType: false,
        processData: false,
        cache: false,

        success(data) {}
      });
    }
  });
}

function sale_modal() {
      var total = parseFloat(total_a);
      total_a = 0;
      $("#total_metodo_pago")
        .empty()
        .append("$" + addCommas(parseFloat(total).toFixed(2)));
      $("#total_a_metodo_pago")
        .empty()
        .append("$" + addCommas(parseFloat(total_a).toFixed(2)));
      $("#div_pago").hide();
      $("#tabla_metodos > tbody").empty();
      $("#modalPagar").modal("toggle");
      //efective_pay();
      total_google=total;
      transaccion_google={
        countryCode: 'MX',
        currencyCode: "MXN",
        totalPriceStatus: "FINAL",
        totalPrice: parseFloat(total).toFixed(2),
        totalPriceLabel: "Total"
      };
      paypal.Buttons({
        locale:'es-MX',
          style: {
              shape: 'rect',
              color: 'gold',
              layout: 'vertical',
              label: 'paypal',
              
          },
          createOrder: function(data, actions) {
              return actions.order.create({
                  purchase_units: [{
                      amount: {
                          value: total
                      }
                  }]
              });
          },
          onApprove: function(data, actions) {
              return actions.order.capture().then(function(details) {
                  //alert('Se ha completado la transacción ' + details.payer.name.given_name + '!');
                  check_quantities("Paypal", total);
              });
          }
      }).render('#paypal-button-container');
    
}
function check_quantities(tipo, cantidad_pago, tarjeta = "",referencia_id=null,referencia=null,tipo_referencia=null) {
  
  total_a += parseFloat(cantidad_pago);
  var auxiliar = $("#totalcarrito").html();
  auxiliar = auxiliar.replace("$", "");
  auxiliar = auxiliar.replace(",", "");
  var total = parseFloat(auxiliar);
  if (total <= total_a) {
    $("#div_pago").show();
  } else {
    $("#div_pago").hide();
  }
  $("#total_a_metodo_pago")
    .empty()
    .append("$" + addCommas(parseFloat(total_a).toFixed(2)));
  $("#tabla_metodos > tbody").append(
    "<tr><td>" +
      tipo +
      "</td><td>" +
      "$" +
      addCommas(parseFloat(cantidad_pago).toFixed(2)) +
      "</td><td>" +
      tarjeta +
      "</td></tr>"
  );
  pagos.push({ tipo_pago: tipo, cantidad_pago: cantidad_pago,referencia_id:referencia_id ,referencia:referencia,tipo_referencia:tipo_referencia  });
}
function efective_pay() {
  $("#modalPagar").modal("hide");
  swal({
    type: "info",
    title: "<p id='prealizarventa'>Realizar Cobro en Efectivo</i>",
    html: "<p id='psswal'>¿Cantidad recibida?</p>",
    input: "number",
    confirmButtonText: "Aceptar",
    showCancelButton: true,
    cancelButtonText: "Cancelar"
  }).then(result => {
    $("#modalPagar").modal("toggle");
    if (result.value) {
      var inputValue = result.value;

      check_quantities("Efectivo", inputValue);
    }
  });
}
function card_pay() {
  //$("#modalPagar").modal("hide");
  $("#modalGenerarPagoTarjeta").modal("toggle");
}
$("#add_clients_form").submit(function(event) {
  event.preventDefault();
  var data = $("#add_clients_form").serializeArray();
  $.ajax({
    url: server + "webserviceapp/registrar_cliente.php",
    type: "post",
    data: data,
    dataType: "json",
    beforeSend() {
      swal({
        title: "Cargando...",
        showConfirmButton: false,
        imageUrl: "resources/loader.gif"
      });
    },
    success(data) {
      if (data.result) {
        swal(
          "<p id='pswal'> Exito </p>",
          "<p id='psswalerror'>" + data.mensaje + ".</p> ",
          "success"
        );
        $("#exampleModalCenter").modal("hide");
        $("#add_clients_form")[0].reset();
        get_clientes_info();
      } else {
        swal(
          "<p id='pswalerror'> Error </p>",
          "<p id='psswalerror'>" + data.mensaje + ".</p> ",
          "error"
        );
      }
    },
    error(error) {}
  });
});
$("#pago_tarjeta").submit(function(event) {
  event.preventDefault();
  check_quantities("Tarjeta", $("#cantidad_tarjetas").val());
  $("#modalGenerarPagoTarjeta").modal("hide");
  $("#pago_tarjeta")[0].reset();
});
$("#card_payment").submit(function(event) {
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
var sucess_callbak = function(response) {
  var token_id = response.data.id;
  $("#token_id").val(token_id);
  $.ajax({
    url: server + "webserviceapp/process_pay.php",
    type: "post",
    data: {
      nombre: $("#nombre_tarjeta").val(),
      token_id: token_id,
      amount: cantidad_tarjeta,
      description: "Esta es solo prueba",
      deviceIdHiddenFieldName: deviceSessionId
    },
    dataType: "html",
    beforeSend() {
      swal({
        title: "Cargando...",
        showConfirmButton: false,
        imageUrl: "resources/loader.gif"
      });
    },
    success(data) {
      swal.close();
      check_quantities(
        "Terminal Electronica",
        cantidad_tarjeta,
        numero_tarjeta
      );
      $("#card_payment")[0].reset();
      $("#modalTarjeta").modal("hide");
    },
    error(error) {
      swal(
        "<p id='pswalerror'> Error </p>",
        "<p id='psswalerror'>El pago no ha podido ser procesado. Intente de nuevo, por favor.</p> ",
        "error"
      );
    }
  });
};

var error_callbak = function(response) {
  var desc =
    response.data.description != undefined
      ? response.data.description
      : response.message;
  swal(
    "<p id='pswalerror'> Error </p>",
    "<p id='psswalerror'>Los datos de la tarjeta son incorrectos.</p> ",
    "error"
  );
  $("#pay-button").prop("disabled", false);
};

function sale() {
    pagos.forEach(function(element, index) {
    $.ajax({
      url: server + "webserviceapp/sale_pagos.php",
      type: "post",
      async: false,
      data: {
        tipo_pago: element.tipo_pago,
        cantidad: element.cantidad_pago,
        referencia: element.referencia_id,
        venta: $("#venta_id").val()
      },
      dataType: "html",
      success() {}
    });
});
$.ajax({
    url: server + "webserviceapp/update_ventas.php",
    type: "post",
    async: false,
    data: {
      venta: $("#venta_id").val()
    },
    dataType: "html",
    success() {}
  });
swal({
    type: "success",
    title:  "<p id='pswal'>Pago Recibido</p>",
    html:  "<p id='psswal'> El pago por la cantidad de: <br> <b id='psbswal'>$" +
    addCommas(total_a) +
    ".<sup id='supswal'>00</sup></b> ha sido recibido con exito.</p>",
    confirmButtonText: "Aceptar",
    showCancelButton: false,
    cancelButtonText: "Cancelar"
  }).then(result => {
    location.reload();
  });

  
}
document.getElementById("close-window").addEventListener("click", function(){ 
    window.close();
});

$(".swal2-input").on("input", function() {
  this.value = this.value.replace(/[^0-9]/g, "");
});

function addCommas(nStr) {
  nStr += "";
  var x = nStr.split(".");
  var x1 = x[0];
  var x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}
function logout() {
  window.location.replace("index.html");
}
function iniciovender() {
  window.location.href =
    "vender.html?id=" + userid + "&name=" + username + "&stock=" + stock;
}
function inicioventas() {
  window.location.href =
    "ventas.html?id=" + userid + "&name=" + username + "&stock=" + stock;
}

function iniciodevoluciones() {
  window.location.href =
    "devoluciones.html?id=" + userid + "&name=" + username + "&stock=" + stock;
}
function inicioinicio() {
  window.location.href =
    "inicio.html?id=" + userid + "&name=" + username + "&stock=" + stock;
}



//Aqui empieza  google
var transaccion_google;
const baseRequest = {
  apiVersion: 2,
  apiVersionMinor: 0
};

const allowedCardNetworks = ["AMEX", "DISCOVER", "INTERAC", "JCB", "MASTERCARD", "VISA"];
const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];
const tokenizationSpecification = {
  type: 'PAYMENT_GATEWAY',
  parameters: {
    'gateway': 'example',
    'gatewayMerchantId': 'exampleGatewayMerchantId'
  }
};

const baseCardPaymentMethod = {
  type: 'CARD',
  parameters: {
    allowedAuthMethods: allowedCardAuthMethods,
    allowedCardNetworks: allowedCardNetworks
  }
};

/**
 * Describe your site's support for the CARD payment method including optional
 * fields
 *
 * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters}
 */
const cardPaymentMethod = Object.assign(
  {},
  baseCardPaymentMethod,
  {
    tokenizationSpecification: tokenizationSpecification
  }
);

let paymentsClient = null;

function getGoogleIsReadyToPayRequest() {
  return Object.assign(
      {},
      baseRequest,
      {
        allowedPaymentMethods: [baseCardPaymentMethod]
      }
  );
}
function getGooglePaymentDataRequest() {
  const paymentDataRequest = Object.assign({}, baseRequest);
  paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
  paymentDataRequest.transactionInfo = getGoogleTransactionInfo();
  paymentDataRequest.merchantInfo = {
    merchantName: 'Productos Power Golden'
  };

  paymentDataRequest.callbackIntents = ["PAYMENT_AUTHORIZATION"];

  return paymentDataRequest;
}
function getGooglePaymentsClient() {
  if ( paymentsClient === null ) {
    paymentsClient = new google.payments.api.PaymentsClient({
        environment: 'TEST',
      paymentDataCallbacks: {
        onPaymentAuthorized: onPaymentAuthorized
      }
    });
  }
  return paymentsClient;
}

function onPaymentAuthorized(paymentData) {
  return new Promise(function(resolve, reject){
    // handle the response
    processPayment(paymentData)
      .then(function() {
		resolve({transactionState: 'SUCCESS'});
		console.log("Pedos");
		check_quantities("Google", total_google);
      })
      .catch(function() {
        resolve({
          transactionState: 'ERROR',
          error: {
            intent: 'PAYMENT_AUTHORIZATION',
            message: 'Insufficient funds, try again. Next attempt should work.',
            reason: 'PAYMENT_DATA_INVALID'
          }
        });
	    });
  });
}

function onGooglePayLoaded() {
  const paymentsClient = getGooglePaymentsClient();
  paymentsClient.isReadyToPay(getGoogleIsReadyToPayRequest())
    .then(function(response) {
      if (response.result) {
        addGooglePayButton();
      }
    })
    .catch(function(err) {
      // show error in developer console for debugging
      console.error(err);
    });
}

function addGooglePayButton() {
  const paymentsClient = getGooglePaymentsClient();
  const button =
      paymentsClient.createButton({onClick: onGooglePaymentButtonClicked});
  document.getElementById('container').appendChild(button);
}

function getGoogleTransactionInfo() {
	
  return transaccion_google;
}

function onGooglePaymentButtonClicked() {
  const paymentDataRequest = getGooglePaymentDataRequest();
  paymentDataRequest.transactionInfo = getGoogleTransactionInfo();

  const paymentsClient = getGooglePaymentsClient();
  paymentsClient.loadPaymentData(paymentDataRequest);
}

let attempts = 0;

function processPayment(paymentData) {
  return new Promise(function(resolve, reject) {
    setTimeout(function() {
      // @todo pass payment token to your gateway to process payment
      paymentToken = paymentData.paymentMethodData.tokenizationData.token;

			if (attempts++ % 2 == 0) {
	      reject(new Error('Every other attempt fails, next one should succeed'));      
      } else {
	      resolve({
			 
		  });      
      }
    }, 500);
  });
}