<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">


<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Logotipos</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section style="padding-top:30px; padding-bottom: 40px" class="main-container">
    <div class="container-fluid">
        <div id="grid_group" class="row">
            <div class="col-md-12">
                <div class="widget-wrap material-table-widget">
                    <div class="widget-container margin-top-0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                            <div class="d-form-logos">
                        <form action="" class="form-logos">
                            <div class="form-row">
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/login.jpg)">

                                       </div>

                                        <p class="t1">Iniciar sesión</p>
                                        <p class="t2">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo1" onchange="send_image(1,'#archivo1')">
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-admin-principal.jpg)">

                                       </div>

                                        <p class="t1 one-line">Administrador principal</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo2" onchange="send_image(2,'#archivo2')">
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-admin-secundario-dif.jpg)">

                                       </div>

                                        <p class="t1 one-line">Administrador secundario DIF</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo3" onchange="send_image(3,'#archivo3')">
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-admin-secundario-oda.jpg)">

                                       </div>

                                        <p class="t1 one-line">Administrador secundario ODAPAZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo4" onchange="send_image(4,'#archivo4')">
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-admin-secundario-imc.jpg)">

                                       </div>

                                        <p class="t1 one-line">Administrador secundario IMCUFIDEZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo5" onchange="send_image(5,'#archivo5')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-app.jpg)">

                                       </div>

                                        <p class="t1 one-line">Inicio App</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo6" onchange="send_image(6,'#archivo6')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-app-dif.jpg)">

                                       </div>

                                        <p class="t1 one-line">Icono inicio DIF</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo7" onchange="send_image(7,'#archivo7')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-app-imc.jpg)">

                                       </div>

                                        <p class="t1 one-line">Icono inicio IMCUFIDEZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo8" onchange="send_image(8,'#archivo8')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-app-oda.jpg)">

                                       </div>

                                        <p class="t1 one-line">Icono inicio ODAPAZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo9" onchange="send_image(9,'#archivo9')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-dif.jpg)">

                                       </div>

                                        <p class="t1 one-line">Inicio App DIF</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo10" onchange="send_image(10,'#archivo10')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-imc.jpg)">

                                       </div>

                                        <p class="t1 one-line">Inicio App IMCUFIDEZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo11" onchange="send_image(11,'#archivo11')">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-12">
                                    <div class="d-item-file-logo">

                                       <div class="d-img-file-logo" style="background-image: url(../assets/images/logotipos-ejemplo/logo-inicio-oda.jpg)">

                                       </div>

                                        <p class="t1 one-line">Inicio App ODAPAZ</p>
                                        <p class="t2 one-line">Formato PNG</p>
                                        <!--file input example -->
                                        <span class="control-fileupload">
                                            <label for="file" class="one-line">Seleccionar logotipo</label>
                                            <input type="file" class="input-file-icon" id="archivo12" onchange="send_image(12,'#archivo12')">
                                        </span>
                                    </div>
                                </div>
                                

                               


                            </div>
                        </form>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Modal -->
<div class="modal fade modal-servicios" id="modalNuevoServicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo servicio</h5>
                <button type="button" class="close" onclick='limpiar_form_modal();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">
                <form action="" class="form-nuevo-servicio">
                    <div class="form-group">
                        <div class="floating-label-group">
                            <input type="text" id="nombre" name="sevicios[nombre]" class="form-control input-form" required />
                            <label class="floating-label">Nombre *</label>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;" class="form-group">
                        <div class="floating-label-group">
                            <textarea id="descripcion" name="sevicios[descripcion]" class="form-control input-form-area" rows="4" required ></textarea>
                            <label class="floating-label">Descripción *</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-lg-6 col-md-6 col-xs-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="citas">
                                <label class="form-check-label" for="citas">Permite citas</label>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-xs-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pagos">
                                <label class="form-check-label" for="pagos">Permite pagos</label>
                            </div>
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" id="eliminar_s" style="display:none;" class="btn btn-secondary" onclick="eliminar_servicio();">Eliminar</button>
                <button type="button" class="btn btn-guardar" onclick="agregar_servicio();">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
