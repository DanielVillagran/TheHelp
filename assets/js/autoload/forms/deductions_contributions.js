var categories = "";
var categories_garnishment = "";
$(document).ready(function() {
    get_categories();
    get_categories_garnishment();
    if ($("#dedus_id").length == 1) {
        get_garnishment_by_employee($("#dedus_id").val());
        get_deductions_by_employee($("#dedus_id").val());
        get_contributions_by_employee($("#dedus_id").val());
    }
});

function delete_garnishment(id, basics_id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/delete_garnishment",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            get_garnishment_by_employee(basics_id);
        }
    });
}

function delete_contributions(id, basics_id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/delete_contributions",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            get_contributions_by_employee(basics_id);
        }
    });
}

function delete_deductions(id, basics_id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/delete_deductions",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            get_deductions_by_employee(basics_id);
        }
    });
}

function get_deductions_by_employee(id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/get_deductions_by_employee",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#deductions_table tbody").empty().append(data.table);
        }
    });
}

function get_contributions_by_employee(id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/get_contributions_by_employee",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#contributions_table tbody").empty().append(data.table);
        }
    });
}

function get_garnishment_by_employee(id) {
    $.ajax({
        type: "post",
        data: {
            id: id
        },
        url: "/Employees/get_garnishment_by_employee",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#garnishment_table tbody").empty().append(data.table);
        }
    });
}

function get_categories_garnishment() {
    $.ajax({
        type: "post",
        url: "/Employees/get_categories_garnishment",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            categories_garnishment = data.categories_select;
        }
    });
}

function get_categories() {
    $.ajax({
        type: "post",
        url: "/Employees/get_categories",
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            categories = data.categories_select;
        }
    });
}

function get_categories_types(id) {
    $.ajax({
        type: "post",
        url: "/Employees/get_categories_types",
        data: {
            id: id
        },
        async: false,
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            $("#deduction_type").empty().append(data.categories_types_select);
        }
    });
}

function insert_deduction() {
    event.preventDefault();
    $("#maximum_to_pay").prop("disabled", false);
    var data = $("#deduction_info").serializeArray();
    $.ajax({
        type: "post",
        url: "/Employees/save_info_deduction",
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
            get_deductions_by_employee($("#basics_id_add_deduction").val());
            swal.close();
            $('#mainModal').hide();
        }
    });
}

function insert_contribution() {
    event.preventDefault();
    $("#maximum_to_pay").prop("disabled", false);
    var data = $("#deduction_info").serializeArray();
    $.ajax({
        type: "post",
        url: "/Employees/save_info_contribution",
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
            get_contributions_by_employee($("#basics_id_add_deduction").val());
            swal.close();
            $('#mainModal').hide();
        }
    });
}

function insert_garnishment() {
    event.preventDefault();
    $("#maximum_to_pay").prop("disabled", false);
    var data = $("#deduction_info").serializeArray();
    $.ajax({
        type: "post",
        url: "/Employees/save_info_garnishment",
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
            get_garnishment_by_employee($("#basics_id_add_deduction").val());
            swal.close();
            $('#mainModal').hide();
        }
    });
}

function change_deduction_category() {
    $("#form_deductions").empty();
    if ($("#deduction_category").val() != "") {
        get_categories_types($("#deduction_category").val());
        $("#div_deduction_type").show();
    } else {
        $("#div_deduction_type").hide();
    }
}

function check_if_conditions(id) {
    var condition = null;
    $.ajax({
        type: "post",
        url: "/Employees/check_if_conditions",
        data: {
            id: id
        },
        async: false,
        dataType: "json",
        beforeSend: function() {},
        success: function(data) {
            condition = data;
        }
    });
    return condition;
}

