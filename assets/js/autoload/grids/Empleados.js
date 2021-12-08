var userID;

var getCurrentUserId = function() {
	$.ajax({
		url : '/Sistemas/Empleados/getCurrentUserId',
		type : 'POST',
		dataType : 'json',
		success : function(data) {
			userID = data.user_id;
		}
	});
}

function Controller(t, ruta, datos) {

    datos = datos || null; // Si hay que enviar algo al controlador

    var res = null;
	
    $.ajax({
        type : "POST",
        url : ruta,
        async : false,
        cache : false,
        data : datos,
        dataType : "json",
        beforeSend : function(e) {
			swal({
				title: "Enviando",
			    showConfirmButton: false,
			    imageUrl: "/assets/images/loader.gif"
			});
		},
        success : function(Response) {
        	swal.close();
            if (Response.Success) {
                if (t == 1) { // Tiene que regresar datos
                    if (Response.Data) {
                        res = Response.Data;
                    }
                } else if (t == 2) { // Solo notificar si se hizo o no la operación
                    res = Response.Success;
                } else if (t == 3) { // Solo regresa una cadena de texto
                    res = Response.Message;
                }
            }
        },
        error : function(Response, Error) {
        	swal.close();
            $("#grid_group .panel-body").prepend(Alerts("Error inesperado! [js-Controller[Empleados]]", 0));
        }
    });
	
    return res;
}

$(document).ready(function() {
	grid_load_data();
	getCurrentUserId();
});

$("#btn_add_new").click(function() {
	$("#nuevoUsuario").removeClass('hidden');
	$("#editarUsuario").addClass('hidden');
	$('html, body').animate({scrollTop: $("#nuevoUsuario").offset().top}, 'slow');
	$("#nuevo").removeClass('hidden');
	$('#nuevo').load('/Sistemas/Empleados/nuevoEmpleadoForm');
});

$('#groups_grid').footable().on('click', '.row-baja', function(e) {
	e.preventDefault();
	var idrow = $(this).attr('rel');
	var Nombre = $(this).attr('nom');
	if (idrow > 0) {
		swal({
			title : "¿Deseas dar de baja al usuario?",
			text : Nombre,
			type : "warning",
			showCancelButton : true,
			confirmButtonColor : "#DD6B55",
			confirmButtonText : "Si",
			cancelButtonText : "No",
			closeOnConfirm : false
		}, function() {
			var data = { 'userid' : idrow };
			var res = Controller(2, '/Sistemas/Empleados/darBajaUsuario', data);
			
			if (res) {
				$("#grid_group").prepend(Alerts("Listo!", 1, 3000));
				grid_load_data();
				$("#editarUsuario").addClass('hidden');
			} else {
				$("#grid_group").prepend(Alerts("Algo no salio bien al dar de baja al usuario!", 2, 3000));
			}
		});
	}
});

$('#groups_grid').footable().on('click', '.row-edit', function(e) {
	e.preventDefault();
	
	var idrow 	= $(this).attr('rel');
	var Nombre 	= $(this).attr('nom');
	var Tipo 	= $(this).attr('ty');
	
	$("#nuevoUsuario").addClass('hidden');
	$("#editarUsuario").removeClass('hidden');
	$("#nombreUsuario").html('Estás editando a <label>'+Nombre+'</label>');
	
	$('html, body').animate({scrollTop: $("#editarUsuario").offset().top}, 'slow');
	
	$('#passwd_view').addClass('hidden');
	$('#info').addClass('hidden');
	$('#permisos').addClass('hidden');
	$("#editarUsuario").show();
	
	switch (Tipo) {
		case '1':
			$('#passwd_view').removeClass('hidden');
	    	$('#passwd_view').load('/Sistemas/Empleados/loadPasswd/'+idrow);
	    	break;
	   	case '2':
	   		$('#info').removeClass('hidden');
	    	$('#info').load('/Sistemas/Empleados/loadInfo/'+idrow);
	   		break;
	   	case '3':
	   		$('#permisos').removeClass('hidden');
	   		$('#user_hide').val(idrow);
	    	$('#permisos').load('/Sistemas/Empleados/loadPermiso');
	   		break;
	  default:
	    return;
	}
});

function grid_load_data() {
	$.ajax({
		url : '/Sistemas/Empleados/getList_Empleados',
		type : 'POST',
		data : {
			'search' : $.trim($('#filter').val())
		},
		dataType : 'json',
		beforeSend : function(e) {
			swal({
				title: "Cargando",
				showConfirmButton: false,
				imageUrl: "/assets/images/loader.gif"
			});
		},
		success : function(data) {
			swal.close();
			$('#groups_grid tbody').empty().append(data).trigger('footable_redraw');
			$('#groups_grid').show();
		}
	});
}

function changePasswd() {
	var usr 	= $('#u').val();
	var usr2 	= $('#username').val();
	var passwd 	= $('#passwd').val();
	
	if (userID != $('#ui').val()) {
		userID = $('#ui').val();
	}

	if (usr != usr2) { $("#passwd_panel").prepend(Alerts("No puedes hacer eso!", 2, 3000)); return; }
	if (usr.length == 0) { $("#passwd_panel").prepend(Alerts("El usuario es requerido!", 2, 3000)); return; }
	if (passwd.length == 0) { $("#passwd_panel").prepend(Alerts("La contraseña es requerida!", 2, 3000)); return; }
	
	var datos = {
		'userid'	: userID,
		'username' 	: usr + "@esprezza.com",
		'password' 	: Sha256.hash(passwd)
	};
	
	var res = Controller(2, '/Sistemas/Empleados/SetNewPasswd', datos);
	
	if (res) {
		$("#passwd_panel").prepend(Alerts("Listo!", 1, 3000));
	} else {
		$("#passwd_panel").prepend(Alerts("Algo no salio bien al cambiar la contraseña!", 2, 3000));
	}
}

