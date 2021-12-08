var typeOfPay = null;

$(document).ready(function(){
	
});

/*-------------------------------------------------------------------------------------------------Basics*/
function addPhoneInputs() {
	alert("For now you only can add 1 phone."); return;
}

$("#employees_basics").submit(function(event) {
	event.preventDefault();
	var data = $("#employees_basics").serializeArray();
	$.ajax({
		type : "post",
		url : "/Employees/save_info_basics",
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
			swal.close();
			if (data.result && data.insert_id) {
				$("#basics_id").val(data.insert_id);
				$("#federal_basics_id").val(data.insert_id);
				$("#state_basics_id").val(data.insert_id);
				$("#excemptions_basics_id").val(data.insert_id);
				$("#vacation_basics_id").val(data.insert_id);
				
				wizard.steps("next");
				
				$("#basics_id_pay").val(data.insert_id);
				$("#basics_id_notes").val(data.insert_id);
			}
		}
	});
});

function update_employees_basics(i) {
	event.preventDefault();
	var data = $("#employees_basics_up").serializeArray();
	$.ajax({
		type : "post",
		url : "/Employees/save_info_basics",
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
			swal.close();
			if (data.result) {
				window.location.href = '/Employees/resume/'+i;
			}
		}
	});
}

/*-------------------------------------------------------------------------------------------------Pay*/

function update_employees_pay(i) {
	event.preventDefault();
	var data = $("#employees_pay_up").serializeArray();
	$.ajax({
		type: "post",
		url: "/Edit_Employees/update_pay_info/"+i,
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
			swal.close();
			if (data.result && data.insert_id) {
				window.location.href = '/Employees/resume/'+i;
			}
		}
	});
}

function Pay_saveandContinue() {
	
	var idemp = $("#basics_id_pay").val();
	
	event.preventDefault();
	var data = $("#employees_pay").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_pay_info/"+idemp,
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
			
			swal.close();
			if (data.result && data.insert_id) {
				wizard.steps("next");
			}
		}
	});
}

$("#pay_rate").change(function(event) {
    var pr = $(this).val();
    typeOfPay = pr;
	
	event.preventDefault();
	$.ajax({
		type : "post",
		url : "/Employees/pay_rate_form/" + pr,
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			$("#pay_rate_forms").html(data);
		}
	});
	
	if (pr == 3) { // Commission Only
		$("#Overtime_Pay").prop("disabled", true); document.getElementById("Overtime_Pay").checked = false;
		$("#Double_Overtime_Pay").prop("disabled", true); document.getElementById("Double_Overtime_Pay").checked = false;
		$("#Holiday_Pay").prop("disabled", true); document.getElementById("Holiday_Pay").checked = false;
		
		document.getElementById("Commission").checked = true;
		
		setTimeout(function() {
	    	get_Commission();
		}, 300);
		    	
		$("#Commission").prop("disabled", true);
		
		$("#Bereavement_Pay").prop("disabled", true); document.getElementById("Bereavement_Pay").checked = false;
	} else {
		$("#Overtime_Pay").prop("disabled", false);
		$("#Double_Overtime_Pay").prop("disabled", false);
		$("#Holiday_Pay").prop("disabled", false);
		$("#Commission").prop("disabled", false);
		$("#Bereavement_Pay").prop("disabled", false);
	}
	
});

function aa(i) {
	var a = document.getElementById(i).checked;
    if (a != 1) {
        $('#'+i+'_2').removeClass('hidden');
    } else {
        $('#'+i+'_2').addClass('hidden');
    }
    $("#typically_works").val('');
    $("#days_per_week").val('');
}

function bb(i) {
	var a = document.getElementById(i).checked;
    if (a == 1) {
        $('#'+i+'_2').removeClass('hidden');
    } else {
        $('#'+i+'_2').addClass('hidden');
        
        $('#'+i+'_3').val('');
    }
}

function get_Other_Earnings() {
	var a = document.getElementById("Other_Earnings").checked;
	if (a == 1) {
        $('#Other_Earnings_2').removeClass('hidden');
        
        //event.preventDefault();
		$.ajax({
			type : "post",
			url : "/Employees/Other_Earnings",
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$("#Other_Earnings_2").append(data);
				
				$('#addnewOther_Earningstype').removeClass('hidden');
			}
		});
   	} else {
   		$("#Other_Earnings_2").html('');
        $('#Other_Earnings_2').addClass('hidden');
        $('#addnewOther_Earningstype').addClass('hidden');
    }
}

