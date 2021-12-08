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

<section style="padding-top:30px; padding-bottom: 40px" class="main-container">
    <div class="container-fluid">
        <div id="grid_group" class="row">
            <div class="col-md-12">
                <div class="widget-wrap material-table-widget">
                    <div class="widget-container margin-top-0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="d-form-agregar-dep">
                                    <p class="title-sec"><?php echo $title; ?></p>
                                    <form id="notificacion_info" onsubmit="send_notificacion()">
                                        <p class="sub-title-sec">Enviar notificación</p>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 offset-lg-2 offset-md-2">
                                                <div class="d-form-registro-productos">
                                                    <form class="form-registro-productos">
                                                        <div class="form-group">
                                                           
                                                            <p class="label-static">Colonias</p>

                                                            <!-- Build your select: -->
                                                            <select id="example-getting-started" name="colonias" multiple="multiple">
                                                                <?php echo $colonias;?>
                                                            </select>
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                           
                                                            <p class="label-static">Intereses</p>
                                                            <select id="example-getting-started2" name="intereses" multiple="multiple">
                                                                <?php echo $intereses;?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="label-static">Título</label>
                                                                <input id="titulo" name="titulo" required type="text" data-toggle="modal" data-target="#modalTitulo" class="form-control input-form" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="label-static">Mensaje</label>
                                                                <textarea id="mensaje" name="mensaje" required type="text" data-toggle="modal" data-target="#modalMensaje" class="form-control input-form-area-emoji"> </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="label-static">Url</label>
                                                                <input id="url" name="url"  type="text"  class="form-control input-form" />
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
                                                    <img id="icon-preview" src="#" alt="" />
                                                </div>

                                            </div>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <legend></legend>
                                        <ul class="list-inline">
                                            <li><button type="button" onclick="enviar()" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-guardar">Enviar</button></li>
                                        </ul>
                                    </form>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center mb">
                <img class="img-mb" src="images/icon-atencion.png" alt="">
                <p class="title-mb mt-20">Atención</p>
                <p class="sub-title-mb">¿Desea enviar la notificacion?</p>
                <p class="sub-title-mb"><b>Titulo: </b><span id="rtitulo"></span></p>
                <p class="sub-title-mb"><b>Mensaje: </b><span id="rmensaje"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancelar-modal" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-aceptar-modal" onclick="final()">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#titulo").emojioneArea({
            pickerPosition: "bottom"
        });
    });
    $(document).ready(function() {
        $("#mensaje").emojioneArea({
            pickerPosition: "bottom"
        });
    });

    $(document).ready(function() {
        $.fn.multiselect.Constructor.prototype.defaults.selectAllText = "Seleccionar todo";
        $.fn.multiselect.Constructor.prototype.defaults.filterPlaceholder = "Buscar";
        $.fn.multiselect.Constructor.prototype.defaults.nonSelectedText = "Seleccionar";
        $.fn.multiselect.Constructor.prototype.defaults.nSelectedText = "seleccionados";
        $.fn.multiselect.Constructor.prototype.defaults.allSelectedText = "Todos";

        $('#example-getting-started').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true
        });
        $('#example-getting-started2').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true
        });

        //$(".multiselect-all label").html("Seleccionar todo");

    });

</script>
