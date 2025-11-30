<!--Page Container Start Here-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bracket.css">

<section class="sec-bread main-container">
    <div class="container-fluid">
        <div style="padding-left: 0px; padding-right: 0px;" class="col-lg-12 col-md-12">
            <div class="d-bread">
                <ul class="list-page-breadcrumb">
                    <li><a href="/home">Inicio<i class="zmdi zmdi-chevron-right"></i></a></li>
                    <li class="active-page">Información colaboradores</li>
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
                                    <p class="title-sec">Información Colaborador</p>

                                    <form id="Departamentos_info" enctype="multipart/form-data" onsubmit="save_Departamentos()" class="form-agregar-Intereses">
                                        <input type="hidden" name="users[id]" id="id" class="form-control" value="<?php echo $id; ?>" />
                                        <div class="row">
                                            <div class="form-group col-lg-4 col-md-4 pl-0">
                                                <span class="floating-label-text">Número de empleado *</span>
                                                <input type="text" name="users[codigo]" class="form-control input-form" />
                                            </div>
                                        </div>
                                        <!-- Apellido Paterno -->
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Apellido paterno *</span>
                                            <input type="text" name="users[apellido_paterno]" class="form-control input-form" />
                                        </div>

                                        <!-- Apellido Materno -->
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Apellido materno *</span>
                                            <input type="text" name="users[apellido_materno]" class="form-control input-form" />
                                        </div>

                                        <!-- Nombre -->
                                        <div class="form-group col-lg-4 col-md-4 pl-0">
                                            <span class="floating-label-text">Nombre *</span>
                                            <input type="text" name="users[nombre]" class="form-control input-form" />
                                        </div>

                                        <!-- Razón Social -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Razón Social *</span>
                                            <select class="form-control input-form" name="users[razon_social]">
                                                <option hidden>Seleccionar razón social</option>
                                                <?php echo $razones; ?>
                                            </select>
                                        </div>

                                        <!-- Cliente -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Cliente *</span>
                                            <select class="form-control input-form" id="empresa_select" name="users[cliente]">
                                                <?php echo $clientes; ?>
                                            </select>
                                        </div>

                                        <!-- Sede -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Sede *</span>
                                            <select class="form-control input-form" name="users[sede]" id="sede_select">

                                            </select>
                                        </div>

                                        <!-- Puesto -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Puesto *</span>
                                            <select class="form-control input-form" name="users[puesto]" id="puesto_select">

                                            </select>
                                        </div>

                                        <!-- Horario -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Horario *</span>
                                            <select class="form-control input-form" name="users[horario_id]" id="horario_select">

                                            </select>
                                        </div>

                                        <!-- RFC -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">RFC *</span>
                                            <input type="text" name="users[rfc]" id="rfc" maxlength="13" class="form-control input-form" required
                                                pattern="^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$"
                                                title="Formato: 4 letras, 6 números y 3 alfanuméricos (ejemplo: ABCD123456XYZ)" />
                                        </div>

                                        <!-- CURP -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">CURP *</span>
                                            <input type="text" name="users[curp]" id="curp" maxlength="18" class="form-control input-form" required
                                                pattern="^[A-Z]{4}\d{6}[A-Z0-9]{8}$"
                                                title="Formato: 4 letras, 6 números y 8 alfanuméricos (ejemplo: ABCD123456HDFRLR09)" />
                                        </div>

                                        <!-- NSS -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">NSS *</span>
                                            <input type="text" name="users[nss]" id="nss" maxlength="11" class="form-control input-form" required
                                                pattern="^\d{11}$"
                                                title="Debe tener exactamente 11 dígitos numéricos" />
                                        </div>

                                        <!-- Tipo de Nómina -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Tipo de Nómina *</span>
                                            <select class="form-control input-form" name="users[tipo_nomina]">
                                                <?php echo $nominas; ?>
                                            </select>
                                        </div>

                                        <!-- SD -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">SD *</span>
                                            <input type="text" name="users[sd]" id="sd_input" readonly class="form-control input-form" />

                                        </div>

                                        <!-- Sueldo -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Sueldo *</span>
                                            <input type="text" name="users[sueldo]" id="sueldo_input" readonly class="form-control input-form" />
                                        </div>

                                        <!-- Fecha de Alta -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Fecha de Alta *</span>
                                            <input type="date" name="users[fecha_alta]" class="form-control input-form" required />
                                        </div>

                                        <!-- Fecha de Nacimiento -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Fecha de Nacimiento *</span>
                                            <input type="date" name="users[fecha_nacimiento]" class="form-control input-form" />
                                        </div>

                                        <!-- Lugar de Nacimiento -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Lugar de Nacimiento *</span>
                                            <input type="text" name="users[lugar_nacimiento]" class="form-control input-form" />
                                        </div>

                                        <!-- Estado Civil -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Estado Civil *</span>
                                            <select class="form-control input-form" name="users[estado_civil]">
                                                <option value="Soltero">Soltero</option>
                                                <option value="Casado">Casado</option>
                                                <option value="Union Libre">Unión Libre</option>
                                                <option value="Divorciado">Divorciado</option>
                                                <option value="Viudo">Viudo</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Calle *</span>
                                            <input type="text" name="users[calle]" class="form-control input-form" />
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Número *</span>
                                            <input type="text" name="users[numero]" class="form-control input-form" />
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Colonia *</span>
                                            <input type="text" name="users[colonia]" class="form-control input-form" />
                                        </div>

                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Municipio *</span>
                                            <input type="text" name="users[municipio]" class="form-control input-form" />
                                        </div>



                                        <!-- Estado -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Estado CIF *</span>
                                            <select class="form-control input-form" name="users[estado]">
                                                <?php echo $estados_mexico; ?>
                                            </select>
                                        </div>

                                        <!-- Código Postal -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Código Postal CIF *</span>
                                            <input type="text" name="users[codigo_postal]" maxlength="5" class="form-control input-form" />
                                        </div>

                                        <!-- Banco -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Banco *</span>
                                            <select class="form-control input-form" name="users[banco]">
                                                <option value="Santander">Santander</option>
                                                <option value="BanBajio">BanBajio</option>
                                            </select>
                                        </div>

                                        <!-- Número de cuenta -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Número de Cuenta *</span>
                                            <input type="text" name="users[numero_cuenta]" maxlength="10" class="form-control input-form" />
                                        </div>

                                        <!-- Clave Interbancaria -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Clave Interbancaria *</span>
                                            <input type="text" name="users[clave_interbancaria]" maxlength="18" class="form-control input-form" />
                                        </div>

                                        <!-- Correo Electrónico -->
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Correo Electrónico *</span>
                                            <input type="email" name="users[email]" class="form-control input-form" />
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 pl-0">
                                            <span class="floating-label-text">Nombre de nomina en Nomipaq *</span>
                                            <select class="form-control input-form " name="users[nomina_nomipaq]">
                                                <option hidden>Seleccionar nomina</option>
                                                <option value="Operativa 1">Operativa 1</option>
                                                <option value="Operativa 2">Operativa 2</option>
                                                <option value="Comude">Comude</option>
                                                <option value="12 Horas">12 Horas</option>
                                                <option value="FMC">FMC</option>
                                                <option value="Pisa 12 horas">Pisa 12 horas</option>
                                                <option value="Rush">Rush</option>
                                            </select>
                                        </div>

                                        <legend></legend>
                                        <div style="margin-top: 15px" class="form-group col-lg-12 col-md-12 pl-0">
                                            <button type="submit" class="btn btn-guardar next-step">Guardar</button>
                                        </div>
                                    </form>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>