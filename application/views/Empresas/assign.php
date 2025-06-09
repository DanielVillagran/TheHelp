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

<section style="padding-top:30px; padding-bottom: 40px;" class="main-container">
    <div class="container-fluid">
        <div id="grid_group" class="row">
            <div class="col-md-12">
                <div class="widget-wrap material-table-widget">
                    <div class="widget-container margin-top-0">
                        <div class="widget-content">
                            <div class="data-action-bar">
                                <p class="title-sec"><?php echo $title; ?></p>
                                <div class="row row-buscar-agregar">
                                    <div class="col-lg-6 col-md-6">
                                          </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="d-btn-agregar">
                                            <a id="btn_add_new" class="btn add-row btn-agregar"><i class="fa fa-plus"></i>Agregar asignación</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive d-table-lg">
                                <table id="groups_grid" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>

                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            
                                        </tr>
                                    </tfoot>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal-servicios modal-citas" id="modalNuevoServicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Asignar empresa</h5>
                <button type="button" class="close" onclick='limpiar_form_modal();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">

                <div class="row row-nueva-cita">
                    <div class="col-lg-8 col-md-8 col-12">
                        <form id="modal_info" class="form-nuevo-servicio form-nueva-cita">
                            <div class="form-group">
                                <span class="floating-label-text">Usuario *</span>
                                <select class="form-control input-form " name="users[user_id]">
                                    <option hidden>Seleccionar usuario</option>
                                    <?php echo $usuarios; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="floating-label-text">Empresa *</span>
                                <select class="form-control input-form " name="users[empresa_id]">
                                    <option hidden>Seleccionar empresa</option>
                                    <?php echo $empresas; ?>
                                </select>
                            </div>

                    </div>
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-guardar" onclick="save_modal();">Guardar</button>
        </div>
        </form>
    </div>
</div>