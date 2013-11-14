<?php session_start(); 
	require_once( "../engine/conf.php" );
	require_once( "../".$_SESSION['folder_engine']."functions.php" );
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" type="image/x-icon" href="../<?php echo $_SESSION['folder_images'] ?>jdbico.ico" />
	<title><?php echo "Journaux de Bord CSS" ?></title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="../design.css" />
	<?php
		$redir = '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=../index.php">';
		if( isset( $_SESSION['logued'] ) ){
			if( !$_SESSION['logued']  )
				echo $redir;
			else
				if( !$_SESSION['admin'] )
					echo $redir;
		}else
			echo $redir;
				
	?>

	
	<script type="text/javascript">
		function montre_div(nom_div)
		{
			if( document.getElementById(nom_div).style.display == "none" )
				document.getElementById(nom_div).style.display="block";
			else
				document.getElementById(nom_div).style.display="none";
		}
	</script>
</head>
<body>	
<?php

	// $debug = false;
	$debug = $_SESSION['debug'];
	$nb_options_max = 10;
	
	///////////////////////////
	// Variables Réceptionnées
	if( $debug ){
		echo "Variables : <br/>";
		foreach( $_POST as $key => $value  ){
			echo '$_POST['.$key.'] = '.$value.'<br/>';
		} 
		echo '<br/>';
	}
	

	
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
	
	affiche_menu( __FILE__ );
	echo '<div class="corps_background">';
	
	
	echo '<div class="titre_general">
	<div class="titre">
		Gestion des Activités :
	</div class="titre">
</div class="titre_general">';	

	
	echo '<div class="menu_options_general">';
	echo '<div class="menu_options_titre">';
	if( isset( $_GET['modif'] ) ){
		if( $_GET['modif'] == '')
			echo "Nouvelle Activité";
		else
			echo "Modification d'une Activité";
	}else
		echo "Nouvelle Entrée";
	echo '</div>';


	echo '<div class="menu_options_corps">';
	//////////////////////////////////////
	// Ajout/Modif d'une nouvelle activité
	if( isset( $_POST['go'] ) ){
		$_POST['activite'] = normalyze_string( $_POST['activite_nom'] );
		
		if( $_POST['activite_nom'] != '' )
		if( $_POST['modif'] == ''){
			$reqLine = "INSERT INTO jdb_activites VALUES
							('','".mysql_real_escape_string(htmlspecialchars($_POST['activite_nom']))."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['activite']))."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['commentaire']))."')";
					
			if( $debug )
				echo $reqLine.'<br/>';
				
			mysql_query( $reqLine ) 
							or $_SESSION['message']= "Erreur de création de l'évènement<br/>".mysql_error();
			$_SESSION['message'] =  "Insertion avec succes.<br/>";
		}else{
			$reqLine = "UPDATE jdb_activites SET
							activite_nom = '".mysql_real_escape_string(htmlspecialchars($_POST['activite_nom']))."',
							activite = '".mysql_real_escape_string(htmlspecialchars($_POST['activite']))."',
							commentaire = '".mysql_real_escape_string(htmlspecialchars($_POST['commentaire']))."' WHERE 
							id = '".$_POST['modif']."'";
							
			if( $debug )
				echo $reqLine.'<br/>';
					mysql_query( $reqLine ) 
							or $_SESSION['message']= "Erreur de création de l'évènement<br/>".mysql_error();
			$_SESSION['message'] = "Mise à jour avec succès.<br/>";
		}
	}

	//////////////////////////////////////
	// Suppression activité
	if( isset( $_GET['conf_suppr'] ) ){
		$_SESSION['message'] =  "Etes-vous sur de vouloir supprimer : ".$_GET['name'].' ? 
			<a href="manager_activity.php?suppr='.$_GET['conf_suppr'].'">OUI</a> ou 
			<a href="manager_activity.php">NON</a><br/>';
	}
	if( isset( $_GET['suppr'] ) ){
		$reqLine = "DELETE FROM `jdb_activites` WHERE `id` = ".$_GET['suppr'];
		mysql_query( $reqLine ) 
						or $_SESSION['message']= "Erreur lors de la suppression<br/>".mysql_error();
		$_SESSION['message']= "Suppression avec succès.<br/>";
	}

	
	
	
	
	
	
	
	//////////////////////////////////////////////////
	// Formulaire d'ajout d'une nouvelle activité.

	echo '<form method="post" id="form_filtre" name="form_filtre" action="manager_activity.php">';
	
	if( isset( $_GET['modif'] ) ){
		$data = mysql_fetch_array(mysql_query( "SELECT * FROM jdb_activites WHERE id='".$_GET['modif']."'" ));
	}
	
		echo '<input type="text" name="activite_nom" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['activite_nom'];
		echo '" placeholder="Nom de l\'activité"/>';
		// echo '<input type="text" name="activite" value="';
		// if( isset( $_GET['modif'] ) )
			// echo $data['activite'];
		// echo '" placeholder="NickName de l\'activité"/>';
		echo '<input type="text" name="commentaire" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['commentaire'];
		echo '" placeholder="Commentaires"/>';
		echo '<input type="hidden" name="modif" value="';
		if( isset( $_GET['modif'] ) )
			echo $_GET['modif'];
		echo '"/>';
		echo '<input type="submit" name="go" value="Go!" class="create"/>';
	
	echo '</form>';
	echo '</div class="corps_menu">';
	echo '</div class="grand_menu">';


	
	
	
	
	
	
	

	echo '<div class="corps_general">';
	echo '<div class="corps_titre">';
		echo "Listing";
	echo '</div>';


	echo '<div class="corps_corps">';
	///////////////////////////////////////////////
	// Listing des activité.
	
	echo '<div class="bidon">';
	echo '<table>';
	echo '<tr>';
		echo '<th > </th>';
		echo '<th > </th>';
		echo '<th> Nom </th>';
		echo '<th> Nick </th>';
		echo '<th> Commentaires </th>';
	echo '</tr>';
	
	$reqLine = "SELECT * FROM jdb_activites";
	$req = mysql_query( $reqLine );
	$count_line = 0;
	while( $data = mysql_fetch_array( $req )){
		$is_pair = is_pair( $count_line++ );
		echo '<tr>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_activity.php?modif='.$data['id'].'" title="Editer"><img src="../'.$_SESSION['folder_images'].'edit.gif" /> </a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_activity.php?conf_suppr='.$data['id'].'&name='.$data['activite_nom'].'" title="Supprimer"><img src="../'.$_SESSION['folder_images'].'delete.gif" /></a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['activite_nom'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['activite'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['commentaire'].'</td>';
		echo '</tr>';
	}
	
	
	echo '</table>';
	echo '</div>';

	echo '</div >';
	echo '</div >';
	
	if( isset( $_SESSION['message'] ) )
	if( $_SESSION['message'] != '' ){
		echo '<div class="message" id="message">';
		echo '<div class="message_titre">';
		echo '<a href="#" onclick="montre_div(\'message\');" style="float:right; padding-right:5px">[ X ]</a>';
		echo 'Information : <br />';
		echo '</div>';
		echo '<div class="message_corps">';
		echo '<p>'.$_SESSION['message'].'</p>';
		echo '</div></div>';
		$_SESSION['message'] = '';
	}

	echo '</div class="corps_background">';
	affiche_pied();

?>
</body>
</html>