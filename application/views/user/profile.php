
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid" >
        <script src="/assets/js/lib/jquery.js"></script>
        <script src="/assets/js/jquery.parsley/dist/parsley.min.js"></script>
        <form id="forma_perfil" action="/contact/update" class="j-forms" method="post" data-parsley-validate="">
            <div class="row">
                <!-- Begin .page-heading -->
                <div class="page-heading">
                    <div class="media clearfix">
                        <div class="media-left pr30" style="text-align:center;">
                            <a href="javascript:;" id="profile_fotografia">
                                <img class="media-object mw150" src="<?php echo (isset($cuenta['contact']->foto) && !empty($cuenta['contact']->foto)) ? '/assets/' . $cuenta['contact']->foto : 'http://santetotal.com/wp-content/uploads/2014/05/default-user.png'; ?>" alt="Foto" title="Foto" style="width:150px;height:150px;"/>
                                <?php
                                ?>
                            </a>
                            <a href="javascript:show_change_foto();" style="text-decoration:none;">Subir Foto</a>
                            <!--<a href="javascript:showWebCam();" style="text-decoration:none;">&nbsp;Tomar Foto</a>-->
                        </div>
                        <div class="media-body va-m">
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    var imgsrc = $('.mw150').attr('src');
                                    $('<img/>').load(function () {
                                    }).error(function () {
                                        $(".mw150").attr("src","http://santetotal.com/wp-content/uploads/2014/05/default-user.png");
                                    }).attr('src', imgsrc);

                                    //pagina_previa = "<?php echo $this->session->userdata('pagina_previa'); ?>";

                                    $('#erp_contact_nacimiento').datepicker({changeYear: true, yearRange: 'c-100:c'});

                                    $('#erp_account_id').val('<?php echo isset($cuenta['account']->id) ? $cuenta['account']->id : ''; ?>');
                                    $('#erp_contact_id').val('<?php echo isset($cuenta['contact']->id) ? $cuenta['contact']->id : ''; ?>');
                                    $('#erp_address_id').val('<?php echo isset($cuenta['address']->id) ? $cuenta['address']->id : ''; ?>');

                                    $('#users_id').val('<?php echo isset($cuenta['user']->id) ? $cuenta['user']->id : ''; ?>');
                                    $('#users_facebook_id').val('<?php echo isset($cuenta['user']->facebook_id) ? $cuenta['user']->facebook_id : ''; ?>');
                                    $('#users_google_id').val('<?php echo isset($cuenta['user']->google_id) ? $cuenta['user']->google_id : ''; ?>');
                                    $('#users_twitter_id').val('<?php echo isset($cuenta['user']->twitter_id) ? $cuenta['user']->twitter_id : ''; ?>');
                                    $('#users_groups_id').val('<?php echo (isset($cuenta['group']->id) && $cuenta['group']->id > 0) ? $cuenta['group']->id : ''; ?>');
                                    $('#users_groups_user_id').val('<?php echo (isset($cuenta['group']->user_id) && $cuenta['group']->user_id > 0) ? $cuenta['group']->user_id : ''; ?>');
                                    $('#users_groups_group_id').val('<?php echo (isset($cuenta['group']->group_id) && $cuenta['group']->group_id > 0) ? $cuenta['group']->group_id : ''; ?>');

                                    <?php if (isset($cuenta['user']->id) && $cuenta['user']->id>0 && $this->tank_auth->user_has_privilege('Acceso POS', $cuenta['user']->id)): ?>
                                    var div_user_nip = '<div class="col-md-4">' +
                                        '<div class="form-content">' +
                                            '<label>Clave de Autorización</label>' +
                                            '<div class="unit">' +
                                                '<div class="input">' +
                                                    '<label class="icon-left" for="icon-left">' +
                                                        '<i class="fa fa-home"></i>' +
                                                    '</label>' +
                                                    '<input class="form-control" type="password" placeholder="Clave Autorizaci&oacute;n" name="user[nip]" id="user_nip" />' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>';
                                    $('#div_user_nip').empty().append(div_user_nip).show();
                                    <?php endif; ?>
                                    
                                    $('#erp_contact_first_name').val('<?php echo isset($cuenta['contact']->first_name) ? $cuenta['contact']->first_name : ''; ?>');
                                    $('#erp_contact_last_name').val('<?php echo isset($cuenta['contact']->last_name) ? $cuenta['contact']->last_name : ''; ?>');
                                    $('#erp_contact_second_last_name').val('<?php echo isset($cuenta['contact']->second_last_name) ? $cuenta['contact']->second_last_name : ''; ?>');
                                    $('#erp_contact_email').val('<?php echo isset($cuenta['contact']->email) ? $cuenta['contact']->email : ''; ?>');

                                    $('#erp_contact_genero').val('<?php echo isset($cuenta['contact']->genero) ? $cuenta['contact']->genero : ''; ?>');

                                    $('#erp_contact_nacimiento').val('<?php echo isset($cuenta['contact']->birthday) ? $cuenta['contact']->birthday : ''; ?>');
                                    $('#erp_contact_sangre').val('<?php echo (isset($cuenta['contact']->sangre) && !empty($cuenta['contact']->sangre)) ? $cuenta['contact']->sangre : ''; ?>');
                                    $('#erp_contact_locker').val('<?php echo isset($cuenta['contact']->locker) ? $cuenta['contact']->locker : ''; ?>');
                                    $('#erp_contact_pin').val('<?php echo isset($cuenta['contact']->pin) ? $cuenta['contact']->pin : ''; ?>');
                                    $('#erp_contacts_phones_id_1').val('<?php echo isset($cuenta['phones'][0]['id']) ? $cuenta['phones'][0]['id'] : ''; ?>');
                                    $('#erp_contacts_phones_id_2').val('<?php echo isset($cuenta['phones'][1]['id']) ? $cuenta['phones'][1]['id'] : ''; ?>');
                                    $('#erp_contacts_phones_id_3').val('<?php echo isset($cuenta['phones'][2]['id']) ? $cuenta['phones'][2]['id'] : ''; ?>');
                                    $('#erp_contacts_phones_number_1').val('<?php echo isset($cuenta['phones'][0]['number']) ? $cuenta['phones'][0]['number'] : ''; ?>');
                                    $('#erp_contacts_phones_number_2').val('<?php echo isset($cuenta['phones'][1]['number']) ? $cuenta['phones'][1]['number'] : ''; ?>');
                                    $('#erp_contacts_phones_number_3').val('<?php echo isset($cuenta['phones'][2]['number']) ? $cuenta['phones'][2]['number'] : ''; ?>');

                                    $('#erp_contact_address_calle').val('<?php echo isset($cuenta['address']->calle) ? $cuenta['address']->calle : ''; ?>');
                                    $('#erp_contact_address_noexterior').val('<?php echo isset($cuenta['address']->noexterior) ? $cuenta['address']->noexterior : ''; ?>');
                                    $('#erp_contact_address_nointerior').val('<?php echo isset($cuenta['address']->nointerior) ? $cuenta['address']->nointerior : ''; ?>');
                                    $('#erp_contact_address_codigopostal').val('<?php echo isset($cuenta['address']->codigopostal) ? $cuenta['address']->codigopostal : ''; ?>');
                                    $('#erp_contact_address_pais_id').val('<?php echo isset($cuenta['address']->pais_id) ? $cuenta['address']->pais_id : ''; ?>');

                                    if ('<?php echo isset($cuenta['address']->pais_id) ? $cuenta['address']->pais_id : ''; ?>' > '0') {
                                        $.ajax({
                                            url: '/contact/get_estados',
                                            type: 'post',
                                            dataType: 'json',
                                            data: {'pais': $('#erp_contact_address_pais_id').val()},
                                            success: function (data) {
                                                if (data.result == true) {
                                                    $('#erp_contact_address_estado_id').empty().append(data.info);
                                                    $('#erp_contact_address_estado_id').val('<?php echo (isset($cuenta['address']->estado_id) && !empty($cuenta['address']->estado_id)) ? $cuenta['address']->estado_id : ''; ?>');
                                                    $.ajax({
                                                        url: '/contact/get_municipios',
                                                        type: 'post',
                                                        dataType: 'json',
                                                        data: {'estado': $('#erp_contact_address_estado_id').val()},
                                                        success: function (data) {
                                                            if (data.result == true) {
                                                                $('#erp_contact_address_municipio_id').empty().append(data.info);
                                                                $('#erp_contact_address_municipio_id').val('<?php echo (isset($cuenta['address']->municipio_id) && !empty($cuenta['address']->municipio_id)) ? $cuenta['address']->municipio_id : ''; ?>');
                                                                $.ajax({
                                                                    url: '/contact/get_colonias',
                                                                    type: 'post',
                                                                    dataType: 'json',
                                                                    data: {'municipio': $('#erp_contact_address_municipio_id').val()},
                                                                    success: function (data) {
                                                                        if (data.result == true) {
                                                                            $('#erp_contact_address_erp_colonia_id').empty().append(data.info);
                                                                            $('#erp_contact_address_erp_colonia_id').val('<?php echo (isset($cuenta['address']->erp_colonia_id) && !empty($cuenta['address']->erp_colonia_id)) ? $cuenta['address']->erp_colonia_id : ''; ?>');
                                                                            if($("#erp_contact_address_erp_colonia_id option[value='"+$('#erp_contact_address_erp_colonia_id').val()+"']").text() == 'Otro'){
                                                                               $(".colonia").show();
                                                                               $("#erp_contact_address_otro").val('<?php echo $cuenta['address']->colonia_texto ?>');
                                                                            }
                                                                        }
                                                                    },
                                                                });
                                                            }
                                                        },
                                                    });
                                                }
                                            },
                                        });
                                    }
                                });
                            </script>
                            <h1 style="border: 1px ">Matricula : <?php echo $cuenta['contact']->id?></h1>
                            <input id="id_matricula" value="<?php echo $cuenta['contact']->id?>" type="hidden">
                            <button id="lock_buttom" class="btn btn-danger" onclick="change_dmp_status_locked(<?php echo $cuenta['contact']->id?>)" style="display: none">Bloquear Acceso</button>
                            <button id="dislock_buttom" class="btn btn-success" onclick="change_dmp_status_dislocker(<?php echo $cuenta['contact']->id?>)" style="display: none">Activar Acceso</button>
                            <h2 class="media-heading">
                                <?php echo (isset($cuenta['contact']->first_name)) ? $cuenta['contact']->first_name . ' ' . $cuenta['contact']->last_name . ' ' . $cuenta['contact']->second_last_name : ''; ?> 
                                <small> - <?php
                                    if ($cuenta['account']->is_principal):
                                        echo isset($cuenta['group']->role) ? $cuenta['group']->role : ((isset($cuenta['contact']->main) && $cuenta['contact']->main == 1) ? 'Titular' : 'Adicional');
                                    else:
                                        echo (isset($cuenta['contact']->main) && $cuenta['contact']->main == 1) ? 'Titular' : 'Adicional';
                                    endif;
                                    ?></small>
                            </h2>
                            <p class="lead"><?php echo ($cuenta['account']->is_principal) ? '' : ((isset($cuenta['account']->folio) && !empty($cuenta['account']->folio)) ? 'Membresia: ' . $cuenta['account']->folio : (isset($cuenta['membership']['id']) ? 'Membresia: ' . $cuenta['membership']['id'] : '')); ?></p>
                            <div class="media-links">
                                <ul class="list-inline list-unstyled">
                                    <?php if (!$cuenta['account']->is_principal && 'Administrador' != $this->tank_auth->get_user_role()): ?>
                                        <li>
                                            <a href="<?php echo isset($cuenta['user']->facebook_id) ? 'https://www.facebook.com/' . $cuenta['user']->facebook_id . '" target="_blank"' : 'javascript:;'; ?>" title="facebook link"><span class="fa fa-facebook-square fs35 <?php echo isset($cuenta['user']->facebook_id) ? 'text-primary' : 'text-muted'; ?>"></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo isset($cuenta['user']->twitter_id) ? 'https://twitter.com/intent/user?screen_name=' . $cuenta['user']->twitter_id . '" target="_blank"' : 'javascript:;'; ?>" title="twitter link"><span class="fa fa-twitter-square fs35 <?php echo isset($cuenta['user']->twitter_id) ? 'text-system' : 'text-muted'; ?>"></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo isset($cuenta['user']->google_id) ? 'https://plus.google.com/' . $cuenta['user']->google_id . '" target="_blank"' : 'javascript:;'; ?>" title="google plus link"><span class="fa fa-google-plus-square fs35 <?php echo isset($cuenta['user']->google_id) ? 'text-danger' : 'text-muted'; ?>"></span></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="">
                                        <a href="<?php echo isset($cuenta['phones'][0]['number']) ? 'callto:' . $cuenta['phones'][0]['number'] : 'javascript:;'; ?>" title="phone link"><span class="fa fa-phone-square fs35 <?php echo isset($cuenta['contact']->email) ? 'text-system' : 'text-muted'; ?>"></span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo isset($cuenta['contact']->email) ? 'mailto:' . $cuenta['contact']->email : 'javascript:;'; ?>" title="email link"><span class="fa fa-envelope-square fs35 <?php echo isset($cuenta['contact']->email) ? 'text-info' : 'text-muted'; ?>"></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" title="Cambiar contrase&ntilde;a" rel="<?php echo (isset($cuenta['contact']->id)) ? $cuenta['contact']->id : '0'; ?>" id="perfil_password"><span class="fa fa-lock fs35 text-danger"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php if (isset($cuenta['account']->is_principal) && !$cuenta['account']->is_principal): ?>
                        <?php if ($cuenta['membership']): ?>
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-icon">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    <span class="panel-title">Cuenta</span>
                                </div>
                                <div class="panel-body pb5">
                                    <h6>Membresia</h6>
                                    <h4><?php echo $cuenta['membership']['producto']; ?></h4>
                                    <p class="text-muted">
                                        Condiciones: <?php echo $cuenta['membership']['payment_condition']; ?>
                                        <br> Tipo: <?php echo $cuenta['membership']['client_status']; ?>
                                        <br> Fecha Efectiva: <?php echo $cuenta['membership']['erp_account_details_eff_date']; ?>
                                        <br> Fecha Fin Contrato: <?php echo $cuenta['membership']['erp_account_details_exp_date']; ?>
                                        <?php if ($cuenta['membership']['payment_condition'] == 'Mensual'): ?>
                                            <br> Fecha Prox. Pago: <?php echo $cuenta['membership2'][0]['expiration_date']; ?>
                                            <br> Pago Mensual: $<?php echo number_format($cuenta['membership2'][0]['mensualidad'], 2); ?>
                                        <?php endif; ?>
                                        <br> Cliente: <?php echo $cuenta['account']->client_type_name; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <span class="panel-icon">
                                    <i class="fa fa-adjust"></i>
                                </span>
                                <span class="panel-title">Status</span>
                            </div>
                            <div class="panel-body pn">
                                <table class="table mbn tc-icon-1 tc-med-2 tc-bold-last">
                                    <thead>
                                        <tr class="hidden">
                                            <th class="mw30">&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            if (isset($cuenta['user']->active) && $cuenta['user']->active == '1'):
                                                $status_label = 'ACTIVADO';
                                                $status_class = 'fa-thumbs-o-up text-success';
                                            else:
                                                $status_label = 'SUSPENDIDO';
                                                $status_class = 'fa-thumbs-o-down text-danger';
                                            endif;
                                            ?>
                                            <td><span class="fa fa-desktop text-warning"></span></td>
                                            <td>Status</td>
                                            <td><i class="fa <?php echo $status_class; ?> pr10"></i><?php echo $status_label; ?></td>
                                        </tr>
                                        <?php if ($cuenta['membership']): ?>
                                            <tr>
                                                <td><span class="fa fa-newspaper-o text-primary"></span></td>
                                                <td>Membres&iacute;a</td>
                                                <td><?php echo (isset($cuenta['account']->folio) && !empty($cuenta['account']->folio)) ? $cuenta['account']->folio : $cuenta['membership']['id']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span class="fa fa-arrow-right text-system"></span></td>
                                                <td>&Uacute;ltimo Acceso</td>
                                                <td><?php echo isset($cuenta['user']->last_login) ? date('d/m/Y H:i', strtotime($cuenta['user']->last_login)) . ' Hrs' : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span class="fa fa-arrow-left text-system"></span></td>
                                                <td>&Uacute;ltima Salida</td>
                                                <td><?php echo isset($cuenta['user']->last_logout) ? date('d/m/Y H:i', strtotime($cuenta['user']->last_logout)) . ' Hrs' : ''; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <span class="panel-icon">
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="panel-title">Rol</span>
                            </div>
                            <div class="panel-body pb5">
                                <div class="unit">
                                    <label class="input select">
                                        <?php $ant = &get_instance(); ?>
                                        <input type="hidden" id="users_groups_id" name="users_groups[id]" readonly="readonly" />
                                        <input type="hidden" id="users_groups_user_id" name="users_groups[user_id]" readonly="readonly" />
                                        <select class="form-control" name="users_groups[group_id]" id="users_groups_group_id"<?php echo ($this->tank_auth->get_user_role() == 'Administrador' || $this->tank_auth->get_user_role() == 'Gerente Comercial') ? '' : ' disabled="disabled"'; ?>>
                                            <option value="">Rol</option>
                                            <?php echo isset($select_roles) ? $select_roles : ''; ?>
                                        </select>
                                        <i></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>
                    <div class="panel">
                        <!--<?php
                        foreach ($modules as $r) {
                            echo $r->name;
                        }
                        ?>-->
                        <!--<div class="panel-heading">
                            <span class="panel-icon">
                                <i class="fa fa-user"></i>
                            </span>
                            <span class="panel-title">Privilegios</span>
                        </div>
                        <div class="panel-body pb5">
                            <select  class="form-control" id="rol">
                                <option value="">Rol --Seleccione--</option>
                        <?php echo isset($rol) ? $rol : ''; ?>
                            </select>
                            <div class="row">
                                <div class="col-md-5">
                                    <select multiple class="form-control" name="origen[]" id="origen">
                        <?php echo isset($module) ? $module : ''; ?>
                                    </select>  
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default btn-xs" id="quitar">
                                        <span class="glyphicon glyphicon-backward"></span>
                                    </button>
                                    <br>
                                    <button type="button" class="btn btn-default btn-xs" id="quitartodos">
                                        <span class="glyphicon glyphicon-step-backward"></span>
                                    </button>
                                    <br>
                                    <button type="button" class="btn btn-default btn-xs" id="pasar">
                                        <span class="glyphicon glyphicon-forward"></span>
                                    </button>
                                    <br>
                                    <button type="button" class="btn btn-default btn-xs" id="pasartodos">
                                        <span class="glyphicon glyphicon-fast-forward"></span>
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <select multiple class="form-control" name="destino[]" id="destino">
                                    </select>
                                    <br>
                                    <div class="btn-ex-container"><a href="#" class="btn btn-success add-account" id="guardar">Asignar</a></div>	
                                </div>
                            </div>
                        </div>-->

                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-icon">
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="panel-title">Etiquetas</span>
                        </div>
                        <div class="panel-body pb5">
                            <div id="tags_tags_1"><?php echo isset($cuenta['tags']->tags) ? $cuenta['tags']->tags : ''; ?></div>
                            <input type="text" name="tags" placeholder="Tags" class="tagManager"/>
                            <input type="text" name="id_user" id="id_user" value="<?php echo isset($cuenta['user']->id) ? $cuenta['user']->id : $user_id; ?>" style="visibility: hidden"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-block">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">Informaci&oacute;n</a>
                            </li>

                            <li>
                                <a id = "rutinas_tab" href="#tab3" data-toggle="tab">Rutinas</a>
                            </li>

                            <li>
                                <a id="nutrition_tab" href="#tab4" data-toggle="tab">Nutrición</a>
                            </li>

                            <li>
                                <a href="#tab2" data-toggle="tab">Soporte</a>
                            </li>
                            <?php if (isset($cuenta['account']->is_principal) && !$cuenta['account']->is_principal): ?>
                                <li>
                                    <a href="#tab3" data-toggle="tab">Redes Sociales</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content p30">
                            <div id="tab1" class="tab-pane active">
                                <input type="hidden" name="erp_account[id]" id="erp_account_id" readonly="readonly" />
                                <input type="hidden" name="erp_contact[id]" id="erp_contact_id" readonly="readonly" />
                                <input type="hidden" name="erp_contact_address[id]" id="erp_address_id" readonly="readonly" />
                                <input type="hidden" name="user[id]" id="users_id" readonly="readonly" />
                                <?php if (isset($cuenta['contact']->main) && $cuenta['contact']->main == 1): ?>
                                    <input type="hidden" name="erp_contact[main]" id="erp_contact_main" value="1" readonly="readonly" />
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Nombre</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Nombre(s)" name="erp_contact[first_name]" id="erp_contact_first_name" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 " style="display:block;">
                                        <div class="form-content">
                                            <label>Apellido Paterno</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Primer Apellido" name="erp_contact[last_name]" id="erp_contact_last_name" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Apellido Materno</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Segundo Apellido" name="erp_contact[second_last_name]" id="erp_contact_second_last_name" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-content">
                                            <label>Correo electrónico</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-envelope"></i>
                                                    </label>
                                                    <input class="form-control" type="email" placeholder="Correo Electr&oacute;nico" id="erp_contact_email" name="erp_contact[email]" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 " style="display:block;">
                                        <div class="form-content">
                                            <label>Genero</label>
                                            <div class="unit">
                                                <div class="input select">
                                                    <select class="form-control" name="erp_contact[genero]" id="erp_contact_genero">
                                                        <option value = ''>-- Selecciona --</option>
                                                        <option value = 0>Femenino</option>
                                                        <option value = 1>Masculino</option>
                                                    </select>
                                                    <i></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>F. de Nacimiento</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-gift"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="F.Nacimiento" name="erp_contact[birthday]" id="erp_contact_nacimiento" readonly="readonly" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 " style="display:block;">
                                        <div class="form-content">
                                            <label>Tipo de Sangre</label>
                                            <div class="unit">
                                                <div class="input select">
                                                    <select class="form-control" name="erp_contact[sangre]" id="erp_contact_sangre">
                                                        <?php echo $select_sangre; ?>
                                                    </select>
                                                    <i></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Locker</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-lock"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Locker" name="erp_contact[locker]" id="erp_contact_locker" data-parsley-type="digits" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row " style="display:block;">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Teléfono</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-phone"></i>
                                                    </label>
                                                    <input type="hidden" name="erp_contacts_phones[0][id]" id="erp_contacts_phones_id_1" readonly="readonly" />
                                                    <input class="form-control" type="text" placeholder="Tel&eacute;fono 1" name="erp_contacts_phones[0][number]" id="erp_contacts_phones_number_1" required data-parsley-type="digits" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Teléfono</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-phone"></i>
                                                    </label>
                                                    <input type="hidden" name="erp_contacts_phones[1][id]" id="erp_contacts_phones_id_2" readonly="readonly" />
                                                    <input class="form-control" type="text" placeholder="Tel&eacute;fono 2" name="erp_contacts_phones[1][number]" id="erp_contacts_phones_number_2" data-parsley-type="digits" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Teléfono</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-phone"></i>
                                                    </label>
                                                    <input type="hidden" name="erp_contacts_phones[2][id]" id="erp_contacts_phones_id_3" readonly="readonly" />
                                                    <input class="form-control" type="text" placeholder="Tel&eacute;fono 3" name="erp_contacts_phones[2][number]" id="erp_contacts_phones_number_3" data-parsley-type="digits" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row " style="display:block;">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Dirección</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-home"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Calle" name="erp_contact_address[calle]" id="erp_contact_address_calle" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>No. Ext</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-home"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="No. Exterior" name="erp_contact_address[noexterior]" id="erp_contact_address_noexterior" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>No. Int</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-home"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="No. Interior" name="erp_contact_address[nointerior]" id="erp_contact_address_nointerior" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row " style="display:block;">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>C.P.</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-home"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="C&oacute;digo Postal" name="erp_contact_address[codigopostal]" id="erp_contact_address_codigopostal" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>País</label>
                                            <div class="unit">
                                                <label class="input select">
                                                    <select class="form-control" name="erp_contact_address[pais_id]" id="erp_contact_address_pais_id">
                                                        <option value="">Pa&iacute;s</option>
                                                        <?php echo isset($select_pais) ? $select_pais : ''; ?>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Estado</label>
                                            <div class="unit">
                                                <label class="input select">
                                                    <select class="form-control" name="erp_contact_address[estado_id]" id="erp_contact_address_estado_id">
                                                        <option value="">Estado</option>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row " style="display:block;">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Municipio</label>
                                            <div class="unit">
                                                <label class="input select">
                                                    <select class="form-control" name="erp_contact_address[municipio_id]" id="erp_contact_address_municipio_id">
                                                        <option value="">Municipio</option>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <label>Colonia</label>
                                            <div class="unit">
                                                <label class="input select">
                                                    <select class="form-control" name="erp_contact_address[erp_colonia_id]" id="erp_contact_address_erp_colonia_id">
                                                        <option value="">Colonia</option>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 colonia" style="display: none;">
                                        <div class="form-content">
                                            <label>Otro</label>
                                            <div class="unit">
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-home"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Colonia Otro" name="erp_contact_address[colonia_texto]" id="erp_contact_address_otro" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <button type="button" id="btn_copy_account" class="btn btn-info">Copiar Direcci&oacute;n Cuenta</button>
                                        <label>&nbsp;</label>
                                    </div>
                                </div>
                                <?php
                                if ($this->tank_auth->user_has_privilege('Ver Pin') && $edit_target != 'NO_APLICA' ):
                                    ?>
                                <div style="display: block;">
                                    <div class="col-md-4">
                                        <div class="form-content">
                                            <div class="unit">
                                                <label class="label">Pin</label>
                                                <div class="input">
                                                    <label class="icon-left" for="icon-left">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <input class="form-control" type="text" placeholder="Pin" name="erp_contact[pin]" id="erp_contact_pin" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="row" style="display:none;" id="div_user_nip"></div>
                                <?php 
                                    if ($this->tank_auth->user_has_privilege('Asignar Metas') && $edit_target != 'NO_APLICA' ): 
                                ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-content">
                                                <label>Meta</label>
                                                <div class="unit">
                                                    <div class="input">
                                                        <label class="icon-left" for="icon-left">
                                                            <i class="fa fa-trophy"></i>
                                                        </label>
                                                        <input class="form-control" type="text" placeholder="Meta" name="user[agent_target]" id="agent_target" value="<?php echo $cuenta['user']->agent_target; ?>" "<?php echo $edit_target; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <!-- Meta de Citas -->

                                <?php
                                    if ($cuenta['group']) {
                                        if ($cuenta['group']->role == 'Nutricion'){
                                ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-content">
                                                        <label>Meta de citas</label>
                                                        <div class="unit">
                                                            <div class="input">
                                                                <label class="icon-left" for="icon-left">
                                                                    <i class="fa fa-trophy"></i>
                                                                </label>
                                                                <input class="form-control" type="text" placeholder="Meta" name="user[appointment_target]" id="agent_target" value="<?php echo $cuenta['user']->appointment_target; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                <?php 
                                        }
                                    }
                                ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="sendform">
                                            <button type="submit" class="btn btn-success primary-btn">Guardar</button>
                                            <button type="button" id="btn_back_contact" rel="<?php ?>" class="btn btn-danger primary-btn">Regresar</button>
                                        </div>
                                        <div id="processform" style="display:none;">
                                            <button type="button" class="btn btn-success primary-btn processing">Procesando...</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab2" class="tab-pane">
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <div class="widget-header">
                                            <div class="btn-ex-container"><a  href="/support/add" class="btn btn-primary add-ticket" id="add-ticket">+ Ticket</a></div>	                                    
                                        </div>
                                    </div>
                                </div>
                                <table class="table" id="erp_accounts_grid" data-filter="#filter" data-filter-text-only="true" data-page-size="5" data-limit-navigation="3">
                                    <thead>
                                        <tr>
                                            <th>Titulo</th>
                                            <th>Asignado</th>
                                            <th>Relacionado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="hide-if-no-paging">
                                        <tr>
                                            <td colspan="6" class="footable-visible">
                                                <div class="pagination pagination-centered"></div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>


                            </div>

                            <div id="tab3" class="tab-pane active" name="tab3">
                                <br><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="calendario_ejercicios" class="table table-hover table-striped table-bordered" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Semana</th>
                                                    <th>Dia</th>
                                                    <th>Descanso min</th>
                                                    <th>Descanso max</th>
                                                    <th>Ejercicios</th>
                                                    <th style="width: 10px">Día Realizado</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="tab4" class="tab-pane active" name="tab4">
                                <div class="row">
                                    <div class="col-md-12 unit">
                                        <label class="label"><h4>Plan nutrimental</h4></label>
                                    </div>
                                    <div class="col-md-12 unit" id="tbl_plans">
                                        <table class="table table-condensed table-striped" id="plans_tbl">
                                            <thead>
                                                <tr><th>&nbsp;</th><th>Hoy</th><th>Mañana</th><th>Pasado</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>No se ha asignado un plan nutricional.</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php if (isset($cuenta['account']->is_principal) && !$cuenta['account']->is_principal): ?>
                                <div id="tab3" class="tab-pane">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-content">
                                                <div class="unit">
                                                    <div class="input">
                                                        <label class="icon-left" for="icon-left">
                                                            <i class="fa fa-facebook"></i>
                                                        </label>
                                                        <input class="form-control" type="text" placeholder="ID Facebook" name="user[facebook_id]" id="users_facebook_id" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-content">
                                                <div class="unit">
                                                    <div class="input">
                                                        <label class="icon-left" for="icon-left">
                                                            <i class="fa fa-google-plus"></i>
                                                        </label>
                                                        <input class="form-control" type="text" placeholder="ID Google+" name="user[google_id]" id="users_google_id" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-content">
                                                <div class="unit">
                                                    <div class="input">
                                                        <label class="icon-left" for="icon-left">
                                                            <i class="fa fa-twitter"></i>
                                                        </label>
                                                        <input class="form-control" type="text" placeholder="ID Twitter" name="user[twitter_id]" id="users_twitter_id" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="sendform2">
                                                <button type="button" id="save_redessociales" class="btn btn-success primary-btn">Guardar</button>
                                                <button type="button" id="btn_back_contact" class="btn btn-danger primary-btn">Regresar</button>
                                            </div>
                                            <div id="processform2" style="display:none;">
                                                <button type="button" class="btn btn-success primary-btn processing">Procesando...</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tagmanager.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tags.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tagmanager.css">
    <form id="forma_foto" action="/contact/update_foto" class="j-forms" method="post"  enctype="multipart/form-data" data-parsley-validate="" style="display:none;">
        <input type="hidden" id="foto_id" name="foto[id]" value="<?php echo isset($cuenta['contact']->id) ? $cuenta['contact']->id : ''; ?>" />
        <input type="hidden" id="hidden_data" name="foto[data]"/>
    </form>
    <canvas id="canvas" style="display: none;"></canvas>