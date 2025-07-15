<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Editar empresa</li>
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
                        <ul class="nav nav-tabs" id="empresaTabs">
                            <li class="active"><a class="color-principal" data-toggle="tab" href="#datos">Datos de la empresa</a></li>
                            <li><a class="color-principal" data-toggle="tab" href="#otros">Sedes</a></li>
                            <li><a class="color-principal" data-toggle="tab" href="#funciones">Horarios</a></li>
                            <li><a class="color-principal" data-toggle="tab" href="#puestos">Puestos por horario</a></li>
                            <li><a class="color-principal" data-toggle="tab" href="#asists">Asistencias</a></li>
                            <li><a class="color-principal" data-toggle="tab" href="#encuestas_tab">Encuestas</a></li>


                        </ul>

                        <div class="tab-content" style="margin-top: 20px">
                            <!-- Tab 1: Formulario -->
                            <div id="datos" class="tab-pane in active">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Datos de la empresa</p>
                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />

                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <span class="floating-label-text">Razón social *</span>
                                            <select class="form-control input-form" name="users[razon_social_id]">
                                                <option hidden>Seleccionar empresa</option>
                                                <?php echo $razones; ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <input type="text" name="users[nombre]" class="form-control input-form" required />
                                                <label class="floating-label">Nombre del cliente *</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Tipo de nómina *</span>
                                            <select class="form-control input-form " name="users[tipo_nomina]">
                                                <option hidden>Seleccionar tipo de nómina</option>
                                                <?php echo $nominas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">

                                            <span class="floating-label-text">Tipo de facturación *</span>
                                            <select class="form-control input-form " name="users[tipo_facturacion]">
                                                <option hidden>Seleccionar tipo de facturación</option>
                                                <?php echo $facturacion; ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <textarea name="users[comentarios]" class="form-control input-form-area" rows="2"></textarea>
                                                <label class="floating-label">Comentarios</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Días de crédito *</span>
                                                <input type="number" name="users[dias_credito]" class="form-control input-form" />
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Responsable *</span>
                                            <select class="form-control input-form " name="users[responsable]">
                                                <option hidden>Seleccionar responsable</option>
                                                <?php echo $responsables; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Municipio *</span>
                                            <select class="form-control input-form " name="users[municipio]">
                                                <option hidden>Seleccionar municipio</option>
                                                <?php echo $municipios; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Zona *</span>
                                            <select class="form-control input-form " name="users[zona]">
                                                <option hidden>Seleccionar zona</option>
                                                <?php echo $zonas; ?>
                                            </select>
                                        </div>

                                        <div style="margin-top: 15px" class="form-group col-lg-12 col-md-12 pl-0">
                                            <button type="submit" class="btn btn-guardar next-step">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Tab 2: Otro -->
                            <div id="otros" class="tab-pane">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Sedes</p>
                                    <div class="col-lg-6 col-md-6"></div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="d-btn-agregar">
                                            <a id="btn_add_new" class="btn add-row btn-agregar"><i class="fa fa-plus"></i>Agregar sede</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive d-table-lg">
                                    <table id="sedes_grid" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
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
                            <div id="funciones" class="tab-pane">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Horarios</p>
                                    <div class="col-lg-6 col-md-6"></div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="d-btn-agregar">
                                            <a id="btn_add_new_horario" class="btn add-row btn-agregar"><i class="fa fa-plus"></i>Agregar horario</a>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="table-responsive d-table-lg">
                                    <table id="horarios_grid" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
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
                            <div id="puestos" class="tab-pane">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Puestos por horario</p>
                                    <div class="col-lg-6 col-md-6"></div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="d-btn-agregar">
                                            <a id="btn_add_new_puesto" class="btn add-row btn-agregar"><i class="fa fa-plus"></i>Agregar puesto</a>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="table-responsive d-table-lg">
                                    <table id="puestos_grid" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
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
                            <div id="asists" class="tab-pane">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Asistencias</p>
                                </div>
                                <div class="table-responsive d-table-lg">
                                    <table id="asistencias_grid" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
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
                            <div id="encuestas_tab" class="tab-pane">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec">Encuestas</p>
                                </div>
                                <div class="table-responsive d-table-lg">
                                    <table id="encuestas_grid" style="width:100%" class="datatable_wr datatable_wrapper table table-striped table-bordered">
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
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal-servicios modal-citas" id="modalSede" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar sede</h5>
                <button type="button" class="close" onclick='limpiar_form_sede();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">

                <div class="row row-nueva-cita">
                    <div class="col-lg-8 col-md-8 col-12">
                        <form id="modal_sede" class="form-nuevo-servicio form-nueva-cita">
                            <div class="floating-label-group">
                                <input type="hidden" name="sede[empresa_id]" class="form-control" value="<?php echo $id; ?>" />
                                <input type="text" name="sede[nombre]" class="form-control input-form" required />
                                <label class="floating-label">Nombre*</label>
                            </div>

                    </div>
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-guardar" onclick="save_sede();">Guardar</button>
        </div>
        </form>
    </div>
