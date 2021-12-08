<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Agregar usuario</li>
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
                                    <p class="title-sec">Agregar usuario</p>

                                    <form id="client_info" onsubmit="save_client()" class="form-agregar-usuarios">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <p class="sub-title-sec">Información del usuario</p>
                                        <div class="form-row">
                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <div class="floating-label-group">
                                                    <input type="text" id="" name="users[name]" class="form-control input-form" required />
                                                    <label class="floating-label">Nombre *</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 pr-0">
                                                <div class="floating-label-group">
                                                    <input type="text" id="" name="users[middle_name]" class="form-control input-form" required />
                                                    <label class="floating-label">Apellidos *</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 pl-0">
                                                <div class="floating-label-group">
                                                    <input type="text" name="users[user_name]" class="form-control input-form input-contacto-principal" required />
                                                    <label class="floating-label">Correo o nombre de usuario *</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 pr-0">
                                                <div class="floating-label-group">
                                                    <input type="password" id="pass" name="users[user_passwd]" class="form-control input-form" required />
                                                    <label class="floating-label">Contraseña *</label>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12 col-md-12 pl-0 pr-0">


                                                <label class="floating-label-active">Tipo de Usuarios *</label>
                                                <div class="floating-label-group">
                                                    <select name="users[user_type]" class="form-control input-form" required>
                                                        <option value="1">Zumpango</option>
                                                        <option value="2">DIF</option>
                                                        <option value="3">ODAPAZ</option>
                                                        <option value="4">IMCUFIDEZ</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="permisos_div" style="display:<?php echo ($id > 0 ? 'block' : 'none'); ?>">
                                            <div class="fromrow">

                                                <div class="col-lg-6 col-md-6 col-xs-6">
                                                    <p class="sub-title-sec">Permisos</p>
                                                </div>

                                            </div>


                                            <div class="row">
                                                <?php 
                                                echo '<script>var permissions=[];</script>';
                                                    foreach ($permissions as $key) {
                                                        $checked="";
                                                        $respuesta=0;
                                                        if($key['has']!=""){
                                                            $checked="checked";
                                                            $respuesta=1;
                                                        }
                                                        echo '<div class="form-group col-lg-6 col-md-6 col-xs-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="'.$key['id'].'i" onchange="cambio('.$key['id'].')" '.$checked.'>
                                                            <label class="form-check-label" for="'.$key['id'].'i">'.$key['nombre'].'</label>
                                                        </div>
                                                    </div>';
                                                    echo '<script> permissions.push({ module_id: '.$key['id'].', respuesta: '.$respuesta.' });</script>';
                                                    
                                                    }
                                                ?>
                                            </div>
                                        </div>

                                        <legend></legend>
                                        <ul class="list-inline">
                                            <li><button type="submit" class="btn btn-guardar next-step">Guardar</button>
                                            </li>
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
