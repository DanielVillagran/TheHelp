$(document).ready(function() {
    grid_load_data();
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    set_max_min_date(output, 'to_date', 'max');
    set_max_min_date(output, 'from_date', 'max');
});
$("#btn_add_new").click(function() {
    window.location.href = '/employees/newEmployee';
});
$("#from_date").change(function(){
	set_max_min_date($("#from_date").val(), 'to_date', 'min');
});

function set_max_min_date(date, input, type) {
    $("#" + input).attr(type, date);
}

$("#proccess_data").click(function(event) {
    var data = {
        'from_date': $("#from_date").val(),
        'to_date': $("#to_date").val()
    };
    $.ajax({
        type: "post",
        url: "/Export_Employees/export_excel_dos",
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
            
            fetch('/assets/files/'+data+'_Employee_Details.xlsx')
		  		.then(resp => resp.blob())
		  		.then(blob => {
		    		const url = window.URL.createObjectURL(blob);
		    		const a = document.createElement('a');
		    		a.style.display = 'none';
		    		a.href = url;
		    		// the filename you want
		    		a.download = data+'_Employee_Details.xlsx';
		    		document.body.appendChild(a);
		    		a.click();
		    		window.URL.revokeObjectURL(url);
		  })
		  .catch(() => alert('Error file!'));
		  
		  setTimeout(function() {
		    	$.ajax({
					type : "post",
					url : "/Export_Employees/unlink_file/"+data+"_Employee_Details",
					success : function(data) {
						return true;
					}
				});
			}, 3000);
        }
    });
});

$("#filter_data").click(function(event) {
	
	var tipo_fecha = $("#filter_by").val();
	var from = $("#from_date_f").val();
	var to = $("#to_date_f").val();
	
	if (!from || !to || !tipo_fecha) {
		return;
	}
    
    $.ajax({
        url: "/Employees/getListEmployee/",
        type: 'POST',
        data: {
            'search': $.trim($('#filter').val()),
            'from_date': from,
	        'to_date': to,
	        'type': tipo_fecha
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
            $('#groups_grid tbody').empty().append(data).trigger('footable_redraw');
            $('#groups_grid').show();
        }
    });
});

function grid_load_data() {
    $.ajax({
        url: "/Employees/getListEmployee/",
        type: 'POST',
        data: {
            'search': $.trim($('#filter').val())
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
            $('#groups_grid tbody').empty().append(data).trigger('footable_redraw');
            $('#groups_grid').show();
        }
    });
}
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var Nombre = $(this).attr('nom');
    window.location.href = '/employees/resume/' + idemp;
});