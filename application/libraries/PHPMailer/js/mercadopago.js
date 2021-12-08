window.Mercadopago.setPublishableKey("TEST-e9fef8ff-e1a9-49ae-ac0a-3c6e7104bc7e");
    
window.Mercadopago.getIdentificationTypes();

document.getElementById('cardNumber').addEventListener('keyup', guessPaymentMethod);
document.getElementById('cardNumber').addEventListener('change', guessPaymentMethod);

function guessPaymentMethod(event) {
    let cardnumber = document.getElementById("cardNumber").value;

    if (cardnumber.length >= 6) {
        let bin = cardnumber.substring(0,6);
        window.Mercadopago.getPaymentMethod({
            "bin": bin
        }, setPaymentMethod);
    }
};

function setPaymentMethod(status, response) {
    if (status == 200) {
        let paymentMethodId = response[0].id;
        let element = document.getElementById('payment_method_id');
        element.value = paymentMethodId;
        getInstallments();
    } else {
        alert(`payment method info error: ${response}`);
    }
}

function getInstallments(){
    window.Mercadopago.getInstallments({
        "payment_method_id": document.getElementById('payment_method_id').value,
        "amount": parseFloat(document.getElementById('transaction_amount').value)
        
    }, function (status, response) {
        if (status == 200) {
            document.getElementById('installments').options.length = 0;
            response[0].payer_costs.forEach( installment => {
                let opt = document.createElement('option');
                opt.text = installment.recommended_message;
                opt.value = installment.installments;
                document.getElementById('installments').appendChild(opt);
            });
        } else {
            alert(`installments method info error: ${response}`);
        }
    });
}    

$("#pay").submit(function(event) {
event.preventDefault();
   
        var $form = document.querySelector('#pay');

        window.Mercadopago.createToken($form, sdkResponseHandler);

        return false;
    
});



function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
        alert("Verifica los datos");
    }else{
        var form = document.querySelector('#pay');
        var card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        form.appendChild(card);
        doSubmit=true;
        //form.submit();
        $.ajax({
            url: server + "webserviceapp/create_payment_mp.php",
            type: "post",
            data: $("#pay").serializeArray(),
            dataType: "html",
            beforeSend() {
              swal({
                title: "Cargando...",
                showConfirmButton: false,
                imageUrl: "resources/loader.gif"
              });
            },
            success(data) {
                console.log(data);
                swal.close();
              /*check_quantities(
                "Terminal Electronica",
                cantidad_tarjeta,
                numero_tarjeta
              );
              $("#card_payment")[0].reset();
              $("#modalTarjeta").modal("hide");*/
            },
            error(error) {
              swal(
                "<p id='pswalerror'> Error </p>",
                "<p id='psswalerror'>El pago no ha podido ser procesado. Intente de nuevo, por favor.</p> ",
                "error"
              );
            }
          });
    }
};