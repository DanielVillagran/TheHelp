<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar empresa</li>
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
                                    <p class="title-sec">Agregar empresa</p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <span class="floating-label-text">Razon social *</span>
                                            <select class="form-control input-form " name="users[razon_social_id]">
                                                <option hidden>Seleccionar empresa</option>
                                                <?php echo $razones; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[nombre]" class="form-control input-form" required />
                                                <label class="floating-label">Nombre del cliente *</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Tipo de nómina *</span>
                                            <select class="form-control input-form " name="users[tipo_nomina]">
                                                <option hidden>Seleccionar tipo de nómina</option>
                                                <?php echo $nominas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Tipo de facturación *</span>
                                            <select class="form-control input-form " name="users[tipo_facturacion]">
                                                <option hidden>Seleccionar tipo de facturación</option>
                                                <?php echo $facturacion; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <textarea name="users[comentarios]" class="form-control input-form-area" rows="2"></textarea>
                                                <label class="floating-label">Comentarios</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Días de crédito *</span>
                                                <input type="number" name="users[dias_credito]" class="form-control input-form" />
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Responsable *</span>
                                            <select class="form-control input-form " name="users[responsable]">
                                                <option hidden>Seleccionar responsable</option>
                                                <?php echo $responsables; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Municipio *</span>
                                            <select class="form-control input-form " name="users[municipio]">
                                                <option hidden>Seleccionar municipio</option>
                                                <?php echo $municipios; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Zona *</span>
                                            <select class="form-control input-form " name="users[zona]">
                                                <option hidden>Seleccionar zona</option>
                                                <?php echo $zonas; ?>
                                            </select>
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