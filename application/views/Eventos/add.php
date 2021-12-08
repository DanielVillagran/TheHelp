<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">


<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar evento</li>
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

                                    <p class="title-sec">Agregar evento</p>

                                    <form id="Eventos_info" class="form-agregar-Eventos" enctype="multipart/form-data" onsubmit="save_Eventos()">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <p class="sub-title-sec">Información del evento</p>

                                        <div class="form-row form-row-dep">
                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <div class="floating-label-group">
                                                    <input type="text" id="" name="users[nombre]" class="form-control input-form" required />
                                                    <label class="floating-label">Nombre *</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pr-0">
                                                <div class="floating-label-group">
                                                    <textarea name="users[descripcion]" class="form-control input-form" required></textarea>
                                                    <label class="floating-label">Descripción*</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <select name="users[tipo]" id="tipo" class="form-control input-form input-contacto-principal" required>
                                                    <option hidden>Seleccionar tipo de evento</option>
                                                    <option value="1">Calificación Evento</option>
                                                    <option value="2">Formulario Google</option>
                                                    <option value="3">Concurso</option>
                                                    <option value="4">Encuesta</option>
                                                </select>

                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 pl-0" id="google_div" style="display:none;">
                                                <div class="floating-label-group">
                                                    <input type="text" id="google" name="users[fgoogle]" class="form-control input-form" />
                                                    <label class="floating-label">Formulario google</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 pr-0 d-file-img">

                                                <div class="input append-big-btn">
                                                    <!--file input example -->
                                                    <span class="control-fileupload">
                                                        <label for="file" class="one-line">Seleccionar Imagen </label>
                                                        <input type="file" class="input-file-icon" id="file" name="users[logo]">
                                                    </span>
                                                    <span class="hint">Formato recomendado PNG y SVG.</span>
                                                </div>

                                                <div class="current_logo">
                                                    <img id="icon-preview" src="#" alt="Imagen" />
                                                </div>

                                            </div>

                                        </div>
                                        <div id="<?php echo ($id > 0 ? 'preguntas_div' : ''); ?>" style="display:<?php echo ($id > 0 ? 'none' : 'none'); ?>">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <p class="sub-title-sec">Preguntas de encuesta</p>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <div class="d-btn-nuevo-pregunta">
                                                        <button type="button" class="btn btn-nuevo-servicio">
                                                            <i class="fa fa-plus"></i>Agregar pregunta
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                <span class="btn-excel btn-excel-encuesta" onclick="descarga_excel_pre()"><img src="/assets/images/icon-excel.svg" alt=""> Descargar Excel</span>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="row row-table-preguntas">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="details_grid" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Pregunta</th>
                                                                    <th>Si</th>
                                                                    <th>No</th>
                                                                    <th></th>

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
                                        <div id="<?php echo ($id > 0 ? 'participantes_div' : ''); ?>" style="display:<?php echo ($id > 0 ? 'none' : 'none'); ?>">
                                            <div class="row">

                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <p class="sub-title-sec">Participantes</p>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <span class="btn-excel" onclick="descarga_excel_par()"><img src="/assets/images/icon-excel.svg" alt=""> Descargar Excel</span>
                                                </div>

                                            </div>


                                            <div class="row row-table-preguntas">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="participantes_list" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Número Telefonico</th>
                                                                    <th>Fecha de participación</th>
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
                                        <div id="<?php echo ($id > 0 ? 'calificaciones_div' : ''); ?>" style="display:<?php echo ($id > 0 ? 'none' : 'none'); ?>">
                                            <div class="row">

                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <p class="sub-title-sec">Calificaciones</p>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <span class="btn-excel" onclick="descarga_excel_calif()"><img src="/assets/images/icon-excel.svg" alt=""> Descargar Excel</span>
                                                </div>

                                            </div>


                                            <div class="row row-table-preguntas">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="calificaciones_list" class="table table-lg" data-filter="#filter" data-filter-text-only="true" data-page-size="10" data-limit-navigation="3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Comentario</th>
                                                                    <th>Puntuación</th>
                                                                    <th>Fecha de Calificación</th>
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
                                            <li><button type="submit" class="btn btn-primary next-step btn-guardar mt-4">Guardar</button></li>
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
<div class="modal fade modal-servicios" id="modalNuevopregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nueva Pregunta</h5>
                <button type="button" class="close" onclick='limpiar_form_modal();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_pregunta" value="0">
            <div class="modal-body">
                <form action="" class="form-nuevo-servicio">

                    <div style="margin-bottom: 20px;" class="form-group">
                        <div class="floating-label-group">
                            <textarea id="descripcion" name="sevicios[pregunta]" class="form-control input-form-area" rows="4" required></textarea>
                            <label class="floating-label">Pregunta *</label>
                        </div>
                    </div>





            </div>
            <div class="modal-footer">
                <button type="button" id="eliminar_s" style="display:none;" class="btn btn-secondary" onclick="eliminar_pregunta();">Eliminar</button>
                <button type="button" class="btn btn-guardar" onclick="agregar_pregunta();">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<a href="" download id="descargador" style="display:none;"></a>
