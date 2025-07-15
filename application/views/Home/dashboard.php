<script src="<?php echo base_url(); ?>assets/js/lib/mdb.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lib/mdb.min.js"></script>
<style>
    .card-custom {
        background-color: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        border: 1px solid #e0e0e0;
        padding: 20px;
    }

    .card-custom h4 {
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 18px;
        color: #333;
    }

    .form-inline .form-group {
        margin-bottom: 10px;
    }

    canvas {
        margin-top: 20px;
    }

    .chart-container {
        width: 50%;
        position: relative;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<section class="main-container">
    <div class="container-fluid">
        <div class="row" style="margin-top: 20px;">
            <!--Grafica Asistencias vs Faltas -->
            <div class="col-md-6" style="margin-top: 20px;">
                <div class="card-custom">
                    <h4 class="text-center">Asistencias vs Faltas</h4>
                    <form id="form_filtros_asistencias" class="form-inline text-center" style="margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="asistencias_razon_social">Razón social</label>
                            <select id="asistencias_razon_social" name="razon_social" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $razones; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="asistencias_empresa">Empresa</label>
                            <select id="asistencias_empresa" name="empresa_id" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $empresas; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="asistencias_sede">Sede</label>
                            <select id="asistencias_sede" name="sede_id" class="form-control">
                                <option value="">Todos</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="asistencias_fecha_inicio">Fecha Inicio</label>
                            <input type="date" value="<?php echo $fecha_inicio; ?>" id="asistencias_fecha_inicio" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="asistencias_fecha_fin">Fecha Fin</label>
                            <input type="date" value="<?php echo $fecha_fin; ?>" id="asistencias_fecha_fin" name="fecha_fin" class="form-control">
                        </div>
                    </form>
                    <div class="chart-container">
                        <canvas id="grafica_asistencias" width="100%"></canvas>
                    </div>
                </div>
            </div>
            <!--Grafica Facturado -->
            <div class="col-md-6" style="margin-top: 20px;">
                <div class="card-custom">
                    <h4 class="text-center">Facturado</h4>
                    <form id="form_filtros_facturados" class="form-inline text-center" style="margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="facturados_razon_social">Razón social</label>
                            <select id="facturados_razon_social" name="razon_social" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $razones; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="facturados_empresa">Empresa</label>
                            <select id="facturados_empresa" name="empresa_id" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $empresas; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="facturados_sede">Sede</label>
                            <select id="facturados_sede" name="sede_id" class="form-control">
                                <option value="">Todos</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="facturados_fecha_inicio">Fecha Inicio</label>
                            <input type="date" value="<?php echo $fecha_inicio; ?>" id="facturados_fecha_inicio" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="facturados_fecha_fin">Fecha Fin</label>
                            <input type="date" value="<?php echo $fecha_fin; ?>" id="facturados_fecha_fin" name="fecha_fin" class="form-control">
                        </div>
                    </form>
                    <div class="chart-container">
                        <canvas id="grafica_facturados" width="100%"></canvas>
                    </div>
                </div>
            </div>
            <!--Grafica Head Count -->
            <div class="col-md-6" style="margin-top: 20px;">
                <div class="card-custom">
                    <h4 class="text-center">Head Count</h4>
                    <h4 class="text-center">Activos vs Vacantes</h4>
                    <form id="form_filtros_hc" class="form-inline text-center" style="margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="hc_razon_social">Razón social</label>
                            <select id="hc_razon_social" name="razon_social" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $razones; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="hc_empresa">Empresa</label>
                            <select id="hc_empresa" name="empresa_id" class="form-control">
                                <option value="">Todos</option>
                                <?php echo $empresas; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <label for="hc_sede">Sede</label>
                            <select id="hc_sede" name="sede_id" class="form-control">
                                <option value="">Todos</option>
                            </select>
                        </div>
                    </form>
                    <div class="chart-container">
                        <canvas id="grafica_hc" width="100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
<div class="modal fade modal-ubicacion" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetalleLabel">Detalle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContenido">
                <div class="table-responsive d-table-lg" style="margin-top: 30px;">
                    <table id="tablaDetalle" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
                        <thead class="datatable_wrapper">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade modal-ubicacion" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ubicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-ubicacion" id="modalFoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Fotografía</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-foto" style="backgroud-image: url(../assets/images/bg-denuncia.jpg)">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<input type="text" value="<?php echo $user_id ?>" style="visibility: hidden" id="usuario">
<form id="frm_reporte" method="post"></form>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/home.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/autoload/reports/prospectos_report_home.js"></script>
<script src="<?php echo base_url(); ?>assets/js/autoload/reports/clientes_report_home.js"></script>
<script src="<?php echo base_url(); ?>assets/js/autoload/forms/merchandising.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lib/jquery.dataTables.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEbbrpEt4YFPGtniBWbFkO_fII3cqywUA">
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>