<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page"><?php echo $title ?></li>
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
                                    <p class="title-sec"><?php echo $title ?></p>

                                    <form id="form-asistencias" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Fecha Inicio*</span>
                                                <input type="date" id="fecha_inicio" name="users[fecha_inicio]" class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Fecha Fin*</span>
                                                <input type="date" id="fecha_fin" name="users[fecha_fin]" class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Empresa *</span>
                                            <select class="form-control input-form" id="empresa_select" name="users[empresa]">
                                                <option hidden>Seleccionar empresa</option>
                                                <?php echo $empresas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Sede *</span>
                                            <select class="form-control input-form" id="select_sede" name="users[sede]">
                                                <option hidden>Seleccionar sede</option>

                                            </select>
                                        </div>
                                        <div style="margin-top: 15px" class="form-group col-lg-6 col-md-6 pl-0 text-right">
                                            <button type="button" id="search" class="btn btn-guardar next-step">Ver reporte</button>
                                        </div>
                                        <legend></legend>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">

                                            <div class="table-responsive d-table-lg">
                                                <table id="asistencias_grid" style="width:100%" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>

                                                    <tfoot class="hide-if-no-paging">
                                                        <tr>
                                                            <td colspan="7" class="footable-visible">
                                                                <div class="pagination pagination-centered"></div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <legend></legend>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<div class="modal fade modal-servicios modal-citas" id="modalExtra" tabindex="-1" role="dialog" aria-labelledby="modalHorarioTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalHorarioTitle">Agregar extra</h5>
                <button type="button" class="close" onclick="limpiar_form_extra();" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <input type="hidden" id="id_horario" value="0">
            <div class="modal-body">
                <div class="row row-nueva-cita">
                    <div class="col-lg-12 col-md-12 col-12">
                        <form id="modal_extra" class="form-nuevo-servicio form-nueva-cita">
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <span class="floating-label-text">Horario *</span>
                                <select class="form-control input-form" id="select_horario" name="puesto[horario_id]">
                                    <option hidden>Seleccionar horario</option>

                                </select>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <span class="floating-label-text">Puesto *</span>
                                <select class="form-control input-form" name="puesto[puesto_id]">
                                    <option hidden>Seleccionar puesto</option>
                                    <?php echo $puestos; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <span class="floating-label-text">Colaborador *</span>
                                <select class="form-control input-form" id="select_colaboradores" name="puesto[colaborador_id]">
                                    <option hidden>Seleccionar colaborador</option>

                                </select>
                            </div>



                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-guardar" onclick="save_extra();">Guardar</button>
            </div>
        </div>
    </div>
</div>