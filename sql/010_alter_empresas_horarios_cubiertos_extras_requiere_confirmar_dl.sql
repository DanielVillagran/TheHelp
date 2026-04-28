ALTER TABLE empresas_horarios_cubiertos_extras
ADD COLUMN requiere_confirmar_dl TINYINT(1) NOT NULL DEFAULT 0;
