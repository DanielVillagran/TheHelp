<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar servicio</li>
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
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Agregar servicio</p>
                                    <canvas></canvas>
                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agendar-cita">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <select class="form-control input-form input-contacto-principal" name="users[vehiculoId]">
                                                <option hidden>Seleccionar Vehiculo</option>
                                                <?php echo $vehiculos; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <select class="form-control input-form input-contacto-principal" name="users[tipoServicioId]">
                                                <option hidden>Seleccionar Tipo de Servicio</option>
                                                <?php echo $tipos_servicios; ?>
                                            </select>
                                        </div>

                                        <br>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <textarea id="descripcion" name="users[descripcion]" class="form-control input-form-area" rows="4" required></textarea>

                                            </div>
                                        </div>
                                        <legend></legend>

                                        <div style="margin-top: 15px" class="form-group col-lg-12 col-md-12 pl-0">
                                            <button type="submit" class="btn btn-guardar next-step">Guardar</button>
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