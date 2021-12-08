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
					<h2 id="title"><?php echo $title . ' ' . $this->tank_auth->get_user_name(); ?></h2>
					<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
				</div>
				<div class="col-md-6">
					<ul class="list-page-breadcrumb">
						<li><a href="/home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
						<li class="active-page" id="page_active"> <?php echo $title; ?></li>
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
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="border-radius: 25px;" onclick="detail('head')">
									<div class="box-part text-center bg-primary">
										<!-- <i class="fa fa-instagram fa-3x" aria-hidden="true"></i> -->
										<div class="title">
											<h4>Head Count:</h4>
										</div>
										<div class="text">
											<span id="head_count" ></span>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="border-radius: 25px;" onclick="detail('contestado')">
									<div class="box-part text-center bg-primary">
										<!-- <i class="fa fa-instagram fa-3x" aria-hidden="true"></i> -->
										<div class="title">
											<h4>Encuestas Contestadas:</h4>
										</div>
										<div class="text">
											<span id="contestado" ></span>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="border-radius: 25px;">
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
							<canvas id="categoria" style="height: 200px !important;"></canvas>
							<canvas id="dominio" style="height: 200px !important;"></canvas>
							<canvas id="categoria_sexo" style="height: 200px !important;"></canvas>
							<canvas id="dominio_sexo" style="height: 200px !important;"></canvas>
							<canvas id="categoria_edad" style="height: 200px !important;"></canvas>
							<canvas id="dominio_edad" style="height: 200px !important;"></canvas>
							<canvas id="categoria_tipo" style="height: 200px !important;"></canvas>
							<canvas id="dominio_tipo" style="height: 200px !important;"></canvas>
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
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>