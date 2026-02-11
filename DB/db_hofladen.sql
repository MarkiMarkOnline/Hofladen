-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Feb 2026 um 08:52
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_hofladen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_artikel`
--

CREATE TABLE `t_artikel` (
  `id_artikel` int(11) NOT NULL,
  `artikelbezeichnung` varchar(100) NOT NULL,
  `preis` float NOT NULL,
  `fk_mwst` int(11) NOT NULL,
  `fk_einheit` int(11) NOT NULL,
  `lagerbestand` float NOT NULL,
  `saisonware` tinyint(1) NOT NULL DEFAULT 0,
  `saisonstart` date NOT NULL DEFAULT '2000-01-01',
  `saisonende` date NOT NULL DEFAULT '2000-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_artikel`
--

INSERT INTO `t_artikel` (`id_artikel`, `artikelbezeichnung`, `preis`, `fk_mwst`, `fk_einheit`, `lagerbestand`, `saisonware`, `saisonstart`, `saisonende`) VALUES
(1, 'Kartoffeln festkochend', 0.99, 1, 1, 4362, 0, '2000-01-01', '2000-01-01'),
(2, 'Kartoffeln festkochend 2,5kg Sack', 1.99, 1, 3, 58, 0, '2000-01-01', '2000-01-01'),
(3, 'Kartoffeln festkochend 2,5 kg Sack', 1.99, 1, 3, 33, 0, '2000-01-01', '2000-01-01'),
(4, 'Milch 3,5% fett', 1.39, 1, 2, 26, 0, '2000-01-01', '2000-01-01'),
(5, 'Erdbeermarmelade', 2.98, 2, 3, 48, 0, '2000-01-01', '2000-01-01'),
(6, 'Orangenmarmelade', 2.98, 2, 3, 17, 0, '2000-01-01', '2000-01-01'),
(7, 'Früchteriegel', 1.99, 2, 3, 66, 1, '2000-01-01', '2000-03-31'),
(8, 'Karotten', 1.35, 1, 1, 34, 0, '2000-01-01', '2000-01-01'),
(9, 'Gurken', 1.79, 1, 1, 44.5, 0, '2000-01-01', '2000-01-01'),
(10, 'Sonnenblumenkernöl 500ml', 2.98, 1, 3, 214, 0, '2000-01-01', '2000-01-01'),
(11, 'Olivenöl 500ml', 8.95, 1, 3, 19, 0, '2000-01-01', '2000-01-01'),
(12, 'Birnen', 3.45, 1, 1, 22.6, 1, '2000-06-01', '2000-12-31'),
(13, 'Butter', 2.95, 1, 3, 41, 0, '2000-01-01', '2000-01-01'),
(14, 'Jagdtwurds', 12.95, 2, 1, 6.25, 0, '2000-01-01', '2000-01-01'),
(15, 'Rumpsteak', 24.5, 2, 1, 9.8, 0, '2000-01-01', '2000-01-01'),
(16, 'Salami', 6.45, 2, 1, 2.8, 0, '2000-01-01', '2000-01-01'),
(17, 'Schinken', 22.8, 2, 1, 12.4, 0, '2000-01-01', '2000-01-01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_bestelldetails`
--

CREATE TABLE `t_bestelldetails` (
  `fk_transaktionsnummer` int(11) NOT NULL,
  `fk_artikelnummer` int(11) NOT NULL,
  `menge` float NOT NULL DEFAULT 0,
  `einzelpreis` float NOT NULL DEFAULT 0,
  `rabatt` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_bestelldetails`
--

INSERT INTO `t_bestelldetails` (`fk_transaktionsnummer`, `fk_artikelnummer`, `menge`, `einzelpreis`, `rabatt`) VALUES
(1, 1, 15, 0.99, 0),
(2, 8, 2, 1.35, 0),
(2, 11, 1, 8.95, 0),
(3, 5, 1, 2.98, 0),
(3, 6, 2, 2.98, 0),
(3, 7, 3, 1.99, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_bestellung`
--

CREATE TABLE `t_bestellung` (
  `fk_transaktionsnummer` int(11) NOT NULL,
  `fk_kundennummer` int(11) NOT NULL,
  `bestelldatum` datetime NOT NULL,
  `lieferdatum` datetime DEFAULT NULL,
  `versanddatum` datetime DEFAULT NULL,
  `fk_versandmit` int(11) NOT NULL,
  `sendungsnummer` varchar(200) NOT NULL,
  `lieferkosten` float NOT NULL DEFAULT 0,
  `empfaenger` varchar(200) NOT NULL,
  `strasse` varchar(200) NOT NULL,
  `plz` varchar(5) NOT NULL,
  `ort` varchar(200) NOT NULL,
  `land` varchar(200) NOT NULL,
  `signatur` varchar(200) NOT NULL,
  `pruefwert` int(11) NOT NULL,
  `fk_zahlungsart` int(11) NOT NULL,
  `fk_kassensignatur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_bestellung`
--

INSERT INTO `t_bestellung` (`fk_transaktionsnummer`, `fk_kundennummer`, `bestelldatum`, `lieferdatum`, `versanddatum`, `fk_versandmit`, `sendungsnummer`, `lieferkosten`, `empfaenger`, `strasse`, `plz`, `ort`, `land`, `signatur`, `pruefwert`, `fk_zahlungsart`, `fk_kassensignatur`) VALUES
(1, 9, '2026-02-05 11:51:08', NULL, NULL, 2, '', 0, '', '', '', '', '', '0K010126', 4676, 1, 1),
(2, 10, '2026-02-05 12:08:54', NULL, NULL, 2, '', 0, '', '', '', '', '', '0K010126', 9578, 2, 1),
(3, 7, '2026-02-05 12:08:54', NULL, NULL, 2, '', 0, '', '', '', '', '', '0K010226', 1544, 4, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_einheit`
--

CREATE TABLE `t_einheit` (
  `id_einheit` int(11) NOT NULL,
  `einnheit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_einheit`
--

INSERT INTO `t_einheit` (`id_einheit`, `einnheit`) VALUES
(1, 'kg'),
(2, 'l'),
(3, 'Gebinde');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_herkunft`
--

CREATE TABLE `t_herkunft` (
  `id_herkunft` int(11) NOT NULL,
  `herkunft` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_herkunft`
--

INSERT INTO `t_herkunft` (`id_herkunft`, `herkunft`) VALUES
(1, 'Eigenerzeugnis'),
(2, 'Eigenproduktion'),
(3, 'Zulieferung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_kasse`
--

CREATE TABLE `t_kasse` (
  `id_kasse` int(11) NOT NULL,
  `kasse` varchar(200) NOT NULL,
  `id_mitarbeiter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_kasse`
--

INSERT INTO `t_kasse` (`id_kasse`, `kasse`, `id_mitarbeiter`) VALUES
(1, 'K0101', 0),
(2, 'K0102', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_kunde`
--

CREATE TABLE `t_kunde` (
  `id_kunde` int(11) NOT NULL,
  `vorname` varchar(200) NOT NULL,
  `nachname` varchar(200) NOT NULL,
  `firma` varchar(200) NOT NULL,
  `strasse` varchar(200) NOT NULL,
  `hausnummer` varchar(10) NOT NULL,
  `ort` varchar(200) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `region` varchar(200) NOT NULL,
  `land` varchar(60) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `telefax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_kunde`
--

INSERT INTO `t_kunde` (`id_kunde`, `vorname`, `nachname`, `firma`, `strasse`, `hausnummer`, `ort`, `plz`, `region`, `land`, `telefon`, `telefax`, `email`) VALUES
(7, 'Marius', 'Stegmann', '', 'Hauptstrasse', '27a', 'Berlin', '12658', 'Berlin', 'Deutschland', '0157 55789648', '', 'Stegmann@gmail.com'),
(8, 'Julian', 'Borghauser', 'Restaurant Le Petit', 'Marktstraße', '127', 'Berlin', '14568', '', 'Deutschland', '0304759856', '', ''),
(9, 'Anna', 'Reusling', 'Kochschule Neugedacht', 'Kohlweg', '4', 'Wildau', '15745', 'Brandenburg', 'Deutschland', '+49156 548785225', '', 'kochschule-neugedacht.de'),
(10, 'Falk', 'Keller', 'Weingut Falk', 'Sonnenallee', '4b', 'Oberbergen', '79235', 'Baden-Württemberg', 'Deutschland', '49167 8754691', '', 'weingut@falk.com'),
(11, 'Andreas', 'Wimmer', 'Panoramahotel Fliesenhof', 'Dorfstraße', '45', 'Schwerin', '19061', 'Mecklenburg-Vorpommern', 'Deutschland', '0157 33556478', '061 255 254 186', 'fliesenhof@info.com');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_lieferant`
--

CREATE TABLE `t_lieferant` (
  `id_lieferant` int(11) NOT NULL,
  `firma` varchar(100) NOT NULL,
  `kontaktperson` varchar(100) NOT NULL,
  `strasse` varchar(100) NOT NULL,
  `hausnummer` varchar(10) NOT NULL,
  `ort` varchar(100) NOT NULL,
  `plz` varchar(5) NOT NULL,
  `region` varchar(100) NOT NULL,
  `land` varchar(100) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `telefax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `homepage` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_lieferant`
--

INSERT INTO `t_lieferant` (`id_lieferant`, `firma`, `kontaktperson`, `strasse`, `hausnummer`, `ort`, `plz`, `region`, `land`, `telefon`, `telefax`, `email`, `homepage`) VALUES
(1, 'Bauernhof Müller', 'Herr Müller', 'Getreideweg', '1', 'Neuruppin', '16883', 'Brandenburg', 'Deutschland', '0123 555 888', '0123 555 887', 'mueller@mueller.de', ''),
(2, 'Weideland', 'Frau Wiese', 'Am Hügel', '7', 'Warin', '19417', 'Mecklenburg-Vorpommern', 'Deutschland', '0125 156 658', '0125 156 758', 'support@weideland.de', 'weideland.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_mitarbeiter`
--

CREATE TABLE `t_mitarbeiter` (
  `id_mitarbeiter` int(11) NOT NULL,
  `vorname` varchar(50) NOT NULL,
  `nachname` varchar(50) NOT NULL,
  `rolle` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_mitarbeiter`
--

INSERT INTO `t_mitarbeiter` (`id_mitarbeiter`, `vorname`, `nachname`, `rolle`) VALUES
(6, 'Armin', 'Anders', 1),
(7, 'Lisa', 'Hofstedt', 0),
(8, 'Kevin', 'Hofreiter', 0),
(9, 'Franzsika', 'Kobol', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_mwst`
--

CREATE TABLE `t_mwst` (
  `id_mwst` int(11) NOT NULL,
  `mwst` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_mwst`
--

INSERT INTO `t_mwst` (`id_mwst`, `mwst`) VALUES
(1, 7),
(2, 19);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_versandtdienstleister`
--

CREATE TABLE `t_versandtdienstleister` (
  `id_versandtdienstleister` int(11) NOT NULL,
  `versandtdienstleister` varchar(200) NOT NULL,
  `kontaktperson` varchar(200) NOT NULL,
  `strasse` varchar(200) NOT NULL,
  `hausnummer` varchar(10) NOT NULL,
  `ort` varchar(200) NOT NULL,
  `plz` varchar(10) NOT NULL,
  `region` varchar(200) NOT NULL,
  `land` varchar(60) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `telefax` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `homepage` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_versandtdienstleister`
--

INSERT INTO `t_versandtdienstleister` (`id_versandtdienstleister`, `versandtdienstleister`, `kontaktperson`, `strasse`, `hausnummer`, `ort`, `plz`, `region`, `land`, `telefon`, `telefax`, `email`, `homepage`) VALUES
(2, 'selbstabholung', 'Frau Schneider', '', '', '', '', '', '', '030 45687', '', '', ''),
(3, 'DHL', 'Herr Hofstett', 'Am Versandzentrum', '1', 'Berlin', '12547', 'Berlin', 'Deutschland', '030 123564', '030 123566', 'support@dhl.de', 'dhl.de'),
(4, 'Spedition Frisch', 'Herr Buchenholz', 'Landsberger Allee ', '15', 'Berlin', '15647', 'Berlin', 'Deutschland', '030987574', '', 'info@frisch.de', 'spedition-frisch.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_warengruppe`
--

CREATE TABLE `t_warengruppe` (
  `id_warengruppe` int(11) NOT NULL,
  `warengruppe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_warengruppe`
--

INSERT INTO `t_warengruppe` (`id_warengruppe`, `warengruppe`) VALUES
(1, 'Obst'),
(2, 'Gemüse'),
(3, 'Milchprodukt'),
(4, 'Fleisch'),
(5, 'Nüsse'),
(6, 'Marmelade'),
(7, 'Öl');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_zahlungsart`
--

CREATE TABLE `t_zahlungsart` (
  `id_zahlungsart` int(11) NOT NULL,
  `zahlungsart` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `t_zahlungsart`
--

INSERT INTO `t_zahlungsart` (`id_zahlungsart`, `zahlungsart`) VALUES
(1, 'Bar'),
(2, 'EC'),
(3, 'Kreditkarte'),
(4, 'Rechnung'),
(5, 'Gutschein');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vt_artikel_herkunft`
--

CREATE TABLE `vt_artikel_herkunft` (
  `fk_artikel` int(11) NOT NULL,
  `fk_herkunft` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `vt_artikel_herkunft`
--

INSERT INTO `vt_artikel_herkunft` (`fk_artikel`, `fk_herkunft`) VALUES
(1, 1),
(2, 1),
(3, 3),
(4, 1),
(5, 2),
(6, 2),
(7, 2),
(8, 1),
(9, 1),
(10, 2),
(11, 3),
(12, 3),
(13, 2),
(14, 2),
(15, 2),
(16, 3),
(17, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vt_artikel_lieferant`
--

CREATE TABLE `vt_artikel_lieferant` (
  `fk_artikel` int(11) NOT NULL,
  `fk_lieferant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `vt_artikel_lieferant`
--

INSERT INTO `vt_artikel_lieferant` (`fk_artikel`, `fk_lieferant`) VALUES
(3, 1),
(11, 1),
(12, 2),
(16, 2),
(17, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vt_artikel_warengruppe`
--

CREATE TABLE `vt_artikel_warengruppe` (
  `fk_artikel` int(11) NOT NULL,
  `fk_warengruppe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `vt_artikel_warengruppe`
--

INSERT INTO `vt_artikel_warengruppe` (`fk_artikel`, `fk_warengruppe`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 3),
(5, 1),
(6, 1),
(7, 1),
(7, 3),
(8, 2),
(9, 2),
(10, 7),
(11, 7),
(12, 1),
(13, 3),
(14, 4),
(15, 4),
(16, 4),
(17, 4);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `t_artikel`
--
ALTER TABLE `t_artikel`
  ADD PRIMARY KEY (`id_artikel`),
  ADD KEY `fk_mwst` (`fk_mwst`),
  ADD KEY `fk_einheit` (`fk_einheit`);

--
-- Indizes für die Tabelle `t_bestelldetails`
--
ALTER TABLE `t_bestelldetails`
  ADD PRIMARY KEY (`fk_transaktionsnummer`,`fk_artikelnummer`),
  ADD KEY `fk_transaktionsnummer` (`fk_transaktionsnummer`),
  ADD KEY `fk_artikelnummer` (`fk_artikelnummer`);

--
-- Indizes für die Tabelle `t_bestellung`
--
ALTER TABLE `t_bestellung`
  ADD UNIQUE KEY `fk_transaktionsnummer_3` (`fk_transaktionsnummer`,`fk_kundennummer`),
  ADD KEY `fk_zahlungsart` (`fk_zahlungsart`),
  ADD KEY `fk_kassensignatur` (`fk_kassensignatur`),
  ADD KEY `fk_versandmit` (`fk_versandmit`);

--
-- Indizes für die Tabelle `t_einheit`
--
ALTER TABLE `t_einheit`
  ADD PRIMARY KEY (`id_einheit`);

--
-- Indizes für die Tabelle `t_herkunft`
--
ALTER TABLE `t_herkunft`
  ADD PRIMARY KEY (`id_herkunft`);

--
-- Indizes für die Tabelle `t_kasse`
--
ALTER TABLE `t_kasse`
  ADD PRIMARY KEY (`id_kasse`),
  ADD KEY `id_mitarbeiter` (`id_mitarbeiter`);

--
-- Indizes für die Tabelle `t_kunde`
--
ALTER TABLE `t_kunde`
  ADD PRIMARY KEY (`id_kunde`);

--
-- Indizes für die Tabelle `t_lieferant`
--
ALTER TABLE `t_lieferant`
  ADD PRIMARY KEY (`id_lieferant`);

--
-- Indizes für die Tabelle `t_mitarbeiter`
--
ALTER TABLE `t_mitarbeiter`
  ADD PRIMARY KEY (`id_mitarbeiter`);

--
-- Indizes für die Tabelle `t_mwst`
--
ALTER TABLE `t_mwst`
  ADD PRIMARY KEY (`id_mwst`);

--
-- Indizes für die Tabelle `t_versandtdienstleister`
--
ALTER TABLE `t_versandtdienstleister`
  ADD PRIMARY KEY (`id_versandtdienstleister`);

--
-- Indizes für die Tabelle `t_warengruppe`
--
ALTER TABLE `t_warengruppe`
  ADD PRIMARY KEY (`id_warengruppe`);

--
-- Indizes für die Tabelle `t_zahlungsart`
--
ALTER TABLE `t_zahlungsart`
  ADD PRIMARY KEY (`id_zahlungsart`);

--
-- Indizes für die Tabelle `vt_artikel_herkunft`
--
ALTER TABLE `vt_artikel_herkunft`
  ADD PRIMARY KEY (`fk_artikel`,`fk_herkunft`),
  ADD KEY `fk_herkunft` (`fk_herkunft`);

--
-- Indizes für die Tabelle `vt_artikel_lieferant`
--
ALTER TABLE `vt_artikel_lieferant`
  ADD PRIMARY KEY (`fk_artikel`,`fk_lieferant`),
  ADD KEY `fk_lieferant` (`fk_lieferant`);

--
-- Indizes für die Tabelle `vt_artikel_warengruppe`
--
ALTER TABLE `vt_artikel_warengruppe`
  ADD PRIMARY KEY (`fk_artikel`,`fk_warengruppe`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `t_artikel`
--
ALTER TABLE `t_artikel`
  MODIFY `id_artikel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `t_bestellung`
--
ALTER TABLE `t_bestellung`
  MODIFY `fk_transaktionsnummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `t_einheit`
--
ALTER TABLE `t_einheit`
  MODIFY `id_einheit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `t_herkunft`
--
ALTER TABLE `t_herkunft`
  MODIFY `id_herkunft` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `t_kasse`
--
ALTER TABLE `t_kasse`
  MODIFY `id_kasse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `t_kunde`
--
ALTER TABLE `t_kunde`
  MODIFY `id_kunde` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT für Tabelle `t_lieferant`
--
ALTER TABLE `t_lieferant`
  MODIFY `id_lieferant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `t_mitarbeiter`
--
ALTER TABLE `t_mitarbeiter`
  MODIFY `id_mitarbeiter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `t_mwst`
--
ALTER TABLE `t_mwst`
  MODIFY `id_mwst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `t_versandtdienstleister`
--
ALTER TABLE `t_versandtdienstleister`
  MODIFY `id_versandtdienstleister` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `t_warengruppe`
--
ALTER TABLE `t_warengruppe`
  MODIFY `id_warengruppe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `t_zahlungsart`
--
ALTER TABLE `t_zahlungsart`
  MODIFY `id_zahlungsart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `t_artikel`
--
ALTER TABLE `t_artikel`
  ADD CONSTRAINT `t_artikel_ibfk_1` FOREIGN KEY (`fk_einheit`) REFERENCES `t_einheit` (`id_einheit`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_artikel_ibfk_2` FOREIGN KEY (`fk_mwst`) REFERENCES `t_mwst` (`id_mwst`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `t_bestelldetails`
--
ALTER TABLE `t_bestelldetails`
  ADD CONSTRAINT `t_bestelldetails_ibfk_1` FOREIGN KEY (`fk_artikelnummer`) REFERENCES `t_artikel` (`id_artikel`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_bestelldetails_ibfk_2` FOREIGN KEY (`fk_transaktionsnummer`) REFERENCES `t_bestellung` (`fk_transaktionsnummer`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `t_bestellung`
--
ALTER TABLE `t_bestellung`
  ADD CONSTRAINT `t_bestellung_ibfk_3` FOREIGN KEY (`fk_kassensignatur`) REFERENCES `t_kasse` (`id_kasse`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_bestellung_ibfk_4` FOREIGN KEY (`fk_versandmit`) REFERENCES `t_versandtdienstleister` (`id_versandtdienstleister`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_bestellung_ibfk_5` FOREIGN KEY (`fk_zahlungsart`) REFERENCES `t_zahlungsart` (`id_zahlungsart`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_bestellung_ibfk_6` FOREIGN KEY (`fk_kundennummer`) REFERENCES `t_kunde` (`id_kunde`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `vt_artikel_herkunft`
--
ALTER TABLE `vt_artikel_herkunft`
  ADD CONSTRAINT `vt_artikel_herkunft_ibfk_1` FOREIGN KEY (`fk_herkunft`) REFERENCES `t_herkunft` (`id_herkunft`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_artikel_herkunft_ibfk_2` FOREIGN KEY (`fk_artikel`) REFERENCES `t_artikel` (`id_artikel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `vt_artikel_lieferant`
--
ALTER TABLE `vt_artikel_lieferant`
  ADD CONSTRAINT `vt_artikel_lieferant_ibfk_1` FOREIGN KEY (`fk_artikel`) REFERENCES `t_artikel` (`id_artikel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_artikel_lieferant_ibfk_2` FOREIGN KEY (`fk_lieferant`) REFERENCES `t_lieferant` (`id_lieferant`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `vt_artikel_warengruppe`
--
ALTER TABLE `vt_artikel_warengruppe`
  ADD CONSTRAINT `vt_artikel_warengruppe_ibfk_1` FOREIGN KEY (`fk_warengruppe`) REFERENCES `t_warengruppe` (`id_warengruppe`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vt_artikel_warengruppe_ibfk_2` FOREIGN KEY (`fk_artikel`) REFERENCES `t_artikel` (`id_artikel`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
