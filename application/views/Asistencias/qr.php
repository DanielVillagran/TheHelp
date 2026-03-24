<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">
<style>
    .qr-badge-flotante {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 1050;
        display: none;
        min-width: 260px;
        max-width: 340px;
        padding: 14px 16px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: #fff;
        box-shadow: 0 14px 32px rgba(15, 118, 110, 0.28);
    }

    .qr-badge-flotante.error {
        background: linear-gradient(135deg, #b91c1c, #ef4444);
        box-shadow: 0 14px 32px rgba(185, 28, 28, 0.28);
    }

    .qr-badge-flotante strong {
        display: block;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .qr-badge-flotante span {
        display: block;
        font-size: 12px;
        opacity: 0.95;
    }
</style>

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Registro asistencias</li>
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
                                    <p class="title-sec">Registro asistencias QR</p>

                                    <form id="form-asistencias" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <input type="hidden" id="fecha" name="users[fecha]" class="form-control" value="<?php echo date("Y-m-d"); ?>" />
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
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
                                        <input type="hidden" id="colaborador_qr_id" name="users[colaborador_id]" />

                                        <div id="qr_scanner_wrapper" class="form-group col-lg-12 col-md-12 pl-0" style="display:none;">
                                            <div class="widget-wrap material-table-widget" style="padding: 15px; border: 1px solid #e5e5e5;">
                                                <p class="title-sec" style="margin-bottom: 10px;">Lector QR</p>
                                                <p id="qr_scanner_status" style="margin-bottom: 15px;">Valida la sede para habilitar el lector.</p>
                                                <div id="qr-reader" style="width:100%; max-width:420px;"></div>
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
<div id="qr_colaborador_badge" class="qr-badge-flotante">
    <strong id="qr_colaborador_badge_nombre"></strong>
    <span id="qr_colaborador_badge_codigo"></span>
    <span id="qr_colaborador_badge_id"></span>
</div>
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
