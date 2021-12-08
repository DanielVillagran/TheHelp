var Alerts = function(mensaje, exito, time) {

    if (time) {
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").fadeOut();
            }, time);
        });
    }

    var tipo_alert = "";
    var icono = "";

    if (exito == 1) {
        tipo_alert = "alert-success";
        icono = "fa-check";
    }

    if (exito == 0) {
        tipo_alert = "alert-danger";
        icono = "fa-close";
    }

    if (exito == 2) {
        tipo_alert = "alert-warning";
        icono = "fa-exclamation";
    }

    if (exito == 3) {
        tipo_alert = "alert-info";
        icono = "fa-exclamation";
    }

    var template = "";
    template += "<div class='alert " + tipo_alert + " fade in'>";
        template += "<span class='close' data-dismiss='alert'>×</span>";
        template += "<i class='fa " + icono + " fa-2x pull-left'></i>";
        template += "<p>" + mensaje + "</p>";
    template += "</div>";
    return template;
};

function soloNumeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    //patron =/[0-9]/;
    patron = /[0-9-.]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

var loadViews = function(id, vista, donde) {
    $.get('./assets/ajax/include/'+ donde +'/' + vista + '.php', function(htmlexterno) {
        $("#" + id).html(htmlexterno);
        $("#" + id).removeClass("hidden");
    });
}

function getRandomInt() {
    return new Date().getTime();
}

function number_format(amount, decimals) {

    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0)
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

    return amount_parts.join('.');
}

function getCurrentDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    }

    if(mm<10) {
        mm = '0'+mm
    }

    today = dd + '-' + mm + '-' + yyyy;
    return today;
}

function Selects(Id, Datos, nu) {
	nu = nu || false; // Es para mostrar o no un campo vacio en la lista
    if (Datos) {
        var select = "";

        $("#"+Id).selectpicker('destroy');
        $("#"+Id).empty();

        if (!nu) {
            select += '<option value=""></option>';
        }

        for (var x = 0; x < Datos[0].length; x++) {
            var id      = Datos[0][x][0];
            var valor   = Datos[1][x][0];
            select += "<option value='" + id + "'>" + valor + "</option>";
        }

        $("#"+Id).append(select);
        $("#"+Id).attr("title", "");

        $("#"+Id).selectpicker({
            style : 'form-control',
            size : 10
        });

        select = null;
    } else {
        $("#"+Id).attr("title", "Sin registros");
        $("#"+Id).prop("disabled", true);
    }
}

function SelectsGO(Id, Datos, nu) {
	nu = nu || false; // Es para mostrar o no un campo vacio en la lista
    if (Datos) {
    	
    	$("#"+Id).html('');
    	
        var select = "";

        if (!nu) {
            select += '<option value=""></option>';
        }

        for (var x = 0; x < Datos[0].length; x++) {
            var id      = Datos[0][x][0];
            var valor   = Datos[1][x][0];
            select += "<option value='" + id + "'>" + valor + "</option>";
        }

        $("#"+Id).append(select);
        $("#"+Id).attr("title", "");


        select = null;
    } else {
        $("#"+Id).attr("title", "Sin registros");
        $("#"+Id).prop("disabled", true);
    }
}

function cargarContenido(div, URL) {
    Pace.restart();
    $(div).load(URL);
}

function abrirModulo(Departamento, Modulo, Submodulo) {
    cargarContenido(("#ajax-content"), "./assets/ajax/include/" + Departamento + "_" + Modulo + "_" + Submodulo + "/index.php");
}

function validarFuncion(func) {
    var operadores  = ['+', '-', '*'];
    var contenedor  = ['(', ')'];

    /*
        Condiciones

            .- Después de cada operador siempre va una variable. [+variable]
            .- Ántes de cada operador siempre va una variable. [variable+]
            .- Después de cada variable siempre va o un operador o un contenedor
            .- Después de cada inicio de contenedor siempre va una variable u otro inicio de contenedor. [(variable] [((variable]
                .- Por cada inicio de un contenedor debe de haber un final. O lo que es lo mismo, el contador de los mismos debe ser un número par. [()][(())]...[...]

            Cualquiera de lo anterior que no se cumpla: La función está mal construida.

    */

    funcionPorPartes = func.split("|");
    var posicion = 0;
    var o = 0, c = 0, v = 0;
    var acum = "";
    var status = true;
    funcionPorPartes.forEach(function(element) {
        if (element != "") {
            ////console.log(element); // El valor en si.

            if (operadores.includes(element)) {
                ++ o;
            } else {
                o = 0;
            }

            if (contenedor.includes(element)) {
                ++ c;
            } else {
                c = 0;
            }

            if (!operadores.includes(element) && !contenedor.includes(element)) {
                ++ v;
            } else {
                v = 0;
            }

            if (o > 1 || c > 1 || v > 1) { // Cuando el mismo tipo se repite seguido
                status = false;
            }


            alert(o+" "+c+" "+v);

            ++ posicion;
        }
    });

    return status;
}

function removeItemFromArr ( arr, item ) {
    var i = arr.indexOf( item );

    if ( i !== -1 ) {
        arr.splice( i, 1 );
    }
}

function validarCampo(id, tipo, que, donde) { // * las expresiones regulares pueden mejorar
    var text    = "es requerido";
    var text2   = "está mal construido";

    var success = false;

    var valor   = document.getElementById(id).value;

    if(valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
        success = null;
        $("#"+donde+" .panel-body").prepend(Alerts("["+que+"] "+text, 2, 3000));
    } else {
        if (tipo == 1) { // Enteros
            if (!isNaN(valor)){
                success = valor;
            }
        } else if (tipo == 2) { // Correo
            if((/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(valor))) {
                success = valor;
            }
        } else if (tipo == 3) { // Caracteres Especiales
            if(!(/[\^@;&*+?=!|\\/()\[\]{}]/.test(valor))) {
                success = valor;
            }
        } else if (tipo == 4) { // Teléfono
            if((/^\(\d{3}\)\s\d{8}$/.test(valor))) {
                success = valor;
            }
        }
        if (!success) {
            $("#"+donde+" .panel-body").prepend(Alerts("["+que+"] "+text2, 2, 3000));
        }
    }
    campos.push({[que + " = " + valor] : success});
    return success;
}

function valida(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    //patron =/[0-9]/;
    patron = /[0-9-.]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