</div>
<div class="modal fade modal-servicios modal-citas" id="modalHorario" tabindex="-1" role="dialog" aria-labelledby="modalHorarioTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalHorarioTitle">Agregar horario</h5>
                <button type="button" class="close" onclick="limpiar_form_horario();" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <input type="hidden" id="id_horario" value="0">
            <div class="modal-body">
                <div class="row row-nueva-cita">
                    <div class="col-lg-12 col-md-12 col-12">
                        <form id="modal_horario" class="form-nuevo-servicio form-nueva-cita">
                            <input type="hidden" name="horario[empresa_id]" class="form-control" value="<?php echo $id; ?>" />
                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                <div class="floating-label-group">
                                    <input type="text" name="horario[nombre]" class="form-control input-form" required />
                                    <label class="floating-label">Nombre *</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                <div class="floating-label-group">
                                    <input type="text" name="horario[horario]" class="form-control input-form" required />
                                    <label class="floating-label">Horario (ej. 08:00 - 17:00) *</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="display:block;">Días laborales:</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[lunes]" value="1"> Lunes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[martes]" value="1"> Martes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[miercoles]" value="1"> Miércoles
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[jueves]" value="1"> Jueves
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[viernes]" value="1"> Viernes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[sabado]" value="1"> Sábado
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="horario[domingo]" value="1"> Domingo
                                </label>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-guardar" onclick="save_horario();">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-servicios modal-citas" id="modalPuesto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar puesto por horario</h5>
                <button type="button" class="close" onclick='limpiar_form_sede();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">

                <div class="row row-nueva-cita">
                    <div class="col-lg-8 col-md-8 col-12">
                        <form id="modal_puesto" class="form-nuevo-servicio form-nueva-cita">
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <span class="floating-label-text">Sede *</span>
                                <select class="form-control input-form" id="select_sede" name="puesto[sede_id]">
                                    <option hidden>Seleccionar sede</option>
                                    <?php echo $razones; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <span class="floating-label-text">Horario *</span>
                                <select class="form-control input-form" id="select_horario" name="puesto[horario_id]">
                                    <option hidden>Seleccionar horario</option>
                                    <?php echo $razones; ?>
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
                                <span class="floating-label-text">Tipo de nómina *</span>
                                <select class="form-control input-form" name="puesto[tipo_nomina]">
                                    <option hidden>Seleccionar tipo de nómina</option>
                                    <option value="Con bonos">Con bonos</option>
                                    <option value="Sin bonos">Sin bonos</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <input type="number" name="puesto[salario_diario]" class="form-control input-form" required />
                                    <label class="floating-label">Salario Diario*</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <input type="number" name="puesto[sueldo_neto_semanal]" class="form-control input-form" required />
                                    <label class="floating-label">Sueldo Neto Semanal*</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <input type="number" name="puesto[costo_unitario]" id="costo_unitario" class="form-control input-form" required />
                                    <label class="floating-label">Costo Unitario*</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">

                                    <input type="number" name="puesto[costo_por_dia]" readonly class="form-control input-form" required />

                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <span class="floating-label-text">Costo por Descanso Laborado*</span>
                                    <input type="number" name="puesto[costo_descanso_laborado]" readonly class="form-control input-form" required />

                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <span class="floating-label-text">Costo por Día Festivo*</span>
                                    <input type="number" name="puesto[costo_dia_festivo]" readonly class="form-control input-form" required />

                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <span class="floating-label-text">Costo Hora Extra*</span>
                                    <input type="number" name="puesto[costo_hora_extra]" readonly class="form-control input-form" required />

                                </div>
                            </div>


                            <div class="form-group ol-lg-12 col-md-12 pl-0">
                                <div class="floating-label-group">
                                    <input type="hidden" name="puesto[id]" class="form-control" />
                                    <input type="hidden" name="puesto[empresa_id]" class="form-control" value="<?php echo $id; ?>" />
                                    <input type="number" name="puesto[cantidad]" class="form-control input-form" required />
                                    <label class="floating-label">Cantidad*</label>
                                </div>
                            </div>

                    </div>
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-guardar" onclick="save_puesto();">Guardar</button>
        </div>
        </form>
    </div>
</div>