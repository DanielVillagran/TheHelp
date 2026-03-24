$(document).ready(function () {
    grid_load_data();

});
$("#btn_add_new").click(function () {
    window.location.href = '/Tickets/add';
});

function grid_load_data() {
    $.ajax({
        url: "/Tickets/get_Tickets",
        type: 'POST',
        data: {
            'search': $.trim($('#filter').val())
        },
        dataType: 'json',
        beforeSend: function (e) {
            swal({
                title: "Cargando",
                showConfirmButton: false,
                imageUrl: "/assets/images/loader.gif"
            });
        },
        success: function (data) {
            swal.close();
           if ($.fn.DataTable.isDataTable('#groups_grid')) {
                $('#groups_grid').DataTable().destroy();
            }
           $('#groups_grid thead').empty().append(data.head);
            $('#groups_grid tbody').empty().append(data.table);
            $('#groups_grid').show();
            inicializarDatatable('#groups_grid');
        }
    });
}
$('#groups_grid').footable().on('click', '.row-delete', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    swal({
        title: "<p id='pswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        html: "<p id='psswalerror'>Estas seguro que deseas eliminar este elemento?</p>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0066D1",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }, function () {

            $.ajax({
                url: "/Tickets/eliminar",
                type: 'POST',
                data: {
                    'id': idemp
                },
                dataType: 'json',
                beforeSend: function (e) {

                },
                success: function (data) {
                    if (data && data.status === false) {
                        swal('Error!', data.message, 'error');
                        return;
                    }
                    grid_load_data();
                }
            });

    });
});
$('#groups_grid').footable().on('click', '.row-edit', function (e) {
    e.preventDefault();
    var idemp = $(this).attr('rel');
    var tipo = $(this).attr('tipo');
    var tipoServicioId = parseInt($(this).attr('tipo-servicio-id') || "0", 10);
    var Nombre = $(this).attr('nom');
    if (tipo) {
        var modalHtml = '<div style="margin-top:15px;">' +
            '<textarea id="ticket-comentario-cierre" class="form-control" placeholder="Comentario" style="width:100%; min-height:100px; resize:vertical;"></textarea>' +
            (tipoServicioId === 2 ? '<div style="margin-top:15px; text-align:left;"><input id="ticket-documento-cierre" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display:block !important; width:100% !important; height:auto !important; opacity:1 !important; position:static !important; visibility:visible !important; border:none; box-shadow:none; padding:0;"></div>' : '') +
            '</div>';
        swal({
            title: "Completar ticket",
            text: (tipoServicioId === 2 ? "Agrega un comentario y adjunta un documento." : "Agrega un comentario para completar el ticket.") + modalHtml,
            type: "warning",
            html: true,
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonColor: "#0066D1",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }, function () {
            var comentario = $.trim($("#ticket-comentario-cierre").val());
            var documentoInput = $("#ticket-documento-cierre")[0];
            var documento = documentoInput && documentoInput.files.length ? documentoInput.files[0] : null;

            if (comentario === "") {
                swal.showInputError("Debes capturar un comentario.");
                return false;
            }
            if (tipoServicioId === 2 && !documento) {
                swal.showInputError("Debes adjuntar un documento.");
                return false;
            }

            var data = new FormData();
            data.append('id', idemp);
            data.append('comentario', comentario);
            if (documento) {
                data.append('documento', documento);
            }

            $.ajax({
                url: "/Tickets/completar",
                type: 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function (e) {
                    swal({
                        title: "Cargando",
                        showConfirmButton: false,
                        imageUrl: "/assets/images/loader.gif"
                    });
                },
                success: function (data) {
                    swal.close();
                    if (data && data.status === false) {
                        swal('Error!', data.message, 'error');
                        return;
                    }
                    grid_load_data();
                    if (typeof updateTicketsPendientesBadge === 'function') {
                        updateTicketsPendientesBadge();
                    }
                }
            });

        });
    } else {
        window.location.href = '/Tickets/edit/' + idemp;
    }
});
