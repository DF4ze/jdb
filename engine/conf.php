<?php
/////////////////////////////////
/////////////////////////////////
//!\\ Ce fichier doit obligatoirement se trouver à l'emplacement suivant : url_site/engine/conf.php
/////////////////////////////////
/////////////////////////////////

// Serveur MySQL
$_SESSION['server_sql'] = 'localhost';
$_SESSION['user_sql'] = 'root';
$_SESSION['pwd_sql'] = '';
$_SESSION['base_sql'] = 'jdb_v1_11_7';

////// Emplacements
//// "Fichiers de données"
$_SESSION['folder_files'] = "files/";
// Images :
$_SESSION['folder_images'] = $_SESSION['folder_files']."images/";
// Uploads
$_SESSION['folder_uploads'] = $_SESSION['folder_files']."uploads/";
// Rss
$_SESSION['folder_rss'] = $_SESSION['folder_files']."flux/";
// Extracts
$_SESSION['folder_extracts'] = $_SESSION['folder_files']."extracts/";
// Documentations
$_SESSION['folder_documentations'] = $_SESSION['folder_files']."docs/";

//// "Fichiers Moteurs"
$_SESSION['folder_engine'] = "engine/";
// Création de RSS
$_SESSION['folder_phpmyrss'] = $_SESSION['folder_engine']."phpmyrss/";
// Création de fichiers Excel
$_SESSION['folder_write_excel'] = $_SESSION['folder_engine']."writeexcel/";
// Chemin vers le viewer
$_SESSION['viewer_evmt'] = substr_replace( "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'viewer.php?id=', strpos( "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'engine/conf.php' ) );


////// Divers
// temps de refresh de la page d'accueil (en s)
$_SESSION['time_refresh'] = 100;
// Tab contenant les alertes deja poped up.
// $_SESSION['tab_haspop'] = null; //nooooooooooooooooon si est init a chaque refresh ... le tableau sera tt le temps vide et les popup poperont a fusion!
?>