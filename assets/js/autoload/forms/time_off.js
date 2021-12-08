var accrued="";
$(document).ready(function(){
	get_vacations_policy();
	get_sick_policy();
	get_paid_policy();
	get_unpaid_policy();
	get_vacations_types();
	 if ($("#id_vacations").length == 1) {
      	get_info_vacations($("#id_vacations").val());
    } 

});
function get_info_vacations(id){
	$.ajax({
        type: "post",
        url: "/Edit_Employees/get_info_vacations",
        data: {id:id},
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
        	console.log(data.current_vacation);
            $("#vacation_basics_id").val(data.basics_id);
            $("#vacation_policy_id").val(data.vacation_policy_id);
            $("#vacation_current").val(data.current_vacation);
            $("#sick_policy_id").val(data.sick_policy_id);
            $("#sick_current").val(data.current_sick);
            $("#paid_policy_id").val(data.paid_policy_id);
            $("#paid_current").val(data.current_paid);
            $("#unpaid_policy_id").val(data.unpaid_policy_id);
            $("#unpaid_current").val(data.current_unpaid);
            change_vacation_policy();
            change_sick_policy();
            change_paid_policy();
            change_unpaid_policy();
            
            
        }
    });

}
$("#time_off_info").submit(function(event) {
	event.preventDefault();
	var data = $("#time_off_info").serializeArray();
	$.ajax({
		type : "post",
		url : "/Employees/save_info_time_off",
		data : data,
		dataType : "json",
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			if ($("#id_vacations").length != 1) {
                wizard.steps("next");
            }
			swal.close();

		}
	});
});

