<aside class="leftbar material-leftbar">
    <div class="left-aside-container">
        <div class="user-profile-container">
            <div class="user-profile clearfix">
                <div class="admin-user-info">
                    <ul>
                        <?php
                        if ($this->tank_auth->get_user_id() != "") {
                        ?>
                            <li><a href="javascript:;" title="<?php echo $this->tank_auth->get_user_name(); ?>" class="name-user one-line"><?php echo $this->tank_auth->get_user_name(); ?></a></li>
                            <?php $logged_email = $this->tank_auth->get_user_email(); ?>
                            <li><a href="#0" title="Usuario conectado" class="sub-name-user one-line"><?php echo $logged_email; ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">


            </script>
            <div class="admin-bar" id="admin-bar">
                <ul>

                    <li><a href="/user/logout" title="Cerrar sesión"><i class="zmdi zmdi-power"></i>
                        </a>
                    </li>
                    <?php
                    if ($this->tank_auth->get_user_id() != "") {
                    ?>
                        <li><a href="/user/change_pass" title="Cambiar contrase&ntilde;a" name="changepass" id="changepass"><i class="zmdi zmdi-key"></i>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div id="MainMenu">
            <div class="list-group panel">
                <a href="/home/" class="list-group-item">Inicio</a>
                <?php if ($this->tank_auth->user_has_privilege('Reportes')) : ?>
                    <a href="#reportes" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Reportes<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="reportes">
                        <a href="/Reportes/asistencias" class="list-group-item item-second-list">Reporte Asistencias</a>
                        <a href="/Reportes/asistencias_facturacion" class="list-group-item item-second-list">Reporte Asistencias-Facturación</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Empresas') || $this->tank_auth->user_has_privilege('Modificar empresas')) : ?>
                    <a href="#empresas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Empresas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="empresas">
                        <a href="/Empresas" class="list-group-item item-second-list">Ver Empresas</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar empresas')) : ?>

                            <a href="/Empresas/add" class="list-group-item item-second-list">Agregar Empresas</a>
                        <?php endif; ?>
                        <?php if ($this->tank_auth->user_has_privilege('Asignación empresas')) : ?>
                            <a href="/Empresas/assign" class="list-group-item item-second-list">Asignar Empresas</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Colaboradores')) : ?>
                    <a href="#prealtas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Pre-altas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="prealtas">
                        <a href="/PreAltas" class="list-group-item item-second-list">Ver Pre-altas</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar colaboradores')) : ?>
                            <a href="/PreAltas/add" class="list-group-item item-second-list">Agregar Pre-altas</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Colaboradores')) : ?>
                    <a href="#clientes" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Colaboradores<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="clientes">
                        <a href="/Colaboradores" class="list-group-item item-second-list">Ver Colaboradores</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar colaboradores')) : ?>
                            <a href="/Colaboradores/add" class="list-group-item item-second-list">Agregar Colaboradores</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Colaboradores')) : ?>
                    <a href="#prebajas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Bajas - Pre bajas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="prebajas">
                        <a href="/PreBajas" class="list-group-item item-second-list">Ver bajas - pre bajas</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Horarios Colaboradores')) : ?>
                    <a href="#hcolaboradores" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Horarios colaboradores<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="hcolaboradores">
                        <a href="/Horarios" class="list-group-item item-second-list">Ver Horarios colaboradores</a>
                        <a href="/Horarios/add" class="list-group-item item-second-list">Agregar Horarios colaboradores</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Asistencias')) : ?>
                    <a href="#asistencias" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Asistencias<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="asistencias">
                        <a href="/Asistencias" class="list-group-item item-second-list">Ver lista de asistencias</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar asistencias')) : ?>
                            <a href="/Asistencias/add" class="list-group-item item-second-list">Agregar asistencias</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Encuestas')) : ?>
                    <a href="#encuestas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Encuestas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="encuestas">
                        <a href="/Encuestas" class="list-group-item item-second-list">Ver Encuestas</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar encuestas')) : ?>
                            <a href="/Encuestas/add" class="list-group-item item-second-list">Agregar Encuestas</a>
                        <?php endif; ?>

                        <a href="/Encuestas/reporte" class="list-group-item item-second-list">Reporte</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Respuestas Encuestas')) : ?>
                    <a href="#rencuestas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Respuestas Encuestas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="rencuestas">
                        <a href="/Encuestas/Respuestas" class="list-group-item item-second-list">Ver Respuestas</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar respuestas encuestas')) : ?>
                            <a href="/Encuestas/RespuestasAdd" class="list-group-item item-second-list">Agregar Respuestas</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Configuración')) : ?>
                    <a href="#configuracion" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Configuración<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="configuracion">
                        <a href="/Municipios" class="list-group-item item-second-list">Ver Municipios</a>
                        <a href="/Zonas" class="list-group-item item-second-list">Ver Zonas</a>
                        <a href="/Puestos" class="list-group-item item-second-list">Ver Puestos</a>
                        <a href="/FechasFestivas" class="list-group-item item-second-list">Ver Fechas Festivas</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Usuarios')) : ?>
                    <a href="#clients" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Usuarios<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="clients">
                        <a href="/Clients" class="list-group-item item-second-list" data-parent="#employees_list">Ver usuarios</a>
                        <?php if ($this->tank_auth->user_has_privilege('Modificar usuarios')) : ?>
                            <a href="/Clients/add" class="list-group-item item-second-list" data-parent="#employees">Agregar usuario</a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
</aside>
<script src="<?php echo base_url(); ?>assets/js/autoload/grids/change_pass.js"></script>

<script>
    $(".list-group .list-group-item").click(function() {



        if ($(this).attr('aria-expanded') == "true") {
            $(this).find('.fa-caret-down').removeClass('active-icon-menu');

        } else {
            if ($('.fa-caret-down').hasClass('active-icon-menu')) {
                $('.fa-caret-down').removeClass('active-icon-menu');
            }
            $(this).find('.fa-caret-down').addClass('active-icon-menu');

        }


    });
</script>
<!--Leftbar End Here-->