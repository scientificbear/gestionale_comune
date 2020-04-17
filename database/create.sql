CREATE TABLE `immobili` (
  `id` int PRIMARY KEY,
  `nome` varchar(255),
  `descrizione` varchar(255),
  `indirizzo` varchar(255),
  `cap` varchar(255),
  `circoscrizione` int,
  `codice` varchar(255),
  `id_tipologia` varchar(255),
  `telefono_ref` varchar(255),
  `nome_ref` varchar(255),
  `created_at` timestamp,
  `modified_at` timestamp
);

CREATE TABLE `tipo_immobili` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tipologia` varchar(255)
);

CREATE TABLE `ditte` (
  `id` int PRIMARY KEY,
  `nome` varchar(255),
  `indirizzo` varchar(255),
  `cap` varchar(255),
  `comune` varchar(255),
  `provincia` varchar(255),
  `email` varchar(255),
  `telefono_ref` varchar(255),
  `nome_ref` varchar(255),
  `categoria` varchar(255)
);

CREATE TABLE `interventi_immobili` (
  `id` int PRIMARY KEY,
  `data` date,
  `descrizione` varchar(255),
  `id_immobile` int,
  `id_utente` int,
  `id_ditta` int,
  `created_at` timestamp,
  `modified_at` timestamp
);

CREATE TABLE `utenti` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255),
  `cognome` varchar(255),
  `email` varchar(255),
  `created_at` timestamp,
  `password` varchar(255)
);

CREATE TABLE `festivita` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `data` date,
  `descrizione` varchar(255)
);

CREATE TABLE `assenze` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_utente` in,
  `data` date,
  `descrizione` varchar(255)
);

ALTER TABLE `immobili` ADD FOREIGN KEY (`id_tipologia`) REFERENCES `tipo_immobili` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_immobile`) REFERENCES `immobili` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`);

ALTER TABLE `interventi_immobili` ADD FOREIGN KEY (`id_ditta`) REFERENCES `ditte` (`id`);

ALTER TABLE `assenze` ADD FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`);
