-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Mer 27 Juin 2012 à 08:30
-- Version du serveur: 5.0.45
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de données: `jdb_v1`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `jdb_activites`
-- 

CREATE TABLE `jdb_activites` (
  `id` int(11) NOT NULL auto_increment,
  `activite_nom` varchar(255) NOT NULL,
  `activite` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `jdb_activites`
-- 

INSERT INTO `jdb_activites` (`id`, `activite_nom`, `activite`, `commentaire`) VALUES 
(1, 'Exploitation', 'exploitation', ''),
(2, 'PinUp', 'pinup', ''),
(3, 'Reporting', 'reporting', ''),
(4, 'Réseau', 'reseau', ''),
(5, 'Supervision', 'supervision', ''),
(6, 'Système', 'systeme', ''),
(7, 'ToIP', 'toip', ''),
(8, 'Outils', 'outils', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `jdb_champs`
-- 

CREATE TABLE `jdb_champs` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL,
  `nick` varchar(255) NOT NULL,
  `activite` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `correspondance` smallint(6) NOT NULL,
  `type` enum('text','textarea','select') NOT NULL default 'text',
  `menu` varchar(255) NOT NULL,
  `is_oblig` tinyint(1) NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- 
-- Contenu de la table `jdb_champs`
-- 

INSERT INTO `jdb_champs` (`id`, `nom`, `nick`, `activite`, `commentaire`, `correspondance`, `type`, `menu`, `is_oblig`, `visible`) VALUES 
(4, 'Impact', 'impact', 'systeme', 'Probleme chez le client ou chez nous?', 1, 'select', 'Client;CSS', 0, 1),
(5, 'Numéro de dossier', 'numero_de_dossier', 'systeme', 'Numéro de dossier? clarify ou tiger', 2, 'text', '', 1, 1),
(6, 'Actions réalisées', 'actions_realisees', 'systeme', 'Quelles sont les actions réalisées par rapport à cet évènement?', 3, 'textarea', '', 0, 1),
(9, 'Impact', 'impact', 'reseau', 'Probleme chez le client ou chez nous?', 1, 'select', 'Client;CSS', 0, 1),
(11, 'Numéro de dossier', 'numero_de_dossier', 'reseau', 'N° du Tiger ou Clarify', 2, 'text', '', 1, 1),
(12, 'Actions Réalisées', 'actions_realisees', 'reseau', 'Descriptif des actions réalisées', 3, 'textarea', '', 0, 1),
(35, 'Origine', 'origine', 'systeme', 'Sous quelle forme a-t-on recu l''information : Mail/Tel/Supervision...', 4, 'select', 'Mail;Téléphone;Supervision', 0, 1),
(36, 'Correspondant', 'correspondant', 'systeme', 'Nom du contact', 5, 'text', '', 0, 1),
(37, 'Origine', 'origine', 'reseau', 'Sous quelle forme a-t-on recu l''information : Mail/Tel/Supervision..', 4, 'select', 'Mail;Téléphone;Supervision', 0, 1),
(38, 'Correspondant', 'correspondant', 'reseau', 'Nom du contact', 5, 'text', '', 0, 1),
(39, 'Numéro de dossier', 'numero_de_dossier', 'toip', 'N° du Tiger ou Clarify', 1, 'text', '', 1, 1),
(40, 'Libellé Page', 'libelle_page', 'pinup', 'Nom de la page sur laquelle il y a eu intervention', 0, 'text', '', 0, 1),
(41, 'Niveau', 'niveau', 'pinup', 'N° du menu de cette page', 1, 'text', '', 0, 1),
(45, 'Numéro de dossier', 'numero_de_dossier', 'exploitation', 'N° du Tiger ou Clarify', 1, 'text', '', 1, 1),
(47, 'Numéro de dossier', 'numero_de_dossier', 'supervision', '', 0, 'text', '', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `jdb_evenements`
-- 

CREATE TABLE `jdb_evenements` (
  `id` int(11) NOT NULL auto_increment,
  `visible` tinyint(1) NOT NULL default '1',
  `activite` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `heure` varchar(255) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `evenement` varchar(255) NOT NULL,
  `communication` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `client` varchar(255) NOT NULL,
  `alerting` tinyint(4) NOT NULL default '0',
  `alerte_date` varchar(255) NOT NULL,
  `alerte_heure` varchar(255) NOT NULL,
  `alerting_fin` tinyint(4) NOT NULL default '0',
  `alerte_date_fin` varchar(255) NOT NULL,
  `alerte_heure_fin` varchar(255) NOT NULL,
  `is_acquit` tinyint(4) NOT NULL default '0',
  `alerte_date_acquit` varchar(255) NOT NULL,
  `alerte_heure_acquit` varchar(255) NOT NULL,
  `user_acquit` varchar(255) NOT NULL,
  `0` varchar(255) NOT NULL,
  `1` varchar(255) NOT NULL,
  `2` varchar(255) NOT NULL,
  `3` varchar(255) NOT NULL,
  `4` varchar(255) NOT NULL,
  `5` varchar(255) NOT NULL,
  `6` varchar(255) NOT NULL,
  `7` varchar(255) NOT NULL,
  `8` varchar(255) NOT NULL,
  `9` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=153 ;

-- 
-- Contenu de la table `jdb_evenements`
-- 

INSERT INTO `jdb_evenements` (`id`, `visible`, `activite`, `date`, `heure`, `auteur`, `evenement`, `communication`, `description`, `client`, `alerting`, `alerte_date`, `alerte_heure`, `alerting_fin`, `alerte_date_fin`, `alerte_heure_fin`, `is_acquit`, `alerte_date_acquit`, `alerte_heure_acquit`, `user_acquit`, `0`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`) VALUES 
(2, 1, 'PinUp', '2012/03/30', '', 'Clément ORTIZ', 'Suppression', 'CS + Equipe', 'Suppression de liens vers des types de mails précis', '', 0, '', '', 0, '', '', 0, '', '', '', 'Gestion boites mails', '8.2.2', '', '', '', '', '', '', '', ''),
(3, 1, 'PinUp', '2012/03/30', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', '_ " Je fais quoi de ce mail ? " Lien vers un Excel qui "tente" de le lister tous les mails et les actions qui en résultent._ Règles de base_ bouton Process : Liste des process concernant la gestion de la messagerie Prod-Services.', '', 0, '', '', 0, '', '', 0, '', '', '', 'Gestion boites mails', '8.2.2', '', '', '', '', '', '', '', ''),
(4, 1, 'PinUp', '2012/03/30', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', '5 Pages affichants les divers process', '', 0, '', '', 0, '', '', 0, '', '', '', 'Process détaillé Process Général Traitement Process Communications Internes Organisation des dossiers Process', '8.2.2.1 8.2.2.2 8.2.2.3 8.2.2.4 8.2.2.5', '', '', '', '', '', '', '', ''),
(5, 1, 'PinUp', '2012/03/30', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Procedure prise d''appel Tresorerie', '', 0, '', '', 0, '', '', 0, '', '', '', 'Procédure', '3.5.18.4', '', '', '', '', '', '', '', ''),
(6, 1, 'PinUp', '2012/03/27', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Fiche HNO Ville de Boulogne', '', 0, '', '', 0, '', '', 0, '', '', '', 'Vile de Boulogne - HNO', '5.5.5', '', '', '', '', '', '', '', ''),
(7, 1, 'PinUp', '2012/03/20', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Planning astreinte DSIH 2012', '', 0, '', '', 0, '', '', 0, '', '', '', 'EPS - Ville d''Evrard', '6.5', '', '', '', '', '', '', '', ''),
(8, 1, 'PinUp', '2012/03/27', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Escalade DESD', '', 0, '', '', 0, '', '', 0, '', '', '', 'Escalade DESD', '8.1.20', '', '', '', '', '', '', '', ''),
(9, 1, 'PinUp', '2012/04/02', '', 'Clément ORTIZ', 'Ajout', 'Chef Service', 'Ajout du commentaire de Mme LEROY sur les transferts d''appels vers l''astreinte', '', 0, '', '', 0, '', '', 0, '', '', '', 'SIS SDIS-HNO', '5.5.4', '', '', '', '', '', '', '', ''),
(10, 1, 'PinUp', '2012/04/02', '', 'Clément ORTIZ', 'Ajout', 'Chef Service', 'ajout de la ligne : ( dans la sélection du produit, bien prendre ''Ex_PCG_ASSISTANCE TECHNIQ'', sinon le contrat bascule sur le CTASI1046 .', '', 0, '', '', 0, '', '', 0, '', '', '', 'Informations principales - VILM', '3.5.34.1', '', '', '', '', '', '', '', ''),
(11, 1, 'PinUp', '2012/04/02', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'ajout consigne remplacement LVM', '', 0, '', '', 0, '', '', 0, '', '', '', 'LVM Zone EUROPE - info principales', '3.4.10.1', '', '', '', '', '', '', '', ''),
(12, 1, 'PinUp', '2012/04/03', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'ajout fiche ADOMA', '', 0, '', '', 0, '', '', 0, '', '', '', 'Adoma HNO', '5.2.1', '', '', '', '', '', '', '', ''),
(13, 1, 'PinUp', '2012/04/04', '', 'Kévin RENAERD', 'Modification', 'Non', 'Ajout référentiel équipement LVM', '', 0, '', '', 0, '', '', 0, '', '', '', 'LVM Zone EUROPE - info principales', '3.4.10.1', '', '', '', '', '', '', '', ''),
(14, 1, 'PinUp', '2012/04/04', '', 'Clément ORTIZ', 'Modification', 'Non', 'Modif de la file de dispatch : DGE-BO1 Client', '', 0, '', '', 0, '', '', 0, '', '', '', 'BRED', '3.1.20 ???', '', '', '', '', '', '', '', ''),
(15, 1, 'PinUp', '2012/04/06', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Lien vers le fichier CSU HNO', '', 0, '', '', 0, '', '', 0, '', '', '', 'CLIENTS SERVICES UTILISATEURS HNO', '5', '', '', '', '', '', '', '', ''),
(16, 1, 'PinUp', '2012/04/06', '', 'Clément ORTIZ', 'Modification', 'Non', 'lien vers ''Gestion Incident'' broken', '', 0, '', '', 0, '', '', 0, '', '', '', 'MIDI LIBRE', '3.4.18', '', '', '', '', '', '', '', ''),
(18, 1, 'PinUp', '2012/04/06', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Lien vers ''Astreinte''', '', 0, '', '', 0, '', '', 0, '', '', '', '?SIS SDIS-HNO', '5.5.4', '', '', '', '', '', '', '', ''),
(19, 1, 'PinUp', '2012/04/10', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Ajout Page ''Connexion HPOV''', '', 0, '', '', 0, '', '', 0, '', '', '', 'Connexion HPOV', '8.2.5.1', '', '', '', '', '', '', '', ''),
(20, 1, 'PinUp', '2012/04/10', '', 'Clément ORTIZ', 'Ajout', 'Non', 'Ajout d''un lien ''Pour les RA'' dans le client OPTIM', '', 0, '', '', 0, '', '', 0, '', '', '', 'OPTIM', '4.3.1', '', '', '', '', '', '', '', ''),
(21, 1, 'PinUp', '2012/04/11', '', 'Clément ORTIZ', 'Intégration', 'CS + Equipe', 'Intégration du client 3A', '', 0, '', '', 0, '', '', 0, '', '', '', '3A', '5.2.4', '', '', '', '', '', '', '', ''),
(22, 1, 'PinUp', '2012/04/11', '10:00', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Début Coupure électrique', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, 1, 'PinUp', '2012/04/11', '11:00', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Fin Coupure électrique', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, 1, 'PinUp', '2012/04/11', '11:00', 'Clément ORTIZ', 'Incident Majeur', 'Non', 'Perte accès au domaine spiecom.Com', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 1, 'PinUp', '2012/04/11', '15:45', 'Clément ORTIZ', 'Incident Majeur', 'Non', 'Fin Perte accès au domaine spiecom.Com', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, 1, 'PinUp', '2012/04/11', '10:58', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Fiche client ville d''evrard', '', 0, '', '', 0, '', '', 0, '', '', '', 'EPS - Ville d''Evrard-HNO', '5.2.5', '', '', '', '', '', '', '', ''),
(27, 1, 'PinUp', '2012/04/13', '', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'fiche Téléphone ACP, comment programmer les touches', '', 0, '', '', 0, '', '', 0, '', '', '', 'Téléphone, ACP', '8?', '', '', '', '', '', '', '', ''),
(28, 1, 'PinUp', '2012/04/20', '', 'Kévin RENAERD', 'Ajout', 'CS + Equipe', 'Intégration du client MCC', '', 0, '', '', 0, '', '', 0, '', '', '', 'Minist?re de la culture - HNO', '5.3.2', '', '', '', '', '', '', '', ''),
(29, 1, 'exploitation', '2012/03/04', '09:15', 'Clément ORTIZ', 'Modification', 'Non', 'Neutralisation de l''activité du PEJ suivante :\r\n\r\n« SPIE COMMUNICATIONS  - TELEPRO. Vérification des mises à jours Microsoft et Antivirus sur FRSV002030. Modification de la procédure, les Maj doivent être passées durant ce créneau (pas besoin de validation). Créer un cas clarify en cas d''incident. »\r\n', 'SPIE COMMUNICATION', 0, '', '', 0, '', '', 0, '', '', '', '', '5826079', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(30, 1, 'exploitation', '2012/03/04', '09:16', 'Clément ORTIZ', 'Modification', 'Non', 'Neutralisation des tâches nécessitant un Rapport d''Activités\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(31, 1, 'exploitation', '2012/04/10', '09:17', 'Clément ORTIZ', 'Information', 'CS + Equipe', 'Gestion des droits d''administration PEJ ; depuis Blagnac, seuls Olivier BARAIZE, William BOURGEOIS, Jean-Charles FOUSSAL DE BÉLERD et Abdel ASSABI peuvent administrer le PEJ\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(32, 1, 'exploitation', '2012/04/11', '09:17', 'Clément ORTIZ', 'Incident Majeur', 'CS + Equipe + ROC', 'PEJ inaccessible dû à un stagiaire de Cergy qui ne sait pas faire la différence entre un interrupteur de lumière et un bouton d''arrêt d''urgence jusqu''à 15h50\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(33, 1, 'exploitation', '2012/04/16', '09:17', 'Clément ORTIZ', 'Incident Majeur', 'CS + Equipe + ROC', 'PEJ DOWN jusqu''à 16h00\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(34, 1, 'reseau', '2012/04/04', '09:19', 'Clément ORTIZ', 'Ajout', 'Chef Service', 'Ajout de la file ''spiecom CSS RESEAU''dans TIGER\r\n ', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(35, 1, 'reseau', '2012/04/11', '10:20', 'Clément ORTIZ', 'Fait Marquant', 'CS + Equipe + ROC', 'Coupure électrique générale sur Blagnac\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(36, 1, 'reseau', '2012/04/13', '06:00', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Serveur DHCP KO\r\nImpossible de prendre des appels durant 30 minutes, retard sur PEJ\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(37, 1, 'reseau', '2012/04/24', '10:20', 'Clément ORTIZ', 'Incident Majeur', 'CS + Equipe', 'Réseau TIGA DOWN\r\n', 'LVM', 0, '', '', 0, '', '', 0, '', '', '', '', 'client', '', 'Tiger crée pour les N2', 'Téléphone', 'Mme BOUTON', ' ', ' ', ' ', ' '),
(38, 1, 'reseau', '2012/04/04', '09:25', 'Clément ORTIZ', 'Ajout', 'Chef Service', 'creation de la file SPIECOM CSS SYSTÈME\r\ndans TIGER', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(39, 1, 'reseau', '2012/04/10', '09:26', 'Clément ORTIZ', 'Ajout', 'Non', 'Ajout de l''utilisateur « Marcel DANIEL » dans le PEJ Supervision\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(40, 1, 'systeme', '2012/04/02', '07:00', 'Clément ORTIZ', 'Fait Marquant', 'CS + Equipe', 'Le compte administrateur &quot;admspie&quot; a expiré\r\n', 'SPSE', 0, '', '', 0, '', '', 0, '', '', '', '', 'CSS', '', 'Mail envoyé au supérieur', 'Supervision', '', ' ', ' ', ' ', ' '),
(41, 1, 'systeme', '2012/04/04', '12:00', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Création de la file &quot;Système&quot; dans Tiger', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'CSS', '-', '', 'Mail', '', ' ', ' ', ' ', ' '),
(42, 1, 'systeme', '2012/04/13', '16:00', 'Clément ORTIZ', 'Incident Majeur', 'CS + Equipe', 'Serveur DHCP down\r\n', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'Client', '-', '', 'Mail', '', ' ', ' ', ' ', ' '),
(43, 1, 'toip', '2012/04/11', '09:31', 'Clément ORTIZ', 'Incident Majeur', 'Non', 'serveur Cergy indisponible suite coupure courant \r\nimpossible de traiter cas pas acces a ipdiva \r\n', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(44, 1, 'systeme', '2012/01/16', '10:30', 'Jean-Claude LAILLON', 'Fait Marquant', 'non', 'SPIE SA - TIGER n''est pas à l''heure 253793', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(45, 1, 'reseau', '2012/03/01', '10:30', 'Olivier BARAIZE', 'Fait Marquant', 'non', 'SPIE SA - Perte DNS sur FRSV001650  (Tiger : 263831)', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(46, 1, 'systeme', '2012/03/15', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'CH RODEZ - Coupure de courant sur les sites de Peyrières et Saint-Thomas', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(47, 1, 'reseau', '2012/03/21', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'SPIE SA - Tache du PEJ non effectuée :  Validation des patches critiques et sécurité , faute de procédure et de moyens de connexion', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(48, 1, 'toip', '2012/03/26', '10:30', 'Germain BOUCHET', 'Fait Marquant', 'non', 'SPIE SA - Pb de reception téléphonique entre 6h et 6h30 lors du débordement CSU', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(49, 1, 'reseau', '2012/03/26', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'SPSE - Coupure réseau sur le site d''OBENHOFFER à SPSE', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(50, 1, 'systeme', '2012/03/27', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'CH RODEZ - Coupure électrique dans le Centre Hospitalier', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(51, 1, 'systeme', '2012/03/27', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'VILM - Pannes groupées de plusieurs serveurs', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(52, 1, 'systeme', '2012/03/29', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'SPIE Communications - Bagots du serveur IPDIVA', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(53, 1, 'systeme', '2012/03/30', '10:30', 'Germain BOUCHET', 'Fait Marquant', 'non', 'SPIE Communications - Ipdiva down sur tech1 et tech2 n''est pas renseigné pour effectuer les taches des clients', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(54, 1, 'systeme', '2012/03/30', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'SPIE Communications - FRSV002030 inaccessible ; tache du PEJ associée impossible à réaliser', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(55, 1, 'systeme', '2012/04/02', '10:30', 'Germain BOUCHET', 'Fait Marquant', 'non', 'VILM - Serveur DHCP sur ILMPRTADC04CAM', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(56, 1, 'reseau', '2012/04/02', '10:30', 'Germain BOUCHET', 'Fait Marquant', 'non', 'VILM - Problème DHCP sur VILM', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(57, 1, 'systeme', '2012/04/02', '10:30', 'Germain BOUCHET', 'Fait Marquant', 'non', 'SPSE - Compte admin ''admspie'' sur le domaine ''spse-paris'' qui a expiré', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(58, 1, 'systeme', '2012/04/02', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'LVM - Pole CERGY 3 DOWN', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(59, 1, 'systeme', '2012/04/02', '10:30', 'William BOURGEOIS', 'Fait Marquant', 'non', 'CH RODEZ - CH RODEZ - 172.18.41.252 non joignable', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(60, 1, 'supervision', '2012/04/10', '10:30', 'Marcel DANIEL', 'Fait Marquant', 'non', 'SPIE SA - Erreur de la trésorerie', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(61, 1, 'reseau', '2012/04/11', '10:30', 'Adrien JAVIERRE', 'Fait marquant', 'non', 'SPIE SA - Coupure réseau sru CERGY, impact sur Thruck, Ipdiva durant 2h. Coupure électrique le matin, impact durant 1h30', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(62, 1, 'reseau', '2012/04/12', '10:30', 'Jean-Claude LAILLON', 'Fait Marquant', 'non', 'SPIE SA - Serveur DHCP DOWN jeudi 12 avril à 21h jusqu''à Vendredi 13 avril vers 8h', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(63, 1, 'reseau', '2012/04/17', '10:30', 'Jean-Claude LAILLON', 'Fait Marquant', 'non', 'SPIE SA - Serveur FTPCorp ne peut etre accéder qu''en anonymous (pb de droit)', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(64, 1, 'supervision', '2012/04/27', '14:20', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Test du client CH Agen sur la SUP.\r\nA débranché volontairement un matériel pour voir le temps de réactivité de la Sup.\r\n\r\nHervé ROBUCHON responsable du compte a été informé par C. DUCHEN.', ' CH Agen', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(65, 1, 'exploitation', '2012/04/27', '21:52', 'William BOURGEOIS', 'Information', 'Non', 'CACTI : impossible de récupérer les graphes pour Louis Vuitton Malletier.', 'LVM', 0, '', '', 0, '', '', 0, '', '', '', '', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(67, 1, 'pinup', '2012/04/30', '09:06', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'ajout d''un lien vers la création de nouveau établissement pour dominos pizza\r\n', '', 0, '', '', 0, '', '', 0, '', '', '', 'Dominos Pizza - Informations Principales', '3.2.30', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(68, 1, 'reseau', '2012/05/03', '09:30', 'Marcel DANIEL', 'Incident Majeur', 'CS + Equipe', 'Serveur DHCP DOWN', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(69, 1, 'pinup', '2012/05/07', '09:14', 'Kévin RENAERD', 'Modification', 'Chef Service', 'Mise à jour de la fiche SIS - SDIS - HNO', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(70, 1, 'pinup', '2012/05/07', '11:01', 'Clément ORTIZ', 'Modification', 'Non', 'Modification de la fiche AMPLIVIA.\r\n\r\n- Si detection d''une alarme HPOV : Lot 10 et non Lot 11\r\n- Ajout d''un N° de contrat car le CTUN7157AA ne fonctionne pas : Utiliser le CTUN7158AA.', '', 0, '', '', 0, '', '', 0, '', '', '', 'Amplivia', '3.1.6 et 3.1.8', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(71, 1, 'reseau', '2012/04/30', '6:00', 'Germain BOUCHET', 'Fait Marquant', 'CS + Equipe + ROC', 'Pb avec le DHCP, espace disque du nb0 trop faible pour créer le fichier de baux', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(72, 1, 'pinup', '2012/05/11', '15:58', 'Kevin RENAERD', 'Ajout', 'CS + Equipe', 'Ajout de la grille d''escalade en region', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(73, 1, 'pinup', '2012/05/15', '10:39', 'Kevin RENAERD', 'Modification', 'Chef Service', 'ajout de la procédure avant déclenchemetn de l''astreinte', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(74, 1, 'pinup', '2012/05/18', '16:28', 'Clément ORTIZ', 'Modification', 'CS + Equipe', 'Modification du lien vers les &quot;Journées Rouges&quot; pour l''&quot;Aeroports De Paris&quot;', '', 0, '', '', 0, '', '', 0, '', '', '', 'ADP - Aeroports de paris', '3.1.2.3', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(75, 1, 'systeme', '2012/05/21', '10:12', 'Marcel DANIEL', 'Incident Majeur', 'CS + Equipe + ROC', 'Licence TINA expiré', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', ' ', ' ', ' ', ' '),
(76, 1, 'outils', '2012/05/21', '11:10', 'JDB', 'Fait Marquant', 'CS + Equipe + ROC', 'Problème avec le contrat de la VILM : CTASI1047 contrat désactivé dans Clarify.', '', 0, '', '', 0, '', '', 0, '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(77, 1, 'supervision', '2012/05/23', '00:21', 'Yoann CORBEL', 'Fait Marquant', 'Non', 'DNS / DHCP down', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(78, 1, 'supervision', '2012/05/23', '00:25', 'Yoann CORBEL', 'Ajout', 'Non', 'tests en cours sur ADP sur le local S3N. appel de stéphane mezei', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(79, 1, 'supervision', '2012/05/23', '01:26', 'Yoann CORBEL', 'Fait Marquant', 'Non', 'Alarmes sur ADP. Site 2 F. prévu par Stéphane.', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(80, 1, 'supervision', '2012/05/23', '02:41', 'Yoann CORBEL', 'Incident Majeur', 'Non', 'PP8600 Edison et vinci port 1/5 et 2/5 sur edi; 1/7 et 2/7 sur vinci.\r\nContact astreinte manager Sandrine.\r\nContact astreinte réseau Emmanuel Schluk.', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(81, 1, 'supervision', '2012/05/23', '04:06', 'Yoann CORBEL', 'Incident Majeur', 'Non', 'coupure courant edison 1 , 2 , 3', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(82, 1, 'reseau', '2012/05/23', '23:58', 'Yoann CORBEL', 'Incident Majeur', 'Non', 'Plus de GESAST !! 23h30', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(83, 1, 'supervision', '2012/05/23', '23:58', 'Yoann CORBEL', 'Incident Majeur', 'Non', 'Plus de GESAST !! 23h30', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(84, 1, 'supervision', '2012/05/24', '00:02', 'Yoann CORBEL', 'Fait Marquant', 'Non', 'ADP - Site 2E en test - Onduleurs + swichs', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(85, 1, 'supervision', '2012/05/24', '01:06', 'Yoann CORBEL', 'Information', 'Non', 'Tous les postes occupés ... FT déborde sur moi .. impossibilité de transférer l''appel à David car son tel sonne dès qu''il raccroche... question du tech : &quot;l''alarme est-elle tjrs présente ..&quot; Je connais pas FT. Impossibilité de répondre au tech. D''ailleurs le tech me signale que c''est très dur de nous avoir : bascule sur répondeur auto.', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(86, 1, 'supervision', '2012/05/24', '01:11', 'Yoann CORBEL', 'Fait Marquant', 'Non', 'Récupération de gesast. Sauvegarde faite dans mes téléchargements dès la récupération.', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(87, 1, 'supervision', '2012/05/31', '02:35', 'Yoann CORBEL', 'Fait Marquant', 'Non', 'Au niveau de la supervision, deux hosts downs. Déclenchement astreinte réseau, astreinte manager, technicien DGE DATA.\r\nRecherche d''information laborieuse pour connaitre la localisation du matériel.\r\nTechnicien à 1h30 du site. Se déplace quand même.', '', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(88, 1, 'supervision', '2012/06/01', '22:30', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Reception de mails de la part de l''automate DOMINOS.\r\nJ''applique la procédure PINUP.\r\n\r\nJ''arrive bien sur la supervision.\r\n\r\nJe range les mails dans le dossier client DOMINOS\r\ncas 5858305', 'Dominos', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(89, 1, 'supervision', '2012/06/02', '05:09', 'Clément ORTIZ', 'Information', 'Non', 'nombreux bagots sur le DHCP SPIE SA,\r\nCRITICAL: No DHCPOFFERs were received.\r\n\r\nTombe 10min puis UP.', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(91, 1, 'supervision', '2012/06/04', '16:59', 'Adrien JAVIERRE', 'Ajout', 'Non', 'Multiples alarmes sur les équipements spie', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(92, 1, 'pinup', '2012/06/05', '09:31', 'Kevin RENAERD', 'Ajout', 'Non', 'ajout de la fiche client intespace', '', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(93, 1, 'supervision', '2012/06/05', '18:58', 'Adrien JAVIERRE', 'Fait Marquant', 'Non', 'Maintenance sur le site 9 enfin de journée.\r\nMaintenance sur le site 7 dans la nuit', 'MEEDM', 0, '', '', 0, '', '', 0, '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(94, 1, 'reseau', '2012/06/06', '11:40', 'Adrien JAVIERRE', 'Incident Majeur', 'Chef Service', 'Perte de la supervision, ligne téléphonique escalade DESD HS. durée 10 minutes', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', '', '', '', '', '', ' ', ' ', ' ', ' '),
(95, 1, 'supervision', '2012/06/06', '16:13', 'Clément ORTIZ', 'Information', 'Non', 'HPOV : plusieurs node down\r\n- Chambery\r\n- Annecy\r\n- Bourget\r\n- Valence\r\n- Pradel\r\n- Bourg en bresse\r\n- Oyonnax\r\n- St Etienne\r\n- Roanne\r\n\r\nVu avec F. Taran : il s''agit de bago\r\nAlarmes acquittées sans création de cas. ', 'Amplivia', 0, '', '', 0, '', '', 0, '', '', '', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(96, 1, 'reseau', '2012/06/06', '21:13', 'William BOURGEOIS', 'Fait Marquant', 'Chef Service', 'Brève coupure de l''Internet (Sites externes à SPIE).\r\nVéronique GALY et Chafike SABAR avertis.\r\n\r\nRetour à la normale survenu pendant l''appel à Chafike SABAR.', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'Client', '', 'Appel à Véronique GALY et Chafike SABAR.\r\n', 'Mail', '', ' ', ' ', ' ', ' '),
(97, 1, 'supervision', '2012/06/07', '10:46', 'Enguerrand FOUSSAL', 'Information', 'Chef Service', 'Bonjour,\r\nDepuis quelques jours le serveur DHCP/DNS remonte quelques états Critical ou Warning mais si on regarde bien, ça revient en up tout seul. Sans faire quoi que ce soit.\r\nPour le moment, si cela reste comme ça, il ne faut pas créer de cas ou alerter un admin. Mais si jamais ça reste down pendant une bonne dizaine de minutes, là il faut nous alerter.\r\nJe vais prévenir Hakim (l''admin Linux) demain qu''il y a un problème à ce niveau là.\r\nPour l''instant il a fini sa journée.\r\nCdt.s.mcharek on 06-06-2012 18:51:48  ', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '282012', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(98, 1, 'outils', '2012/06/07', '15:06', 'William BOURGEOIS', 'Incident Majeur', 'Non', 'Le serveur où se trouve la base de données KEEPASS ne répond plus.\r\nLorsque KEEPASS essaie d''ouvrir un emplacement réseau qui n''existe plus, celui-ci se gèle et on est alors obligé de tuer le processus KEEPASS.EXE.\r\n\r\nNous avons donc perdu TOUS LES MOTS DE PASSE DE TOUS LES CLIENTS.\r\n\r\nLe N2 Réseau a été appelé et a constaté également la non-réponse du serveur :\r\n\r\nEnvoi d''une requête ''ping'' sur 10.222.212.171 avec 32 octets de données :\r\n\r\nDélai d''attente de la demande dépassé.\r\nDélai d''attente de la demande dépassé.\r\nDélai d''attente de la demande dépassé.\r\nDélai d''attente de la demande dépassé.\r\n\r\nStatistiques Ping pour 10.222.212.171:\r\n    Paquets : envoyés = 4, reçus = 0, perdus = 4 (perte 100%),\r\n\r\n\r\n\r\n\r\n*** RÉSOLU À 15h39 ***', 'DESD', 0, '', '', 0, '', '', 0, '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(99, 1, 'reseau', '2012/06/07', '15:38', 'Kevin RENAERD', 'Fait Marquant', 'Non', 'Extinction de :\r\n-	Dash-board Rubis\r\n-	Frsu0021 (physique)\r\n-	Modem act\r\n-	Modem ft\r\n-	Switch ft\r\n-	Oghma (physique)\r\n-	Srv debian test\r\n-	Rsa nvision\r\n-	Frsv002018 (camera)\r\n-	Frsv002038 (camera)\r\n-	Frsv001237 (ipme)\r\n', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(107, 1, 'outils', '2012/06/10', '17:41', 'Yannick TRAORE', 'Modification', 'Chef Service', 'Mise à jour du mot de passe pour IWS dans la base Keepass.', 'SIS', 0, '', '', 0, '', '', 0, '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(108, 1, 'exploitation', '2012/06/11', '15:13', 'Clément ORTIZ', 'Fait Marquant', 'Chef Service', 'Plus de code contrat valide pour le client TICE', 'TICE', 0, '', '', 0, '', '', 0, '', '', '', ' ', '5867197', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(109, 1, 'exploitation', '2012/06/12', '13:50', 'Marcel DANIEL', 'Incident Majeur', 'CS + Equipe', 'Problème de sauvegarde hebdomadaire dû au manque d''espace sur VLS.', 'VILM', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'VILM', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(110, 1, 'pinup', '2012/06/12', '15:29', 'Clément ORTIZ', 'Modification', 'CS + Equipe', 'Ajout d''une ligne indiquant que TICE n''est plus dans notre périmètre.\r\nIl sera nécessaire de retirer le client par la suite.', 'TICE', 0, '', '', 0, '', '', 0, '', '', '', 'Tice', '3.5.29', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(111, 1, 'supervision', '2012/06/12', '15:35', 'Clément ORTIZ', 'Suppression', 'CS + Equipe', 'Demande de retrait du client TICE de la sup ', 'TICE', 0, '', '', 0, '', '', 0, '', '', '', '5868108', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(112, 1, 'pinup', '2012/06/12', '15:48', 'Clément ORTIZ', 'Ajout', 'Non', 'Ajout des contacts du CDS pour la VILM', 'VILM', 0, '', '', 0, '', '', 0, '', '', '', 'Escalade ', '3.5.34.3', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(113, 1, 'pinup', '2012/06/14', '09:14', 'Clément ORTIZ', 'Modification', 'Non', 'Modification du code contrat pour VILM', 'VILM', 0, '', '', 0, '', '', 0, '', '', '', 'Informations Principales', '3.5.34.1', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(114, 1, 'supervision', '2012/06/14', '11:03', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Vague d''EXPANDS qui tombent,\r\nDOWN le temps de 5 min,\r\nPas eu le temps de creer un Tiger,\r\n\r\nQuelques alarmes restent : No response\r\nMais disparaissent peu de temps apres.', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(115, 1, 'supervision', '2012/06/14', '16:20', 'Yoann CORBEL', 'Fait Marquant', 'Chef Service', 'hier soir routeur WAN SPIE en maintenance sans nous prévenir.\r\nBackup puis principal', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', '283489 ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(116, 1, 'pinup', '2012/06/14', '17:37', 'Clément ORTIZ', 'Suppression', 'Non', 'Client TICE désactivé sous Pinup', 'TICE', 0, '', '', 0, '', '', 0, '', '', '', 'Tice', '3.5.29', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(117, 1, 'exploitation', '2012/06/14', '19:59', 'Yoann CORBEL', 'Information', 'Non', 'Journal de sécurité du S-GESPRO vide et inactif', 'SPSE', 0, '', '', 0, '', '', 0, '', '', '', ' ', '0', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(122, 1, 'reseau', '2012/06/15', '09:08', 'Kevin RENAERD', 'Fait Marquant', 'Non', 'La plage pour joindre les astreintes est  désormais  entre  18h00 et  8h00, ceci pour les équipes réseaux / sécu  et system.', 'CSS', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(123, 1, 'supervision', '2012/06/15', '09:08', 'Kevin RENAERD', 'Fait Marquant', 'Non', 'La plage pour joindre les astreintes est  désormais  entre  18h00 et  8h00, ceci pour les équipes réseaux / sécu  et system.', 'CSS', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(124, 1, 'systeme', '2012/06/15', '09:08', 'Kevin RENAERD', 'Fait Marquant', 'Non', 'La plage pour joindre les astreintes est  désormais  entre  18h00 et  8h00, ceci pour les équipes réseaux / sécu  et system.', 'CSS', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(126, 1, 'reseau', '2012/06/15', '12:00', 'Clément ORTIZ', 'Incident Majeur', 'Non', 'Divers alertes sur le DNS/DHCP de SPIE.\r\nEscalade déclenchée.', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'Client', '-', '', 'Mail', '', ' ', ' ', ' ', ' '),
(127, 1, 'pinup', '2012/06/15', '16:47', 'Clément ORTIZ', 'Modification', 'Non', 'Mise a jour de la file de dispatch pour ADP : (par Kévin ;) )\r\nSC-DES-AS-Reseau Depot par SC-DGE-Reseau1 Depot.', 'ADP', 0, '', '', 0, '', '', 0, '', '', '', 'Informations Principales', '3.1.2.3', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(128, 1, 'supervision', '2012/06/18', '07:00', 'Clément ORTIZ', 'Fait Marquant', 'Non', 'Tres nombreux composants en alerte sous THRUK.\r\n\r\nTentative de connexion à Clarilog : Echec d''ouverture de l''application dû a une erreur SQL.\r\n\r\n(pas pu déclencher d''intervention suite à la reception d''appel ATB)\r\n---------------\r\nIncident sur le VCentre : Tous les serveurs ont basculés sur le même ESX.\r\nReboot des serveurs un par un pour étaler la charge sur les différents ESX.', 'MISSENARD', 0, '', '', 0, '', '', 0, '', '', '', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(133, 1, 'pinup', '2012/06/18', '12:43', 'Clément ORTIZ', 'Modification', 'CS + Equipe + ROC', 'Modification de la procédure TRAP :\r\nIl est explicitement écrit qu''il faut réaliser la création d''un cas celon le process client.', 'DESD', 0, '', '', 0, '', '', 0, '', '', '', 'Procédure et Correspondance alarme TRAP', '8.4.3', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(136, 1, 'exploitation', '2012/06/18', '20:09', 'William BOURGEOIS', 'Incident Majeur', 'Non', 'PEJ inaccessible pour deux personnes (C.-A. ORTIZ et M. THOUVENOT).\r\nAppel à Guenhael GOMEZ-BETTI pour résolution du problème.\r\n\r\nFichiers à remplacer présents dans \\\\frsv001053\\Centre_Appel\\SERVICE SUPPORT\\FORMATION\\03 - N1\\Fix-it Felix.zip', 'DESD', 0, '', '', 0, '', '', 0, '', '', '', ' ', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(137, 1, 'exploitation', '2012/06/18', '20:11', 'William BOURGEOIS', 'Incident Majeur', 'CS + Equipe + ROC', 'Réseau MISSENARD sujet à problèmes.\r\nPlusieurs utilisateurs ont fait remonter le problème.\r\nBeaucoup d''alarmes remontent dans la supervision.', 'MISSENARD QUINT', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(138, 1, 'supervision', '2012/06/18', '20:11', 'William BOURGEOIS', 'Incident Majeur', 'CS + Equipe + ROC', 'Réseau MISSENARD sujet à problèmes.\r\nPlusieurs utilisateurs ont fait remonter le problème.\r\nBeaucoup d''alarmes remontent dans la supervision.', 'MISSENARD QUINT', 0, '', '', 0, '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', ''),
(139, 1, 'pinup', '2012/06/21', '09:24', 'Clément ORTIZ', 'Suppression', 'Non', 'Suppression de l''adresse tristan.vialaret@spie.com.', 'MCC', 0, '', '', 0, '', '', 0, '', '', '', 'MCC', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(140, 1, 'pinup', '2012/06/22', '13:20', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Ajout du référentiel CTV ', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', 'Informations principales', '3.5.19.1', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(141, 1, 'pinup', '2012/06/22', '13:21', 'Clément ORTIZ', 'Ajout', 'CS + Equipe + ROC', 'Ajout de plusieurs contacts dans l''escalade', 'IMS', 0, '', '', 0, '', '', 0, '', '', '', 'Escalade', '3.3.14.1', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(142, 1, 'pinup', '2012/06/22', '13:23', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Ajout des numéros de téléphone pour contacter TelView (HO/HNO/etc...)', 'TELVIEW', 0, '', '', 0, '', '', 0, '', '', '', 'Téléphone, ACP', '8.4.8', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(143, 1, 'pinup', '2012/06/21', '21:30', 'Yoann CORBEL', 'Fait Marquant', 'CS + Equipe + ROC', 'Récupération de la supervision chez IMS.', 'IMS', 0, '', '', 0, '', '', 0, '', '', '', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(144, 1, 'systeme', '2012/06/22', '17:24', 'Adrien JAVIERRE', 'Fait Marquant', 'Non', 'erreur de frappe dans le fichier de conf du DHCP de SPIE ==&gt; alarme NAGIOS', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'Client', '-', '', 'Mail', '', ' ', ' ', ' ', ' '),
(145, 1, 'supervision', '2012/06/22', '20:04', 'Yoann CORBEL', 'Fait Marquant', 'Chef Service', 'Appel bizzare provenant de domino''s : Je lui ai que je n’étais pas dominos, elle a raccroché.\r\ncheck boites mail prod-services et sup_hno, aucun mail d’automate arrivé.\r\n', 'Dominos', 0, '', '', 0, '', '', 0, '', '', '', 'aucun', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(146, 1, 'systeme', '2012/06/23', '10:40', 'Enguerrand FOUSSAL', 'Ajout', 'Non', 'Impossible de se connecter pour verification EVA4400 inc 5875528\r\nImpossible de relancer VNC pour TINA  ', 'PEJ PROBLEME', 0, '', '', 0, '', '', 0, '', '', '', ' ', 'CSS', 'VILM et SOGEC', '5875528', 'Mail', '', ' ', ' ', ' ', ' '),
(147, 1, 'supervision', '2012/06/23', '11:35', 'Enguerrand FOUSSAL', 'Ajout', 'Non', 'Host 	Service 	Status 	Last Check 	Duration 	Attempt 	Status Information\r\nGEODIS_PBX_nexspan_bonneuil	\r\n			\r\nPING	\r\n			\r\nCRITICAL	06:06:45	261d 16h 45m 10s	1/1 #1	PING CRITICAL - Packet loss = 100%\r\nGEODIS_PBX_nexspan_collegien	\r\n			\r\nPING	\r\n			\r\nCRITICAL	06:06:45	0d 5h 30m 14s	1/1	PING CRITICAL - Packet loss = 100%\r\nGEODIS_PBX_nexspan_cpb	\r\n			\r\nPING	\r\n			\r\nCRITICAL	06:06:45	415d 14h 40m 58s	1/1	PING CRITICAL - Packet loss = 100%\r\nGEODIS_PBX_nexspan_express	\r\n			\r\nPING	\r\n			\r\nCRITICAL	06:06:46	5d 5h 30m 20s	1/1	PING CRITICAL - Packet loss = 100%\r\nGEODIS_PBX_nexspan_paris	\r\n			\r\nPING	\r\n			\r\nCRITICAL	06:06:46	174d 4h 42m 19s	1/1	PING CRITICAL - Packet loss = 100%\r\n\r\n\r\n5875548 ticket cree pour le n2 probleme de sup vu avec manu \r\n', 'SUP', 0, '', '', 0, '', '', 0, '', '', '', '5875548 ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(148, 1, 'supervision', '2012/06/23', '12:05', 'Enguerrand FOUSSAL', 'Ajout', 'CS + Equipe', 'Bago sur localNagios IMS running	\r\n	\r\nCRITICAL	12:05:48	0d 0h 0m 24s	1/1 #1	local nagios is not running anymore\r\n\r\nPas plus de 2 min ', 'BAGO', 0, '', '', 0, '', '', 0, '', '', '', 'nocase ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(149, 1, 'supervision', '2012/06/24', '08:32', 'Enguerrand FOUSSAL', 'Fait Marquant', 'CS + Equipe', 'Les serveurs de la VILM remontent 1 par 1', 'VILM', 0, '', '', 0, '', '', 0, '', '', '', 'VILM', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(150, 1, 'pinup', '2012/06/25', '15:26', 'Clément ORTIZ', 'Ajout', 'CS + Equipe', 'Ajout des 3 documents de présentation de VeePee', 'VeePee', 0, '', '', 0, '', '', 0, '', '', '', 'VEEPEE - CSS', '8.4.10', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
(151, 1, 'exploitation', '2012/06/26', '18:07', 'Adrien JAVIERRE', 'Fait Marquant', 'Non', 'La DSTI récupère le périmètre réseau de Spie. Cependant il restent encore des interrogations sur le périmètre qui reste à la charge du CSS.', 'SPIE', 0, '', '', 0, '', '', 0, '', '', '', ' ', '-', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

-- --------------------------------------------------------

-- 
-- Structure de la table `jdb_logins`
-- 

CREATE TABLE `jdb_logins` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `actived` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- 
-- Contenu de la table `jdb_logins`
-- 

INSERT INTO `jdb_logins` (`id`, `user`, `name`, `pwd`, `admin`, `actived`) VALUES 
(6, 'k.renaerd', 'Kevin RENAERD', 'Spie', 1, 1),
(7, 'c.ortiz', 'Clément ORTIZ', 'Q5RxDv2', 1, 1),
(8, 'w.bourgeois', 'William BOURGEOIS', 'YoshiBiscuit', 0, 1),
(9, 'jc.foussal', 'Jean-Charles FOUSSAL', 'Spiecom2012', 0, 1),
(10, 'jdb', 'JDB', 'jdb', 0, 1),
(11, 'a.lopes', 'Antoinette LOPES', 'desd2012', 1, 1),
(12, 'g.bouchet', 'Germain BOUCHET', 'keiby19', 0, 1),
(13, 'm.daniel', 'Marcel DANIEL', 'M@rC3L', 0, 1),
(14, 'y.corbel', 'Yoann CORBEL', 'Spie2012', 0, 1),
(15, 'o.baraize', 'Olivier BARAIZE', 'Spiecom', 0, 1),
(16, 'j.marty', 'Jérôme MARTY', 'Azerty13', 0, 1),
(17, 'y.traore', 'Yannick TRAORE', 'jdb', 0, 1),
(18, 'e.foussal', 'Enguerrand FOUSSAL', 'jdb', 0, 1),
(19, 'a.javierre', 'Adrien JAVIERRE', 'jdb', 0, 1),
(20, 'l.florence', 'Lionel FLORENCE', 'jdb', 0, 1),
(21, 'l.blaza', 'Lydie BLAZA', 'jdb', 0, 1),
(22, 'jc.laillon', 'Jean-Claude LAILLON', 'jdb', 0, 1),
(23, 's.renfer', 'Sébastien RENFER', 'jdb', 0, 1),
(24, 'd.poinin', 'David POININ', 'jdb', 0, 1),
(25, 'm.thouvenot', 'Maxime THOUVENOT', 'jdb', 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `jdb_variables`
-- 

CREATE TABLE `jdb_variables` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `com` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL default '1',
  `enable` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `jdb_variables`
-- 

INSERT INTO `jdb_variables` (`id`, `nom`, `value`, `com`, `visible`, `enable`) VALUES 
(1, 'run_crash', '0', '', 0, 0),
(2, 'date', '2012/04/14', '', 0, 0),
(3, 'nb_options_max', '10', 'Nombre maximum de colonne pour une activité', 1, 0),
(4, 'debug', '0', 'Mode débugage', 1, 1),
(5, 'truncate_text_accueil', '100', 'Nb caractère apres lequel le texte est tronqué', 1, 1);
