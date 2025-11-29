<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar respuestas</li>
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
                                    <p class="title-sec">Agregar respuestas</p>

                                    <form id="form-respuestas" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Fecha *</span>
                                                <input
                                                    type="date"
                                                    id="fecha"
                                                    name="users[fecha]"
                                                    class="form-control input-form"
                                                    value="<?php echo date('Y-m-d'); ?>"
                                                    required />
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Empresa *</span>
                                            <select class="form-control input-form" id="empresa_select" name="users[empresa_id]">
                                                <option hidden>Seleccionar empresa</option>
                                                <?php echo $empresas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Encuesta *</span>
                                            <select class="form-control input-form" id="encuesta_select" name="users[encuesta_id]">
                                                <option hidden>Seleccionar encuesta</option>
                                                <?php echo $encuestas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Mes *</span>
                                            <select class="form-control input-form" id="mes_select" name="users[mes]">
                                                <option hidden>Seleccionar mes</option>
                                                <?php
                                                $meses = [
                                                    1 => "Enero",
                                                    2 => "Febrero",
                                                    3 => "Marzo",
                                                    4 => "Abril",
                                                    5 => "Mayo",
                                                    6 => "Junio",
                                                    7 => "Julio",
                                                    8 => "Agosto",
                                                    9 => "Septiembre",
                                                    10 => "Octubre",
                                                    11 => "Noviembre",
                                                    12 => "Diciembre"
                                                ];
                                                $mesActual = date("n");
                                                foreach ($meses as $num => $nombre) {
                                                    $selected = ($num == $mesActual) ? "selected" : "";
                                                    echo "<option value='$num' $selected>$nombre</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Año *</span>
                                            <select class="form-control input-form" id="anio_select" name="users[anio]">
                                                <option hidden>Seleccionar año</option>
                                                <?php
                                                $anioActual = date("Y");
                                                for ($anio = 2025; $anio <= $anioActual; $anio++) {
                                                    $selected = ($anio == $anioActual) ? "selected" : "";
                                                    echo "<option value='$anio' $selected>$anio</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div style="margin-top: 15px" class="form-group col-lg-6 col-md-6 pl-0 text-right">
                                            <button type="button" id="search" class="btn btn-guardar next-step">Responder encuesta</button>
                                        </div>
                                        <legend></legend>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div style="margin-top: 15px; display:none" id="addExtra" class="form-group col-lg-12 col-md-12 pl-0 text-right">
                                                <button type="button" id="btn_add_new_extra" class="btn btn-guardar next-step">Agregar extra</button>
                                            </div>
                                            <p id="introduccion" style="
                                                font-size: 2rem; 
                                                font-weight: 600; 
                                                color: #1E3A8A; 
                                                margin-bottom: 15px;
                                                line-height: 1.5;
                                            "></p>
                                            <div class="table-responsive d-table-lg">
                                                <table id="respuestas_grid" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="10">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>