function borrarEarningstype(cual) {
	$("#Earnings_"+cual).html('');
	
	-- addnewEarningstype_van;
	
	if (addnewEarningstype_van == 0) {
		document.getElementById("Other_Earnings").checked = false;
		$('#addnewOther_Earningstype').addClass('hidden');
	}
}

var addnewEarningstype_van = 1;
function addnewEarningstype() {
	get_Other_Earnings();
	
	++ addnewEarningstype_van;
}

function insert_newEarningsName(w){
	event.preventDefault();
	var data = $("#Earnings_name").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/insert_newEarningsName",
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
			swal.close();
			if (data.insert_id) {
				get_Earnings(w);
				
				$('#mainModal').hide();
				
				setTimeout(function() {
		        	$('#'+w).val(data.insert_id).change();
		    	}, 500);
			} else {
				$('#note_modal').html("You've entered a name that already exists. Enter a new name.");
			}
		}
	});
}

function get_Earnings(w){
	$.ajax({
		type: "post",
		url: "/Employees/get_Earnings",
		dataType: "json",
		success: function(data) {
			$("#"+w).empty().append(data.Earnings_select);
		}
	});
}

function addnewEarningsName(w){
	$("#mainModalLabel").empty().append('Add Other Earnings name');
	var inputs = "";
	
	var inputs = "<form id='Earnings_name' onsubmit='insert_newEarningsName(\""+w+"\")'>";
		inputs += '<div class="row">';
			inputs += '<div class="col-md-8">';
				inputs += '<label >Name* </label>';
				inputs += '<input placeholder="Up to 20 characters" maxlength="20" type="text" name="Earnings[Earnings]" class="form-control" id="Earnings" required>';
				inputs += '<br><label id="note_modal">Enter a name that describes the other earnings pay type, such as Retroactive Pay or Severance Pay.</label>';
			inputs += '</div>';
			inputs += '<div class="col-md-4">';
				inputs += '<br>';
				inputs += '<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			inputs += '</div>';
		inputs += '</div>';
	inputs += '</form>';
	
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}

function get_Other_Reimbursement() {
	var a = document.getElementById("Reimbursement").checked;
	if (a == 1) {
        $('#Reimbursement_2').removeClass('hidden');
        
        //event.preventDefault();
		$.ajax({
			type : "post",
			url : "/Employees/Other_Reimbursement",
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$("#Reimbursement_2").append(data);
				
				$('#addnewReimbursementtype').removeClass('hidden');
			}
		});
   	} else {
   		$("#Reimbursement_2").html('');
        $('#Reimbursement_2').addClass('hidden');
        $('#addnewReimbursementtype').addClass('hidden');
    }
}

function borrarReimbursementtype(cual) {
	$("#Reimbursement_"+cual).html('');
	
	-- addnewReimbursementtype_van;
	
	if (addnewReimbursementtype_van == 0) {
		document.getElementById("Reimbursement").checked = false;
		$('#addnewReimbursementtype').addClass('hidden');
	}
}

var addnewReimbursementtype_van = 1;
function addnewReimbursementtype() {
	get_Other_Reimbursement();
	
	++ addnewReimbursementtype_van;
}

function addnewReimbursementName(w){
	$("#mainModalLabel").empty().append('Add Reimbursement name');
	var inputs = "";
	
	var inputs = "<form id='Reimbursement_name' onsubmit='insert_newReimbursementName(\""+w+"\")'>";
		inputs += '<div class="row">';
			inputs += '<div class="col-md-8">';
				inputs += '<label >Name* </label>';
				inputs += '<input placeholder="Up to 20 characters" maxlength="20" type="text" name="Reimbursement[Reimbursement]" class="form-control" id="Reimbursement" required>';
				inputs += '<br><label id="note_modal">Enter a name that describes the reimbursement pay type, such as Mileage or Supplies.</label>';
			inputs += '</div>';
			inputs += '<div class="col-md-4">';
				inputs += '<br>';
				inputs += '<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			inputs += '</div>';
		inputs += '</div>';
	inputs += '</form>';
	
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}

