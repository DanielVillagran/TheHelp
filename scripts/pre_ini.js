var server = 'https://thehelp.ddns.net/';
var user_id = null;
var cache_s = {};
var resultado = null;
var id=0;
$(document).ready(function() {
    var datos=window.location.href;
    datos=datos.split("?")[1];
    get_info_clients(datos);
    
});
function get_info_clients(token) {
    $.ajax({
        type: "post",
        data: {
            'cliente': token
        },
        url: "functions.php?function=get_info_client",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#nombre_comercial").empty().append(data.n_comercial);
            id=data.id;
            if(data.answer_date==null){
                get_data_encuesta();
            }else{
                $("#contestado").show();
            }
        }
    });
}
$("#look_at").click(function() {
    $.ajax({
        type: "post",
        data: {
            'name': $('#look').val()
        },
        url: "functions.php?function=search_by_name",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            for (var key in data) {
                $("#" + key).empty().append(data[key]);
            }
        }
    });
});


function get_data_encuesta() {
    $("#encuesta").show();
    $.material.init();
    var myCss = {
        matrix: {
            root: "table table-striped"
        },
        navigationButton: "button btn-lg"
    };
    Survey.defaultBootstrapMaterialCss.navigationButton = "btn btn-green";
    Survey.defaultBootstrapMaterialCss.rating.item = "btn btn-default my-rating";
    Survey.StylesManager.applyTheme("bootstrapmaterial");
    $.ajax({
        type: "post",
        url: "functions.php?function=construct_encuesta",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            var json = {
                completeText: 'Terminar',
                pages: [{
                    questions: data,
                    requiredErrorText: 'Por favor, contesta todas las preguntas de esta sección'
                }]
            };
            $('html, body').animate({
                scrollTop: $("#title_encuesta").offset().top
            }, 2000);
            window.survey = new Survey.Model(json);
            survey.onComplete.add(function(result) {
                console.log(JSON.stringify(result.data, null, 3));
                $("#title_encuesta").empty().append("Listo!");
                resultado = result;
                save_encuesta_data();
            });
            $("#surveyElement").Survey({
                locale: 'es',
                model: survey,
                css: myCss
            });
        }
    });
}

function save_encuesta_data() {
    $.ajax({
        type: "post",
        data: {
            'elements': resultado.data,
            'encuesta_id': id
        },
        url: "functions.php?function=send_encuesta",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {

        }
    });
}