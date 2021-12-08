<!--Page Container Start Here-->
<style type="text/css">
	a{
		text-decoration:none;
		color: #000;
	}
	.box{
		padding:40px 0px;
		border-radius: 25px;
	}
	.box-part{
		background:#FFF;
		border-radius: 25px;
		padding:40px 10px;
		margin:30px px;
	}
	.text{
		margin:20px 0px;
	}
	.fa{
		color:#000;
	}
	.pop_font{
		font-family: "Poppins", sans-serif;
		font-weight: 500;
	}
	.text span {
		border:none;
		font-family: "Poppins", sans-serif;
		font-weight: 900;
		font-size: 14px;
	}
	.styled_span {
		border:none;
		font-family: "Poppins", sans-serif;
		font-weight: 800;
		font-size: 30px;
	}
	.nulo{
		background-color: #0CD6F0 !important;

	}
	.bajo{
		background-color: #5cb85c !important;
	}
	.medio{
		background-color: #ffdf00 !important;
	}
	.alto{
		background-color: #f0ad4e !important;
	}
	.m_alto{
		background-color: #e9573f !important;
	}
</style>
<link rel="stylesheet" type="text/css" href="/assets/css/add_report.css">
<link rel="stylesheet" type="text/css" href="/assets/css/awesome-bootstrap-checkbox.css">
<section class="main-container">
	<div class="container-fluid">
		<div class="page-header filled full-block light">
			<div class="row">
				<div class="col-md-6">
					<h2 id="title"><?php echo $title . ' ' . $this->tank_auth->get_user_name(); ?> <span id="employee_name"></span></h2>
				</div>
				<div class="col-md-6">
					<input type="hidden" id="id" value="<?php echo $id; ?>">
					<ul class="list-page-breadcrumb">
						<li><a href="/home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
						<li class="active-page" id="page_active"> <?php echo $title; ?></li>
						<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
					</ul>
				</div>
			</div>
		</div>
		<div id="form_reports" class="row" style="">
			<div class="col-md-12">
				<div class="widget-wrap">
					<div class="widget-container margin-top-0 clearfix">
						<div class="box">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="border-radius: 25px;" onclick="get_cuestionario()">
									<div id="box_calificacion" class="box-part text-center bg-primary">
										<!-- <i class="fa fa-instagram fa-3x" aria-hidden="true"></i> -->
										<div class="title">
											<h4>Calificación final del cuestionario:</h4>
										</div>
										<div class="text">
											<span id="calificacion" ></span>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="border-radius: 25px;">
									<div id="box_trauma" class="box-part text-center bg-primary">
										<!-- <i class="fa fa-instagram fa-3x" aria-hidden="true"></i> -->
										<div class="title">
											<h4>¿Ha sufrido Traumas?:</h4>
										</div>
										<div class="text">
											<span id="traumas" ></span>
										</div>
									</div>
								</div>
							</div>
							<br>
								<br>
							<table id="report_grid" class="table table-striped table-bordered table-sm" cellspacing="2" width="100%" >
								<thead>
									<tr>
										<th>Categoria</th>
										<th>Calificación general</th>
										<th>Puntaje Obtenido</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<br>
							<br>
							<table id="dominio_grid" class="table table-striped table-bordered table-sm" cellspacing="2" width="100%" >
								<thead>
									<tr>
										<th>Dominio</th>
										<th>Calificación general</th>
										<th>Puntaje Obtenido</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<br><br>
							<div class="row">
								<input type="hidden" class="form-control" required id="year" name="encu[year]">
								<input type="hidden" class="form-control" required id="e_type" name="encu[type]">
								<input type="hidden" id="clave_empleado" name="info[clave]" required>
								<input type="hidden" id="puesto" name="info[puesto]" required>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Nombre</label>
									<input type="text" class="form-control" required readonly id="name" name="info[name]">
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Apellido Paterno</label>
									<input type="text" class="form-control" required readonly id="last_name" name="info[last_name]">
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Apellido Materno</label>
									<input type="text" class="form-control" readonly required id="second_last_name" name="info[second_last_name]">
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Género</label>
									<select class="form-control" required id="gender"  disabled name="info[usuariotce_gender_id]">
										<option value="">-- Selecciona el género --</option>
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Edad en Años</label>
									<select class="form-control" required  disabled id="age" name="info[analisis_catalogo_age_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Estado Civil</label>
									<select class="form-control" required  disabled id="civil_status" name="info[analisis_catalogo_civil_status_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Ultimo grado de estudios</label>
									<select class="form-control" required  disabled id="last_grade" name="info[analisis_catalogo_last_grade_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12" style="display: none;">
									<label class="h4">&nbsp;</label><br>
									<label><input type="checkbox" id="cbox1" value="1"> Terminado?</label><br>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Area</label>
									<select class="form-control" required  disabled id="area" name="info[usuariotce_area_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Tipo de Puesto</label>
									<select class="form-control" required  disabled id="position_type" name="info[analisis_catalogo_position_type_id]">
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Tipo de contratación</label>
									<select class="form-control" required  disabled id="contract_type" name="info[analisis_catalogo_contract_type_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Tipo de jornada de trabajo</label>
									<select class="form-control" required  disabled id="workday_type" name="info[analisis_catalogo_workday_type_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Realiza Rotación de Turnos?</label><br>
									<label><input type="checkbox" id="cbox1"  disabled value="1"> Si.</label><br>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Tiempo en el puesto actual</label>
									<select class="form-control" required  disabled id="actual_experience" name="info[analisis_catalogo_actual_experience_id]">
									</select>
								</div>
								<div class="col-lg-4 col-md-6 col-sm-12">
									<label class="h4">Tiempo experiencia laboral</label>
									<select class="form-control" required  disabled id="experience" name="info[analisis_catalogo_experience_id]">
									</select>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade in" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="mainModalLabel">Modal title</h5>
						<button type="button" class="close" onclick="$('#mainModal').hide();" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="body_modal">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" onclick="$('#mainModal').hide();" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo base_url(); ?>assets/js/lib/Chart.js"></script>