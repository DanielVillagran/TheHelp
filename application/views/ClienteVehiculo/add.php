<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Detalles del vehiculo</li>
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
                                    <p class="title-sec">Detalles del vehiculo</p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agendar-cita">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Marca</label>
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[marca]" disabled class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Modelo</label>
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[modelo]" disabled class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Serie</label>
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[serie]" disabled class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Año</label>
                                            <div class="floating-label-group">

                                                <input type="number" id="" name="users[anio]" disabled class="form-control input-form" required />

                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <textarea id="descripcion" name="users[descripcion]" readonly class="form-control input-form-area" rows="4" required></textarea>

                                            </div>
                                        </div>

                                        <div id="tableServiciosDiv" style="display:none" class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Servicios</label>
                                            <div class="table-responsive d-table-lg">
                                                <table id="groups_grid" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="10">
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
                                        <div id="tableTicketsDiv" style="display:none" class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="col-md-6">
                                                <label>Tickets</label>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" onclick='$("#exampleModalCenter").modal("toggle");' class="btn btn-guardar next-step ">Crear Ticket</button>
                                            </div>
                                            <div class="table-responsive d-table-lg">
                                                <table id="tickets_grid" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="10">
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
                                        <div id="qrCodeDiv" style="display:none" class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Click en QR para descargar</label>
                                            <br><br>
                                            <div onclick="downloadAsImage()" id="qrcode"></div>
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
<!-- Modal -->
<div class="modal fade modal-ubicacion" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <br><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="d-form-agregar-dep">
                            <form id="TicketsInfo" enctype="multipart/form-data" class="form-agendar-cita">
                                <input type="hidden" name="tickets[id]" id="id" class="form-control" value="0" />
                                <input type="hidden" name="tickets[vehiculoId]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                <div class="form-group col-lg-6 col-md-6 pl-0">
                                    <select class="form-control input-form input-contacto-principal" name="tickets[tipoServicioId]">
                                        <option hidden>Seleccionar Tipo de Ticket</option>
                                        <?php echo $tipos_servicios; ?>
                                    </select>
                                </div>

                                <br>
                                <div class="form-group col-lg-12 col-md-12 pl-0">
                                    <div class="floating-label-group">
                                        <textarea id="descripcion" name="tickets[descripcion]" class="form-control input-form-area" rows="4" required></textarea>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="save_Ticket()" class="btn btn-guardar" data-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>