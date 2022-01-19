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
                <?php if ($this->tank_auth->user_has_privilege('Servicios')) : ?>
                    <a href="#servicios" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Servicios<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="servicios">
                        <a href="/Servicios" class="list-group-item item-second-list">Ver Servicios</a>
                        <a href="/Servicios/add" class="list-group-item item-second-list">Agregar Servicios</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Empresas')) : ?>
                    <a href="#empresas" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Empresas<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="empresas">
                        <a href="/Empresas" class="list-group-item item-second-list">Ver Empresas</a>
                        <a href="/Empresas/add" class="list-group-item item-second-list">Agregar Empresas</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Clientes')) : ?>
                    <a href="#clientes" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Clientes<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="clientes">
                        <a href="/Clientes" class="list-group-item item-second-list">Ver Clientes</a>
                        <a href="/Clientes/add" class="list-group-item item-second-list">Agregar Clientes</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Vehiculos')) : ?>
                    <a href="#vehiculos" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Vehiculos<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="vehiculos">
                        <a href="/Vehiculos" class="list-group-item item-second-list">Ver Vehiculos</a>
                        <a href="/Vehiculos/add" class="list-group-item item-second-list">Agregar Vehiculos</a>
                    </div>
                <?php endif; ?>
                <?php if ($this->tank_auth->user_has_privilege('Usuarios')) : ?>
                    <a href="#clients" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Usuarios<i class="fa fa-caret-down pull-right"></i> </a>
                    <div class="collapse list-group-submenu" id="clients">
                        <a href="/Clients" class="list-group-item item-second-list" data-parent="#employees_list">Ver usuarios</a>
                        <a href="/Clients/add" class="list-group-item item-second-list" data-parent="#employees">Agregar usuario</a>
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