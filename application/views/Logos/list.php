<!--Page Container Start Here-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css">
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

<section style="padding-top:30px; padding-bottom: 40px" class="main-container">
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
                                    <div class="col-lg-6 col-md-6 col-xs-6">
                                                <span class="btn-excel" onclick="descarga_excel()"><img src="/assets/images/icon-excel.svg" alt=""> Descargar Excel</span>
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


<div class="modal fade modal-servicios modal-citas" id="modalNuevoServicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agendar cita</h5>
                <button type="button" class="close" onclick='limpiar_form_modal();' data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_servicio" value="0">
            <div class="modal-body">
                <p class="p-citas">Para agendar una nueva cita, selecciona la fecha y hora.</p>

                <div class="row row-nueva-cita">
                    <div class="col-lg-8 col-md-8 col-12">
                        <form action="" class="form-nuevo-servicio form-nueva-cita">
                            <div class="form-group">
                                <label for="datepicker" class="t1">Seleccionar fecha</label>
                                <br>
                                <input id="datepicker" class="input-date" placeholder="Fecha" />
                            </div>
                            <div class="form-group">
                                <label for="datetime" class="t1">Seleccionar hora</label>
                                <br>
                                <input id="datetime" class="input-date" placeholder="Hora" />
                            </div>
                    </div>
                </div>


            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-guardar" onclick="agendar_cita();">Guardar</button>
        </div>
        </form>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dist/messages.es-es.js"></script>



<script>
    $('#datepicker').datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        modal: true,
        header: true,
        footer: true
    });

    $('#datetime').timepicker({
        locale: 'es-es',
        format: 'HH:MM',
        modal: true,
        header: true,
        footer: true
    });

    var $datepicker = $('#datepicker').datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        modal: true,
        header: true,
        footer: true
    }); 
    
    var $timepicker = $('#datetime').timepicker({
        locale: 'es-es',
        format: 'HH:MM',
        modal: true,
        header: true,
        footer: true
    });
    
    $("#datepicker").click(function() {
        $datepicker.open();
    });
    
    $("#datetime").click(function() {
        $timepicker.open();
    });

    // if($("#")){

    //  }

</script>
