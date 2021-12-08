<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">


<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar departamento</li>
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

                                    <p class="title-sec">Agregar departamento</p>

                                    <form id="Departamentos_info" class="form-agregar-departamentos" enctype="multipart/form-data" onsubmit="save_Departamentos()">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id;?>" />
                                        <p class="sub-title-sec">Información del departamento</p>

                                        <div class="form-row form-row-dep">
                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <div class="floating-label-group">
                                                    <input type="text" id="" name="users[nombre]" class="form-control input-form" required />
                                                    <label class="floating-label">Nombre *</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pr-0">
                                                <div class="floating-label-group">
                                                    <input type="text" id="" name="users[telefono]" class="form-control input-form" required />
                                                    <label class="floating-label">Teléfono</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <div class="floating-label-group">
                                                    <input type="text" name="users[contacto]" class="form-control input-form input-contacto-principal" required />
                                                    <label class="floating-label">Contacto principal</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pr-0 d-file-img">
                                                
                                                <div class="input append-big-btn">
                                                    <!--file input example -->
                                                    <span class="control-fileupload">
                                                        <label for="file" class="one-line">Seleccionar icono</label>
                                                        <input type="file" class="input-file-icon" id="file" name="users[logo]">
                                                    </span>
                                                    <span class="hint">Formato recomendado PNG y SVG.</span>
                                                </div>
                                                
                                                <div class="current_logo">
                                                    <img id="icon-preview" src="#" alt="Icono" />
                                                </div>

                                            </div>

                                        </div>

                                    

    <div  id="servicios_div" style="display:<?php echo ($id>0?'block':'none');?>">
                                    <div class="row" >

                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                            <p class="sub-title-sec">Servicios</p>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                            <div class="d-btn-nuevo-servicio">
                                                <button type="button" class="btn btn-nuevo-servicio">
                                                    <i class="fa fa-plus"></i>Agregar servicio
                                                </button>
                                            </div>

                                        </div>

                                    </div>


                                        <div class="row row-table-servicios">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="table-responsive">
                                                    <table id="details_grid" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="3">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre</th>
                                                                <th>Descripción</th>
                                                                <th class="th-permite-citas">Permite citas</th>
                                                                <th class="th-permite-pagos">Permite pagos</th>
                                                                <th class="th-editar-servicios">Editar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot class="hide-if-no-paging">
                                                            <tr>
                                                                <td colspan="6" class="footable-visible">
                                                                    <div class="pagination pagination-centered"></div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <legend></legend>
                                    <ul class="list-inline">
                                        <li><button type="submit" class="btn btn-primary next-step btn-guardar">Guardar</button></li>
                                    </ul>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Modal -->
<div class="modal fade modal-servicios" id="modalNuevoServicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nuevo servicio</h5>
                <button type="button" class="close" onclick='limpiar_form_modal();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">
                <form action="" class="form-nuevo-servicio">
                    <div class="form-group">
                        <div class="floating-label-group">
                            <input type="text" id="nombre" name="sevicios[nombre]" class="form-control input-form" required />
                            <label class="floating-label">Nombre *</label>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;" class="form-group">
                        <div class="floating-label-group">
                            <textarea id="descripcion" name="sevicios[descripcion]" class="form-control input-form-area" rows="4" required ></textarea>
                            <label class="floating-label">Descripción *</label>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;margin-top: 20px;" class="form-group">
                        <div class="floating-label-group">
                            <input type="text" id="c_pago" name="sevicios[c_pago]" value="" class="form-control input-form" />
                            <label class="floating-label">Monto a pagar (En caso de permitir pago)</label>
                        </div>
                    </div>
                        
                    <div class="form-row">
                        <div class="form-group col-lg-6 col-md-6 col-xs-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="citas">
                                <label class="form-check-label" for="citas">Permite citas</label>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-xs-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pagos">
                                <label class="form-check-label" for="pagos">Permite pagos</label>
                            </div>
                        </div>
                    </div>

               

            </div>
            <div class="modal-footer">
                <button type="button" id="eliminar_s" style="display:none;" class="btn btn-secondary" onclick="eliminar_servicio();">Eliminar</button>
                <button type="button" class="btn btn-guardar" onclick="agregar_servicio();">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
