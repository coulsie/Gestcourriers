-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mer. 28 jan. 2026 à 08:13
-- Version du serveur : 11.4.9-MariaDB
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestcourriers`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

DROP TABLE IF EXISTS `absences`;
CREATE TABLE IF NOT EXISTS `absences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `type_absence_id` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `approuvee` tinyint(1) NOT NULL DEFAULT 0,
  `document_justificatif` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absences_agent_id_foreign` (`agent_id`),
  KEY `absences_type_absence_id_foreign` (`type_absence_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `absences`
--

INSERT INTO `absences` (`id`, `agent_id`, `type_absence_id`, `date_debut`, `date_fin`, `approuvee`, `document_justificatif`, `created_at`, `updated_at`) VALUES
(3, 15, 3, '2026-01-01', '2026-12-31', 1, '1769004536_Document_2025-11-27_121019.pdf', '2026-01-21 14:08:56', '2026-01-21 14:08:56'),
(2, 10, 2, '2026-01-13', '2026-01-15', 1, '1769004466_Document_2025-11-27_121019.pdf', '2026-01-21 13:50:03', '2026-01-21 14:07:46');

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

DROP TABLE IF EXISTS `affectations`;
CREATE TABLE IF NOT EXISTS `affectations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `courrier_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `statut` varchar(191) NOT NULL DEFAULT 'pending',
  `commentaires` text DEFAULT NULL,
  `date_affectation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_traitement` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affectations_courrier_id_foreign` (`courrier_id`),
  KEY `affectations_user_id_foreign` (`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_professionnel` varchar(191) DEFAULT NULL,
  `matricule` varchar(191) NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `status` enum('Agent','Chef de service','Sous-directeur','Directeur','Conseiller Technique','Conseiller Spécial') NOT NULL DEFAULT 'Agent',
  `sexe` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `Place_birth` varchar(191) DEFAULT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `Emploi` varchar(191) DEFAULT NULL,
  `Grade` varchar(191) DEFAULT NULL,
  `Date_Prise_de_service` date DEFAULT NULL,
  `Personne_a_prevenir` varchar(191) DEFAULT NULL,
  `Contact_personne_a_prevenir` varchar(191) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agents_matricule_unique` (`matricule`),
  UNIQUE KEY `agents_email_unique` (`email`),
  UNIQUE KEY `agents_email_professionnel_unique` (`email_professionnel`),
  KEY `agents_service_id_foreign` (`service_id`),
  KEY `agents_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `email_professionnel`, `matricule`, `first_name`, `last_name`, `status`, `sexe`, `date_of_birth`, `Place_birth`, `photo`, `email`, `phone_number`, `address`, `Emploi`, `Grade`, `Date_Prise_de_service`, `Personne_a_prevenir`, `Contact_personne_a_prevenir`, `service_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'yacouba.coulibaly@dgi.gouv.ci', '287688C', 'Sié Yacouba', 'COULIBALY', 'Agent', 'Male', '1972-12-04', 'COCODY', '1768915886_Photo identite COULIBALY Sié Yacouba 287 688C.JPG', 'yacouba.coulibaly@dgi.gouv.ci', '0707584396', '08 BP 2359', 'Ingenieur Principal Informatique', 'A5', '2025-07-08', 'COULIBALY Youssef Kiyali', '0143677424', 1, 3, '2026-01-20 13:25:02', '2026-01-20 14:04:02'),
