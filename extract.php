<?php session_start();
	require_once( 'engine/conf.php' );
	require_once( $_SESSION['folder_engine'].'functions.php' );
	// sources : http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
	require_once( $_SESSION['folder_write_excel'].'class.writeexcel_workbook.inc.php' );
	require_once( $_SESSION['folder_write_excel'].'class.writeexcel_worksheet.inc.php' );
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_SESSION['folder_images'] ?>jdbico.ico" />
	<title><?php echo "Journaux de Bord CSS" ?></title>
	<?php
		if( isset( $_SESSION['logued'] ) ){
			if( !$_SESSION['logued']  )
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
			else
				if( !isset( $_GET['query'] ) )
					echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
		}else
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
				
	?>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
</head>
<body>	


<?php

	affiche_menu( __FILE__ );
	echo '<div class="corps_background">';

	///////////////////////////////////////////////////////
	// Connexion My SQL
	if( @mysql_connect( $_SESSION['server_sql'], $_SESSION['user_sql'], $_SESSION['pwd_sql']) ){
		if( $debug ) 
			echo "Connexion au Serveur MySQL ".$_SESSION['server_sql']." avec succes.<br/>";
		// Alors on lance la connexion à la base.
		mysql_select_db($_SESSION['base_sql']) or die ("Connexion à la base MySql ".$_SESSION['server_sql']." impossible");
		if( $debug )
			echo "Connexion a la base MySQL ".$_SESSION['server_sql']." avec succes.<br/>";
	}else
		die( "Erreur de connexion au serveur MySql ".$_SESSION['server_sql']."." );
	///////////////////////////////////////////////////////	


	// Affichage
	echo '<div class="bidon"><div class = "corps_general">';
	echo '<div class = "corps_titre">';
	echo "Extraction :<br/>";
	echo '</div>';
	echo '<div class = "corps_corps">';


	try {
	//On indique ensuite un emplacement sur le serveur, là où; sera stocké le fichier
	$fname = $_SESSION['folder_extracts'].$_SESSION['user']."_extraction.xls";
	
	if( $_SESSION['debug'] )
		echo 'Nom du fichier : '.$fname.'<br/>';
	 
	/**
	 * on instancie la classe principal de writeexcel.
	 * la classe  « writeexcel_workbook » permet de créer le fichier excel en lui même.
	 * La fonction  addworksheet de la class « writeexcel_workbook » permet de créer une feuille au sein du fichier Excel ( Vous savez les petit onglet en bas à droite )
	 */
	$workbook =& new writeexcel_workbook($fname); // on lui passe en paramètre le chemin de notre fichier
	$worksheet =& $workbook->addworksheet('Extraction'); //le paramètre ici est le nom de la feuille
	 
	 /**
	  * Ici on va définir un format pour les colonnes
	  */
	if( $_SESSION['debug'] )
		echo "Préparation de l'entete<br/>";
		
	$worksheet->set_column('A:P', 15); // le 15 représente la largeur de chaque colonne
	$worksheet->set_column('B:B', 10); 
	$worksheet->set_column('C:C', 6); 
	$worksheet->set_column('G:G', 50); 
	$worksheet->set_column('H:H', 30); 
	$heading  =& $workbook->addformat(array('bold' => 1, // on met le texte en gras
											'color' => 'black', // de couleur noire
											'size' => 12, // de taille 12
											'merge' => 1, // avec une marge
											'fg_color' => 0x33 // coloration du fond des cellules
											));
	 
	$headings = $_SESSION['header']; //array('Nom', 'Prénom', 'société', 'Email', 'Pays'); //définition du texte pour chaque célulles
	$worksheet->write_row('A1', $headings, $heading); //On intègre notre texte et les le format de cellule.
	

	// Corps du tableau
	if( $_SESSION['debug'] )
		echo "Insciption des valeurs dans le fichier.<br/>reqLine : ".$_SESSION['reqLine'].'<br/>';
		
	$req = mysql_query( $_SESSION['reqLine'] );
	$i=2;
	while( $data = mysql_fetch_array( $req )){
		$lettre = 'A';
		// Entete de base
		$worksheet->write($lettre++.$i, $data['activite']);
		$worksheet->write($lettre++.$i, reverse_date($data['date']));
		$worksheet->write($lettre++.$i, $data['heure']);
		$worksheet->write($lettre++.$i, $data['auteur'] );
		$worksheet->write($lettre++.$i, $data['evenement'] );
		$worksheet->write($lettre++.$i, $data['communication']);
		$worksheet->write($lettre++.$i, $data['description']);
		$worksheet->write($lettre++.$i, $data['client']);
		
		// Options de l'activité si necessaire
		if( $_GET['query'] == 'detail' )
		for( $x=0; $x < $_SESSION['nb_options_max']; $x++ ){
			$worksheet->write($lettre++.$i, $data["$x"]);
		} 
		$i++;
	}
	
	if( $_SESSION['debug'] )
		echo "Fermeture du fichier.<br/><br/>";

	$workbook->close(); // on ferme le fichier Excel créer
	/* 
	Fin de la génération du document
	 */
	 

	//On propose le téléchargement
	echo '<strong>Génération du fichier avec succes.</strong><br/><br/>Le téléchargement est disponible <a href="'.$fname.'"> ICI </a><br/>';

	 
	} catch( Exception $e ){
		echo "<strong>Une erreur s'est produite lors de la génération du fichier.</strong><br/><br/> Détails : ".$e->getMessage()."<br/>";
	}
	
	echo '</div>';
	echo '</div>';
	echo '</div>';
	
	
	echo '</div class="corps_background">';
	affiche_pied();
	?>

</body>
</html>