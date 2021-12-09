-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 09, 2021 at 08:30 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `2b6u0_mrbin`
--

--
-- Dumping data for table `composition`
--

INSERT INTO `composition` (`id`, `matiere_id`, `produit_id`, `label`) VALUES
                                                                          (1, 2, 1, 'Support'),
                                                                          (2, 2, 1, 'Opercule'),
                                                                          (3, NULL, NULL, ''),
                                                                          (4, 4, 1, 'Pot');

--
-- Dumping data for table `contenu`
--

INSERT INTO `contenu` (`id`, `label`, `icon`) VALUES
                                                  (1, 'Papier', 'https://mrbin.julienwagentrutz.com/assets/contenues/paper.svg'),
                                                  (2, 'Carton', 'https://mrbin.julienwagentrutz.com/assets/contenues/carton.svg'),
                                                  (3, 'Plastique', 'https://mrbin.julienwagentrutz.com/assets/contenues/plastic.svg'),
                                                  (4, 'Verre', '');

--
-- Dumping data for table `couleurs`
--

INSERT INTO `couleurs` (`id`, `label`, `class`) VALUES
                                                    (1, 'Jaune', 'yellow'),
                                                    (2, 'Verte', 'green');

--
-- Dumping data for table `poubelles`
--

INSERT INTO `poubelles` (`id`, `couleur_id`, `ville_id`) VALUES
                                                             (1, 1, 2),
                                                             (2, 2, 1),
                                                             (3, 2, 2);

--
-- Dumping data for table `poubelles_contenu`
--

INSERT INTO `poubelles_contenu` (`poubelles_id`, `contenu_id`) VALUES
                                                                   (1, 1),
                                                                   (1, 2),
                                                                   (1, 3),
                                                                   (2, 1),
                                                                   (3, 4);

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `code_barre`, `label`, `marque`, `saviez_vous`) VALUES
    (1, '3017620425035', 'Pâte à tartiner aux noisettes', 'Nutella', 'Eh oui c\'est ça');

--
-- Dumping data for table `villes`
--

INSERT INTO `villes` (`id`, `cp`, `label`) VALUES
(1, '68350', 'Brunstatt'),
(2, '93100', 'Montreuil');
