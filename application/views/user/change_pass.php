<section class="main-container">


	<script type="text/javascript">
		$(document).ready(function() {


			$("#guardado").click(function() {

				var  clave1 = document.getElementById("pas1").value;
                var clave2 = document.getElementById("pas2").value;

			if (( clave1 ==  clave2)  && ( clave1 != "" ) && ( clave2!= "" )){

							var parametros = {
                                  "clave" :  Sha256.hash(clave1)
							};  

							$.ajax({
								data:  parametros,
								dataType:"json",
								url:   '/user/cambia_contrasena',  
								type:  'post',
								success:  function (response) { 
									var status = response.status;
									//console.log(response);
									if ( status == 'ok' ){
											//alert ( " se ha cambiado la contraseña del usuario");
									         swal("LISTO","Se ha cambiado la contraseña con exito","success");
									     
									} 							   
								}
								
							});

				}else{
			
					if (( clave1 == "") || ( clave2 == "" ) ){
					//	alert("capture los campos de la contraseña que desea cambiar.");
						swal("DATOS INCOMPLETOS","capture los campos de la contraseña que desea cambiar.","warning");
						}else{
							//alert("la contraseña proporcionada no coincide en los dos campos que capturo");
							swal("CONTRASEÑAS NO IGUALES","Las contraseñas no son iguales.","warning");
							document.getElementById("pas1").value = empty;
							document.getElementById("pas2").value = empty;
						}
			
				}

    	});

		});
	</script>




	<div class="container-fluid">
		<div class="page-header filled full-block light">
			<div class="row">
				<div class="col-md-6">
					<h2><?php echo $title; ?></h2>
				</div>
				<div class="col-md-6">
					<ul class="list-page-breadcrumb">
						<li><a href="/home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
						<li class="active-page"> <?php echo $title; ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div id="form_reports" class="row" style="">
			<div class="col-md-12">
				<div class="widget-wrap">
					<div class="widget-container margin-top-0 clearfix">
						<div class="widget-content">
							<script src="/assets/js/lib/jquery.js"></script>
							<script src="/assets/js/jquery.parsley/dist/parsley.min.js"></script>
							<form id="forma_reports" method="post" data-parsley-validate="" class="j-forms" enctype="multipart/form-data">
								<input type="hidden" name="reports[id]" id="reports_id" readonly="readonly" />
								<div class="form-content">
									<div class="row">
										<div class="col-md-12 unit">
											<div id="serverresponse"></div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">

											<span class="input-group-text" id="addon-wrapping">Contraseña:</span>

											<input type="password" id ="pas1" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping">
											<span class="input-group-text" id="addon-wrapping">Repetir Contraseña:</span>

											<input type="password" id = "pas2" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="addon-wrapping">
										</div>
									</div>



									<!--div class="row">
                                    <div class="col-md-12 unit">
                                    	<label class="label" for="reportss_module_id">M&oacute;dulo</label>
                                    	<label class="input select">
                                    		<select class="form-control" id="reports_module_id" name="reports[module_id]" required><?php
																																	?></select>
                                    		<i></i>
                                    	</label>
                                    </div>
                                </div-->
								</div>
								<div class="form-footer block-form-footer">
									<div id="sendform">
										<button type="button" id="guardado" class="btn btn-success primary-btn">Guardar</button>
										<button type="button" id="btn_back_reports" class="btn btn-danger primary-btn">Cancelar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>