function insert_newReimbursementName(w){
	event.preventDefault();
	var data = $("#Reimbursement_name").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/insert_newReimbursementName",
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
			swal.close();
			if (data.insert_id) {
				get_Reimbursement(w);
				
				$('#mainModal').hide();
				
				setTimeout(function() {
		        	$('#'+w).val(data.insert_id).change();
		    	}, 500);
			} else {
				$('#note_modal').html("You've entered a name that already exists. Enter a new name.");
			}
		}
	});
}

function get_Reimbursement(w){
	$.ajax({
		type: "post",
		url: "/Employees/get_Reimbursement",
		dataType: "json",
		success: function(data) {
			$("#"+w).empty().append(data.Reimbursement_select);
		}
	});
}

function get_Commission() {
	var a = document.getElementById("Commission").checked;
	if (a == 1) {
        $('#Commission_2').removeClass('hidden');
        
        //event.preventDefault();
		$.ajax({
			type : "post",
			url : "/Employees/Other_Commission",
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$("#Commission_2").append(data);
				
				$('#addnewCommissiontype').removeClass('hidden');
			}
		});
   	} else {
   		$("#Commission_2").html('');
        $('#Commission_2').addClass('hidden');
        $('#addnewCommissiontype').addClass('hidden');
    }
}

function borrarCommissiontype(cual) {
	$("#Commission_"+cual).html('');
	
	-- addnewCommissiontype_van;
	
	if (addnewCommissiontype_van == 0) {
		document.getElementById("Commission").checked = false;
		$('#addnewCommissiontype').addClass('hidden');
	}
}

var addnewCommissiontype_van = 1;
function addnewCommissiontype() {
	get_Commission();
	
	++ addnewCommissiontype_van;
}

function addnewCommissionName(w){
	$("#mainModalLabel").empty().append('Add Commission name');
	var inputs = "";
	
	var inputs = "<form id='Commission_name' onsubmit='insert_newCommissionName(\""+w+"\")'>";
		inputs += '<div class="row">';
			inputs += '<div class="col-md-8">';
				inputs += '<label >Name* </label>';
				inputs += '<input placeholder="Up to 20 characters" maxlength="20" type="text" name="Commission[Commission]" class="form-control" id="Commission" required>';
				inputs += '<br><label id="note_modal">Enter a name that describes the commission pay type, such as Car Sales or Warranty Sales.</label>';
			inputs += '</div>';
			inputs += '<div class="col-md-4">';
				inputs += '<br>';
				inputs += '<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			inputs += '</div>';
		inputs += '</div>';
	inputs += '</form>';
	
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}

function insert_newCommissionName(w){
	event.preventDefault();
	var data = $("#Commission_name").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/insert_newCommissionName",
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
			swal.close();
			if (data.insert_id) {
				get_Commissions(w);
				
				$('#mainModal').hide();
				
				setTimeout(function() {
		        	$('#'+w).val(data.insert_id).change();
		    	}, 500);
			} else {
				$('#note_modal').html("You've entered a name that already exists. Enter a new name.");
			}
		}
	});
}

function get_Commissions(w){
	$.ajax({
		type: "post",
		url: "/Employees/get_Commission",
		dataType: "json",
		success: function(data) {
			$("#"+w).empty().append(data.Commission_select);
		}
	});
}

function borrarHourlyRate(cual) {
	$("#Hourly_"+cual).html('');
}

function addHourlyRate() {
	//event.preventDefault();
	$.ajax({
		type : "post",
		url : "/Employees/Other_HourlyRate",
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			$("#Hourly_2").append(data);
		}
	});
}

