$(document).ready(function(){
	grid_load_data();

});
$("#btn_add_report").click(function(){
	location.href="reports/add";
});
function grid_load_data() {
	$.ajax({
		url: '/reports/get_report_generated',
		type:'POST',
		data: {'id_report' : $("#report_id").val(),'search': $.trim($('#filter').val())},
		dataType:'json',
		beforeSend: function(e) {
			$('#groups_grid tbody').empty().append('<tr><td colspan="4">PROCESANDO...</td></tr>');
			swal({
				title: "Cargando",
				showConfirmButton: false,
				imageUrl: "/assets/images/loader.gif"
			});
		},
		success: function(data) {
			swal.close();
			$('#groups_grid tbody').empty().append(data.results);
			$('#groups_grid').show();
			var table = $('#groups_grid').DataTable( {
				dom: 'Bfrtip',
				"scrollX": true,
				"paging": true,
				buttons: ['copyHtml5',
				{
					extend: 'excelHtml5',
					title: 'Reporteador General'
				},
				{
					extend: 'pdfHtml5',
					title: 'Reporteador General',
					download: 'open'
				}, {
					extend: 'print',
					title: 'Reporteador General'

				}
				],language: {
					"lengthMenu": "Mostrar _MENU_ registros por pagina",
					"zeroRecords": "No hemos encontrado nada, perdon.",
					"info": "Mostrando la pagina _PAGE_ de _PAGES_",
					"infoEmpty": "No hemos encontrado nada, perdon.",
					"infoFiltered": "(Filtrado de _MAX_ registros totales)"
				}
			}
			);
			$('.dataTables_length').addClass('bs-select');

			
		}
	});
}

