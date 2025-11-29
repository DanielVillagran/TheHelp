<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">
<style>
    .permisoEdicion {
        display: <?php echo ($this->tank_auth->user_has_privilege('Modificar colaboradores') ? "block" :  "none"); ?>;
    }
</style>

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page"><?php echo $title; ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section style="padding-top:30px; padding-bottom: 40px;" class="main-container">
    <div class="container-fluid">
        <div id="grid_group" class="row">
            <div class="col-md-12">
                <div class="widget-wrap material-table-widget">
                    <div class="widget-container margin-top-0">
                        <div class="widget-content">
                            <div class="data-action-bar">
                                <p class="title-sec"><?php echo $title; ?></p>
                                <div class="row row-buscar-agregar permisoEdicion">
                                    <div class="col-lg-6 col-md-6">
                                        <!-- Espacio para búsqueda u otros filtros si los necesitas -->
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                                        <div class="d-btn-agregar d-flex flex-column align-items-end gap-3">
                                            <label for="descargar_formato" class="btn btn-sm add-row mb-0 cursor-pointer" onclick="window.open('Colaboradores/Formato/prebaja','_blank')">
                                                <i class="fa fa-download me-1"></i> Descargar información
                                            </label>
                                            <label for="archivo_excel_baja" class="btn btn-sm add-row mb-0 cursor-pointer">
                                                <i class="fa fa-upload me-1"></i> Subir acuse de baja
                                            </label>
                                            <input type="file" id="archivo_excel_baja" accept=".pdf" style="display:none">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="table-responsive d-table-lg">
                            <table id="groups_grid" style="width:100%" class="table table-striped table-bordered">
                                <thead>
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
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>