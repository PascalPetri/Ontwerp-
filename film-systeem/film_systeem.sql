-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 07 nov 2025 om 22:34
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `film_systeem`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `acteurs`
--

CREATE TABLE `acteurs` (
  `acteur_id` int(10) UNSIGNED NOT NULL,
  `acteurnaam` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `acteurs`
--

INSERT INTO `acteurs` (`acteur_id`, `acteurnaam`) VALUES
(1, 'Keanu Reeves'),
(2, 'Laurence Fishburne'),
(3, 'Carrie-Anne Moss'),
(4, 'Leonardo DiCaprio'),
(5, 'Tom Hardy'),
(6, 'Christian Bale'),
(7, 'Heath Ledger'),
(8, 'John Travolta'),
(9, 'Samuel L. Jackson'),
(10, 'Uma Thurman'),
(11, 'test');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `films`
--

CREATE TABLE `films` (
  `film_id` int(10) UNSIGNED NOT NULL,
  `filmaam` text NOT NULL,
  `genre_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `films`
--

INSERT INTO `films` (`film_id`, `filmaam`, `genre_id`) VALUES
(1, 'The Matrix', 5),
(2, 'Inception', 5),
(3, 'The Dark Knight', 1),
(4, 'Pulp Fiction', 2),
(5, 'test', 1),
(6, 'test', 3),
(7, 'IT', 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `film_acteurs`
--

CREATE TABLE `film_acteurs` (
  `film_id` int(10) UNSIGNED NOT NULL,
  `acteur_id` int(10) UNSIGNED NOT NULL,
  `rol` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `film_acteurs`
--

INSERT INTO `film_acteurs` (`film_id`, `acteur_id`, `rol`) VALUES
(1, 1, 'Neo'),
(1, 2, 'Morpheus'),
(1, 3, 'Trinity'),
(2, 4, 'Dom Cobb'),
(2, 5, 'Eames'),
(3, 6, 'Bruce Wayne / Batman'),
(3, 7, 'Joker'),
(4, 1, 'test'),
(4, 8, 'Vincent Vega'),
(4, 9, 'Jules Winnfield'),
(4, 10, 'Mia Wallace'),
(5, 8, 'test'),
(7, 4, 'IT');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(10) UNSIGNED NOT NULL,
  `genre_naam` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_naam`) VALUES
(1, 'Actie'),
(2, 'Drama'),
(3, 'Comedie'),
(4, 'Thriller'),
(5, 'Science Fiction'),
(6, 'Romantiek'),
(7, 'Horror'),
(8, 'Documentaire');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `acteurs`
--
ALTER TABLE `acteurs`
  ADD PRIMARY KEY (`acteur_id`);

--
-- Indexen voor tabel `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`film_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexen voor tabel `film_acteurs`
--
ALTER TABLE `film_acteurs`
  ADD PRIMARY KEY (`film_id`,`acteur_id`),
  ADD KEY `acteur_id` (`acteur_id`);

--
-- Indexen voor tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `acteurs`
--
ALTER TABLE `acteurs`
  MODIFY `acteur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `films`
--
ALTER TABLE `films`
  MODIFY `film_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE SET NULL;

--
-- Beperkingen voor tabel `film_acteurs`
--
ALTER TABLE `film_acteurs`
  ADD CONSTRAINT `film_acteurs_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `films` (`film_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `film_acteurs_ibfk_2` FOREIGN KEY (`acteur_id`) REFERENCES `acteurs` (`acteur_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