function construct_retirement_form() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    if (conditions != null) {
        condition_number = conditions;
        conditions = ' max="' + conditions + '" title="Annual maximum amount $' + format_number(conditions) + '" ';
    } else {
        conditions = "";
        condition_optional = "";
    }
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Provider* </label>\
	<input placeholder="Provider" type="text" class="form-control" name="info_deduction[provider]" id="provider" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" ' + conditions + ' class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" value="' + condition_number + '" ' + condition_optional + ' class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_health_form() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    if (conditions != null) {
        condition_number = conditions;
        conditions = ' max="' + conditions + '" title="Annual maximum amount $' + format_number(conditions) + '" ';
    } else {
        conditions = "";
        condition_optional = "";
    }
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label>Taxable/Pretax *</label>\
	<select class="form-control" name="info_deduction[health_type]" id="health_type" required >\
	<option value="Taxable Insurance Premium">Taxable Insurance Premium</option>\
	<option value="Pretax Insurance Premium">Pretax Insurance Premium</option>\
	</select>\
	</div>\
	<div class="col-md-4">\
	<label >Provider* </label>\
	<input placeholder="Provider" type="text" class="form-control" name="info_deduction[provider]" id="provider" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" ' + conditions + ' class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" value="' + condition_number + '" ' + condition_optional + ' class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_retirement_form_contribution() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Provider* </label>\
	<input placeholder="Provider" type="text" class="form-control" name="info_deduction[provider]" id="provider" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_health_form_contribution() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Provider* </label>\
	<input placeholder="Provider" type="text" class="form-control" name="info_deduction[provider]" id="provider" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_other_form() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    if (conditions != null) {
        condition_number = conditions;
        conditions = ' max="' + conditions + '" title="Annual maximum amount $' + format_number(conditions) + '" ';
    } else {
        conditions = "";
        condition_optional = "";
    }
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" ' + conditions + ' class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" value="' + condition_number + '" ' + condition_optional + ' class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_hsa_form() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    if (conditions != null) {
        condition_number = conditions;
        conditions = ' max="' + conditions + '" title="Annual maximum amount $' + format_number(conditions) + '" ';
    } else {
        conditions = "";
        condition_optional = "";
    }
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" ' + conditions + ' class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" value="' + condition_number + '" ' + condition_optional + ' class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_fsa_form() {
    var conditions = check_if_conditions($("#deduction_type").val());
    var condition_number = "";
    var condition_optional = "disabled";
    if (conditions != null) {
        condition_number = conditions;
        conditions = ' max="' + conditions + '" title="Annual maximum amount $' + format_number(conditions) + '" ';
    } else {
        conditions = "";
        condition_optional = "";
    }
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Gross Pay</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >To pay* </label>\
	<input placeholder="" type="number" ' + conditions + ' class="form-control" name="info_deduction[to_pay]" id="to_pay" required>\
	</div>\
	<div class="col-md-4">\
	<label >Annual maximum </label>\
	<input placeholder="" type="number" value="' + condition_number + '" ' + condition_optional + ' class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function change_deduction_category_type() {
    switch ($("#deduction_category").val()) {
        case "1":
            construct_retirement_form();
            break;
        case "2":
            construct_health_form();
            break;
        case "3":
            construct_other_form();
            break;
        case "4":
            construct_hsa_form();
            break;
        case "5":
            construct_fsa_form();
            break;
    }
}

function change_contribution_category_type() {
    switch ($("#deduction_category").val()) {
        case "1":
            construct_retirement_form_contribution();
            break;
        case "2":
            construct_health_form_contribution();
            break;
    }
}

function add_deduction() {
    $("#mainModalLabel").empty().append('Add Deduction');
    var inputs = ' <form id="deduction_info" onsubmit="insert_deduction()">\
	<input  type="hidden" class="form-control" name="info_deduction[basics_id]" id="basics_id_add_deduction">\
	<div class="row">\
	<div class="col-md-4">\
	<label>Category*</label>\
	<select class="form-control" onchange="change_deduction_category();" name="deduction_category[id]" id="deduction_category" required >\
	' + categories + '\
	</select>\
	</div>\
	<div class="col-md-4" id="div_deduction_type" style="display:none;">\
	<label>Type*</label>\
	<select class="form-control"onchange="change_deduction_category_type();" name="info_deduction[deductions_categories_types_id]" id="deduction_type" required >\
	</select>\
	</div>\
	</div>\
	<div id="form_deductions">\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<br>\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</div>\
	</form>';
    $("#body_modal").empty().append(inputs);
    $("#basics_id_add_deduction").val($("#basics_id").val());
    $("#mainModal").show();
}

function add_garnishment() {
    $("#mainModalLabel").empty().append('Add Garnishment');
    var inputs = ' <form id="deduction_info" onsubmit="insert_garnishment()">\
	<input  type="hidden" class="form-control" name="info_deduction[basics_id]" id="basics_id_add_deduction">\
	<div class="row">\
	<div class="col-md-4">\
	<label>Category*</label>\
	<select class="form-control" onchange="change_garnishment_category();" name="info_deduction[garnishment_categories_id]" id="deduction_category" required >\
	' + categories_garnishment + '\
	</select>\
	</div>\
	</div>\
	<div id="form_deductions">\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<br>\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</div>\
	</form>';
    $("#body_modal").empty().append(inputs);
    $("#basics_id_add_deduction").val($("#basics_id").val());
    $("#mainModal").show();
}

function change_contribution_category() {
    $("#form_deductions").empty();
    if ($("#deduction_category").val() != "") {
        get_categories_types($("#deduction_category").val());
        $("#div_deduction_type").show();
        $(".hide_element").hide();
        $(".company").show();
    } else {
        $("#div_deduction_type").hide();
    }
}

function add_contribution() {
    $("#mainModalLabel").empty().append('Add Contribution');
    var inputs = ' <form id="deduction_info" onsubmit="insert_contribution()">\
	<input  type="hidden" class="form-control" name="info_deduction[basics_id]" id="basics_id_add_deduction">\
	<div class="row">\
	<div class="col-md-4">\
	<label>Category*</label>\
	<select class="form-control" onchange="change_contribution_category();" name="deduction_category[id]" id="deduction_category" required >\
	' + categories + '\
	</select>\
	</div>\
	<div class="col-md-4" id="div_deduction_type" style="display:none;">\
	<label>Type*</label>\
	<select class="form-control"onchange="change_contribution_category_type();" name="info_deduction[deductions_categories_types_id]" id="deduction_type" required >\
	</select>\
	</div>\
	</div>\
	<div id="form_deductions">\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<br>\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</div>\
	</form>';
    $("#body_modal").empty().append(inputs);
    $(".all").hide();
    $("#basics_id_add_deduction").val($("#basics_id").val());
    $("#mainModal").show();
}

function format_date(date) {
    var formated_date = "";
    var array_date = date.split('T')[0].split('-');
    var array_hour = date.split('T')[1].split(':');
    formated_date += array_date[2] + "-" + array_date[1] + "-" + array_date[0] + " " + array_hour[0] + ":" + array_hour[1] + ":00";
    return formated_date;
}

function format_number(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

function change_garnishment_category() {
    $("#form_deductions").empty();
    if ($("#deduction_category").val() != "") {
        $("#div_deduction_type").show();
        console.log("Pruebas");
        switch ($("#deduction_category").val()) {
            case "6":
                construct_child_form();
                break;
            case "7":
                construct_other_gar_form();
                break;
            case "8":
                construct_federal_form();
                break;
        }
    } else {
        $("#div_deduction_type").hide();
    }
}

function construct_child_form() {
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >Amount Requested* </label>\
	<input placeholder="" type="number" class="form-control" name="info_deduction[amount_requested]" id="amount_requested" required>\
	</div>\
	<div class="col-md-6">\
	<label >Maximum % of disposable income* </label>\
	<input placeholder="Maximum % of disposable income " required type="number" class="form-control" name="info_deduction[maximum_to_pay]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_other_gar_form() {
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	<div class="col-md-4">\
	<label>Amount per period * </label>\
	<select class="form-control" name="info_deduction[type_of_amount]" id="type_of_amount" required >\
	<option value="percent">% of Disposable Income</option>\
	<option value="amount">$ Amount</option>\
	</select>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >Total Amount Owed* </label>\
	<input placeholder="Total Amount Owed" type="number" class="form-control" name="info_deduction[total_amount]" id="total_amount" required>\
	</div>\
	<div class="col-md-4">\
	<label >Alternate Garnishment Cap </label>\
	<input placeholder="Alternate Garnishment Cap  " type="number" class="form-control" name="info_deduction[alternate_garnishment]" id="maximum_to_pay">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}

function construct_federal_form() {
    var inputs = '<div class="row">\
	<div class="col-md-4">\
	<label >Description* </label>\
	<input placeholder="Description" type="text" class="form-control" name="info_deduction[description]" id="description" required>\
	</div>\
	</div>\
	<div class="row">\
	<div class="col-md-4">\
	<label >Amount Exempt </label>\
	<input placeholder="Amount Exempt " type="number" class="form-control" name="info_deduction[amount_exempt]" id="amount_exempt">\
	</div>\
	</div>';
    $("#form_deductions").empty().append(inputs);
}