(2, 'nafie410@dgi.gouv.ci', '410702H', 'Nafata', 'KONE', 'Agent', 'Female', '1982-07-21', NULL, '1768916792_Photo_nafi.jpg', 'nafie410@dgi.gouv.ci', '0707188674', 'Bassam Mockeyville', 'Contrôleur des Impôts', 'B3', '2014-11-03', 'KONE Dieudoné', '0747234646', 1, 4, '2026-01-20 13:46:32', '2026-01-20 13:56:14'),
(3, 'roussou@dgi.gouv.ci', '291264E', 'Rosine Générosa Epse Dje', 'OUSSOU', 'Chef de service', 'Female', '1976-01-21', NULL, '1768916939_Photo_2026-01-13_114214.jpg', 'roussou@dgi.gouv.ci', '0707728488', NULL, 'Attaché de Direction', 'A3', '2011-04-12', 'M. DJE', NULL, 3, 5, '2026-01-20 13:48:59', '2026-01-20 14:02:58'),
(4, 'andoume@dgi.gouv.ci', '410770Q', 'Arlette', 'N\'DOUME', 'Chef de service', 'Female', '1977-01-19', 'COCODY', '1768992193_Photo n\'doumbe.jpg', 'andoume@dgi.gouv.ci', '0707080437', 'COCODY Camp AKOUEDO', 'Inspecteur des Impôts', 'A3', '2014-12-29', 'M. AKA', '0707080437', 1, 6, '2026-01-21 10:43:13', '2026-01-21 10:54:15'),
(5, 'iadico@dgi.gouv.ci', '278886H', 'Innocent', 'ADICO', 'Directeur', 'Male', NULL, NULL, NULL, 'iadico@dgi.gouv.ci', NULL, NULL, 'Inspecteur Principal Statisticien Economiste', 'A5', NULL, NULL, NULL, 20, 9, '2026-01-21 11:50:04', '2026-01-21 12:03:29'),
(6, 'michelkeita@dgi.gouv.ci', '305211F', 'Moctar Michel Djépa', 'KEITA', 'Conseiller Technique', 'Male', NULL, NULL, NULL, 'michelkeita@dgi.gouv.ci', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, 10, '2026-01-21 11:56:29', '2026-01-21 11:56:29'),
(7, 'asokpekon@dgi.gouv.ci', '209438K', 'Assi Roger', 'OKPEKON', 'Chef de service', 'Male', NULL, NULL, NULL, 'asokpekon@dgi.gouv.ci', NULL, NULL, 'Attaché des Finances', 'A3', '1980-01-01', NULL, NULL, 2, 11, '2026-01-21 11:59:18', '2026-01-21 12:18:37'),
(8, 'kbrou04@dgi.gouv.ci', '332674H', 'Née Brou Amenan M.', 'KOUADIO', 'Agent', 'Female', NULL, NULL, NULL, 'kbrou04@dgi.gouv.ci', NULL, NULL, 'Attaché de Direction', 'A3', NULL, NULL, NULL, 20, 12, '2026-01-21 12:02:21', '2026-01-21 12:19:22'),
(9, 'mballo@dgi.gouv.ci', '421319X', 'Maïmouna', 'BALLO', 'Agent', 'Male', NULL, 'ADZOPE', NULL, 'mballo@dgi.gouv.ci', NULL, NULL, 'Agent de bureau', 'D1', NULL, NULL, NULL, 3, 13, '2026-01-21 12:37:32', '2026-01-21 12:44:53'),
(10, 'adjedjemel@dgi.gouv.ci', '298075B', 'Akpa Leonard', 'DJEDJEMEL', 'Agent', 'Male', NULL, 'DABOU', NULL, 'adjedjemel@dgi.gouv.ci', NULL, NULL, 'Secrétaire Comptable', 'C2', NULL, NULL, NULL, 3, 14, '2026-01-21 12:47:16', '2026-01-21 12:48:12'),
(11, 'mtraore@dgi.gouv.ci', '305722F', 'Mamadou', 'TRAORE', 'Sous-directeur', 'Male', NULL, 'TAFIRE', NULL, 'mtraore@dgi.gouv.ci', NULL, NULL, 'Ingenieur Principal Informatique', 'A5', NULL, NULL, NULL, 18, 15, '2026-01-21 12:51:08', '2026-01-21 12:52:34'),
(12, NULL, '333011M', 'Bi Douby Lazare', 'DOUBY', 'Agent', 'Male', NULL, NULL, NULL, 'doubi@dgi.gouv.ci', NULL, NULL, 'Agent de Maïtrise des Travaux Publics', 'C2', NULL, NULL, NULL, 20, NULL, '2026-01-21 13:09:01', '2026-01-21 13:12:32'),
(13, NULL, '313318H', 'Adingra William', 'KOUAKOU', 'Agent', 'Male', NULL, NULL, NULL, 'kouakouadingra@dgi.gouv.ci', NULL, NULL, 'Agent Spécialisé des Travaux Public', 'D1', NULL, NULL, NULL, 20, NULL, '2026-01-21 13:11:06', '2026-01-21 13:15:10'),
(14, NULL, '862439S', 'Mathieu', 'TERE', 'Agent', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, 'Agent Spécialisé des Travaux Public', 'D1', NULL, NULL, NULL, 20, NULL, '2026-01-21 13:13:50', '2026-01-21 13:13:50'),
(15, NULL, '472108g', 'Josué', 'GUELAGNINHON', 'Agent', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, 'Agent Technique de la Statistique', 'C2', NULL, NULL, NULL, 20, NULL, '2026-01-21 13:28:08', '2026-01-21 13:28:08'),
(16, 'thoro@dgi.gouv.ci', '234758K', 'Tiekoura', 'HORO', 'Chef de service', 'Male', NULL, NULL, NULL, 'thoro@dgi.gouv.ci', NULL, NULL, 'Attaché des Finances', 'A3', NULL, NULL, NULL, 10, 16, '2026-01-22 08:10:12', '2026-01-22 08:11:03'),
(17, 'bnguessan04@dgi.gouv.ci', '284953S', 'Brou tchoumou serge', 'N\'GUESSAN', 'Sous-directeur', 'Male', NULL, NULL, NULL, 'bnguessan04@dgi.gouv.ci', NULL, NULL, 'Administrateur des services Financiers', 'A4', NULL, NULL, NULL, 16, 17, '2026-01-22 08:27:00', '2026-01-22 08:27:59'),
(18, 'mbezi@dgi.gouv.ci', '278865K', 'Mathurin', 'BEZI', 'Sous-directeur', 'Male', NULL, NULL, NULL, 'mbezi@dgi.gouv.ci', NULL, NULL, 'Administrateur des services Financiers', 'A4', NULL, NULL, NULL, 17, 18, '2026-01-22 08:41:51', '2026-01-22 08:42:22'),
(19, 'rtouboui@dgi.gouv.ci', '298834Z', 'Bi suy robert', 'TOUBOUI', 'Chef de service', 'Male', NULL, NULL, NULL, 'rtouboui@dgi.gouv.ci', NULL, NULL, 'Attaché des Finances', 'A3', NULL, NULL, NULL, 11, 19, '2026-01-23 10:25:21', '2026-01-23 10:26:34'),
(20, 'mamadoulcoul@dgi.gouv.ci', '360244S', 'Mamadou lamine', 'COULIBALY', 'Agent', 'Male', NULL, NULL, NULL, 'mamadoulcoul@dgi.gouv.ci', NULL, NULL, 'Demographe', 'A4', NULL, NULL, NULL, 11, 20, '2026-01-23 10:29:57', '2026-01-23 10:30:27'),
(21, 'akeita@dgi.gouv.ci', '305436Z', 'Née keita aramata anne elise', 'KEDI', 'Agent', 'Female', NULL, NULL, NULL, 'akeita@dgi.gouv.ci', NULL, NULL, 'Inspecteur des Impôts', 'A3', NULL, NULL, NULL, 10, 21, '2026-01-27 07:57:48', '2026-01-27 08:14:44'),
(22, 'christiankonankoffi@dgi.gouv.ci', '804367G', 'Konan christian rené', 'KOFFI', 'Agent', 'Male', NULL, NULL, NULL, 'christiankonankoffi@dgi.gouv.ci', NULL, NULL, 'Secrétaire Administratif', 'B3', NULL, NULL, NULL, 11, 22, '2026-01-27 08:14:10', '2026-01-27 08:15:28'),
(23, 'erasthene16ja17@dgi.gouv.ci', '435014F', 'M\'bo erasthène', 'ALEXANDRE', 'Agent', 'Male', '1991-12-20', 'Akoupe/Yaffo-Attié', '1769527329_IMG_M\'bo alexandre.jpg', 'erasthene16ja17@dgi.gouv.ci', '0707661901', NULL, 'Analyste Statisticien', 'B3', '2025-12-15', 'M\'bo Sylvain', '0758033663', 13, 23, '2026-01-27 14:44:26', '2026-01-27 15:22:09');

-- --------------------------------------------------------

--
-- Structure de la table `agent_imputation`
--

DROP TABLE IF EXISTS `agent_imputation`;
CREATE TABLE IF NOT EXISTS `agent_imputation` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `imputation_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_imputation_imputation_id_foreign` (`imputation_id`),
  KEY `agent_imputation_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agent_imputation`
--

INSERT INTO `agent_imputation` (`id`, `imputation_id`, `agent_id`, `created_at`, `updated_at`) VALUES
(7, 7, 1, NULL, NULL),
(9, 9, 1, NULL, NULL),
(10, 10, 1, NULL, NULL),
(6, 6, 1, NULL, NULL),
(5, 5, 1, NULL, NULL),
(11, 10, 2, NULL, NULL),
(12, 11, 1, NULL, NULL),
(13, 11, 2, NULL, NULL),
(14, 11, 4, NULL, NULL),
(15, 12, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(191) NOT NULL,
  `contenu` text NOT NULL,
  `type` enum('urgent','information','evenement','avertissement','general') NOT NULL DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `contenu`, `type`, `is_active`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'SEMINAIRE ANNUEL 2025', 'Bilan annuel des activités fiscales 2025', 'information', 1, NULL, '2026-01-20 15:59:53', '2026-01-20 15:59:53');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:8:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"creer-articles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:18:\"supprimer-articles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:17:\"voir-utilisateurs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"manage-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:17:\"modifier articles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:18:\"supprimer articles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"gerer-roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:17:\"acceder-dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;}}}s:5:\"roles\";a:5:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"utilisateur\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"Superviseur\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:2:\"rh\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"editeur\";s:1:\"c\";s:3:\"web\";}}}', 1769598176);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `courriers`
--

