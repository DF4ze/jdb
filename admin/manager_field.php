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
		function auto_post(){
			document.forms['form_filtre_affiche'].submit(); 
		}
		function is_a_select_field(){
			if( document.getElementById('type').value == "select" )
				document.getElementById( 'field_select' ).style.display="inline";
			else
				document.getElementById( 'field_select' ).style.display="none";
		
		}
	</script>
		  
</head>
<body>	
<?php

	// $debug = false;
	$debug = $_SESSION['debug'];
	$nb_options_max = $_SESSION['nb_options_max']; //fleme de replacer dans le code...
	
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
		Gestion des champs :
	</div class="titre">
</div class="titre_general">';	

	
	echo '<div class="menu_options_general">';
	echo '<div class="menu_options_titre">';
	if( isset( $_GET['modif'] ) ){
		if( $_GET['modif'] == '')
			echo "Nouvelle Entrée";
		else
			echo "Modification d'une Entrée";
	}else
		echo "Nouvelle Entrée";
	echo '</div>';


	echo '<div class="menu_options_corps">';
	//////////////////////////////////////
	// Ajout/Modif d'une nouvelle activité
	if( isset( $_POST['go'] ) ){
		$nick = normalyze_string( mysql_real_escape_string(htmlspecialchars($_POST['nom'] )));
		
		if( $_POST['activite'] != '-1' and $_POST['nom'] != '' )
		if( $_POST['modif'] == ''){
			// Calcul de la correspondance.
/* 			$reqLine = "SELECT COUNT( * ) AS nb
							FROM jdb_champs
							WHERE activite = '".$_POST['activite']."' ";
			if( $debug )
				echo "Calcul Corresp : ".$reqLine.'<br/>';
			$req = mysql_fetch_array(mysql_query( $reqLine ));
			if( $debug )
				echo 'Donne le résultat : '.$req['nb'].'<br/>'; */
			// On sait maintenant le nombre d'item,
			// Mais ils peuvent etre de la sorte : 0, 3, 5...
			//On fait le tour  des correpondances de l'activité en question
			$reqLine = "SELECT correspondance 
							FROM jdb_champs
							WHERE activite = '".$_POST['activite']."' ORDER BY correspondance";
			if( $debug )
				echo $reqLine.'<br/>';
				
			$req = mysql_query( $reqLine );
			//On récup tout les N° de correspondance.
			$tab_corresp = array();
			while( $data = mysql_fetch_array( $req )){
				$tab_corresp[ count( $tab_corresp ) ] = $data['correspondance'];
				
				if( $debug )
					echo 'Count Tab Corresp : '.count( $tab_corresp ).' | N° Corresp : '.$data['correspondance'].'<br/>';
			} 
			//On regarde s'il y a un trou... et on rempli s'il y en a un.
			$correspondance = 0;
			$trou = false;
			for( $i=0; $i < count( $tab_corresp ); $i++ ){
				if( $tab_corresp[$i] > $i ){
					$correspondance = $i;
					$trou = true;
				}
			}
			// s'il n'y a pas de trou, on prend la derniere place.
			if( !$trou )
				$correspondance = count( $tab_corresp );
			
			if( $debug )
				echo "Corresp : $correspondance<br/>";
			
			
			
			// Préparation de la requete d'insertion.
			$reqLine = "INSERT INTO jdb_champs VALUES
							('','".mysql_real_escape_string(htmlspecialchars($_POST['nom']))."',
							'".$nick."',
							'".$_POST['activite']."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['commentaire']))."',
							'".$correspondance."',
							'".$_POST['type']."',
							'".$_POST['select']."',";
							
			if( isset( $_POST['oblig'] ) )
				$reqLine .= "'1'";
			else
				$reqLine .= "'0'";
				
			$reqLine .= ",";
			if( isset( $_POST['visible'] ) )
				$reqLine .= "'1')";
			else
				$reqLine .= "'0')";
				
			if( $debug )
				echo "Req Insertion : ".$reqLine.'<br/>';
			// Exécution de la requete.
			if( mysql_query( $reqLine ) )
				$_SESSION['message']= "Insertion avec succes.<br/>";
			else
				$_SESSION['message']= "<strong>Erreur de création du champs</strong><br/>".mysql_error();
			
			
			
		}else{
			$reqLine = "UPDATE jdb_champs SET
							nom = '".mysql_real_escape_string(htmlspecialchars($_POST['nom']))."',
							nick = '".$nick."',
							activite = '".$_POST['activite']."',
							type = '".$_POST['type']."',
							commentaire = '".mysql_real_escape_string(htmlspecialchars($_POST['commentaire']))."',";
			
			if( $_POST['type'] == "select" )				
				$reqLine .= " menu = '".$_POST['select']."',";
			else
				$reqLine .= " menu = '',";
							
			$reqLine .= " is_oblig = ";
			if( isset( $_POST['oblig'] ) )
				$reqLine .= "'1'";
			else
				$reqLine .= "'0'";		

			$reqLine .= ", visible = ";
			if( isset( $_POST['visible'] ) )
				$reqLine .= "'1'";
			else
				$reqLine .= "'0'";
				
			$reqLine .= " WHERE id = '".$_POST['modif']."'";
							
			if( $debug )
				echo $reqLine.'<br/>';

				if( mysql_query( $reqLine ) )
					$_SESSION['message']= "Mise à jour avec succès.<br/>";
				else
					$_SESSION['message']= "<strong>Erreur de modification du champs</strong><br/>".mysql_error();
			
		}
	}

	//////////////////////////////////////
	// Suppression activité
	if( isset( $_GET['conf_suppr'] ) ){
		$_SESSION['message']= "Etes-vous sur de vouloir supprimer : ".$_GET['name'].' ? 
			<a href="manager_field.php?suppr='.$_GET['conf_suppr'].'">OUI</a> ou 
			<a href="manager_field.php">NON</a><br/>';
	}
	if( isset( $_GET['suppr'] ) ){
		$reqLine = "DELETE FROM `jdb_champs` WHERE `id` = ".$_GET['suppr'];
		mysql_query( $reqLine ) 
						or $_SESSION['message']= "Erreur lors de la suppression<br/>".mysql_error();
		$_SESSION['message']= "Suppression avec succès.<br/>";
	}

	
	
	
	
	
	
	
	//////////////////////////////////////////////////
	// Formulaire d'ajout d'un nouveau champ.

	echo '<form method="post" id="form_filtre" name="form_filtre" action="manager_field.php">';
	
	if( isset( $_GET['modif'] ) ){
		$data = mysql_fetch_array(mysql_query( "SELECT * FROM jdb_champs WHERE id='".$_GET['modif']."'" ));
	}
	
		// Nom du champs
		echo '<input type="text" name="nom" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['nom'];
		echo '" placeholder="Nom du champ" title="Nom du champ"/>';
		
		// Activité
		echo '<select name="activite" id="activite" title="Activité à laquelle se rattache ce champ">';
		$req = mysql_query( "SELECT activite, activite_nom FROM jdb_activites" );
		echo '<option value="-1">Activités</option>';
		while( $data2 = mysql_fetch_array( $req ) ){
			echo '<option value="'.$data2['activite'].'"';
			if( isset( $_GET['modif'] ) )
				if( $data['activite'] == $data2['activite'] )
					echo ' selected ';
			echo '>'.$data2['activite_nom'].'</option>';
		}
		echo '</select>';
		
		// Type de champs
		echo '<select name="type" id="type" title="Type de champ" onchange="is_a_select_field();">';
			echo '<option value="text">Texte simple</option>';
			echo '<option value="textarea">Zone Texte</option>';
			echo '<option value="select">Menu Déroulant</option>';
		echo '</select>';

		// Si menu déroulant : Items du menu déroulant
		echo '<div id="field_select" style="display:none;">';
		echo '<input type="text" name="select" id="select" placeholder="Items du menu déroulant" title="; ou , pour séparer les entrées"/>';
		echo '</div>';
		
		// Commentaire
		echo '<input type="text" name="commentaire" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['commentaire'];
		echo '" placeholder="Commentaires" title="Commentaire sur le champ"/>';
		
		echo '<input type="hidden" name="modif" value="';
		if( isset( $_GET['modif'] ) )
			echo $_GET['modif'];
		echo '"/>';
		
		if( isset( $_POST['filtre_activite'] ) )
			echo '<input type="hidden" name="filtre_activite" value="'.$_POST['filtre_activite'].'" /><br/>';
		
		// Visible?
		echo '<input type="checkbox" name="visible" ';
		if( isset( $_GET['modif'] )){
			if( $data['visible'] == '1' )
				echo 'checked';
		}else
			echo 'checked';
		echo' />Visible? ';
		
		// Obligatoire?
		echo '<input type="checkbox" name="oblig" ';
		if( isset( $_GET['modif'] ))
			if( $data['is_oblig'] == '1' )
				echo 'checked';
		echo' />Champ Obligatoire? ';
		
		echo '<input type="submit" name="go" value="Enregistrer" class="create"/>';
	
	echo '</form>';
	echo '</div class="corps_menu">';
	echo '</div class="grand_menu">';



	
	
	
	
	
	
	
	
	
	
	
	echo '<div class="corps_general">';
	echo '<div class="corps_titre">';
		echo "Listing";
	echo '</div>';

	echo '<div class="corps_corps">';
	
	//////////////////////////
	// Posibilité de filtrer l'affichage pour n'afficher qu'un certaine activité.
	echo '<form method="post" id="form_filtre_affiche" name="form_filtre_affiche" action="manager_field.php" >';
		echo '<select name="filtre_activite" id="filtre_activite" onchange="auto_post();">';
			echo '<option value="-1">Toute les activités</option>';
		
			$req = mysql_query( "SELECT activite, activite_nom FROM jdb_activites" );
			while( $data = mysql_fetch_array( $req ) ){
				echo '<option value="'.$data['activite'].'"';
				if( isset( $_POST['filtre_activite'] ) )
					if( $_POST['filtre_activite'] == $data['activite'] )
						echo ' selected ';
				echo '>'.$data['activite_nom'].'</option>';
			}
		
		echo '</select>';
		//echo '<input type="submit" value="Go!" />';
	echo '</form>';

	///////////////////////////////////////////////
	// Listing des activité.
	
	echo '<table>';
	echo '<tr>';
		echo '<th > </th>';
		echo '<th > </th>';
		echo '<th> Nom </th>';
		echo '<th> Activité </th>';
		echo '<th> Commentaire </th>';
		echo '<th> Visible </th>';
		echo '<th> Obligatoire </th>';
		echo '<th> Type Champs</th>';
		echo '<th> Valeurs Menu Déroulant</th>';
	echo '</tr>';
	
	$reqLine = "SELECT * FROM jdb_champs";
	if( isset( $_POST['filtre_activite'] ) )
		if( $_POST['filtre_activite'] != '-1' )
			$reqLine .= " WHERE activite='".$_POST['filtre_activite']."'";
		
	if( $debug )
		echo $reqLine.'<br/>';
		
	$req = mysql_query( $reqLine );
	$count_line = 0;
	while( $data = mysql_fetch_array( $req )){
		$is_pair = is_pair( $count_line++ );
		echo '<tr>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_field.php?modif='.$data['id'].'" title="Modifier"><img src="../'.$_SESSION['folder_images'].'edit.gif" /></a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_field.php?conf_suppr='.$data['id'].'&name='.$data['nom'].'" title="Supprimer"><img src="../'.$_SESSION['folder_images'].'delete.gif" /></a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['nom'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['activite'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['commentaire'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';if( $data['visible'] == '1' ) echo '<img src="../'.$_SESSION['folder_images'].'true.gif">'; else echo '<img src="../'.$_SESSION['folder_images'].'false.gif">'; echo '</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';if( $data['is_oblig'] == 1 ) echo '<img src="../'.$_SESSION['folder_images'].'true.gif">'; else echo '<img src="../'.$_SESSION['folder_images'].'false.gif">'; echo '</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';
			switch( $data['type'] ){
				case "text" : echo "Texte"; break;
				case "textarea" : echo "Zone Texte"; break;
				case "select" : echo "Menu Déroulant"; break;
			}
			echo '</td>';
			if( $data['type'] == "select" ){
				echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['menu'].'</td>';
			}
		echo '</tr>';
	}
	
	
	echo '</table>';

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