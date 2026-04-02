ALTER TABLE tipo_servicios
ADD usuario_asignado INT NULL,
    con_copia_correo TINYINT(1) NOT NULL DEFAULT 0;
