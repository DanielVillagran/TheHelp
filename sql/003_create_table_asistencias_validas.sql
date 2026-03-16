create table asistencias_validas(
	id int not null AUTO_INCREMENT PRIMARY KEY,
    user_id int null,
	sede_id int null,
    fecha date null,
    lat varchar(100) null,
    lng varchar(100) null,
    colaborador_id int null,
    created_at datetime null default now()
)