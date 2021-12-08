<aside class="leftbar material-leftbar">
    <div class="left-aside-container">
        <div class="user-profile-container">
            <div class="user-profile clearfix">
                <div class="admin-user-info">
                    <ul>
                        <li><a href="javascript:;" title="<?php echo $this->tank_auth->get_user_name(); ?>" class="name-user one-line"><?php echo $this->tank_auth->get_user_name(); ?></a></li>
                        <?php $logged_email = $this->tank_auth->get_user_email();?>
                        <li><a href="#0" title="Usuario conectado" class="sub-name-user one-line"><?php echo $logged_email; ?></a></li>

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
                    <li><a href="/user/change_pass" title="Cambiar contrase&ntilde;a" name="changepass" id="changepass"><i class="zmdi zmdi-key"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="MainMenu">
            <div class="list-group panel">
                <a href="/home/" class="list-group-item">Inicio</a>
                <?php if ($this->tank_auth->user_has_privilege('Departamentos')): ?>
                <a href="#employees" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Departamentos<i class="fa fa-caret-down pull-right"></i></a>
                <div class="collapse list-group-submenu" id="employees">
                    <a href="/Departamentos/" class="list-group-item item-second-list">Ver departamentos</a>
                    <a href="/Departamentos/add" class="list-group-item item-second-list">Agregar departamento</a>

                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Noticias')): ?>
                <a href="#noticias" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Noticias<i class="fa fa-caret-down pull-right"></i></a>
                <div class="collapse list-group-submenu" id="noticias">
                    <a href="/Noticias/" class="list-group-item item-second-list" data-parent="#employees">Ver noticias</a>
                    <a href="/Noticias/add" class="list-group-item item-second-list" data-parent="#employees">Agregar noticias</a>

                </div>
                <?php endif;?>
                <a href="#logos" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Logotipos<i class="fa fa-caret-down pull-right"></i></a>
                <div class="collapse list-group-submenu" id="logos">
                    <a href="/Logos/" class="list-group-item item-second-list" data-parent="#employees">Ver logos</a>
                   

                </div>
                <?php if ($this->tank_auth->user_has_privilege('Eventos')): ?>
                <a href="#eventos" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Eventos<i class="fa fa-caret-down pull-right"></i></a>
                <div class="collapse list-group-submenu" id="eventos">
                    <a href="/Eventos/" class="list-group-item item-second-list" data-parent="#employees">Ver eventos</a>
                    <a href="/Eventos/add" class="list-group-item item-second-list" data-parent="#employees">Agregar eventos</a>

                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Colonias')): ?>
                <a href="#colonias" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Colonias<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="colonias">
                    <a href="/Colonias" class="list-group-item item-second-list">Ver colonias</a>
                    <a href="/Colonias/add" class="list-group-item item-second-list">Agregar colonia</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Tipos de Denuncias')): ?>
                <a href="#tipos_denuncias" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Tipos de denuncias<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="tipos_denuncias">
                    <a href="/Tipos_Denuncias" class="list-group-item item-second-list">Ver tipos de denuncias</a>
                    <a href="/Tipos_Denuncias/add" class="list-group-item item-second-list">Agregar tipo de denuncia</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Citas')): ?>
                <a href="#citas_d" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Citas<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="citas_d">
                    <a href="/Citas" class="list-group-item item-second-list">Ver citas</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Pagos')): ?>
                <a href="#pagos_d" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Pagos<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="pagos_d">
                    <a href="/Pagos" class="list-group-item item-second-list">Ver pagos</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Intereses')): ?>
                <a href="#intereses" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Intereses<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="intereses">
                    <a href="/Intereses" class="list-group-item item-second-list">Ver Intereses</a>
                    <a href="/Intereses/add" class="list-group-item item-second-list">Agregar interes</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Notificaciones')): ?>
                <a href="#notificaciones" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Notificaciones<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="notificaciones">
                    <a href="/Notificaciones/history" class="list-group-item item-second-list" data-parent="#notificaciones">Ver notificaciones</a>
                    <a href="/Notificaciones" class="list-group-item item-second-list" data-parent="#notificaciones">Enviar notificaciones</a>
                </div>
                <?php endif;?>
                <?php if ($this->tank_auth->user_has_privilege('Usuarios')): ?>
                <a href="#clients" class="list-group-item list-group-item" data-toggle="collapse" data-parent="#MainMenu">Usuarios<i class="fa fa-caret-down pull-right"></i> </a>
                <div class="collapse list-group-submenu" id="clients">
                    <a href="/Clients" class="list-group-item item-second-list" data-parent="#employees_list">Ver usuarios</a>
                    <a href="/Clients/add" class="list-group-item item-second-list" data-parent="#employees">Agregar usuario</a>
                </div>
                <?php endif;?>

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
