<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

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
                                <div class="row row-buscar-agregar">
                                    <div class="col-lg-6 col-md-6">
                                          </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="d-btn-agregar">
                                            <a id="btn_add_new" class="btn add-row btn-agregar"><i class="fa fa-plus"></i>Agregar usuario</a>
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
