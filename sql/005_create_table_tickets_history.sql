CREATE TABLE `tickets_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(100) NOT NULL,
  `comentario` text DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ticket_id` int NOT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tickets_history_ticket_id` (`ticket_id`),
  CONSTRAINT `fk_tickets_history_ticket`
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
