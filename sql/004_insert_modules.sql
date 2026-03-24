INSERT INTO `modules` (`id`, `nombre`) VALUES (NULL, 'Modificar tickets');
INSERT INTO `modules` (`id`, `nombre`) VALUES (NULL, 'Tickets');
ALTER TABLE tickets add empresaId int null;
ALTER TABLE tickets add sedeId int null;
ALTER TABLE tickets add createdBy int null;
UPDATE tipo_servicios set nombre='General' where id=1;
UPDATE tipo_servicios set nombre='RPU' where id=2;
delete from tickets;
