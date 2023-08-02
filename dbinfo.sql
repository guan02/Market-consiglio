-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 02, 2023 alle 19:18
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbinfo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `fornitore`
--

CREATE TABLE `fornitore` (
  `codF` varchar(20) NOT NULL,
  `nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fornitore`
--

INSERT INTO `fornitore` (`codF`, `nome`) VALUES
('A1', 'ZA'),
('A2', 'XA'),
('A3', 'SA'),
('A4', 'QA');

-- --------------------------------------------------------

--
-- Struttura della tabella `fp`
--

CREATE TABLE `fp` (
  `codF` varchar(11) NOT NULL,
  `codP` varchar(11) NOT NULL,
  `quantita` int(200) NOT NULL,
  `costo` int(100) NOT NULL,
  `giornoSpedizione` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fp`
--

INSERT INTO `fp` (`codF`, `codP`, `quantita`, `costo`, `giornoSpedizione`) VALUES
('A1', 'P1', 500, 4, 2),
('A1', 'P2', 500, 4, 1),
('A2', 'P1', 500, 5, 1),
('A2', 'P2', 500, 3, 1),
('A3', 'P1', 500, 3, 3),
('A3', 'P2', 500, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `p`
--

CREATE TABLE `p` (
  `CodP` varchar(11) NOT NULL,
  `Nome` varchar(11) DEFAULT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `p`
--

INSERT INTO `p` (`CodP`, `Nome`, `foto`) VALUES
('P1', 'penna', 'https://m.media-amazon.com/images/I/61furRjhz3L._AC_UF1000,1000_QL80_.jpg'),
('P2', 'gomma', 'https://www.cartucciopolistore.it/wp-content/uploads/2019/01/temp-84.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `registrosconto`
--

CREATE TABLE `registrosconto` (
  `id_codF` varchar(20) NOT NULL,
  `id_codSconto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `registrosconto`
--

INSERT INTO `registrosconto` (`id_codF`, `id_codSconto`) VALUES
('A1', 's1'),
('A1', 's3'),
('A1', 's6'),
('A1', 's7'),
('A2', 's2'),
('A2', 's4'),
('A2', 's6'),
('A3', 's3'),
('A3', 's6'),
('A4', 's1'),
('A4', 's6');

-- --------------------------------------------------------

--
-- Struttura della tabella `sconto`
--

CREATE TABLE `sconto` (
  `id` varchar(20) NOT NULL,
  `tipo` enum('prezzoTotale','quantita','stagione','') NOT NULL,
  `condizione` int(100) NOT NULL,
  `valore` int(100) NOT NULL,
  `scadenza` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sconto`
--

INSERT INTO `sconto` (`id`, `tipo`, `condizione`, `valore`, `scadenza`) VALUES
('s1', 'prezzoTotale', 50, 10, '2024-04-01'),
('s2', 'prezzoTotale', 40, 10, '2024-04-01'),
('s3', 'quantita', 20, 5, '2024-04-01'),
('s4', 'quantita', 15, 5, '2024-04-01'),
('s6', 'stagione', 0, 5, '2024-04-01'),
('s7', 'stagione', 0, 5, '2024-04-01');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `fornitore`
--
ALTER TABLE `fornitore`
  ADD PRIMARY KEY (`codF`);

--
-- Indici per le tabelle `fp`
--
ALTER TABLE `fp`
  ADD PRIMARY KEY (`codF`,`codP`),
  ADD KEY `codP` (`codP`),
  ADD KEY `codF` (`codF`);

--
-- Indici per le tabelle `p`
--
ALTER TABLE `p`
  ADD PRIMARY KEY (`CodP`);

--
-- Indici per le tabelle `registrosconto`
--
ALTER TABLE `registrosconto`
  ADD PRIMARY KEY (`id_codF`,`id_codSconto`),
  ADD KEY `id_codSconto` (`id_codSconto`);

--
-- Indici per le tabelle `sconto`
--
ALTER TABLE `sconto`
  ADD PRIMARY KEY (`id`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `fp`
--
ALTER TABLE `fp`
  ADD CONSTRAINT `fp_ibfk_1` FOREIGN KEY (`codP`) REFERENCES `p` (`CodP`),
  ADD CONSTRAINT `fp_ibfk_2` FOREIGN KEY (`codF`) REFERENCES `fornitore` (`codF`);

--
-- Limiti per la tabella `registrosconto`
--
ALTER TABLE `registrosconto`
  ADD CONSTRAINT `registrosconto_ibfk_1` FOREIGN KEY (`id_codF`) REFERENCES `fornitore` (`codF`),
  ADD CONSTRAINT `registrosconto_ibfk_2` FOREIGN KEY (`id_codSconto`) REFERENCES `sconto` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
