 var types = [];
 $(document).ready(function() {
     //get_multiple_paydays();
     grid_load_data();
 });

 function load_info(type) {
     pos = types.map(function(e) {
         return e.type;
     }).indexOf(type);
     if (pos >= 0) {
         types.splice(pos, 1);
     }
     types.push({
         'id': $("#period_" + type).val(),
         'type': type
     });
     grid_load_data($("#period_" + type).val(), type);
 }

 function grid_load_data(id = 0, type = '') {
     $.ajax({
         url: "/Employees/getListEmployeePayDay/",
         type: 'POST',
         data: {
             'search': $.trim($('#filter').val()),
             'types': types
         },
         dataType: 'json',
         beforeSend: function(e) {
             swal({
                 title: "Cargando",
                 showConfirmButton: false,
                 imageUrl: "/assets/images/loader.gif"
             });
         },
         success: function(data) {
             swal.close();
             var arrayDeCadenas = data.paydays.split(",");
             var empresa = "";
             if (data.empresa) {
                 empresa = '<th data-name="client">Client Name</th>';
             }
             $('#contenedor').empty();
             for (var i = 0; i < arrayDeCadenas.length; i++) {
                 $('#contenedor').append("<div class='data-action-bar'>\
                    <legend>\Create Paychecks for " + arrayDeCadenas[i] + " payday</legend>\
                    <div class='row'>\
                    <div class='col-md-12'>\
                    <div class='col-md-3 col-sm-3'>\
                    <span class='tfh-label'>\Period: </span>\
                    <select id='period_" + arrayDeCadenas[i] + "' onchange='load_info(\"" + arrayDeCadenas[i] + "\")' class='form-control' >\
                    " + data["schedule_" + arrayDeCadenas[i]] + "\
                    </select>\
                    </div>\
                    </div>\
                    </div>\
                    </div>\
                    <table id='" + arrayDeCadenas[i] + "_grid'  class='table table-striped table-bordered table-sm' cellspacing='2' width='100%''>\
                    <thead>\
                    <tr>\
                    <th data-name='No'>\No.</th>\
                    " + empresa + "\
                    <th data-name='Last_Name'>\Last Name</th>\
                    <th data-name='First_Name'>\First Name</th>\
                    <th data-name='Pay_Rate' style='min-width:130px;'>\Regular&nbsp;&nbsp;&nbsp;&nbsp;</th>\
                    <th data-name='Pay_Schedule' style='min-width:130px;'>\OT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>\
                    <th data-name='Pay_Method' style='min-width:130px;'>\Sick Pay</th>\
                    <th data-name='Status' style='min-width:130px;'>\Vacation Pay</th>\
                    <th data-name='Status' style='min-width:130px;'>\Comissions</th>\
                    </tr>\
                    </thead>\
                    <tbody>\
                    <tr>\
                    <td>\</td>\
                    </tr>\
                    </tbody>\
                    <tfoot class='hide-if-no-paging'>\
                    <tr>\
                    <td colspan='7' class='footable-visible'>\
                    <div class='pagination pagination-centered'>\</div>\
                    </td>\
                    </tr>\
                    </tfoot>\
                    </table><br><br>");
                 $('#' + arrayDeCadenas[i] + '_grid tbody').empty().append(data[arrayDeCadenas[i]]).trigger('footable_redraw');
                 $('#' + arrayDeCadenas[i] + '_grid').show();
                 //$('#' + arrayDeCadenas[i] + '_grid').Datatable();
                 var table = $('#' + arrayDeCadenas[i] + '_grid').DataTable({
                     dom: 'Bfrtip',
                     "scrollX": true,
                     "pageLength": 10,
                     "responsive": false,
                     "paging": true,
                     buttons: [{
                         extend: 'excelHtml5',
                         title: 'PayDays '+ arrayDeCadenas[i],
                         exportOptions: {
                             format: {
                                 body: function(data, row, column, node) {
                                     return $(data).is("input") ? $(data).val() : data;
                                 }
                             }
                         }
                     }, {
                         extend: 'pdfHtml5',
                         title: 'PayDays '+ arrayDeCadenas[i],
                         download: 'open',
                         exportOptions: {
                             format: {
                                 body: function(data, row, column, node) {
                                     return $(data).is("input") ? $(data).val() : data;
                                 }
                             }
                         }
                     }, {
                         extend: 'print',
                         title: 'PayDays '+ arrayDeCadenas[i],
                         exportOptions: {
                             format: {
                                 body: function(data, row, column, node) {
                                     return $(data).is("input") ? $(data).val() : data;
                                 }
                             }
                         }
                     }]
                     
                 });
             }
         }
     });
 }

 function employee_pay(type, employee, calendar, valor) {
     $.ajax({
         url: "/Employees/add_employee_pay",
         type: 'POST',
         data: {
             'employee': employee,
             'calendar': calendar,
             'type': type,
             'value': valor
         },
         dataType: 'json',
         beforeSend: function(e) {},
         success: function(data) {}
     });
 }

 function export_info(type) {
     $.ajax({
         url: "/Employees/export_info",
         type: 'POST',
         data: {
             'employee': employee,
             'calendar': $("#period_" + type).val()
         },
         dataType: 'json',
         beforeSend: function(e) {},
         success: function(data) {}
     });
 }