function change_vacation_policy(){
	if($("#vacation_policy_id").val()!=-1){
		$("#edit_vacation_div").show();
		$("#vacation_current").prop("disabled", false);
	}else{
		$("#edit_vacation_div").hide();
		$("#vacation_current").prop("disabled", true);
	}
}
function change_sick_policy(){
	if($("#sick_policy_id").val()!=-1){
		$("#edit_sick_div").show();
		$("#sick_current").prop("disabled", false);
	}else{
		$("#edit_sick_div").hide();
		$("#sick_current").prop("disabled", true);
	}
}
function change_paid_policy(){
	if($("#paid_policy_id").val()!=-1){
		$("#edit_paid_div").show();
		$("#paid_current").prop("disabled", false);
	}else{
		$("#edit_paid_div").hide();
		$("#paid_current").prop("disabled", true);
	}
}
function change_unpaid_policy(){
	if($("#unpaid_policy_id").val()!=-1){
		$("#edit_unpaid_div").show();
		$("#unpaid_current").prop("disabled", false);
	}else{
		$("#edit_unpaid_div").hide();
		$("#unpaid_current").prop("disabled", true);
	}
}
function get_vacations_types(){
	$.ajax({
		type: "post",
		url: "/Employees/get_vacations_types",
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			accrued=data.vacation_types;
		}
	});
}
function insert_vacation_policy(){
	event.preventDefault();
	var data = $("#vacation_info").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_info_vacation_policy",
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
			get_vacations_policy();
			swal.close();
			$('#mainModal').hide();
		}
	});

}
function insert_sick_policy(){
	event.preventDefault();
	var data = $("#vacation_info").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_info_sick_policy",
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
			get_sick_policy();
			swal.close();
			$('#mainModal').hide();
		}
	});

}
function insert_unpaid_policy(){
	event.preventDefault();
	var data = $("#vacation_info").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_info_unpaid_policy",
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
			get_unpaid_policy();
			swal.close();
			$('#mainModal').hide();
		}
	});

}
function insert_paid_policy(){
	event.preventDefault();
	var data = $("#vacation_info").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_info_paid_policy",
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
			get_paid_policy();
			swal.close();
			$('#mainModal').hide();
		}
	});

}
function get_unpaid_policy(){
	$.ajax({
		type: "post",
		url: "/Employees/get_unpaid_policy",
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#unpaid_policy_id").empty().append(data.vacation_policy_select);
			change_unpaid_policy();
		}
	});
}
function get_paid_policy(){
	$.ajax({
		type: "post",
		url: "/Employees/get_paid_policy",
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#paid_policy_id").empty().append(data.vacation_policy_select);
			change_paid_policy();
		}
	});
}
function get_sick_policy(){
	$.ajax({
		type: "post",
		url: "/Employees/get_sick_policy",
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#sick_policy_id").empty().append(data.vacation_policy_select);
			change_sick_policy();
		}
	});
}
function get_vacations_policy(){
	$.ajax({
		type: "post",
		url: "/Employees/get_vacations_policy",
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#vacation_policy_id").empty().append(data.vacation_policy_select);
			change_vacation_policy();
		}
	});
}
function change_vacation_type(){
	if($("#vacations_type_id").val()==5){
		$("#hours").hide();
		$("#hours_per_year").attr("required",false);

	}else{
		$("#hours").show();
		$("#hours_per_year").attr("required",true);
	}
}
function get_vacation_by_id(){
	var id=$("#vacation_policy_id").val();
	$.ajax({
		type: "post",
		url: "/Employees/get_vacations_by_id",
		data:{id:id},
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#vacation_name").val(data.name);
			$("#vacations_type_id").val(data.vacations_type_id);
			$("#hours_per_year").val(data.earn);
			$("#maximium").val(data.maximum);
			change_vacation_type();
		}
	});

}
function get_sick_by_id(){
	var id=$("#sick_policy_id").val();
	$.ajax({
		type: "post",
		url: "/Employees/get_sick_by_id",
		data:{id:id},
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#vacation_name").val(data.name);
			$("#vacations_type_id").val(data.vacations_type_id);
			$("#hours_per_year").val(data.earn);
			$("#maximium").val(data.maximum);
			change_vacation_type();
		}
	});

}
function get_unpaid_by_id(){
	var id=$("#unpaid_policy_id").val();
	$.ajax({
		type: "post",
		url: "/Employees/get_unpaid_by_id",
		data:{id:id},
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#vacation_name").val(data.name);
			$("#vacations_type_id").val(data.vacations_type_id);
			$("#hours_per_year").val(data.earn);
			$("#maximium").val(data.maximum);
			change_vacation_type();
		}
	});

}
function get_paid_by_id(){
	var id=$("#paid_policy_id").val();
	$.ajax({
		type: "post",
		url: "/Employees/get_paid_by_id",
		data:{id:id},
		dataType: "json",
		beforeSend: function() {
		
		},
		success: function(data) {
			$("#vacation_name").val(data.name);
			$("#vacations_type_id").val(data.vacations_type_id);
			$("#hours_per_year").val(data.earn);
			$("#maximium").val(data.maximum);
			change_vacation_type();
		}
	});

}
function edit_vacation_selection(){
	$("#mainModalLabel").empty().append('Vacation Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_vacation_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input type="hidden" class="form-control" name="vacations[id]" value="'+$("#vacation_policy_id").val()+'"" id="vacation_id" required>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
	get_vacation_by_id();
}
function add_vacation_policy(){
	$("#mainModalLabel").empty().append('Vacation Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_vacation_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}
function edit_sick_selection(){
	$("#mainModalLabel").empty().append('Vacation Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_sick_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input type="hidden" class="form-control" name="vacations[id]" value="'+$("#sick_policy_id").val()+'"" id="vacation_id" required>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
	get_sick_by_id();
}
function add_sick_policy(){
	$("#mainModalLabel").empty().append('Sick Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_sick_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}
function add_unpaid_policy(){
	$("#mainModalLabel").empty().append('Vacation Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_unpaid_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}
function edit_unpaid_selection(){
	$("#mainModalLabel").empty().append('Unpaid Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_unpaid_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input type="hidden" class="form-control" name="vacations[id]" value="'+$("#unpaid_policy_id").val()+'"" id="vacation_id" required>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
	get_unpaid_by_id();
}
function add_paid_policy(){
	$("#mainModalLabel").empty().append('Vacation Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_paid_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}
function edit_paid_selection(){
	$("#mainModalLabel").empty().append('Paid Policy');
	var inputs=' <form id="vacation_info" onsubmit="insert_paid_policy()">\
	<div class="row">\
	<div class="col-md-4">\
	<label >Name* </label>\
	<input type="hidden" class="form-control" name="vacations[id]" value="'+$("#paid_policy_id").val()+'"" id="vacation_id" required>\
	<input placeholder="Name" type="text" class="form-control" name="vacations[name]" id="vacation_name" required>\
	</div>\
	<div class="col-md-4">\
	<label>Hours are accrued*</label>\
	<select class="form-control" name="vacations[vacations_type_id]" id="vacations_type_id" onchange="change_vacation_type()" required >\
	'+accrued+'\
	</select>\
	</div>\
	</div>\
	<div class="row" id="hours">\
	<div class="col-md-4">\
	<label >Hours per year* </label>\
	<input placeholder="Hours per year" type="number" class="form-control" name="vacations[earn]" id="hours_per_year" required>\
	</div>\
	<div class="col-md-4">\
	<label >Maximum allowed</label>\
	<input placeholder="Maximum allowed" type="number" class="form-control" name="vacations[maximum]" id="maximium">\
	</div>\
	</div>\
	<div class="col-md-4">\
	<br>\
	<div class="row">\
	<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>\
	</div>\
	</form>';
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
	get_paid_by_id();
}
function format_date(date){
	var formated_date="";
	var array_date=date.split('T')[0].split('-');
	var array_hour=date.split('T')[1].split(':');
	formated_date+=array_date[2]+"-"+array_date[1]+"-"+array_date[0]+" "+array_hour[0]+":"+array_hour[1]+":00";
	return formated_date;
}