function addnewHourlyRateName(w){
	$("#mainModalLabel").empty().append('Add Hourly rate');
	var inputs = "";
	
	var inputs = "<form id='Hourly_name' onsubmit='insert_newHourlyName(\""+w+"\")'>";
		inputs += '<div class="row">';
			inputs += '<div class="col-md-8">';
				inputs += '<label >Name* </label>';
				inputs += '<input placeholder="Up to 20 characters" maxlength="20" type="text" name="Hourly[hourlyRate]" class="form-control" id="Hourly" required>';
				inputs += '<br><label id="note_modal">Enter a name that describes the:<br><div style="margin-left: 20px;"><li><b>Job</b> - such as Cook or Dishwasher, or ...</li><li><b>Location</b> - such as Downtown or New Office, or ...</li><li><b>Special</b> - characteristic of the hourly rate</li></div></label>';
			inputs += '</div>';
			inputs += '<div class="col-md-4">';
				inputs += '<br>';
				inputs += '<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			inputs += '</div>';
		inputs += '</div>';
	inputs += '</form>';
	
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
}

function insert_newHourlyName(w){
	event.preventDefault();
	var data = $("#Hourly_name").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/insert_newHourlyName",
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
			swal.close();
			if (data.insert_id) {
				get_Hourly_rate(w);
				
				$('#mainModal').hide();
				
				setTimeout(function() {
		        	$('#'+w).val(data.insert_id).change();
		    	}, 500);
			} else {
				$('#note_modal').html("You've entered a name that already exists. Enter a new name.");
			}
		}
	});
}

function get_Hourly_rate(w){
	$.ajax({
		type: "post",
		url: "/Employees/get_Hourly_rate",
		dataType: "json",
		success: function(data) {
			$("#"+w).empty().append(data.Hourly_select);
		}
	});
}

var activities = document.getElementById("paySchedule_select");
activities.addEventListener("change", function(event) {
     var ps = $(this).val();
	
	if (ps != "" && ps != "New") {
		event.preventDefault();
		$.ajax({
			type : "post",
			url : "/Employees/get_pay_sche_days/" + ps,
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$("#Schedule").html(data);
			}
		});
	} else if (ps == "New") {
		newSchedule();
	}
});

function get_sq(ps) {
	if (ps != "" && ps != "New") {
		$.ajax({
			type : "post",
			url : "/Employees/get_pay_sche_days/" + ps,
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$("#Schedule").html(data);
			}
		});
	} else if (ps == "New") {
		newSchedule();
	}
}

function newSchedule(i) {
	var idEmp = i || 0;
	
	$("#mainModalLabel").empty().append('Pay Schedule');
	var inputs = "";
	
	var inputs = "<form id='Schedules' onsubmit='insert_newSchedule("+idEmp+")'>";
		inputs += '<div class="row">';
			inputs += '<div class="col-md-6">';
				inputs += "<label>Description (Job Description / Title)</label>";
				inputs += '<input type="text" class="form-control" id="paySchedule_name" name="paySchedule[paySchedule_name]" required>';
			inputs += '</div>';
		inputs += '</div><br>';
		inputs += '<div class="row">';
			inputs += '<div class="col-md-6">';
				inputs += '<label>How often do you pay employee?</label>';
				inputs += '<select class="form-control" name="paySchedule[How_often]" id="How_often" required>';
                    inputs += "<option value='Every Week' selected='selected'>Every Week</option>";
                    inputs += "<option value='Every Other Week'>Every Other Week</option>";
                    inputs += "<option value='Twice a Month'>Twice a Month</option>";
                    inputs += "<option value='Every Month'>Every Month</option>";
                    inputs += "<option value='Biweekly'>Biweekly</option>";
                inputs += "</select>";
			inputs += '</div>';
		inputs += '</div><br>';
		inputs += '<div class="row">';
			inputs += '<div class="col-md-6">';
				inputs += "<label>When's the next payday? *</label>";
				inputs += '<input type="date" class="form-control" id="next_payday" name="paySchedule[next_payday]" required>';
			inputs += '</div>';
		inputs += '</div><br>';
		inputs += '<div class="row">';
			inputs += '<div class="col-md-6">';
				inputs += "<label>When's the last day of work (pay period) for that payday?*</label>";
				inputs += '<input type="date" class="form-control" id="last_day_of_work" name="paySchedule[last_day_of_work]" required>';
			inputs += '</div>';
		inputs += '</div>';
		inputs += '<div class="row">';
			inputs += '<div class="col-md-4">';
				inputs += '<br>';
				inputs += '<button type="submit" class="btn btn-success ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			inputs += '</div>';
		inputs += '</div>';
	inputs += '</form>';
	
	$("#body_modal").empty().append(inputs);
	$("#mainModal").show();
	
	if (idEmp == 0) {
		$('#Schedule').html('');
	} else {
		setTimeout(function() {
	    	fill_Schedule_modal(idEmp);
		}, 500);
	}
	
}

