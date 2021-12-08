-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 07, 2021 at 09:44 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `2b6u0_mrbin`
--

--
-- Dumping data for table `contenu`
--

INSERT INTO `contenu` (`id`, `label`, `icon`) VALUES
                                                  (1, 'Plastique', ''),
                                                  (2, 'Carton', '');

--
-- Dumping data for table `couleurs`
--

INSERT INTO `couleurs` (`id`, `label`, `hexa`) VALUES
    (1, 'Jaune', '#FFD363');

--
-- Dumping data for table `horaires`
--

INSERT INTO `horaires` (`id`, `poubelles_id`, `heure`, `jour`) VALUES
                                                                   (1, 1, '18:00:00', '2021-12-08'),
                                                                   (2, 2, '18:00:00', '2021-12-08');

--
-- Dumping data for table `poubelles`
--

INSERT INTO `poubelles` (`id`, `contenue_id`, `couleur_id`, `ville_id`) VALUES
                                                                            (1, 2, 1, 1),
                                                                            (2, 1, 1, 2);

--
-- Dumping data for table `villes`
--

INSERT INTO `villes` (`id`, `cp`, `label`) VALUES
                                               (1, '93100', 'Montreuil'),
                                               (2, '68350', 'Brunstatt');
