var file_name="";
$(document).ready(function() {
    get_data_report();
    get_employee_name();
    get_initial_info();
    get_employee_info();
});
function get_cuestionario(){
    window.open("/functions.php?download="+$("#id").val()+"&name="+file_name+"&user_id="+$("#user_id").val());
}
function get_employee_name() {
    $.ajax({
        type: "post",
       url: "/functions.php?function=get_employee_name",
        data: {id:$("#id").val()},
        dataType: "json",
        success: function(data) {
            file_name=data[0]['name']+'_'+Math.floor(Math.random() * 1000000000)+'.pdf';
            $("#employee_name").empty().append(data[0]['name']);
        }
    });
}
function get_employee_info() {
    $.ajax({
        type: "post",
        url: "/functions.php?function=get_employee_info_complete",
        data:{id:$("#id").val()},
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            for (var key in data) {
                //console.log($('select[name="info['+ key+']"]' ).length);
                $('input[name="info['+ key+']"]' ).val(data[key]);
                $('select[name="info['+ key+']"]' ).val(data[key]);
            }
        }
    });
}
function get_initial_info() {
    $.ajax({
        type: "post",
        data:{user_id:$("#user_id").val()},
        url: "/functions.php?function=get_initial_selects",
        async:false,
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            for (var key in data) {
                $("#" + key).empty().append(data[key]);
            }
        }
    });
}
function get_data_report() {
    $.ajax({
        type: "post",
       url: "/functions.php?function=get_info_resultado_personal",
        data: {id:$("#id").val()},
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
            $("#traumas").empty().append(data.trauma.text);
            $("#box_trauma").addClass(data.trauma.label);
            if(data.trauma.status){
                $("#box_trauma").attr("onclick",'get_traumas('+$("#id").val()+')');
            }
            $("#report_grid").dataTable().fnDestroy();
            $("#report_grid tbody").empty().append(data.table);
            initialize_table('report');
            $("#dominio_grid").dataTable().fnDestroy();
            $("#dominio_grid tbody").empty().append(data.table_dominio);
            initialize_table('dominio');
            $('.dataTables_length').addClass('bs-select');
            swal.close();
        }
    });
}
function get_traumas(response) {
      var title="Respuestas";
    $.ajax({
        type: "post",
       url: "/functions.php?function=get_responses_traumas",
        data: {reponse:response},
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
            '+data.thead+'\
            </tr>\
            </thead>\
            <tbody>\
            '+data.tbody+'\
            </tbody>\
            </table> ';
            $("#body_modal").empty().append(inputs);
            $("#detail_grid").dataTable().fnDestroy();
            initialize_table('detail',5);
            $('.dataTables_length').addClass('bs-select');
            $("#mainModal").show();
            swal.close();

        }
    });

}
function initialize_table(id,page_length=10) {
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

function get_questions(response,domain) {
    var title="Respuestas";
    $.ajax({
        type: "post",
       url: "/functions.php?function=get_responses",
        data: {reponse:response,domain:domain},
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
            '+data.thead+'\
            </tr>\
            </thead>\
            <tbody>\
            '+data.tbody+'\
            </tbody>\
            </table> ';
            $("#body_modal").empty().append(inputs);
            $("#detail_grid").dataTable().fnDestroy();
            initialize_table('detail',5);
            $('.dataTables_length').addClass('bs-select');
            $("#mainModal").show();
            swal.close();

        }
    });
}
function detail_from_employee(id){
    window.open('/Capacitacion/EncuestaNom035/Resultado_Personal/'+id);
}