function saveInfo() {
	var nombres 		= $('#nombres').val();
	var ape_paterno 	= $('#ape_paterno').val();
	var ape_materno 	= $('#ape_materno').val();
	var telefono 		= $('#telefono').val();
	var Sexo 			= $('#Sexo').val();
	var Areas 			= $('#Areas').val();
	var Puestos 		= $('#Puestos').val();
	var Sucursal 		= $('#Sucursal').val();
	var JefeInmediato	= $('#JefeInmediato').val();
	var Encargado 		= $('#Encargado').val();
	
	if (userID != $('#uii').val()) {
		userID = $('#uii').val();
	}
	
	if (nombres.length == 0 || ape_paterno.length == 0 || ape_materno.length == 0 || telefono.length == 0 || Sexo.length == 0 || Areas.length == 0 || Puestos.length == 0 || Sucursal.length == 0 || JefeInmediato.length == 0 || Encargado.length == 0) { $("#info_panel").prepend(Alerts("Todos los campos son requeridos!", 2, 3000)); return; }
	
	var datos = {
		'userid'		: userID,
		'nombres' 		: nombres,
		'ape_paterno'	: ape_paterno,
		'ape_materno' 	: ape_materno,
		'telefono' 		: telefono,
		'Sexo' 			: Sexo,
		'Areas' 		: Areas,
		'Puestos' 		: Puestos,
		'Sucursal' 		: Sucursal,
		'JefeInmediato' : JefeInmediato,
		'Encargado' 	: Encargado
	};
	
	var res = Controller(2, '/Sistemas/Empleados/SetUserInfo', datos);
	
	if (res) {
		$("#info_panel").prepend(Alerts("Listo!", 1, 3000));
		grid_load_data();
	} else {
		$("#info_panel").prepend(Alerts("Algo no salio bien al guardar la información!", 2, 3000));
	}
}

function crearNuevoEmpleado() {
	
	var usr 	= $('#username_2').val();
	var passwd 	= $('#passwd_2').val();
	
	if (usr.length == 0) { $("#new_user_panel").prepend(Alerts("El usuario es requerido!", 2, 3000)); return; }
	
	if (!Controller(2, '/Sistemas/Empleados/usuarioExiste', { 'username': usr + "@esprezza.com" })){ $("#new_user_panel").prepend(Alerts("Ese usuario ya existe!", 2, 3000)); return; }
	
	if (passwd.length == 0) { $("#new_user_panel").prepend(Alerts("La contraseña es requerida!", 2, 3000)); return; }
	
	var nombres 		= $('#nombres_2').val();
	var ape_paterno 	= $('#ape_paterno_2').val();
	var ape_materno 	= $('#ape_materno_2').val();
	var telefono 		= $('#telefono_2').val();
	var Sexo 			= $('#Sexo_2').val();
	var Areas 			= $('#Areas_2').val();
	var Puestos 		= $('#Puestos_2').val();
	var Sucursal 		= $('#Sucursal_2').val();
	var JefeInmediato	= $('#JefeInmediato_2').val();
	var Encargado 		= $('#Encargado_2').val();
	
	if (nombres.length == 0 || ape_paterno.length == 0 || ape_materno.length == 0 || telefono.length == 0 || Sexo.length == 0 || Areas.length == 0 || Puestos.length == 0 || Sucursal.length == 0 || JefeInmediato.length == 0 || Encargado.length == 0) { $("#new_user_panel").prepend(Alerts("Todos los campos son requeridos!", 2, 3000)); return; }
	
	var datos = {
		'username' 		: usr + "@esprezza.com",
		'password' 		: Sha256.hash(passwd),
		'nombres' 		: nombres,
		'ape_paterno'	: ape_paterno,
		'ape_materno' 	: ape_materno,
		'telefono' 		: telefono,
		'Sexo' 			: Sexo,
		'Areas' 		: Areas,
		'Puestos' 		: Puestos,
		'Sucursal' 		: Sucursal,
		'JefeInmediato' : JefeInmediato,
		'Encargado' 	: Encargado
	};
	
	var res = Controller(2, '/Sistemas/Empleados/nuevoUsuario', datos);
	
	if (res) {
		$("#new_user_panel").prepend(Alerts("Listo! Ahora dale permisos", 1, 3000));
		grid_load_data();
		setTimeout(function() {
			$("#nuevo").addClass('hidden');
			$('#permisos_2').removeClass('hidden');
		   	$('#user_hide').val(res);
		   	$('#new_Acc_emp').html('Nuevo acceso para empleado: ' + nombres + " " + ape_paterno);
		}, 100);
		setTimeout(function() {
		   	$('#permisos_2').load('/Sistemas/Empleados/loadPermiso');
			$('html, body').animate({scrollTop: $("#permisos").offset().top}, 'slow');
		}, 100);
		
	} else {
		$("#new_user_panel").prepend(Alerts("Algo no salio bien al crear el nuevo usuario!", 2, 3000));
	}
}