DROP TABLE IF EXISTS `courriers`;
CREATE TABLE IF NOT EXISTS `courriers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `num_enregistrement` varchar(255) DEFAULT NULL,
  `affecter` tinyint(1) NOT NULL DEFAULT 0,
  `reference` varchar(191) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `objet` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_courrier` date NOT NULL,
  `expediteur_nom` varchar(191) NOT NULL,
  `expediteur_contact` varchar(191) DEFAULT NULL,
  `destinataire_nom` varchar(191) NOT NULL,
  `destinataire_contact` varchar(191) DEFAULT NULL,
  `statut` varchar(191) NOT NULL DEFAULT 'pending',
  `assigne_a` varchar(255) DEFAULT NULL,
  `chemin_fichier` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courriers_num_enregistrement_unique` (`num_enregistrement`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `courriers`
--

INSERT INTO `courriers` (`id`, `num_enregistrement`, `affecter`, `reference`, `type`, `objet`, `description`, `date_courrier`, `expediteur_nom`, `expediteur_contact`, `destinataire_nom`, `destinataire_contact`, `statut`, `assigne_a`, `chemin_fichier`, `created_at`, `updated_at`) VALUES
(2, 'REG-2026-6971FEFAADDCF', 1, '000021', 'Outgoing', 'réponses de ls DSESF relative à une demande de stage et une demande de collecte de données sollicitées respectivement par Mlle DJAN Loukou Julienne et DOU Sopie Joëlle Priscae', NULL, '2026-01-21', 'DSESF', NULL, 'DFRC Direction de la Formation et du Renforcement de Capacité', NULL, 'Affecté', 'Non assigné', '1769081946_000021__du_21_01_2026.pdf', '2026-01-22 10:42:02', '2026-01-22 12:59:59'),
(3, 'REG-2026-697203E79FCA1', 1, '0040', 'Incoming', 'Résultat de l\'examen de demande de rectification des Etats Financiers Compagnie ASKI Airline', NULL, '2026-01-21', 'DERAR', NULL, 'DSESF', NULL, 'Affecté', 'Non assigné', '1769081929__0040_du_2026-01-21.pdf', '2026-01-22 11:03:03', '2026-01-22 12:43:23'),
(4, 'REG-2026-697209DAABA90', 1, '06391', 'Incoming', 'Demane de mise à disposition de M. BROU Konan Amani', NULL, '2026-01-21', 'CABINET DGI', NULL, 'Direction DSESFGénérale', NULL, 'Affecté', 'Non assigné', '1769081306_06391_2026-01-22_112741.pdf', '2026-01-22 11:28:26', '2026-01-22 17:37:17'),
(5, 'REG-2026-697210C0D9ED1', 1, '06410', 'Incoming', 'Demande de rectification des états financiers par téléliasse', NULL, '2026-01-21', 'CABINET DGI', NULL, 'DSESF', NULL, 'archivé', 'Non assigné', '1769083072_06410_2026-01-22_115627.pdf', '2026-01-22 11:57:52', '2026-01-22 17:31:59'),
(6, 'REG-2026-69721207AC2B6', 1, '0759232466', 'Incoming', 'Demande données statistiques pour mémoire de fin de cycle', NULL, '2026-01-21', 'KOSSONOU Affoué Ella Mireille Stagiaire de l\'ENA', NULL, 'DSESF', NULL, 'Affecté', 'Non assigné', '1769083399_0759232466_2026-01-22_120224.pdf', '2026-01-22 12:03:19', '2026-01-23 08:28:04'),
(7, 'REG-2026-69774D5D847F5', 1, '03220', 'Incoming', 'Formation des Agents Non Fiscalistes', NULL, '2026-01-26', 'CABINET DGI', NULL, 'DSESF', NULL, 'Affecté', 'Non assigné', '1769426269_Document_2026-01-26_formation.pdf', '2026-01-26 11:17:49', '2026-01-26 11:25:47'),
(8, 'REG-2026-697752B52AA6B', 1, '0784', 'Incoming', 'Proposition pour utilisation du nouveau serveur', NULL, '2026-01-26', 'DSESF', NULL, 'DSESF', NULL, 'archivé', 'Non assigné', '1769427637_Document_2026-01-26_Proposition_serveur.pdf', '2026-01-26 11:40:37', '2026-01-26 11:51:32'),
(9, 'REG-2026-69775E4353DEF', 1, '02218', 'Incoming', 'Mission d\'explication des statistiques mensuelles des recettes', NULL, '2026-01-26', 'CABINET DGI', NULL, 'DSESF', NULL, 'affecté', 'Non assigné', '1769430595_Statistiques_Mensuelles_Recette_2026-01-26_122728.pdf', '2026-01-26 12:29:55', '2026-01-26 12:30:52');

-- --------------------------------------------------------

--
-- Structure de la table `directions`
--

DROP TABLE IF EXISTS `directions`;
CREATE TABLE IF NOT EXISTS `directions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `directions_code_unique` (`code`),
  KEY `directions_head_id_foreign` (`head_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `directions`
--

INSERT INTO `directions` (`id`, `name`, `code`, `description`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Cabinet du Directeur', 'CAB-DIR', NULL, NULL, '2026-01-20 12:48:33', '2026-01-20 12:48:33'),
(2, 'Sous-Direction de la Prévision et des Statitiques', 'SDPS', NULL, NULL, '2026-01-20 12:49:29', '2026-01-20 12:49:29'),
(3, 'Sous-Direction des Etudes et Evaluations Fiscales', 'SDEEF', NULL, NULL, '2026-01-20 12:50:03', '2026-01-20 12:50:03'),
(4, 'Sous-Direction de la Planification et de la Stratégie', 'SDPlan&S', NULL, NULL, '2026-01-20 12:51:40', '2026-01-20 13:01:54'),
(5, 'Sous-Direction du Guichet Unique de Dépôt des Etats Financiers', 'GUDEF', NULL, NULL, '2026-01-20 12:52:43', '2026-01-20 12:52:43');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jour` varchar(191) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `tolerance_retard` int(11) NOT NULL DEFAULT 15,
  `est_ouvre` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `horaires`
--

INSERT INTO `horaires` (`id`, `jour`, `heure_debut`, `heure_fin`, `tolerance_retard`, `est_ouvre`, `created_at`, `updated_at`) VALUES
(1, 'Lundi', '07:30:00', '16:30:00', 15, 1, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(2, 'Mardi', '07:30:00', '16:30:00', 15, 1, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(3, 'Mercredi', '07:30:00', '16:30:00', 15, 1, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(4, 'Jeudi', '07:30:00', '16:30:00', 15, 1, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(5, 'Vendredi', '07:30:00', '16:30:00', 15, 1, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(6, 'Samedi', '00:00:00', '00:00:00', 15, 0, '2026-01-21 15:29:05', '2026-01-21 15:29:05'),
(7, 'Dimanche', '00:00:00', '00:00:00', 15, 0, '2026-01-21 15:29:05', '2026-01-21 15:29:05');

-- --------------------------------------------------------

--
-- Structure de la table `imputations`
--

DROP TABLE IF EXISTS `imputations`;
CREATE TABLE IF NOT EXISTS `imputations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `courrier_id` bigint(20) UNSIGNED NOT NULL,
  `chemin_fichier` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `niveau` enum('primaire','secondaire','tertiaire') NOT NULL,
  `instructions` text DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `documents_annexes` text DEFAULT NULL,
  `date_imputation` date NOT NULL DEFAULT '2026-01-20',
  `date_traitement` date DEFAULT NULL,
  `echeancier` date DEFAULT NULL,
  `statut` enum('en_attente','en_cours','termine') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imputations_courrier_id_foreign` (`courrier_id`),
  KEY `imputations_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `imputations`
--

INSERT INTO `imputations` (`id`, `courrier_id`, `chemin_fichier`, `user_id`, `niveau`, `instructions`, `observations`, `documents_annexes`, `date_imputation`, `date_traitement`, `echeancier`, `statut`, `created_at`, `updated_at`) VALUES
(7, 4, '1769081306_06391_2026-01-22_112741.pdf', 3, 'tertiaire', 'Bilan trimestrielle 4T 2025', NULL, '\"[\\\"1769103437_Note_de_service_DEMANDE_D\'INFORMATIONS_COMITE_COUT_SDEEF.pdf\\\"]\"', '2026-01-22', NULL, '2026-01-26', 'en_attente', '2026-01-22 17:37:17', '2026-01-22 17:37:17'),
(6, 5, '1769083072_06410_2026-01-22_115627.pdf', 9, 'primaire', 'rapidement', NULL, '\"[\\\"1769094964_Note_au_MFB__Objectif_de_recettes_TOFE_Aout_2025.pdf\\\"]\"', '2026-01-22', NULL, '2026-01-23', 'termine', '2026-01-22 15:16:04', '2026-01-22 17:25:44'),
(5, 2, '1769081946_000021__du_21_01_2026.pdf', 3, 'tertiaire', 'urgent', NULL, '\"[\\\"1769091173_0759232466_2026-01-22_120224.pdf\\\"]\"', '2026-01-22', NULL, '2026-01-23', 'termine', '2026-01-22 14:12:53', '2026-01-22 14:54:35'),
(9, 6, '1769083399_0759232466_2026-01-22_120224.pdf', 17, 'secondaire', 'Extraction de données Déclaration TEE & MICRO 2025', NULL, '\"[\\\"1769157764_Note_au_MFB__Objectif_de_recettes_TOFE_Aout_2025.pdf\\\"]\"', '2026-01-23', NULL, '2026-01-27', 'en_attente', '2026-01-23 08:42:44', '2026-01-23 08:42:44'),
(10, 7, '1769426269_Document_2026-01-26_formation.pdf', 3, 'tertiaire', 'Informer les agents et tenir une liste d\'agents à former', NULL, '\"[]\"', '2026-01-26', NULL, '2026-01-28', 'en_attente', '2026-01-26 11:25:47', '2026-01-26 11:25:47'),
(11, 8, '1769427637_Document_2026-01-26_Proposition_serveur.pdf', 3, 'tertiaire', 'Travail urgent pour rendre le serveur operationnel', NULL, '\"[]\"', '2026-01-26', NULL, '2026-01-28', 'termine', '2026-01-26 11:48:55', '2026-01-26 11:50:41'),
(12, 9, NULL, 9, 'primaire', 'Vite fait et bien fait', NULL, '\"documents\\/imputations\\/annexes\\/1769430652_Projet_de_Note_du_DSESF_au_DG_S\\u00e9minaire_Loi_de_r\\u00e8glement_18_20_d\\u00e9c_2024.pdf\"', '2026-01-26', NULL, '2026-01-29', 'en_attente', '2026-01-26 12:30:52', '2026-01-26 12:30:52');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_30_232419_create_courriers_table', 1),
(5, '2025_12_01_000531_create_affectations_table', 1),
(6, '2025_12_01_094543_update_type_column_in_courriers_table', 1),
(7, '2025_12_01_100411_update_type_column_in_courriers_table', 1),
(8, '2025_12_01_100734_increase_type_column_length_in_courriers_table', 1),
(9, '2025_12_03_075419_create_directions_table', 1),
(10, '2025_12_03_075434_create_services_table', 1),
(11, '2025_12_03_075449_create_agents_table', 1),
(12, '2025_12_03_144514_add_profile_fields_to_users_table', 1),
(13, '2025_12_04_121917_make_lieu_de_naissance_nullable_in_agents_table', 1),
(14, '2025_12_04_132311_update_photo_column_in_agents_table', 1),
(15, '2025_12_04_132817_update_email_column_in_agents_table', 1),
(16, '2025_12_04_133355_update__date__prise_de__service_column_in_agents_table', 1),
(17, '2025_12_04_143140_create_presences_table', 1),
(18, '2025_12_04_143949_create_absences_table', 1),
(19, '2025_12_04_145524_create_type_absences_table', 1),
(20, '2025_12_05_123929_rename_user_id_to_agent_id_in_affectations_table', 1),
(21, '2025_12_09_150923_add_email_professionnel_to_agents_table', 1),
(22, '2025_12_10_144650_create_notifications_taches_table', 1),
(23, '2025_12_11_123518_modify_courriers_assigne_a_to_string', 1),
(24, '2025_12_15_124007_add_document_to_notifications_taches_table', 1),
(25, '2025_12_15_145617_rename_id_agent_to_agent_id_in_notifications_taches_table', 1),
(26, '2025_12_15_155804_add_timestamps_to_notifications_taches_table', 1),
(27, '2025_12_23_132434_add_is_archived_to_notifications_taches_table', 1),
(28, '2025_12_23_142311_create_reponse_notifications_table', 1),
(29, '2025_12_23_153002_add_soft_deletes_to_notifications_taches_table', 1),
(30, '2025_12_29_102325_add_approuvee_to_reponse_notifications_table', 1),
(31, '2025_12_29_103202_add_appeciation_du_superieur_to_reponse_notifications_table', 1),
(32, '2026_01_02_105921_add_must_change_password_to_users_table', 1),
(33, '2026_01_04_120452_create_annonces_table', 1),
(34, '2026_01_05_151012_create_horaires_table', 1),
(35, '2026_01_06_151247_add_affecter_to_courriers_table', 1),
(36, '2026_01_09_222247_add_document_justificatif_to_absences_table', 1),
(37, '2026_01_13_151143_create_imputations_table', 1),
(38, '2026_01_13_175520_create_reponses_table', 1),
(39, '2026_01_16_083954_add_validation_fields_to_reponses_table', 1),
(40, '2026_01_20_081732_create_permission_tables', 1),
(41, '2026_01_20_085414_create_posts_table', 1),
(42, '2026_01_20_170506_add_password_changed_at_to_users_table', 2),
(43, '2026_01_22_083540_update_statut_enum_in_presences_table', 3),
(44, '2026_01_22_101554_add_type_to_courriers_table', 4),
(45, '2026_01_22_101853_make_assigne_a_nullable_in_courriers_table', 5),
(46, '2026_01_22_102531_add_numero_enregistrement_to_courriers_table', 6),
(47, '2026_01_22_140612_add_chemin_fichier_to_imputations_table', 7),
(48, '2026_01_26_172154_create_role_user_table', 8);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17),
(2, 'App\\Models\\User', 18),
(2, 'App\\Models\\User', 19),
(2, 'App\\Models\\User', 20);

-- --------------------------------------------------------

--
-- Structure de la table `notifications_taches`
--

DROP TABLE IF EXISTS `notifications_taches`;
CREATE TABLE IF NOT EXISTS `notifications_taches` (
  `id_notification` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_echeance` timestamp NULL DEFAULT NULL,
  `suivi_par` varchar(100) NOT NULL,
  `priorite` enum('Faible','Moyenne','Élevée','Urgent') NOT NULL DEFAULT 'Moyenne',
  `statut` enum('Non lu','En cours','Complétée','Annulée') NOT NULL DEFAULT 'Non lu',
  `lien_action` varchar(512) DEFAULT NULL,
  `document` varchar(512) DEFAULT NULL,
  `date_lecture` timestamp NULL DEFAULT NULL,
  `date_completion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_notification`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('yacouba.coulibaly@dgi.gouv.ci', '$2y$12$j.1Cwxv1QHwDIQJecOhLgOtQiSuGEXw.C3PEg4hPtZM6VMarlxChu', '2026-01-26 16:29:00');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'creer-articles', 'web', '2026-01-20 09:37:00', '2026-01-20 09:37:00'),
(2, 'supprimer-articles', 'web', '2026-01-20 09:37:00', '2026-01-20 09:37:00'),
(3, 'voir-utilisateurs', 'web', '2026-01-20 10:15:15', '2026-01-20 10:15:15'),
(4, 'manage-users', 'web', '2026-01-20 10:37:56', '2026-01-20 10:37:56'),
(5, 'modifier articles', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28'),
(6, 'supprimer articles', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28'),
(7, 'gerer-roles', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28'),
(8, 'acceder-dashboard', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) NOT NULL,
  `content` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `heure_arrivee` timestamp NOT NULL,
  `heure_depart` timestamp NULL DEFAULT NULL,
  `statut` enum('Absent','Présent','En Retard','Absence Justifiée') NOT NULL DEFAULT 'Présent',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presences_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `presences`
--

INSERT INTO `presences` (`id`, `agent_id`, `heure_arrivee`, `heure_depart`, `statut`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-12 07:25:00', '2026-01-12 17:21:00', 'Présent', NULL, '2026-01-21 14:21:39', '2026-01-21 14:21:39'),
(2, 1, '2026-01-13 07:49:00', NULL, 'Présent', NULL, '2026-01-21 14:22:27', '2026-01-21 14:23:05'),
(3, 1, '2026-01-14 08:23:00', '2026-01-21 18:23:00', 'Présent', NULL, '2026-01-21 14:23:38', '2026-01-21 14:23:38'),
(4, 1, '2026-01-15 06:23:00', '2026-01-15 17:23:00', 'Présent', NULL, '2026-01-21 14:24:13', '2026-01-21 14:24:13'),
(5, 1, '2026-01-16 11:24:00', '2026-01-16 17:24:00', 'Présent', NULL, '2026-01-21 14:24:54', '2026-01-21 14:24:54'),
(6, 1, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(7, 2, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(8, 3, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(9, 4, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(10, 5, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(11, 6, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(12, 7, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(13, 8, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(14, 9, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(15, 10, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(16, 11, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(17, 12, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(18, 13, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(19, 14, '2026-01-12 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-12 00:00:00', '2026-01-21 14:26:39'),
(20, 1, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(21, 2, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(22, 3, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(23, 4, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(24, 5, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(25, 6, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(26, 7, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(27, 8, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(28, 9, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(29, 11, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(30, 12, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(31, 13, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(32, 14, '2026-01-13 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-13 00:00:00', '2026-01-21 14:26:39'),
(33, 1, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(34, 2, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(35, 3, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(36, 4, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(37, 5, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(38, 6, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(39, 7, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(40, 8, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(41, 9, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(42, 11, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(43, 12, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(44, 13, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(45, 14, '2026-01-14 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-14 00:00:00', '2026-01-21 14:26:39'),
(46, 1, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(47, 2, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(48, 3, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(49, 4, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(50, 5, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(51, 6, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(52, 7, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(53, 8, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(54, 9, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(55, 11, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(56, 12, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(57, 13, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(58, 14, '2026-01-15 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-15 00:00:00', '2026-01-21 14:26:39'),
(59, 1, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(60, 2, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(61, 3, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(62, 4, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(63, 5, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(64, 6, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(65, 7, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(66, 8, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(67, 9, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(68, 10, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(69, 11, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(70, 12, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(71, 13, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(72, 14, '2026-01-16 08:00:00', NULL, 'Absent', 'Absent (hebdomadaire).', '2026-01-16 00:00:00', '2026-01-21 14:26:39'),
(73, 2, '2026-01-21 07:52:00', NULL, 'Présent', ' (Attention : Aucun horaire trouvé pour Wednesday)', '2026-01-21 14:36:07', '2026-01-21 14:36:07'),
(74, 2, '2026-01-12 07:59:00', '2026-01-12 09:38:00', 'Présent', ' (Attention : Aucun horaire trouvé pour Monday)', '2026-01-21 14:39:16', '2026-01-21 14:39:16'),
(75, 2, '2026-01-13 08:47:00', '2026-01-13 18:47:00', 'Présent', 'Erreur : Aucun horaire trouvé pour le jour [Mardi] dans la table horaires.', '2026-01-21 14:47:22', '2026-01-21 14:47:22'),
(76, 2, '2026-01-14 08:49:00', NULL, 'Présent', 'ERREUR : Aucun horaire trouvé en base pour [Mercredi]. ', '2026-01-21 14:50:10', '2026-01-21 14:50:10'),
(77, 2, '2026-01-15 07:58:00', '2026-01-15 17:58:00', 'Présent', 'ERREUR : Aucun horaire trouvé en base pour [Jeudi]. ', '2026-01-21 14:58:40', '2026-01-21 14:58:40'),
(78, 2, '2026-01-16 07:50:00', NULL, 'Présent', 'ERREUR : Aucun horaire trouvé pour [Vendredi]. ', '2026-01-21 15:02:27', '2026-01-21 15:02:27'),
(79, 4, '2026-01-16 08:12:00', NULL, 'Présent', 'ERREUR : Aucun horaire trouvé pour Vendredi. ', '2026-01-21 15:12:46', '2026-01-21 15:12:46'),
(80, 4, '2026-01-12 07:50:00', NULL, 'Présent', 'ERREUR : Aucun horaire trouvé pour Lundi/Monday. ', '2026-01-21 15:18:01', '2026-01-21 15:18:01'),
(81, 4, '2026-01-13 07:46:00', '2026-01-13 18:31:00', 'En Retard', 'Calculé pour Mardi (Limite: 465m, Arrivée: 466m). ', '2026-01-21 15:31:29', '2026-01-21 15:31:29'),
(82, 16, '2026-01-12 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:12:09', '2026-01-22 08:18:50'),
(83, 16, '2026-01-13 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:12:09', '2026-01-22 08:18:50'),
(84, 16, '2026-01-14 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:12:09', '2026-01-22 08:18:50'),
(85, 16, '2026-01-15 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:12:09', '2026-01-22 08:18:50'),
(86, 16, '2026-01-16 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:12:09', '2026-01-22 08:18:50'),
(87, 17, '2026-01-12 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:30:04', '2026-01-22 08:30:04'),
(88, 17, '2026-01-13 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:30:04', '2026-01-22 08:30:04'),
(89, 17, '2026-01-14 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:30:04', '2026-01-22 08:30:04'),
(90, 17, '2026-01-15 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:30:04', '2026-01-22 08:30:04'),
(91, 17, '2026-01-16 08:00:00', NULL, 'Absent', 'Absence hebdomadaire non justifiée.', '2026-01-22 08:30:04', '2026-01-22 08:30:04'),
(92, 18, '2026-01-12 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-22 08:42:51', '2026-01-22 08:42:51'),
(93, 18, '2026-01-13 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-22 08:42:51', '2026-01-22 08:42:51'),
(94, 18, '2026-01-14 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-22 08:42:51', '2026-01-22 08:42:51'),
(95, 18, '2026-01-15 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-22 08:42:51', '2026-01-22 08:42:51'),
(96, 18, '2026-01-16 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-22 08:42:51', '2026-01-22 08:42:51'),
(97, 10, '2026-01-13 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(98, 10, '2026-01-14 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(99, 10, '2026-01-15 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(100, 15, '2026-01-12 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(101, 15, '2026-01-13 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(102, 15, '2026-01-14 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(103, 15, '2026-01-15 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(104, 15, '2026-01-16 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-22 09:23:51', '2026-01-22 09:23:51'),
(105, 17, '2026-01-23 09:27:27', NULL, 'En Retard', 'Pointage automatique (Self-service)', '2026-01-23 09:27:27', '2026-01-23 09:27:27'),
(106, 4, '2026-01-23 09:39:02', NULL, 'En Retard', 'Pointage automatique (Self-service)', '2026-01-23 09:39:02', '2026-01-23 09:39:02'),
(107, 1, '2026-01-23 09:48:08', NULL, 'En Retard', 'Pointage automatique (Self-service)', '2026-01-23 09:48:08', '2026-01-23 09:48:08'),
(108, 2, '2026-01-23 10:21:08', NULL, 'En Retard', 'Pointage automatique (Self-service)', '2026-01-23 10:21:08', '2026-01-23 10:21:08'),
(109, 1, '2026-01-26 09:22:51', NULL, 'En Retard', 'Pointage automatique (Self-service)', '2026-01-26 09:22:51', '2026-01-26 09:22:51'),
(110, 1, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(111, 1, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(112, 1, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(113, 1, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(114, 2, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(115, 2, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(116, 2, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(117, 3, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(118, 3, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(119, 3, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(120, 3, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(121, 3, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(122, 4, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(123, 4, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(124, 4, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(125, 4, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(126, 5, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(127, 5, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(128, 5, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(129, 5, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(130, 5, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(131, 6, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(132, 6, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(133, 6, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(134, 6, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(135, 6, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(136, 7, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(137, 7, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(138, 7, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(139, 7, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(140, 7, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(141, 8, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(142, 8, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(143, 8, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(144, 8, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(145, 8, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(146, 9, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(147, 9, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(148, 9, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(149, 9, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(150, 9, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(151, 10, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(152, 10, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(153, 10, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(154, 10, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(155, 10, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(156, 11, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(157, 11, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(158, 11, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(159, 11, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(160, 11, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(161, 12, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(162, 12, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(163, 12, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(164, 12, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(165, 12, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(166, 13, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(167, 13, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(168, 13, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(169, 13, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(170, 13, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(171, 14, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(172, 14, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(173, 14, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(174, 14, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(175, 14, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(176, 16, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(177, 16, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(178, 16, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(179, 16, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(180, 16, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(181, 17, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(182, 17, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(183, 17, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(184, 17, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(185, 18, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(186, 18, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(187, 18, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(188, 18, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(189, 18, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(190, 19, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(191, 19, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(192, 19, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(193, 19, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(194, 19, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(195, 20, '2026-01-19 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(196, 20, '2026-01-20 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(197, 20, '2026-01-21 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(198, 20, '2026-01-22 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(199, 20, '2026-01-23 08:00:00', NULL, 'Absent', 'Absence hebdomadaire.', '2026-01-26 10:00:54', '2026-01-26 10:00:54'),
(200, 15, '2026-01-19 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-26 10:01:14', '2026-01-26 10:01:14'),
(201, 15, '2026-01-20 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-26 10:01:14', '2026-01-26 10:01:14'),
(202, 15, '2026-01-21 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-26 10:01:14', '2026-01-26 10:01:14'),
(203, 15, '2026-01-22 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-26 10:01:14', '2026-01-26 10:01:14'),
(204, 15, '2026-01-23 08:00:00', NULL, 'Absence Justifiée', 'Justifié: ', '2026-01-26 10:01:14', '2026-01-26 10:01:14'),
(205, 1, '2026-01-27 07:25:00', '2026-01-27 17:05:00', 'Présent', 'Calculé pour Mardi (Limite: 465m, Arrivée: 445m). ', '2026-01-27 14:59:12', '2026-01-27 14:59:12');

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

DROP TABLE IF EXISTS `reponses`;
CREATE TABLE IF NOT EXISTS `reponses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `validation` enum('en_attente','acceptee','rejetee') NOT NULL DEFAULT 'en_attente',
  `document_final_signe` varchar(191) DEFAULT NULL,
  `date_approbation` timestamp NULL DEFAULT NULL,
  `imputation_id` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `contenu` text NOT NULL,
  `fichiers_joints` text DEFAULT NULL,
  `date_reponse` datetime NOT NULL DEFAULT '2026-01-20 09:36:59',
  `pourcentage_avancement` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reponses_imputation_id_foreign` (`imputation_id`),
  KEY `reponses_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `validation`, `document_final_signe`, `date_approbation`, `imputation_id`, `agent_id`, `contenu`, `fichiers_joints`, `date_reponse`, `pourcentage_avancement`, `created_at`, `updated_at`) VALUES
(1, 'en_attente', NULL, NULL, 4, 5, 'TRAVAIL EFFECTUE', '[\"1769087083_Projet de Note du DSESF au DG_S\\u00e9minaire Loi de r\\u00e8glement 18 20 d\\u00e9c 2024.pdf\"]', '2026-01-22 13:04:43', 100, '2026-01-22 13:04:43', '2026-01-22 13:04:43'),
(2, 'en_attente', NULL, NULL, 5, 1, 'merci', '[\"1769093675_Projet de Note du DSESF au DG_S\\u00e9minaire Loi de r\\u00e8glement 18 20 d\\u00e9c 2024.pdf\"]', '2026-01-22 14:54:35', 100, '2026-01-22 14:54:35', '2026-01-22 14:54:35'),
(3, 'acceptee', 'archives/final/1769103119_FINAL_Note de service_DEMANDE D\'INFORMATIONS COMITE COUT SDEEF.pdf', '2026-01-22 17:31:59', 6, 1, 'voici la reponse jointe', '[\"1769102744_Projet de Note du DSESF au DG_S\\u00e9minaire Loi de r\\u00e8glement 18 20 d\\u00e9c 2024.pdf\"]', '2026-01-22 17:25:44', 100, '2026-01-22 17:25:44', '2026-01-22 17:31:59'),
(4, 'acceptee', 'archives/final/1769428292_FINAL_Projet de Note du DSESF au DG_Séminaire Loi de règlement 18 20 déc 2024.pdf', '2026-01-26 11:51:32', 11, 2, 'Travail accompli', '[\"1769428241__0040_du_2026-01-21.pdf\",\"1769428241_Document_2026-01-26_formation.pdf\"]', '2026-01-26 11:50:41', 100, '2026-01-26 11:50:41', '2026-01-26 11:51:32');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_notifications`
--

DROP TABLE IF EXISTS `reponse_notifications`;
CREATE TABLE IF NOT EXISTS `reponse_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `approuvee` enum('en_attente','acceptee','rejetee') NOT NULL DEFAULT 'en_attente',
  `appreciation_du_superieur` text DEFAULT NULL,
  `id_notification` bigint(20) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `Reponse_Piece_jointe` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reponse_notifications_id_notification_foreign` (`id_notification`),
  KEY `reponse_notifications_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-01-20 09:37:00', '2026-01-20 09:37:00'),
(2, 'utilisateur', 'web', '2026-01-20 09:37:00', '2026-01-20 09:37:00'),
(3, 'editeur', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28'),
(4, 'rh', 'web', '2026-01-27 08:48:28', '2026-01-27 08:48:28'),
(5, 'Superviseur', 'web', '2026-01-27 10:50:42', '2026-01-27 10:50:42');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 5),
(2, 1),
(2, 5),
(3, 1),
(3, 2),
(3, 4),
(3, 5),
(4, 1),
(4, 5),
(5, 1),
(5, 3),
(5, 5),
(6, 1),
(6, 5),
(7, 1),
(7, 5),
(8, 1),
(8, 3),
(8, 4),
(8, 5);

-- --------------------------------------------------------

--
-- Structure de la table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(23, 3, 5),
(3, 9, 1),
(4, 2, 2),
(5, 4, 2),
(6, 6, 2),
(7, 5, 2),
(8, 10, 2),
(9, 11, 2),
(10, 12, 2),
(11, 13, 2),
(12, 14, 2),
(13, 15, 2),
(14, 16, 2),
(15, 17, 2),
(16, 21, 2),
(21, 22, 3),
(18, 20, 2),
(22, 19, 4),
(20, 18, 2),
(24, 23, 2);

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `direction_id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_code_unique` (`code`),
  KEY `services_direction_id_foreign` (`direction_id`),
  KEY `services_head_id_foreign` (`head_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `code`, `description`, `direction_id`, `head_id`, `created_at`, `updated_at`) VALUES
(1, 'Appui Informatique', 'Sce AI', NULL, 1, NULL, '2026-01-20 12:53:23', '2026-01-20 12:55:15'),
(2, 'Service de Production et de Diffusion des Statistiques', 'Sce PDS', NULL, 1, NULL, '2026-01-20 12:54:19', '2026-01-20 12:54:19'),
(3, 'Servcide de la Documentation et des Activités', 'Sce SDA', NULL, 1, NULL, '2026-01-20 12:54:59', '2026-01-20 12:54:59'),
(4, 'Service des Statitiques d\'Assiette et du Controle Fiscal', 'Sce SACF', NULL, 2, NULL, '2026-01-20 12:56:37', '2026-01-20 12:56:37'),
(5, 'Service des Prévisions de Recettes et des Indicateurs Economiques', 'Sce PRIE', NULL, 2, NULL, '2026-01-20 12:57:34', '2026-01-20 12:57:34'),
(6, 'Service d\'Analyse des Statitiques de Recettes Fiscales', 'Sce ASRF', NULL, 2, NULL, '2026-01-20 12:58:19', '2026-01-20 12:58:19'),
(7, 'Servcie des Etudes Fiscales', 'Sce EF', NULL, 3, NULL, '2026-01-20 12:58:54', '2026-01-20 12:58:54'),
(8, 'Service des Etudes Sectorielles et des Monographies', 'Sce ESM', NULL, 3, NULL, '2026-01-20 12:59:49', '2026-01-20 12:59:49'),
(9, 'Service des Simulations et des Evaluations', 'Sce SE', NULL, 3, NULL, '2026-01-20 13:00:30', '2026-01-20 13:00:30'),
(10, 'Service de la Planification et de Suivi de la Performance', 'Sce PSP', NULL, 4, NULL, '2026-01-20 13:01:23', '2026-01-20 13:01:23'),
(11, 'Service de la Veille Stratégique', 'Sce VS', NULL, 4, NULL, '2026-01-20 13:02:53', '2026-01-20 13:02:53'),
(12, 'Service de Gestion et d\'Archivage des Etats Financiers', 'Sce SGAEF', NULL, 5, NULL, '2026-01-20 13:03:41', '2026-01-20 13:03:41'),
(13, 'Service d\'Analyse et d\'Exploitation des Données', 'Sce AED', NULL, 5, NULL, '2026-01-20 13:04:39', '2026-01-20 13:04:39'),
(14, 'Service d\'Appui au Dépôt en Ligne des Etats Financiers', 'Sce ADLEF', NULL, 5, NULL, '2026-01-20 13:05:42', '2026-01-20 13:05:42'),
(15, 'Service Statistiques des Directions Centrales et Régionnales', 'Sce SDCR', NULL, 1, NULL, '2026-01-20 13:07:12', '2026-01-20 13:07:12'),
(16, 'Cabinet S/D Prévision et Statistiques', 'CAB Pré&S', NULL, 2, NULL, '2026-01-20 13:09:00', '2026-01-20 13:11:45'),
(17, 'Cabinet S/D Etude et Evaluations Fiscales', 'CAB EEF', NULL, 3, NULL, '2026-01-20 13:10:08', '2026-01-20 13:10:08'),
(18, 'Cabinet S/D Planification et Stratégie', 'CAM P&S', NULL, 4, NULL, '2026-01-20 13:10:48', '2026-01-20 13:10:48'),
(19, 'Cabinet S/D GUDEF', 'CAB GUDEF', NULL, 5, NULL, '2026-01-20 13:11:14', '2026-01-20 13:11:14'),
(20, 'Cabinet Directeur', 'CAB DSESF', NULL, 1, NULL, '2026-01-21 11:31:40', '2026-01-21 11:31:40');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_absences`
--

DROP TABLE IF EXISTS `type_absences`;
CREATE TABLE IF NOT EXISTS `type_absences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_type` enum('Congé','Repos Maladie','Mission','Permission','Autres') NOT NULL DEFAULT 'Congé',
  `code` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `est_paye` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_absences`
--

INSERT INTO `type_absences` (`id`, `nom_type`, `code`, `description`, `est_paye`, `created_at`, `updated_at`) VALUES
(1, 'Congé', 'CA', 'Congé de 30 jours auquel a droit tout agent', 1, '2026-01-21 13:40:54', '2026-01-21 13:40:54'),
(2, 'Repos Maladie', 'RM', 'Repôt maladie prescrit par un medecin dûment mandaté par la MADGI', 1, '2026-01-21 13:43:07', '2026-01-21 13:43:07'),
(3, 'Mission', 'M', 'Mission de travail ou de formation', 1, '2026-01-21 13:43:36', '2026-01-21 13:43:36'),
(4, 'Permission', 'P', 'Autorisation d\'absence validée par un spérieur hiérarchique', 1, '2026-01-21 13:44:42', '2026-01-21 13:44:42'),
(5, 'Autres', 'Autre', 'Autre type d\'absence', 1, '2026-01-21 13:45:35', '2026-01-21 13:45:35');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `must_change_password`, `password_changed_at`, `remember_token`, `created_at`, `updated_at`, `bio`, `profile_picture`) VALUES
(1, 'Admin Test', 'admin@example.com', NULL, '$2y$12$wAf0LxbRALNrwYQY33KXUeAoOYNIKiI243FdTKLXuaD2/XAiL9LrW', 0, NULL, NULL, '2026-01-20 09:37:00', '2026-01-20 10:44:26', NULL, NULL),
(2, 'Utilisateur Test', 'test@example.com', NULL, '$2y$12$7sPkZYqEOdlX17Wh1A6Iw.Y4Nz6LdMMkPR96o3dqBEfzZePhkdWhK', 0, NULL, NULL, '2026-01-20 09:37:00', '2026-01-20 10:44:26', NULL, NULL),
(3, 'Sié Yacouba COULIBALY', 'yacouba.coulibaly@dgi.gouv.ci', NULL, '$2y$12$3iz87sx3Zdu3pn6fiV.Jl..kCDacVLyLLqqOdlCJGmHH1j600Ro6K', 0, NULL, NULL, '2026-01-20 13:25:02', '2026-01-21 08:27:16', NULL, 'photos_agents/Km5CbkPx8Rry9FPxsIf9Tu3gi9JOqhDVlXxOtuBP.jpg'),
(4, 'Nafata KONE', 'nafie410@dgi.gouv.ci', NULL, '$2y$12$CZuOSdUPUwPyvED6D09Q9.mnei8mj.LAjwu45oPexywZ3yc743jlC', 0, NULL, NULL, '2026-01-20 13:46:32', '2026-01-21 09:19:44', NULL, NULL),
(5, 'Rosine Générosa Epse Dje OUSSOU', 'roussou@dgi.gouv.ci', NULL, '$2y$12$6levTuTb0vIp1M1nBq/oROhQkRMA0T.OpNdr8GOByOjJy.X9YpvMW', 1, NULL, NULL, '2026-01-20 13:48:59', '2026-01-22 12:31:18', NULL, NULL),
(6, 'Arlette N\'DOUME', 'andoume@dgi.gouv.ci', NULL, '$2y$12$dpB3VU9Ywfby5Wyr/uxtdOI8LdIQy2af87Pm6jJIltnIAU/AuYqPy', 0, NULL, NULL, '2026-01-21 10:43:13', '2026-01-23 09:38:46', NULL, NULL),
(9, 'Innocent ADICO', 'iadico@dgi.gouv.ci', NULL, '$2y$12$dCDgWWMz.kvecxIjncNv0eGBT2eXI55l21HCwN.X4Ih3KxbXyfBTG', 0, NULL, NULL, '2026-01-21 11:50:04', '2026-01-22 15:15:21', NULL, NULL),
(10, 'Moctar Michel Djépa KEITA', 'michelkeita@dgi.gouv.ci', NULL, '$2y$12$EoybtZEfYxQi9.SZTiyfqOpsn/q..fJrmhXDQrZYS/ZUXGLI/k9IC', 1, NULL, NULL, '2026-01-21 11:56:29', '2026-01-21 11:56:29', NULL, NULL),
(11, 'Assi Roger OKPEKON', 'asokpekon@dgi.gouv.ci', NULL, '$2y$12$nx0LnF50zhs9E5goM9y76.slvDH99Qm1eoTuuCAAaNJGIiKE9Z..a', 1, NULL, NULL, '2026-01-21 11:59:18', '2026-01-21 11:59:18', NULL, NULL),
(12, 'Née Brou Amenan M. KOUADIO', 'kbrou04@dgi.gouv.ci', NULL, '$2y$12$NRt7n8PnxT41./IvAD.GFetDLPqFXcBveV4WdaA10D4DnMtLI/DDm', 1, NULL, NULL, '2026-01-21 12:02:21', '2026-01-21 12:02:21', NULL, NULL),
(13, 'Maïmouna BALLO', 'mballo@dgi.gouv.ci', NULL, '$2y$12$5pVS9RokI.yFE9sskgIEAe/F8Q3gKgMSitn.geE4Dw00M3epkVr6q', 1, NULL, NULL, '2026-01-21 12:37:32', '2026-01-21 12:37:32', NULL, NULL),
(14, 'Akpa Leonard DJEDJEMEL', 'adjedjemel@dgi.gouv.ci', NULL, '$2y$12$FqeyBSfjG8plbvr45sVr/ubyh35lheTKUoTnOyd6L8OYLr4oBix42', 1, NULL, NULL, '2026-01-21 12:47:16', '2026-01-21 12:47:16', NULL, NULL),
(15, 'Mamadou TRAORE', 'mtraore@dgi.gouv.ci', NULL, '$2y$12$pVJ6NdgPnmRntLHilLZLWO0xUKd.pLRjWt7Wyot1SBnpId5lk.9aK', 1, NULL, NULL, '2026-01-21 12:51:08', '2026-01-21 12:51:08', NULL, NULL),
(16, 'Tiekoura HORO', 'thoro@dgi.gouv.ci', NULL, '$2y$12$rQi.ZoaRTZbTK5FEXZYGveMzs3NSe3hSVZTHZtUeV/yug.TSZ8QDW', 1, NULL, NULL, '2026-01-22 08:10:12', '2026-01-22 08:10:12', NULL, NULL),
(17, 'Brou Tchoumou Serge N\'GUESSAN', 'bnguessan04@dgi.gouv.ci', NULL, '$2y$12$jAQdcsFgGZ4pyMBLv1rNMOCMR0JlUNtAZGbvugRNjW99Rv4nVfLmu', 0, NULL, NULL, '2026-01-22 08:27:00', '2026-01-23 08:26:16', NULL, NULL),
(18, 'Mathurin BEZI', 'mbezi@dgi.gouv.ci', NULL, '$2y$12$BcDeMiB4WI51vVEVNPX/V.Vzjn6pMP2VoM/C96VfJynLvk91c19zO', 1, NULL, NULL, '2026-01-22 08:41:51', '2026-01-22 08:41:51', NULL, NULL),
(19, 'Bi Suy Robert TOUBOUI', 'rtouboui@dgi.gouv.ci', NULL, '$2y$12$dVhNjgRnLUf95A8qOX1w9uF7c8DtE6VXGVBaEIaxXAf1ovSoQTVQu', 1, NULL, NULL, '2026-01-23 10:25:21', '2026-01-23 10:25:21', NULL, NULL),
(20, 'Mamadou Lamine COULIBALY', 'mamadoulcoul@dgi.gouv.ci', NULL, '$2y$12$Lfz7QtoJfaRONcHbgo2Qg.3k3fJ2rlVTyFVr7bVCCBG.roawC3qDG', 1, NULL, NULL, '2026-01-23 10:29:57', '2026-01-23 10:29:57', NULL, NULL),
(21, 'Née Keita Aramata Anne Elise KEDI', 'akeita@dgi.gouv.ci', NULL, '$2y$12$6vph6Z9CtWbD2RBrrtViFOccxOP4LxXoXpcxp4XkqOWH303Oisp4m', 1, NULL, NULL, '2026-01-27 07:57:48', '2026-01-27 07:57:48', NULL, NULL),
(22, 'Konan Christian René KOFFI', 'christiankonankoffi@dgi.gouv.ci', NULL, '$2y$12$iklZZEklhAlFRdc1RcWRkelt6/2BOWWYuzezk4kFgTlmEnAEqUNcO', 1, NULL, NULL, '2026-01-27 08:14:10', '2026-01-27 08:14:10', NULL, NULL),
(23, 'M\'bo Erasthène ALEXANDRE', 'erasthene16ja17@dgi.gouv.ci', NULL, '$2y$12$pyiztyHvVrjRiKOwbPCUhekN8qw1kx4sIHaS0pCk4svXJ341adPAe', 1, NULL, NULL, '2026-01-27 14:44:26', '2026-01-27 14:44:26', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
