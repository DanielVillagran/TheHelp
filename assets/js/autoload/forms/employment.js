var states = "";
$(document).ready(function() {
    get_states();
    get_work_locations();
    if ($("#id").length == 1) {
        get_info_employment($("#id").val());
    }
    $("#file_hire_report").change(function() {
        var archivopre = document.getElementById("file_hire_report");
        var tomaarch = archivopre.files[0];
        var data = new FormData();
        data.append('archivo', tomaarch);
        data.append('basics_id',$("#basics_id").val());
        $.ajax({
            data: data,
            url: '/Employees/upload_hire_report',
            type: 'post',
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) { 
                $("#down_hire_report_div").show();
                $("#down_hire_report").attr('href','/'+response.file);
            }
        });
    });
    $("#file_form_i9").change(function() {
        var archivopre = document.getElementById("file_form_i9");
        var tomaarch = archivopre.files[0];
        var data = new FormData();
        data.append('archivo', tomaarch);
        data.append('basics_id',$("#basics_id").val());
        $.ajax({
            data: data,
            url: '/Employees/upload_form_i9',
            type: 'post',
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) { 
                $("#down_form_i9_div").show();
                $("#down_form_i9").attr('href','/'+response.file);
            }
        });
    });
    $("#file_form_w4").change(function() {
        var archivopre = document.getElementById("file_form_w4");
        var tomaarch = archivopre.files[0];
        var data = new FormData();
        data.append('archivo', tomaarch);
        data.append('basics_id',$("#basics_id").val());
        $.ajax({
            data: data,
            url: '/Employees/upload_form_w4',
            type: 'post',
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $("#down_form_w4_div").show();
                $("#down_form_w4").attr('href','/'+response.file);
            }
        });
    });
});
function add_hire_report_file(){
    $("#file_hire_report").trigger('click');
}
function add_form_i9_file(){
    $("#file_form_i9").trigger('click');
}
function add_form_w4_file(){
    $("#file_form_w4").trigger('click');
}

function get_info_employment(id) {
    $.ajax({
        type: "post",
        url: "/Edit_Employees/get_info_employment",
        data: {
            id: id
        },
        dataType: "json",
        beforeSend: function() {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function(data) {
            swal.close();
            $("#basics_id").val(data.basics_id);
            $("#employee_id").val(data.employee_id);
            $("#status").val(data.status);
            $("#termination_date").val(termination_date);
            $("#hire_date").val(data.hire_date);
            if (data.new_hire_report != 0) {
                $('#new_hire_report').prop('checked', true);
            }
            if (data.form_i9 != 0) {
                $('#form_i9').prop('checked', true);
            }
            if (data.form_w4 != 0) {
                $('#form_w4').prop('checked', true);
            }
            if (data.work_location_id == 0) {
                data.work_location_id = "";
            }
            console.log(data.work_location_id);
            $("#work_location_id").val(data.work_location_id);
            $("#workers_comp_class").val(data.workers_comp_class);
            change_status();
            change_hire_report();
            if(data.file_hire_report!=null&&data.file_hire_report!=""){
                $("#down_hire_report_div").show();
                $("#down_hire_report").attr('href','/'+data.file_hire_report);
            }
            if(data.file_form_i9!=null&&data.file_form_i9!=""){
                $("#down_form_i9_div").show();
                $("#down_form_i9").attr('href','/'+data.file_form_i9);
            }
            if(data.file_form_w4!=null&&data.file_form_w4!=""){
                $("#down_form_w4_div").show();
                $("#down_form_w4").attr('href','/'+data.file_form_w4);
            }
        }
    });
}

function send_employment_info(event) {
    event.preventDefault();
    var data = $("#employment_info").serializeArray();
    $.ajax({
        type: "post",
        url: "/Employees/save_info_employment",
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
            if ($("#id").length != 1) {
                wizard.steps("next");
            }
            swal.close();
        }
    });
}

function get_states() {
    $.ajax({
        type: "post",
        url: "/Employees/get_states",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            states = data.states_select;
        }
    });
}

function get_work_locations() {
    $.ajax({
        type: "post",
        url: "/Employees/get_work_locations",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#work_location_id").empty().append(data.work_locations_select);
        }
    });
}

function insert_addresses() {
    event.preventDefault();
    var data = $("#address_info").serializeArray();
    $.ajax({
        type: "post",
        url: "/Employees/save_info_addresses",
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
            get_work_locations();
            swal.close();
            $('#mainModal').hide();
        }
    });
}

function change_hire_report() {
    if ($("#new_hire_report").prop("checked") == true && $("#form_i9").prop("checked") == true) {
        $("#hire_date").prop('required', false);
        $("#hire_label").empty().append("Hire Date");
    } else {
        $("#hire_date").prop('required', true);
        $("#hire_label").empty().append("Hire Date*");
    }
}

function change_status() {
    if ($("#status").val() == 5) {
        $("#termination_div").show();
    } else {
        $("#termination_div").hide();
    }
}

function add_work_location() {
    $("#mainModalLabel").empty().append('Work Location Address');
    var inputs = ' <form id="address_info" onsubmit="insert_addresses()">\
    <div class="row">\
    <div class="col-md-7">\
    <label >Address* </label>\
    <input placeholder="Address" type="text" class="form-control" name="address[address]" id="address" required>\
    </div>\
    <div class="col-md-5">\
    <label >City* </label>\
    <input placeholder="City" type="text" class="form-control" name="address[city]" id="city" required>\
    </div>\
    </div>\
    <br>\
    <div class="row">\
    <div class="col-md-4">\
    <label>State*</label>\
    <select class="form-control" name="address[state_id]" id="state" required >\
    ' + states + '\
    </select>\
    </div>\
    <div class="col-md-4">\
    <label >ZIP* </label>\
    <input placeholder="(xxxxx or xxxxx-xxxx)" type="text" class="form-control" name="address[zip]" id="zip" required>\
    </div>\
    <div class="col-md-4">\
    <br>\
    <button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
    </div>\
    </form>';
    $("#body_modal").empty().append(inputs);
    $("#mainModal").show();
}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}