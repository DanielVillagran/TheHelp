<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar colaboradores</li>
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
                                    <p class="title-sec">Agregar Colaborador</p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />

                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[codigo]" class="form-control input-form" required />
                                                <label class="floating-label">Código *</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[apellido_paterno]" class="form-control input-form" required />
                                                <label class="floating-label">Apellido paterno *</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[apellido_materno]" class="form-control input-form" required />
                                                <label class="floating-label">Apellido materno *</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[nombre]" class="form-control input-form" required />
                                                <label class="floating-label">Nombre *</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Tipo de periodo *</span>
                                            <select class="form-control input-form " name="users[tipo_periodo]">
                                                <option hidden>Seleccionar tipo de periodo</option>
                                                <option value="Quincenal">Quincenal</option>
                                                <option value="Semanal">Semanal</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Departamento *</span>
                                            <select class="form-control input-form " id="empresa_select" name="users[departamento]">
                                                <option hidden>Seleccionar departamento</option>
                                                <?php echo $departamentos; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Horario *</span>
                                            <select class="form-control input-form " id="horario_select" name="users[horario_id]">
                                                <option hidden>Seleccionar horario</option>
                                                
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Nombre de nomina en Nomipaq *</span>
                                            <select class="form-control input-form " name="users[nomina_nomipaq]">
                                                <option hidden>Seleccionar nomina</option>
                                                <option value="Operativa 1">Operativa 1</option>
                                                <option value="Operativa 2">Operativa 2</option>
                                                <option value="Comude">Comude</option>
                                                <option value="12 Horas">12 Horas</option>
                                                <option value="FMC">FMC</option>
                                                <option value="Pisa 12 horas">Pisa 12 horas</option>
                                                <option value="Rush">Rush</option>
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