
var cache = {};
var total_efectivo_final = 0;
var total_final = 0;

$(document).ready(function () {
    $( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
      if( jqxhr.status >= 400 || jqxhr.status == 0)
        swal(jqxhr.status == 0 ? "No hay conexión a internet." : jqxhr.statusText, "", "warning")
    });
    "use strict";
    // Init Theme Core    
    Core.init();

    $("#membresia").autocomplete({
        minLength: 2,
        select: function (event, ui) {
            $("#my_membresia_id").val(ui.item.id);
        },
        source: function (request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[ term ]);
                return;
            }
            $.ajax({
                url: '/reports/get_member_autocomplete',
                type: 'POST',
                data: {'search': term},
                dataType: 'json',
                beforeSend: function (e) {
                    $("#my_membresia_id").val('');
                },
                success: function (data) {
                    cache[ term ] = data;
                    response(data);
                }
            });
        }
    });

    var id;
        $.ajax({
            url: '/support/get_row_tickets_reports',
            type: 'POST',
            data: {'search': $.trim($('#filter').val()),fin_ba:$("#fin_ba").val(),inicio_ba:$("#inicio_ba").val()},
            dataType: 'json',
            beforeSend: function (e) {
                $('#leads_grid tbody').empty().append('<tr><td colspan="7">PROCESANDO...</td></tr>');
            },
            success: function (data) {
                $('#leads_grid tbody').empty().append(data).trigger('footable_redraw');
                $('#leads_grid').show();
            }
        });
    
    $('#corte_general_membresia').footable();
    $('#corte_general_consumibles').footable();

    $("#estadocuenta").on('click', function () {
        //id = $('#membresia option:selected').attr('value');
        id = $("#my_membresia_id").val();
        var fechaI = $('#fechaI_edo_cuenta').val();
        var fechaF = $('#fechaF_edo_cuenta').val();
        var valid = valida_edo_cuenta(id, fechaI, fechaF);
        if (valid[0]) {
            window.location.href = "/reports/edocuenta/" + id + "/" + fechaI + "/" + fechaF;
        } else {
            swal(valid[1], '', "error");
        }
    });

    $("#carteraVencida").on('click', function () {
        var fechaI = $('#fechaI_cartera').val();
        var fechaF = $('#fechaF_cartera').val();
        var ant_account = $('#ant_account_cartera').val();

        var valid = valida_carteraVencida(fechaI, fechaF);
        if (valid[0]) {
            window.location.href = "/reports/cartera_vencida/" + fechaI + "/" + fechaF + "/" + ant_account;
        } else {
            swal(valid[1], '', "error");
        }
    });

    $("#vencidos_pagados_button").on('click', function () {
        var fechaI = $('#inicio_vp').val();
        var fechaF = $('#fin_vp').val();
        var ant_account = $('#ant_account_vencidos').val();

        var valid = valida_vencidos_paga2(fechaI, fechaF);
        if (valid[0]) {
            window.location.href = "/reports/vencidos/" + fechaI + "/" + fechaF + "/" + ant_account;
        } else {
            swal(valid[1], '', "error");
        }
    });
    $("#bajas_button").on('click', function () {
        var fechaI = $('#inicio_ba').val();
        var fechaF = $('#fin_ba').val();
        var ant_account = $('#ant_account_ba').val();

        var valid = valida_vencidos_paga2(fechaI, fechaF);
        if (valid[0]) {
            window.location.href = "/reports/bajas/" + fechaI + "/" + fechaF + "/" + ant_account;
        } else {
            swal(valid[1], '', "error");
        }
    });
    $("#btn_reporte_quejas").on('click', function () {
        var fechaI = $('#period_start_quejas').val();
        var fechaF = $('#period_end_quejas').val();
        var ant_account = $('#ant_account_quejas').val();

        var valid = valida_vencidos_paga2(fechaI, fechaF);
        if (valid[0]) {
            window.location.href = "/reports/quejas/" + fechaI + "/" + fechaF + "/" + ant_account;
        } else {
            swal(valid[1], '', "error");
        }
    });
    
    var valor = $('#account').val();
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_termino = $('#fecha_final').val();

    var fechai_Cartera = $('#fechaI_cartera').val();
    var fechaf_Cartera = $('#fechaF_cartera').val();
    var ant_account_cartera = $('#ant_account_cartera').val() == undefined ? '' : $('#ant_account_cartera').val();

    var inicio_vp = $('#inicio_vp').val();
    var fin_vp = $('#fin_vp').val();
    var ant_account_vencidos = $('#ant_account_vencidos').val() == undefined ? '' : $('#ant_account_vencidos').val();
    var inicio_ba = $('#inicio_ba').val();
    var fin_ba = $('#fin_ba').val();
    var ant_account = $('#ant_account').val() == undefined ? '' : $('#ant_account').val();

    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm == 1) {
        mm = 'Enero';
    } else if (mm == 2) {
        mm = 'Febrero';
    } else if (mm == 3) {
        mm = 'Marzo';
    } else if (mm == 4) {
        mm = 'Abril';
    } else if (mm == 5) {
        mm = 'Mayo';
    } else if (mm == 6) {
        mm = 'Junio';
    } else if (mm == 7) {
        mm = 'Julio';
    } else if (mm == 8) {
        mm = 'Agosto';
    } else if (mm == 9) {
        mm = 'Septiembre';
    } else if (mm == 10) {
        mm = 'Octubre';
    } else if (mm == 11) {
        mm = 'Noviembre';
    } else if (mm == 12) {
        mm = 'Diciembre';
    }
    var fechahoy = dd + ' ' + mm + ' ' + yyyy;

    $.ajax({
        url: '/reports/get_edocuenta',
        type: 'POST',
        data: {'id_contact': valor},
        dataType: 'json',
        beforeSend: function () {
        },
        success: function (data) {
            if (data.result) {
                $("#fechahoy").append(fechahoy);
                $("#sta").append(data.info.st);

                var div_dir = '<strong>Nombre: </strong>' + data.info.nombre + '<br>';
                div_dir += '<strong>Sucursal: </strong>' + data.info.sucursal + '<br>';
                div_dir += '<strong>Razón Social: </strong>' + data.info.razon_social + '<br>';
                div_dir += '<strong>RFC: </strong>' + data.info.rfc + '<br>';

                div_dir += 
                    '<strong>Calle: </strong>' + data.info.calle + '<br>' +
                    '<strong> No Exterior: </strong>' + data.info.noexterior +
                    '<strong> No Interior: </strong>' + data.info.nointerior + '<br>' +
                    '<strong> Colonia: </strong>' + data.info.colonia +
                    '<strong> Código Postal: </strong>' + data.info.codigopostal + '<br>' +
                    '<strong> Municipio: </strong>' + data.info.localidad +
                    '<strong> Estado: </strong>' + data.info.estado +
                    '<strong> País: </strong>' + data.info.pais + '<br>'
                ;
                div_dir += '<abbr title="Phone" ><strong>Telefono: </strong></abbr>' + data.info.number + '';

                $("#direccion").append(div_dir);
                var div_membresia_id = '<span class="panel-title"><i class="fa fa-certificate"></i> Membresia: ' + data.info.folio + '</span>';
                $("#membresia_id").append(div_membresia_id);
                var div_datos_mem = '<ul class="list-unstyled">';
                div_datos_mem += '<li>';
                div_datos_mem += '<b>Concepto:</b> ' + data.info.st + '</li>';
                div_datos_mem += '<b>Fecha Inicio Contrato:</b>' + data.info.diaef + ' ' + data.info.mesef + ' ' + data.info.anoef + '</li>';
                div_datos_mem += '<li>';
                //div_datos_mem += '<b>Fecha Fin Contrato:</b>' + data.info.diaFC + ' ' + data.info.mesFC + ' ' + data.info.anioFC + '</li>';
                div_datos_mem += '<b>Fecha Fin Contrato:</b>' + data.info.fin_contrato + '</li>';
                div_datos_mem += '<li>';
                var fecha = data.info.inicio;
                var diaprox = fecha.substr(8, 7);
                var mp = fecha.substr(5, 2);
                var mesprox = parseInt(mp) + 1;
                if (mesprox == 1) {
                    mesprox = 'Enero';
                } else if (mesprox == 2) {
                    mesprox = 'Febrero';
                } else if (mesprox == 3) {
                    mesprox = 'Marzo';
                } else if (mesprox == 4) {
                    mesprox = 'Abril';
                } else if (mesprox == 5) {
                    mesprox = 'Mayo';
                } else if (mesprox == 6) {
                    mesprox = 'Junio';
                } else if (mesprox == 7) {
                    mesprox = 'Julio';
                } else if (mesprox == 8) {
                    mesprox = 'Agosto';
                } else if (mesprox == 9) {
                    mesprox = 'Septiembre';
                } else if (mesprox == 10) {
                    mesprox = 'Octubre';
                } else if (mesprox == 11) {
                    mesprox = 'Noviembre';
                } else if (mesprox == 12) {
                    mesprox = 'Diciembre';
                }
                div_datos_mem += '<b>Fecha Proximo Pago:</b> ' + diaprox + ' ' + mesprox + ' ' + yyyy + '</li>';
                div_datos_mem += '<li>';
                div_datos_mem += '<b>Tipo Membresia:</b> ' + data.info.tipoMembresia + '</li>';
                div_datos_mem += '<li>';
                div_datos_mem += '<b>Condición de Pago:</b> ' + data.info.condicion + '</li>';
                div_datos_mem += '</ul>';
                $("#datos_mem").append(div_datos_mem);
            } else {

            }
        }
    });

    reporte_general();

    function reporte_general() {
        //$('#corte_general_membresia').footable();
        var pathname = window.location.pathname;
        var pieces = pathname.split("/");
        var vendedor = pieces[3];
        
        var fein = pieces[5];
        var fefin = pieces[6];

        var prod = pieces[4];
        var consumible = pieces[7];

        var ant_account = pieces[8];

        var cajero = pieces[9];

        $("#invoice-summary-total").hide();
        $("#invoice-summary-membresias").hide();
        $("#invoice-summary-consumibles").hide();

        $("#corte_general_consumibles").hide();
        $("#head_cons").hide();

        $("#corte_general_membresia").hide();
        $("#head_mem").hide();

        if (prod != 'none'){
            membresias_general_ajax(
                vendedor, fein, fefin, prod, consumible, total_efectivo_final, total_final, ant_account, cajero
            );
        }
        else{
            consumibles_general_ajax(
                vendedor, fein, fefin, prod, consumible, total_efectivo_final, total_final, ant_account, cajero
            );
        }
    }

    function membresias_general_ajax(vendedor, fein, fefin, prod, consumible, total_efectivo_final, total_final, sucursal, cajero){
        
        $("#corte_general_membresia").show();
        $("#head_mem").show();
        $("#invoice-summary-membresias").show();

        $.ajax({
            url: '/reports/get_corte_general',
            type: 'POST',
            data: {vendedor: vendedor, prod: prod, fein: fein, fefin: fefin, sucursal: sucursal, cajero: cajero},
            dataType: 'json',
            beforeSend: function (e) {
            },
            success: function (data) {
                if (data) {
                    $('#corte_general_membresia tbody').empty().append(data['table']).trigger('footable_redraw');
                    $('#corte_general_membresia').show();
                    
                    $('#total_general').html('$ ' + data['total']);

                    $('#total_ef').html('$ ' + data['total_metodo1']);
                    $('#total_ch').html('$ ' + data['total_metodo2']);
                    $('#total_tr').html('$ ' + data['total_metodo3']);
                    $('#total_tc').html('$ ' + data['total_metodo4']);
                    $('#total_tb').html('$ ' + data['total_metodo5']);
                    $('#total_ot').html('$ ' + data['total_metodo6']);

                    $('#total_ch_final').html('$ ' + data['total_metodo2']);
                    $('#total_tr_final').html('$ ' + data['total_metodo3']);
                    $('#total_tc_final').html('$ ' + data['total_metodo4']);
                    $('#total_tb_final').html('$ ' + data['total_metodo5']);
                    $('#total_ot_final').html('$ ' + data['total_metodo6']);

                    total_efectivo_final += data['efectivo1'];
                    total_final += data['total_float'];

                    if (consumible != 'none'){
                        consumibles_general_ajax(vendedor, fein, fefin, prod, consumible, total_efectivo_final, total_final, sucursal, cajero);
                    }
                }
            }
        });
    }

    function consumibles_general_ajax(vendedor, fein, fefin, prod, consumible, total_efectivo_final, total_final, sucursal, cajero){
        
        $("#corte_general_consumibles").show();
        $("#head_cons").show();
        $("#invoice-summary-consumibles").show();

        $.ajax({
            url: '/reports/get_corte_general_consumibles',
            type: 'POST',
            data: {vendedor: vendedor, consumible: consumible, fein: fein, fefin: fefin, sucursal: sucursal},
            dataType: 'json',
            beforeSend: function (e) {
                $('#corte_general_consumibles tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
            },
            success: function (data) {
                if (data) {
                    $('#corte_general_consumibles tbody').empty().append(data['table']).trigger('footable_redraw');
                    $('#corte_general_consumibles').show();
                    $('#total_general2').html('$ ' + data['total']);

                    $('#total_ef_c').html('$ ' + data['total']);

                    if (prod != 'none'){
                        total_efectivo_final += data['efectivo2'];
                        total_final += data['efectivo2'];

                        var texto = total_efectivo_final.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                        texto = texto.replace('$', '$ ');

                        $('#total_ef_final').html(texto);

                        ////////////////////////////////////////////

                        var texto1 = total_final.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                        texto1 = texto1.replace('$', '$ ');

                        $('#total_final').html(texto1);

                        $("#invoice-summary-total").show();
                    }
                }
            }
        });
    }

    $.ajax({
        url: '/reports/get_products',
        type: 'POST',
        data: 'id_contact=' + valor + '&fecha_inicio=' + fecha_inicio + '&fecha_final=' + fecha_termino,
        dataType: 'json',
        beforeSend: function (e) {
            $('#produc tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
        },
        success: function (data) {
            $('#produc tbody').empty().append(data['table']).trigger('footable_redraw');
            $('#totall').html('$ ' + data['total']);
            $('#pagado').html('$ ' + data['pagado']);
            $('#facturado').html('$ ' + data['facturado']);
            $('#bVencido').html('$ ' + data['bVencido']);
            $('#bTotal').html('$ ' + data['bTotal']);
        }
    });

    $.ajax({
        url: '/reports/get_cartera_vencida',
        data: 'inicio_cartera=' + fechai_Cartera + '&fin_cartera=' + fechaf_Cartera + '&sucursal=' + ant_account_cartera,
        type: 'POST',
        dataType: 'json',
        beforeSend: function (e) {
            $('#cartera_vencida tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
        },
        success: function (data) {
            $('#cartera_vencida tbody').empty().append(data['table']).trigger('footable_redraw');
            $('#total').html('$ ' + data['total']);
        }
    });

    $.ajax({
        url: '/reports/get_vencidos',
        data: 'inicio_vp=' + inicio_vp + '&fin_vp=' + fin_vp + '&sucursal=' + ant_account_vencidos,
        type: 'POST',
        dataType: 'json',
        beforeSend: function (e) {
            $('#vencidos tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
        },
        success: function (data) {
            $('#vencidos tbody').empty().append(data['table']).trigger('footable_redraw');
            $('#total_v').html('$ ' + data['total']);
        }
    });
    $.ajax({
        url: '/reports/get_bajas',
        
        type: 'POST',
        data: {'inicio_ba' : $("#inicio_ba").val(),  'fin_ba': $("#fin_ba").val(), sucursal:$("#sucursal").val() },
        dataType: 'json',
        beforeSend: function (e) {
            $('#bajas tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
        },
        success: function (data) {
            $('#bajas tbody').empty().append(data['table']).trigger('footable_redraw');
        }
    });
     
    // CONTEO

    $('#btn_conteo').on('click', function (e) {
        $('#frm_reporte').attr('action', '/reports/conteo_membresias');
        $('#frm_reporte').removeAttr('target');
        $('#frm_reporte').empty().
            append('<input type="hidden" name="sucursal" value="' + $("#ant_account_conteo").val() + '" />').
            append('<input type="hidden" name="servicio" value="' + $("#tipo_servicio").val() + '" />');
        $('#frm_reporte').submit();
    });

    $('#btn_conteo_pdf').on('click', function (e) {
        $('#frm_reporte').attr('action', '/reports/conteo_membresias_pdf');
        $('#frm_reporte').removeAttr('target');
        $('#frm_reporte').attr({"target" : "_blank"});
        $('#frm_reporte').empty().
            append('<input type="hidden" name="sucursal" value="' + $("#ant_account_conteo").val() + '" />').
            append('<input type="hidden" name="servicio" value="' + $("#tipo_servicio").val() + '" />');
        $('#frm_reporte').submit();
    });

    $('#btn_conteo_csv').on('click', function (e) {
        $('#frm_reporte').attr('action', '/reports/conteo_membresias_csv');
        $('#frm_reporte').removeAttr('target');
        $('#frm_reporte').empty().
            append('<input type="hidden" name="sucursal" value="' + $("#ant_account_conteo").val() + '" />').
            append('<input type="hidden" name="servicio" value="' + $("#tipo_servicio").val() + '" />');
        $('#frm_reporte').submit();
    });

    // FALTAS

    $('#btn_faltas').on('click', function (e) {
        var valid = valida_faltas();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else {
            $('#frm_reporte').attr('action', '/reports/faltas_membresias');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_faltas").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#faltas_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#faltas_end").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });
    
    $('#btn_faltas_pdf').on('click', function (e) {
        var valid = valida_faltas();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else{
            $('#frm_reporte').attr('action', '/reports/faltas_membresias_pdf');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').attr({"target" : "_blank"});
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_faltas").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#faltas_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#faltas_end").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });

    $('#btn_faltas_csv').on('click', function (e) {
        var valid = valida_faltas();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else{
            $('#frm_reporte').attr('action', '/reports/faltas_membresias_csv');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_faltas").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#faltas_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#faltas_end").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });
    
    // SOCIOS

    $('#btn_socios').on('click', function (e) {
        var valid = valida_socios();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else {
            $('#frm_reporte').attr('action', '/reports/socios');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_socios").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#date_socios_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#date_socios_end").val() + '" />\
                <input type="hidden" name="status" value="' + $("#status_socios").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });
     $('#btn_clientes').on('click', function (e) {
        var valid = valida_clientes();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else {
            $('#frm_reporte').attr('action', '/reports/clientes');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_clientes").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#date_clientes_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#date_clientes_end").val() + '" />\
                <input type="hidden" name="status" value="' + $("#status_clientes").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });


    $('#btn_merca').on('click', function (e) {
        var valid = valida_merca();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else {
            $('#frm_reporte').attr('action', '/reports/merca');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_merca").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#date_merca_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#date_merca_end").val() + '" />\
                <input type="hidden" name="edad" value="' + $("#edad_merca").val() + '" />\
                <input type="hidden" name="actividad" value="' + $("#actividad_merca").val() + '" />\
                <input type="hidden" name="estado" value="' + $("#estado_merca").val() + '" />\
                <input type="hidden" name="gym" value="' + $("#gym_merca").val() + '" />\
                <input type="hidden" name="gustos" value="' + $("#gustos_merca").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });
    
    $('#btn_socios_pdf').on('click', function (e) {
        var valid = valida_socios();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else{
            $('#frm_reporte').attr('action', '/reports/socios_pdf');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').attr({"target" : "_blank"});
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_socios").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#date_socios_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#date_socios_end").val() + '" />\
                <input type="hidden" name="status" value="' + $("#status_socios").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });

    $('#btn_socios_csv').on('click', function (e) {
        var valid = valida_socios();

        if (valid[0] == false) {
            swal(valid[1], '', "error");
        }
        else{
            $('#frm_reporte').attr('action', '/reports/socios_csv');
            $('#frm_reporte').removeAttr('target');
            $('#frm_reporte').empty().append('\
                <input type="hidden" name="sucursal" value="' + $("#ant_account_socios").val() + '" />\
                <input type="hidden" name="inicio" value="' + $("#date_socios_start").val() + '" />\
                <input type="hidden" name="fin" value="' + $("#date_socios_end").val() + '" />\
                <input type="hidden" name="status" value="' + $("#status_socios").val() + '" />\
            ');
            $('#frm_reporte').submit();
        }
    });

    $("#ant_account_corte").change(function (){

        $.ajax({
            url: '/reports/update_select_v_corte',
            type: 'POST',
            data: 'ant_account='+$("#ant_account_corte").val(),
            dataType: 'json',
            success: function (data) {
                $("#vendedor").empty().append("<option value='all' selected='selected'>Todos</option>"+data);                
            }
        });

        $.ajax({
            url: '/reports/update_select_c_corte',
            type: 'POST',
            data: 'ant_account='+$("#ant_account_corte").val(),
            dataType: 'json',
            success: function (data) {
                $("#cajero").empty().append("<option value='all' selected='selected'>Todos</option>"+data);                
            }
        });

    });

    $("#ppto_month").val(0);
    $("#pptoventas_month").val(0);

});

function valida_edo_cuenta(id, fechaI, fechaF) {
    var valid = true;
    var msg = '';
    if (id == 1) {
        valid = false;
        msg += 'Seleccione una membresia\n';
    }
    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } else {
        var string_params = '<input type="hidden" name="fecha_inicio" value="' + fechaI + '" />' +
                '<input type="hidden" name="fecha_termino" value="' + fechaF + '" />';
    }
    return (valid) ? [true, string_params] : [false, msg];

}

function valida_carteraVencida (fechaI, fechaF) {
    var valid = true;
    var msg = '';

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 

    return (valid) ? [true, ''] : [false, msg];
}

function valida_vencidos_paga2 (fechaI, fechaF) {
    var valid = true;
    var msg = '';

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 

    return (valid) ? [true, ''] : [false, msg];
}

function reporte_carteraVencidaPDF() {
    var fechai_Cartera = $('#fechaI_cartera').val();
    var fechaf_Cartera = $('#fechaF_cartera').val();
    var ant_account = $('#ant_account_cartera').val();

    var valid = valida_carteraVencida(fechai_Cartera, fechaf_Cartera);
    if (valid[0]) {
        window.open("/reports/cartera_vencidaPDF/" + fechai_Cartera + "/" + fechaf_Cartera + "/" + ant_account);
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_carteraVencidaCSV() {
    var fechai_Cartera = $('#fechaI_cartera').val();
    var fechaf_Cartera = $('#fechaF_cartera').val();
    var ant_account = $('#ant_account_cartera').val();

    var valid = valida_carteraVencida(fechai_Cartera, fechaf_Cartera);
    if (valid[0]) {
        window.location.href = "/reports/reporte_carteraVencidaCSV/" + fechai_Cartera + "/" + fechaf_Cartera + "/" + ant_account;
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_vencidosPDF() {
    
    var fechaI = $('#inicio_vp').val();
    var fechaF = $('#fin_vp').val();
    var ant_account = $('#ant_account_vencidos').val();

    var valid = valida_vencidos_paga2(fechaI, fechaF);
    if (valid[0]) {
        window.open("/reports/vencidosPDF/"+fechaI+"/"+fechaF+"/"+ant_account);
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_vencidosCSV() {

    var fechaI = $('#inicio_vp').val();
    var fechaF = $('#fin_vp').val();
    var ant_account = $('#ant_account_vencidos').val();

    var valid = valida_vencidos_paga2(fechaI, fechaF);
    if (valid[0]) {
        window.location.href = "/reports/vencidosCSV/"+fechaI+"/"+fechaF+"/"+ant_account;
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_corte_general() {
    var vendedor = $('#vendedor').val();
    var producto = $('#producto').val();
    var finicio = $('#date_start').val();
    var ffin = $('#date_end').val();
    var consumible = $('#consumible').val();
    var ant_account = $("#ant_account_corte").val();
    var cajero = $("#cajero").val();

    var valid = validar_general(vendedor, producto, finicio, ffin, consumible);
    if (valid[0]) {
        window.location.href = "/reports/reporte_general/" + vendedor + "/" + producto + "/" + finicio + "/" + ffin + "/" + consumible + "/" +ant_account + "/" + cajero;
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_corte_general_pdf() {
    var vendedor = $('#vendedor').val();
    var producto = $('#producto').val();
    var finicio = $('#date_start').val();
    var ffin = $('#date_end').val();
    var consumible = $('#consumible').val();
    var ant_account = $("#ant_account_corte").val();
    var cajero = $("#cajero").val();

    var valid = validar_general(vendedor, producto, finicio, ffin, consumible);
    if (valid[0]) {
        window.open("/reports/generalPDF" + "/" + vendedor + "/" + producto + "/" + finicio + "/" + ffin + "/" + consumible + "/" + ant_account + "/" + cajero);
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_corte_general_csv() {
    var vendedor = $('#vendedor').val();
    var producto = $('#producto').val();
    var finicio = $('#date_start').val();
    var ffin = $('#date_end').val();
    var consumible = $('#consumible').val();
    var ant_account = $("#ant_account_corte").val();
    var cajero = $("#cajero").val();

    var valid = validar_general(vendedor, producto, finicio, ffin, consumible);
    if (valid[0]) {
        window.location.href ="/reports/generalCSV" + "/" + vendedor + "/" + producto + "/" + finicio + "/" + ffin + "/" + consumible + "/" + ant_account + "/" + cajero;
    } else {
        swal(valid[1], '', "error");
    }
    //window.location.href = "/reports/generalCSV";
}

function validar_general(vendedor, producto, finicio, ffin, consumible) {

    var msg = "Favor de llenar los campos requeridos:";
    var sucess = true;
    var string_params =
            '<input type="hidden" name="template" value="corte_general" readonly="readonly" />';
    if (vendedor == '') {
        sucess = false;
        msg += "\n\t-Vendedor";
    } else {
        string_params += ('<input type="hidden" name="vendedor" value="' + vendedor + '" />');
    }
    if (finicio == '') {
        sucess = false;
        msg += "\n\t-Fecha Inicio";
    } else {
        string_params += ('<input type="hidden" name="fecha_inicial" value="' + finicio + '" />');
    }
    if (ffin == '') {
        sucess = false;
        msg += "\n\t-Fecha Fin";
    } else {
        string_params += ('<input type="hidden" name="fecha_final" value="' + ffin + '" />');
    }

    if (consumible == 'none' && producto == 'none') {
        sucess = false;
        msg += "\n\t-Consumible y/o Tipo Membresia";
    } 

    return (sucess) ? [true, string_params] : [false, msg];
}

$("#sucursal").change(function() {
  $.ajax({
        url: '/reports/get_products_corte_membresias',
        type: 'POST',
        data: 'sucursal=' + $("#sucursal").val(),
        dataType: 'json',
        beforeSend: function (e) {
            $('#productos').html('');
        },
        success: function (data) {
            $('#productos').html('<option value="todos_productos">Todos</option>');
            $('#productos').append(data);
        }
    });
}).change();

products_sucursal();

function products_sucursal(){
    $.ajax({
        url: '/reports/get_products_corte_membresias',
        type: 'POST',
        data: 'sucursal=0',
        dataType: 'json',
        beforeSend: function (e) {
            $('#producto').html('');
        },
        success: function (data) {
            $('#producto').html('<option value="none" selected>--Tipo--</option><option value="todos_productos">Todos</option>');
            $('#producto').append(data);
        }
    });
}

/*
vendedores_sucursal();
function vendedores_sucursal(){
    $.ajax({
        url: '/reports/get_vendedores_sucursal',
        type: 'POST',
        data: '',
        dataType: 'json',
        beforeSend: function (e) {
            $('#vendedor').html('');
        },
        success: function (data) {
            $('#vendedor').html('<option value="all" selected="selected">Todos</option>');
            $('#vendedor').append(data);
        }
    });
}
*/

function reporte_domiciliado() {
    var tarjeta = $('#tarjeta').val();
    var nombre = $('#nombre').val();
    var membresia_domiciliado = $('#membresia_domiciliado').val();
    var finicio = $('#fecha_domiciliado_inicio').val();
    var ffin = $('#fecha_domiciliado_final').val();
    var valid = validar_domiciliado(tarjeta, nombre, membresia_domiciliado, finicio, ffin);
    if (valid[0]) {
        window.location.href = "/reports/pagos_domiciliados/" + tarjeta + "/" + nombre + "/" + membresia_domiciliado + "/" + finicio + "/" + ffin;
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_domiciliado_pdf() {
    var tarjeta = $('#tarjeta').val();
    var nombre = $('#nombre').val();
    var membresia_domiciliado = $('#membresia_domiciliado').val();
    var finicio = $('#fecha_domiciliado_inicio').val();
    var ffin = $('#fecha_domiciliado_final').val();
    var valid = validar_domiciliado(tarjeta, nombre, membresia_domiciliado, finicio, ffin);
    if (valid[0]) {
        window.open("/reports/pagos_domiciliadosPDF/" + tarjeta + "/" + nombre + "/" + membresia_domiciliado + "/" + finicio + "/" + ffin);
    } else {
        swal(valid[1], '', "error");
    }
}

function reporte_domiciliado_csv() {
    var tarjeta = $('#tarjeta').val();
    var nombre = $('#nombre').val();
    var membresia_domiciliado = $('#membresia_domiciliado').val();
    var finicio = $('#fecha_domiciliado_inicio').val();
    var ffin = $('#fecha_domiciliado_final').val();
    var valid = validar_domiciliado(tarjeta, nombre, membresia_domiciliado, finicio, ffin);
    if (valid[0]) {
        window.location.href = "/reports/pagos_domiciliadosCSV/" + tarjeta + "/" + nombre + "/" + membresia_domiciliado + "/" + finicio + "/" + ffin;
    } else {
        swal(valid[1], '', "error");
    }
}

function validar_domiciliado(tarjeta, nombre, membresia_domiciliado, finicio, ffin) {
    var msg = "Favor de llenar los campos requeridos:";
    var sucess = true;
    var string_params =
            '<input type="hidden" name="template" value="corte_general" readonly="readonly" />';
    if (tarjeta == '') {
        sucess = false;
        msg += "\n\t-Tarjeta";
    } else {
        string_params += ('<input type="hidden" name="vendedor" value="' + vendedor + '" />');
    }
    if (nombre == '') {
        sucess = false;
        msg += "\n\t-Nombre";
    } else {
        string_params += ('<input type="hidden" name="producto" value="' + producto + '" />');
    }
    if (finicio == '') {
        sucess = false;
        msg += "\n\t-Fecha Inicio";
    } else {
        string_params += ('<input type="hidden" name="fecha_inicial" value="' + finicio + '" />');
    }
    if (ffin == '') {
        sucess = false;
        msg += "\n\t-Fecha Fin";
    } else {
        string_params += ('<input type="hidden" name="fecha_final" value="' + ffin + '" />');
    }
    if (membresia_domiciliado == '1') {
        sucess = false;
        msg += "\n\t-Membresia";
    } else {
        string_params += ('<input type="hidden" name="consumible" value="' + consumible + '" />');
    }
    return (sucess) ? [true, string_params] : [false, msg];
}

function valida_faltas() {
    var valid = true;
    var msg = '';
    var fechaI = $("#faltas_start").val();
    var fechaF = $("#faltas_end").val();

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 

    return (valid) ? [true, msg] : [false, msg];
}

function valida_socios() {
    var valid = true;
    var msg = '';
    var fechaI = $("#date_socios_start").val();
    var fechaF = $("#date_socios_end").val();

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 

    return (valid) ? [true, msg] : [false, msg];
}
function valida_clientes() {
    var valid = true;
    var msg = '';
    var fechaI = $("#date_clientes_start").val();
    var fechaF = $("#date_clientes_end").val();

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 
     return (valid) ? [true, msg] : [false, msg];
} 


function valida_merca() {
    var valid = true;
    var msg = '';
    var fechaI = $("#date_merca_start").val();
    var fechaF = $("#date_merca_end").val();

    if (fechaI == '' || fechaF == '') {
        if (fechaI == '') {
            valid = false;
            msg += '- Elija la Fecha Inicial(Desde)\n';
        }
        if (fechaF == '') {
            valid = false;
            msg += '- Elija la Fecha Final (Hasta)';
        }
    } 

    return (valid) ? [true, msg] : [false, msg];
}


// Nuevos reportes


function reporte_prospectos() {

    var fecha1 = $('#pr_date1').val();
    var fecha2 = $('#pr_date2').val();

    if (fecha1 == '' || fecha1 == null){
        fecha1 = 'none';
    }

    if (fecha2 == '' || fecha2 == null){
        fecha2 = 'none';
    }

    window.location.href = "/reports/reporte_prospectos/" + fecha1 + "/" + fecha2;
}
function reporte_oportunidades() {

    var fecha1 = $('#op_date1').val();
    var fecha2 = $('#op_date2').val();

    if (fecha1 == '' || fecha1 == null){
        fecha1 = 'none';
    }

    if (fecha2 == '' || fecha2 == null){
        fecha2 = 'none';
    }

    window.location.href = "/reports/reporte_oportunidades/" + fecha1 + "/" + fecha2;
}


function reporte_ppto() {

    var year = $('#ppto_year').val();
    var month = $('#ppto_month').val();

    window.location.href = "/reports/reporte_ppto/" + year + "/" + month;
}
function reporte_pptoventas() {

    var year = $('#pptoventas_year').val();
    var month = $('#pptoventas_month').val();
    var sucursal = $('#ant_account_metas').val();

    window.location.href = "/reports/reporte_pptoventas/" + year + "/" + month+"/"+sucursal;
}

function csv_reporte_ppto() {
    var year  = $('#ppto_year').val();
    var month = $('#ppto_month').val();

    window.location.href = "/reports/csv_reporte_ppto/" + year + "/" + month;
}


function activar_check_seguimiento(elm, erp_contact_id) {
    $.ajax({
        url: '/reports/activar_check_seguimiento',
        type: 'POST',
        data: {erp_contact_id: erp_contact_id, 'checked': (elm.checked?1:0)},
        dataType: 'json',
        beforeSend: function (e) {},
        success: function (data) {}
    });
}


/* Reporte de notificaciones */


function reporte_rut() {
    window.location.href = "/reports/sin_programa/";
}

function csv_reporte_rut() {
    window.location.href = "/reports/csv_sin_programa/";
}


function reporte_nutri() {
    window.location.href = "/reports/sin_plan/";
}

function csv_reporte_nutri() {
    window.location.href = "/reports/csv_sin_plan/";
}


function reporte_cita() {
    window.location.href = "/reports/sin_cita/";
}

function csv_reporte_cita() {
    window.location.href = "/reports/csv_sin_cita/";
}


function reporte_lead() {
    window.location.href = "/reports/sin_lead/";
}

function csv_reporte_lead() {
    window.location.href = "/reports/csv_sin_lead/";
}


function reporte_cita_pago() {
    window.location.href = "/reports/cita_sin_pago/";
}

function csv_reporte_cita_pago() {
    window.location.href = "/reports/csv_cita_sin_pago/";
}

function reporte_app_login() {
    window.location.href = "/reports/app_wout_login/";
}

function csv_reporte_app_login() {
    window.location.href = "/reports/csv_app_wout_login/";
}

// Auth Rreport

function reporte_cauth() {
    window.location.href = "/reports/cauth/";
}

function csv_reporte_cauth() {
    window.location.href = "/reports/csv_cauth/";
}
