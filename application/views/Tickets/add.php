<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">
<?php $readonly_attr = !empty($readonly) ? 'disabled' : ''; ?>

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page"><?php echo !empty($readonly) ? 'Ver ticket' : 'Agregar ticket'; ?></li>
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
                                    <p class="title-sec"><?php echo !empty($readonly) ? 'Ver ticket' : 'Agregar ticket'; ?></p>
                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agendar-cita">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Empresa *</span>
                                            <select class="form-control input-form" id="empresa_select" name="users[empresa]" required <?php echo $readonly_attr; ?>>
                                                <option hidden>Seleccionar empresa</option>
                                                <?php echo $empresas; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Sede *</span>
                                            <select class="form-control input-form" id="select_sede" name="users[sede]" required <?php echo $readonly_attr; ?>>
                                                <option hidden>Seleccionar sede</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <span class="floating-label-text">Tipo de ticket</span>
                                            <select class="form-control input-form" id="tipoServicioId" name="users[tipoServicioId]" required <?php echo $readonly_attr; ?>>
                                                <option hidden>Seleccionar tipo de ticket</option>
                                                <?php echo $tipos_servicios; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 pl-0">
                                            <div class="floating-label-group">
                                                <span class="floating-label-text">Descripcion *</span>
                                                <textarea id="descripcion" name="users[descripcion]" class="form-control input-form-area" rows="4" required <?php echo $readonly_attr; ?>></textarea>
                                            </div>
                                        </div>
                                        <legend></legend>

                                        <?php if (empty($readonly)) : ?>
                                            <div style="margin-top: 15px" class="form-group col-lg-12 col-md-12 pl-0">
                                                <button type="submit" class="btn btn-guardar next-step">Guardar</button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($readonly)) : ?>
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-md-12">
                                    <div class="table-responsive d-table-lg">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Fecha</th>
                                            <th>Estatus</th>
                                            <th>Comentario</th>
                                            <th>Documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($ticket_history)) : ?>
                                            <?php foreach ($ticket_history as $history) : ?>
                                                <?php
                                                $history_status = isset($history['status']) ? $history['status'] : '';
                                                $history_badge = '<span class="badge" style="background-color:#9e9e9e; color:#fff;">' . htmlspecialchars($history_status, ENT_QUOTES, 'UTF-8') . '</span>';
                                                if ($history_status === 'Pendiente') {
                                                    $history_badge = '<span class="badge" style="background-color:#f0ad4e; color:#fff;">Pendiente</span>';
                                                } elseif ($history_status === 'Completado') {
                                                    $history_badge = '<span class="badge" style="background-color:#5cb85c; color:#fff;">Completado</span>';
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars(!empty($history['usuario_nombre']) ? $history['usuario_nombre'] : 'Sin usuario', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo htmlspecialchars($history['createdAt'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?php echo $history_badge; ?></td>
                                                    <td><?php echo htmlspecialchars(!empty($history['comentario']) ? $history['comentario'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>
                                                        <?php if (!empty($history['documento'])) : ?>
                                                            <a href="/assets/<?php echo htmlspecialchars($history['documento'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Ver documento</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No hay movimientos registrados.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>


</section>
