<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar vehiculo</li>
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
                                    <p class="title-sec">Agregar vehiculo</p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agendar-cita">
                                        <input type="hidden" id="id" name="users[id]" class="form-control" value="<?php echo $id; ?>" />
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <select class="form-control input-form input-contacto-principal" name="users[clienteId]">
                                                <option hidden>Seleccionar Cliente</option>
                                                <?php echo $clientes; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[marca]" class="form-control input-form" required />
                                                <label class="floating-label">Marca *</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[modelo]" class="form-control input-form" required />
                                                <label class="floating-label">Modelo *</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" id="" name="users[serie]" class="form-control input-form" required />
                                                <label class="floating-label">Serie *</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <div class="floating-label-group">
                                                <input type="number" id="" name="users[anio]" class="form-control input-form" required />
                                                <label class="floating-label">Año *</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <textarea id="descripcion" name="users[descripcion]" class="form-control input-form-area" rows="4" required></textarea>

                                            </div>
                                        </div>
                                        <div id="qrCodeDiv" style="display:none" class="form-group col-lg-6 col-md-6 pl-0">
                                            <label>Click en QR para descargar</label>
                                            <br><br>
                                            <div onclick="downloadAsImage()" id="qrcode"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
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

                                            <label>Tickets</label>

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