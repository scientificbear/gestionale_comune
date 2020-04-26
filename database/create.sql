CREATE TABLE `immobili` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descrizione` varchar(255),
  `indirizzo` varchar(255),
  `cap` varchar(255),
  `circoscrizione` int,
  `codice` varchar(255),
  `id_tipologia` int NOT NULL,
  `telefono_ref` varchar(255),
  `nome_ref` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tipo_immobili` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tipologia` varchar(255) NOT NULL
);

CREATE TABLE `categoria_ditte` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `categoria` varchar(255) NOT NULL
);


CREATE TABLE `ditte` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `indirizzo` varchar(255),
  `cap` varchar(255),
  `comune` varchar(255),
  `provincia` varchar(255),
  `email` varchar(255),
  `telefono_ref` varchar(255),
  `nome_ref` varchar(255),
  `id_categoria` int,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `interventi_immobili` (
  `id` int NOT NULL PRIMARY KEY,
  `data` date,
  `descrizione` varchar(255),
  `id_immobile` int NOT NULL,
  `id_utente` int NOT NULL,
  `id_ditta` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `utenti` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255),
  `cognome` varchar(255),
  `email` varchar(255) NOT NULL UNIQUE,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255),
  `role` varchar(255)
);

CREATE TABLE `festivita` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `data` date NOT NULL,
  `descrizione` varchar(255)
);

CREATE TABLE `assenze` (
  `id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_utente` int NOT NULL,
  `data` date NOT NULL,
  `descrizione` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `immobili` ADD FOREIGN KEY (`id_tipologia`) REFERENCES `tipo_immobili` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_immobile`) REFERENCES `immobili` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_ditta`) REFERENCES `ditte` (`id`);

ALTER TABLE `assenze` ADD FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`);
