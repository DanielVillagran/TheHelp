$(document).ready(function() {
    if ($("#federal_id").length == 1) {
      	get_info_federal($("#federal_id").val());
      	get_info_state($("#state_id").val());
      	get_info_exem($("#exem_id").val());
    } 
});
function get_info_federal(id){
	$.ajax({
        type: "post",
        url: "/Edit_Employees/get_info_federal",
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
        	$("#federal_basics_id").val(data.basics_id);
        	$("#social_security_number").val(data.social_security_number);
        	$("#federal_filing_status_id").val(data.federal_filing_status_id);
        	$("#allowances").val(data.allowances);
        	$("#additional_withholdings").val(data.additional_withholdings);
        	swal.close();
        }
    });


}
function get_info_state(id){
	$.ajax({
        type: "post",
        url: "/Edit_Employees/get_info_state",
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
        	$("#state_basics_id").val(data.basics_id);
        	$("#filing_status_id").val(data.filing_status_id);
        	$("#allowances_state").val(data.allowances);
        	$("#additional_amount").val(data.additional_amount);
        	swal.close();
        }
    });


}
function get_info_exem(id){
	$.ajax({
        type: "post",
        url: "/Edit_Employees/get_info_exem",
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
        	$("#excemptions_basics_id").val(data.basics_id);
        	if(data.futa!=0){
            	$('#futa').prop('checked', true);
            	change_futa();
            }
            if(data.social_security_medicare!=0){
            	$('#social_security_medicare').prop('checked', true);
            }
            if(data.sui_ett!=0){
            	$('#sui_ett').prop('checked', true);
            }
            if(data.sdi!=0){
            	$('#sdi').prop('checked', true);
            }
        	$("#futa_type_id").val(data.futa_type_id);
        	swal.close();
        }
    });
}


function send_taxes_info(event){
	event.preventDefault();
	var data = $("#taxes_info").serializeArray();
	$.ajax({
		type: "post",
		url: "/Employees/save_info_taxes",
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
			if ($("#federal_id").length != 1) {
                wizard.steps("next");
            }
			swal.close();
		}
	});
}
function change_futa(){
	if($("#futa").prop("checked") == true){
		$("#futa_type_id_div").show();
		$("#futa_type_id").prop('required',true); 
	}else{
		$("#futa_type_id_div").hide();
		$("#futa_type_id").prop('required',false);
	}

}
function format_date(date){
	var formated_date="";
	var array_date=date.split('T')[0].split('-');
	var array_hour=date.split('T')[1].split(':');
	formated_date+=array_date[2]+"-"+array_date[1]+"-"+array_date[0]+" "+array_hour[0]+":"+array_hour[1]+":00";
	return formated_date;
}
