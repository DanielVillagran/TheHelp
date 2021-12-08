$(document).ready(function(){
	grid_load_data();

});
$("#btn_add_report").click(function(){
	location.href="reports/add";
});
$("#btn_config_report").click(function(){
	location.href="reports/config";
});
$('#groups_grid').footable().on('click', '.row-edit', function(e) {
	e.preventDefault();
	var idrow = $(this).attr('rel');
	if (idrow>0) {
		location.href="/reports/edit/"+idrow;
	}
});
$('#groups_grid').footable().on('click', '.row-view', function(e) {
	e.preventDefault();
	var idrow = $(this).attr('rel');
	if (idrow>0) {
		location.href="/reports/view/"+idrow;
	}
});
$('#groups_grid').footable().on('click', '.row-delete', function(e) {
	e.preventDefault();
	var idrow = $(this).attr('rel');
	if (idrow>0) {
		swal({
			title: "Desea borrar el registro?",
			text: "No podra recuperarlo!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Si",
			cancelButtonText: "No",
			closeOnConfirm: false
		}, function() {
			$.ajax({
				url: '/reports/del_report',
				type:'POST',
				data: {'id': idrow},
				dataType:'json',
				success: function(data) {
					if (data.result) {
						swal({
							title: "El registro ha sido borrado.",
							type: "success",
							confirmButtonColor: "#4caf50"
						}, function(){
							grid_load_data();
						});
					} else {
						swal("Error", data.message, "error");
					}
				}
			});
		});
	}
});
function grid_load_data() {
	$.ajax({
		url: '/reports/get_reports',
		type:'POST',
		data: {'search': $.trim($('#filter').val())},
		dataType:'json',
		beforeSend: function(e) {
			$('#groups_grid tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
		},
		success: function(data) {
			$('#groups_grid tbody').empty().append(data).trigger('footable_redraw');
			$('#groups_grid').show();
		}
	});
}
