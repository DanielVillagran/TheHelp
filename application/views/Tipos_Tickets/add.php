<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page"><?php echo $title; ?></li>
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
                                    <p class="title-sec"><?php echo $title; ?></p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Nombre *</span>
                                                <input type="text" name="users[nombre]" class="form-control input-form" required />
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Usuario asignado *</span>
                                                <select class="form-control input-form" name="users[usuario_asignado]" id="usuario_asignado" required>
                                                    <option value="" hidden>Seleccionar usuario</option>
                                                    <?php echo $usuarios; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <input type="hidden" name="users[con_copia_correo]" value="0" />
                                            <div class="checkbox checkbox-primary" style="display:flex; align-items:center; justify-content:flex-start; gap:12px;">
                                                <label for="con_copia_correo" style="margin:0;">Con copia de correo</label>
                                                <input type="checkbox" name="users[con_copia_correo]" id="con_copia_correo" value="1" style="position:static; margin:0;" />
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