function fill_Schedule_modal(i) {
	$.ajax({
		type : "post",
		url : "/Edit_Employees/get_pay_sche_days_for_edit/" + i,
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			var a = data[0].paySchedule_name;
			var b = data[0].How_often;
			var c = data[0].next_payday;
			var d = data[0].last_day_of_work;
			
			$('#paySchedule_name').val(a);
			$('#How_often').val(b).change();
			$('#next_payday').val(c).change();
			$('#last_day_of_work').val(d).change();
		}
	});
}

function insert_newSchedule(i) {
	event.preventDefault();
	$.ajax({
		type: "post",
		url: "/Employees/insert_newSchedule/" + i,
		data: $("#Schedules").serializeArray(),
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
			if (data.insert_id) {
				get_paySchedule();
				$('#mainModal').hide();
				
				setTimeout(function() {
		        	$('#paySchedule_select').val(data.insert_id).change();
		        	get_sq(data.insert_id);
		    	}, 500);
			} else {
				$('#note_modal').html("You've entered a name that already exists. Enter a new name.");
			}
		}
	});
}

function get_paySchedule(){
	$.ajax({
		type: "post",
		url: "/Employees/get_paySchedule",
		dataType: "json",
		success: function(data) {
			$("#paySchedule_select").empty().append(data.paySchedule_select);
		}
	});
}

$("#pay_type").change(function(event) {
    var pt = $(this).val();
	
	event.preventDefault();
	$.ajax({
		type : "post",
		url : "/Employees/pay_type_form/" + pt,
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			$("#pay_type_forms").html(data);
		}
	});
});

function check_uncheck(id, value) {

	if (value != 0 && value != 1) {
		document.getElementById(id).checked = true;
		$("#"+id+"_3").val(value);
		bb(id);
	}
	
	if (value == 1) {
		document.getElementById(id).checked = true;
	} 
	
	if (value == 0) {
		document.getElementById(id).checked = false;
	}

}

function get_Additional_by_id(where, idEmployee) {
	var a = document.getElementById(where).checked;
	if (a == 1) {
        $('#'+where+'_2').removeClass('hidden');
        
        //event.preventDefault();
		$.ajax({
			type : "post",
			url : "/Edit_Employees/get_"+where+"_by_id/" + idEmployee,
			beforeSend : function() {
				swal({
					title : "Cargando",
					showConfirmButton : false,
					imageUrl : "/assets/images/loader.gif"
				});
			},
			success : function(data) {
				swal.close();
				$('#'+where+'_2').append(data);
				
				$('#addnew'+where+'type').removeClass('hidden');
			}
		});
   	} else {
   		$('#'+where+'_2').html('');
        $('#'+where+'_2').addClass('hidden');
        $('#addnew'+where+'type').addClass('hidden');
    }
}

function get_Hourly_by_id(where, idEmployee) {
	$('#'+where).removeClass('hidden');
        
    //event.preventDefault();
	$.ajax({
		type : "post",
		url : "/Edit_Employees/get_HourlyRate_by_id/" + idEmployee,
		beforeSend : function() {
			swal({
				title : "Cargando",
				showConfirmButton : false,
				imageUrl : "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			$('#'+where).append(data);
		}
	});
}

/*-------------------------------------------------------------------------------------------------Notes*/

function Notes_save() {
	var idemp = $("#basics_id_notes").val();
	event.preventDefault();
	var data = $("#employees_notes").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_employee_note/"+idemp,
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
			swal.close();
			if (data.result && data.insert_id) {
				window.location.href = '/Employees';
			}
		}
	});
}

function Notes_update(i) {
	event.preventDefault();
	var data = $("#employees_notes").serializeArray();
	$.ajax({
		type: "post",
		url: "/Edit_Employees/update_employee_note/"+i,
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
			swal.close();
			if (data.result) {
				window.location.href = '/Employees/resume/'+i;
			}
		}
	});
}
