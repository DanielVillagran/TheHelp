<script src="<?php echo base_url(); ?>assets/js/lib/mdb.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lib/mdb.min.js"></script>
<section class="main-container">
    <div class="container-fluid">

        <div class="row row-home-denuncias">
            <div class="col-lg-12 col-md-12">



                <div class="d-home">
                    <div class="denuncias-entrantes">
                        <p class="title-inicio">Denuncias entrantes</p>
                        <div class="table-responsive table-borderless table-denuncias">
                            <table class="table" id="noa_grid">
                                <thead>
                                    <tr>
                                        <th class="th-foto">Foto</th>
                                        <th class="th-descripcion">Descripción</th>
                                        <th class="th-tipo">Tipo</th>
                                        <th class="th-fecha">Fecha</th>
                                        <th class="th-ubicacion">Ubicación</th>
                                        <th class="th-btn"></th>
                                    </tr>
                                </thead>
                                <tbody>

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
                    <div class="denuncias-atendidas">
                        <p class="title-inicio">Denuncias atendidas</p>


                        <div class="table-responsive table-borderless table-denuncias">
                            <table class="table" id="atendidas">
                                <thead>
                                    <tr>
                                        <th class="th-foto">Foto</th>
                                        <th class="th-descripcion">Descripción</th>
                                        <th class="th-tipo">Tipo</th>
                                        <th class="th-fecha">Fecha</th>
                                        <th class="th-ubicacion">Ubicación</th>
                                        <th class="th-btn"></th>
                                    </tr>
                                </thead>
                                <tbody>

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


</section>




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
