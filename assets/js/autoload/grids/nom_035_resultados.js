$(document).ready(function() {
    get_data_report();
    //construct_chart();
});

function construct_chart(lineChartData, title, type, elemento) {
    var ctx = document.getElementById(type);
    var myChart = Chart.Line(ctx, {
        data: lineChartData,
        options: {
            responsive: true,
            hoverMode: 'index',
            stacked: false,
            title: {
                display: true,
                text: 'Grafica de calificación - ' + title + '.'
            },
            scales: {
                yAxes: [{
                    type: 'linear',
                    display: true,
                    position: 'left',
                    id: 'y-axis-1',
                }],
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        console.log(tooltipItem.index);
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += Math.round(tooltipItem.yLabel * 100) / 100;
                        label += ' ' + elemento[tooltipItem.index];
                        return label;
                    }
                }
            }
        }
    });
}

function get_data_report() {
    var data = $("#forma_filters").serializeArray();
    if ($("#to_date").val() != "" && $("#to_date").val() != null) {
        data.push({
            name: 'to_date',
            value: format_date($("#to_date").val())
        })
    }
    if ($("#from_date").val() != "" && $("#from_date").val() != null) {
        data.push({
            name: 'from_date',
            value: format_date($("#from_date").val())
        })
    }
    data.push({name:'user_id',value:$("#user_id").val()});
    $.ajax({
        type: "post",
        url: "/functions.php?function=get_info_resultados",
        data: data,
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            $("#head_count").val(data.total_empleados);
            if (data.s_general < 50) {
                $("#box_calificacion").addClass('nulo');
                $("#calificacion").empty().append('NULO ----- ' + data.s_general);
            } else if (data.s_general < 75) {
                $("#box_calificacion").addClass('bajo');
                $("#calificacion").empty().append('BAJO ----- ' + data.s_general);
            } else if (data.s_general < 99) {
                $("#box_calificacion").addClass('medio');
                $("#calificacion").empty().append('MEDIO ------ ' + data.s_general);
            } else if (data.s_general < 140) {
                $("#box_calificacion").addClass('alto');
                $("#calificacion").empty().append('ALTO -----' + data.s_general);
            } else if (data.s_general >= 140) {
                $("#box_calificacion").addClass('m_alto');
                $("#calificacion").empty().append('MUY ALTO -----' + data.s_general);
            }
            $("#head_count").empty().append(data.total_empleados);
            $("#contestado").empty().append(data.total_contestado);
            $("#report_grid").dataTable().fnDestroy();
            $("#report_grid tbody").empty().append(data.table);
            initialize_table('report');
            $("#dominio_grid").dataTable().fnDestroy();
            $("#dominio_grid tbody").empty().append(data.table_dominio);
            initialize_table('dominio');
            $('.dataTables_length').addClass('bs-select');
            construct_chart(data.chart_dominio, 'Dominio', 'dominio',data.indicador_dominio);
            construct_chart(data.chart_categoria, 'Categoria', 'categoria',data.indicador_categoria);
            construct_chart(data.chart_dominio_sexo, 'Dominio x Sexo', 'dominio_sexo',data.indicador_dominio_sexo);
            construct_chart(data.chart_categoria_sexo, 'Categoria x Sexo', 'categoria_sexo',data.indicador_categoria_sexo);
            construct_chart(data.chart_dominio_edad, 'Dominio x Edad', 'dominio_edad',data.indicador_dominio_edad);
            construct_chart(data.chart_categoria_edad, 'Categoria x Edad', 'categoria_edad',data.indicador_categoria_edad);
             construct_chart(data.chart_dominio_tipo, 'Dominio x Tipo de empleado', 'dominio_tipo',data.indicador_dominio_tipo);
            construct_chart(data.chart_categoria_tipo, 'Categoria x Tipo de empleado', 'categoria_tipo',data.indicador_categoria_tipo);
            swal.close();
        }
    });
}

function initialize_table(id, page_length = 10) {
    var table = $('#' + id + '_grid').DataTable({
        dom: 'Bfrtip',
        "scrollX": true,
        "responsive": false,
        "pageLength": page_length,
        "paging": true,
        buttons: ['copyHtml5', {
            extend: 'excelHtml5',
            title: 'Reporteador General'
        }, {
            extend: 'pdfHtml5',
            title: 'Reporteador General',
            download: 'open'
        }, {
            extend: 'print',
            title: 'Reporteador General'
        }],
        language: {
            "lengthMenu": "Mostrar _MENU_ registros por pagina",
            "zeroRecords": "No hemos encontrado nada, perdon.",
            "info": "Mostrando la pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hemos encontrado nada, perdon.",
            "infoFiltered": "(Filtrado de _MAX_ registros totales)"
        }
    });
    $('.dataTables_length').addClass('bs-select');
}

function detail(type) {
    var title = "";
    var element = '';
    if (type == 'head') {
        element = 'get_head_count_detail';
        title = "Lista de empleados sin respuestas."
    } else {
        element = 'get_contestado_detail';
        title = "Lista de empleados con respuesta."
    }
    $.ajax({
        type: "post",
       url: "/functions.php?function=" + element,
       data: {'user_id':$("#user_id").val()},
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            $("#mainModalLabel").empty().append(title);
            var inputs = '<table id="detail_grid" class="table table-striped table-bordered table-sm" cellspacing="2" width="100%" >\
            <thead>\
            <tr>\
            ' + data.thead + '\
            </tr>\
            </thead>\
            <tbody>\
            ' + data.tbody + '\
            </tbody>\
            </table> ';
            $("#body_modal").empty().append(inputs);
            $("#detail_grid").dataTable().fnDestroy();
            initialize_table('detail', 5);
            $('.dataTables_length').addClass('bs-select');
            $("#mainModal").show();
            swal.close();
        }
    });
}

function detail_from_employee(id) {
    window.open('/EncuestaNom035/Resultado_Personal/' + id);
}