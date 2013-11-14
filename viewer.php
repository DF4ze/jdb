<?php 
session_start();	
	require_once( 'engine/conf.php' );
	require_once( $_SESSION['folder_engine'].'functions.php' );
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
		}else
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
				
	?>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />

	<script type="text/javascript">
	function Couleur( id ) 
	{
		fenetreA=window.open("alert_info.php?id="+id,"Alerte Infos","status=no,location=no,toolbar=no,directories=no,resizable=no,left=100,top=10,width=400,height=210,top=100,left=100");
		fenetreA.focus();
	} 
	</script >

	  
</head>
<body>	

<?php

	affiche_menu( __FILE__ );

	echo '<div class="corps_background">';

	
	
	echo '<div class="titre_general">
	<div class="titre">
		Visualisation des Evènements :
	</div class="titre">
</div class="titre_general">';
	
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
	

	connect_bdd();

	///////////////////////////
	// finte pour renvoyer tout les $_POST
	echo "<script type=\"text/javascript\">
			function repost_asc(valeur){
				document.forms['form_filtre'].asc.value = valeur;
				document.forms['form_filtre'].desc.value = '';
				document.forms['form_filtre'].submit(); 
			}
			function repost_desc(valeur){
				document.forms['form_filtre'].asc.value = '';
				document.forms['form_filtre'].desc.value = valeur;
				document.forms['form_filtre'].submit(); 
			}
			function grease(){
				if( document.forms['form_filtre'].use_date2.checked )
					document.forms['form_filtre'].date2.disabled = false;
				else
					document.forms['form_filtre'].date2.disabled = true;					
			}
			</script>";
			
	// echo '<form id="form_repost" name="form_repost" method="post" action="viewer.php">';
		// foreach( $_POST as $key => $value  ){
			// if( $key != "asc" and $key != "desc" )
				// echo '<input type="hidden" name="'.$key.'" id ="'.$key.'" value="'.$value.'"/>';
		// } 
		// echo '<input type="hidden" name="asc" />';
		// echo '<input type="hidden" name="desc" />';
	// echo '</form>';

	
	///////////////////////////
	// Début du formulaire.
	
	echo '<div class = "menu_options_general">';
	echo '<form method="post" id="form_filtre" name="form_filtre" action="viewer.php">';
	
	echo '<div class = "menu_options_titre">';
	echo "Filtres :<br/>";
	echo '</div class = "bandeau_menu">';


	echo '<div class = "menu_options_corps">';

	///////////////////////////
	// Filtre Dates
	echo '<div class = "petit_menu">';
	echo "Dates :  <br/>";
	
	// Recherche des dates de la semaine précédente.

	
	echo '<input type="checkbox" name="check_last_week" ';
	if( isset( $_POST['check_last_week'] )){
		echo "checked";

		if( $debug )
			echo "Semaine : ".date('W')." Année : ".date('Y').'<br/>';

		$Tab_Jour = get_semaine_voulue( date('W')-1, date('Y') );
		if( $debug )
				echo '1er Jour : Lundi '.$Tab_Jour[0].' Dernier Jour : Dimanche  '.$Tab_Jour[6].'<br/>';

		$_POST['date1'] = reverse_date( $Tab_Jour[0] );
		$_POST['date2'] = reverse_date( $Tab_Jour[6] );
		$_POST['use_date2'] = true;
	}	
	echo ' /> Semaine derniere<br/>';
	
	echo '<input type="text" name="date1" value="';
	if( isset( $_POST['date1'] ) )
		echo $_POST['date1'];
	else
		echo 'tout';
	echo '"/> 1ere date<br/>';
	
	echo '<input type="checkbox" name="use_date2" id="use_date2" onclick="grease();" ';
	if( isset( $_POST['use_date2'] ) )	
		echo 'checked';
	echo ' />'; 
	
	echo '<input type="date" name="date2" value="';
	if( isset( $_POST['date2'] ) )
		echo $_POST['date2'];
	echo '"';
	if( !isset( $_POST['use_date2'] ) )
		echo ' disabled ';
	echo '/> 2eme date<br/>';
	echo '</div class = "petit_menu">';
	
	///////////////////////////
	// Filtre Activité
	echo '<div class = "petit_menu">';
	echo "Activités :<br/>";
	echo '<select name="activite" id="activite" >
				<option value="tout">Tout</option>';
	
	// Tour de la BDD,
	$reqLine = "SELECT DISTINCT activite FROM jdb_evenements ORDER BY activite ASC";
	$req = mysql_query( $reqLine );
	// On en profite pour enregistrer cette requete pour éviter de consulter la BDD a chaque fois qu'on a besoin de la liste d'activité.
	//$tab_activite = array();
	while( $data = mysql_fetch_array( $req )){
		// On met dans le tableau d'activité comme ca plus besoin de redemander cette requete.
		//$tab_activite[ $data['activite'] ] = $data['activite_nom'];
		
		echo '<option value="'.$data['activite'].'"';
		if( isset( $_POST['activite'] ) )
			if( $_POST['activite'] == $data['activite'] )
				echo ' selected';
			
		$data1 = mysql_fetch_array( mysql_query( "SELECT activite_nom FROM jdb_activites WHERE activite = '".$data['activite']."' ") );
		echo '>'.$data1['activite_nom'].'</option>';
	}

	// foreach( $tab_activite as $activite => $activite_nom  ){
		// echo '<option value="'.$activite.'">'.$activite_nom.'</option>';
	// } 
	echo '</select>';
	echo '</div class = "petit_menu">';
	
	
	///////////////////////////
	// Filtre Auteurs

	echo '<div class = "petit_menu">';
	echo "Auteurs :<br/>";
	echo '<select name="auteur" id="auteur" >
				<option value="tout">Tout</option>';
	
	// Tour de la BDD,
	$reqLine = "SELECT DISTINCT auteur FROM jdb_evenements ORDER BY auteur ASC";
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		echo '<option value="'.$data['auteur'].'"';
		if( isset( $_POST['auteur'] ) )
			if( $_POST['auteur'] == $data['auteur'] )
				echo ' selected';
		echo '>'.$data['auteur'].'</option>';
	}

	// foreach( $tab_activite as $activite => $activite_nom  ){
		// echo '<option value="'.$activite.'">'.$activite_nom.'</option>';
	// } 
	echo '</select>';
	echo '</div>';
	
	
	///////////////////////////
	// Filtre Evènements
	echo '<div class = "petit_menu">';
	echo "Evènements :<br/>";
	echo '<select name="evenement" id="evenement" >
				<option value="tout">Tout</option>';
	
	// Tour de la BDD,
	$reqLine = "SELECT DISTINCT evenement FROM jdb_evenements ORDER BY evenement ASC";
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		echo '<option value="'.$data['evenement'].'"';
		if( isset( $_POST['evenement'] ) )
			if( $_POST['evenement'] == $data['evenement'] )
				echo ' selected';
		echo '>'.$data['evenement'].'</option>';
	}

	// foreach( $tab_activite as $activite => $activite_nom  ){
		// echo '<option value="'.$activite.'">'.$activite_nom.'</option>';
	// } 
	echo '</select>';
	echo '</div class = "petit_menu">';
	

	///////////////////////////
	// Filtre Communication
	echo '<div class = "petit_menu">';
	echo "Communication :<br/>";
	echo '<select name="communication" id="communication" >
				<option value="tout">Tout</option>';
	
	// Tour de la BDD,
	$reqLine = "SELECT DISTINCT communication FROM jdb_evenements ORDER BY evenement ASC";
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		echo '<option value="'.$data['communication'].'"';
		if( isset( $_POST['communication'] ) )
			if( $_POST['communication'] == $data['communication'] )
				echo ' selected';
		echo '>'.$data['communication'].'</option>';
	}

	// foreach( $tab_activite as $activite => $activite_nom  ){
		// echo '<option value="'.$activite.'">'.$activite_nom.'</option>';
	// } 
	echo '</select>';
	echo '</div class = "petit_menu">';
	

	///////////////////////////
	// Filtre Client
	echo '<div class = "petit_menu">';
	echo "Client :<br/>";
	echo '<select name="client" id="client" >
				<option value="tout">Tout</option>';
	
	// Tour de la BDD,
	$reqLine = "SELECT DISTINCT client FROM jdb_evenements ORDER BY client ASC";
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		echo '<option value="'.$data['client'].'"';
		if( isset( $_POST['client'] ) )
			if( $_POST['client'] == $data['client'] )
				echo ' selected';
		echo '>'.$data['client'].'</option>';
	}

	echo '</select>';
	echo '</div class = "petit_menu">';
	

	///////////////////////////
	// Filtre "Recherche"
 	echo '<div class = "petit_menu">';
	echo "Mot Clé :<br/>";
	echo '<input type="text" name="finder" id="finder"';
	if( isset( $_POST['finder'] ) )
		echo ' value="'.$_POST['finder'].'" ';
	echo '/><br/>';
	
	echo '</div class = "petit_menu">';
	
	
	
	
	
	
	
	// Pour le tri croissant ou décroissant
	echo '<input type="hidden" name="asc" />';
	echo '<input type="hidden" name="desc" value="date"/>'; // Permet, par defaut, d'afficher les dernieres entrées.
	echo '<input type="hidden" name="go" value="gogo" />';

	echo '<br/><br/><br/><br/><br/><br/><br/><input type="submit" name="go_filtres" value="Go!" class="create"/>';
	
	echo '</form>';
	
	echo '</div>';
	echo '</div>';
	
	
	








	
	
	
	///////////////////////////////////
	// Reception des variables de Filtrage
	
	if( isset( $_POST['go_filtres'] ) or isset( $_POST['go'] ) or isset( $_GET['id'] )){
		
		
		echo '<div class = "corps_general" >';
		echo '<div class = "corps_titre">';
		echo "Résultats :<br/>";
		echo '</div>';
		
		echo '<div class = "corps_corps">';
		
		// Lien vers le fichier d'extract
		
		// Il faut différencier s'il faut afficher le detail de l'activité ou si non.
		// (probleme dans la gestion du fichier Excel,
		
		if( @$_POST['activite'] != 'tout')
			echo 'Téléchargez ce tableau au format Excel : <a href="extract.php?query=detail"> <img src="'.$_SESSION['folder_images'].'disquette.jpg"></a><br/><br/>';
		else
			echo 'Téléchargez ce tableau au format Excel : <a href="extract.php?query=simple"> <img src="'.$_SESSION['folder_images'].'disquette.jpg"></a><br/><br/>';
			
				
		
		// Création des entetes du tableau
		// 1ere partie : Affichage des colonne générales.
		// echo '<table>
			   // <tr>
				   // <th>Activité</th>
				   // <th>Date</th>
				   // <th>Heure</th>
				   // <th>Auteur</th>
				   // <th>Evènement</th>
				   // <th>Communication</th>
				   // <th>Description</th>';
				   // <th>Client</th>';
		
		
		// Création du tableau d'entete pour la création du fichier excel
		$header = array( 	0 => 'Activité',
							1 => 'Date',
							2 => 'Heure',
							3 => 'Auteur',
							4 => 'Evènement',
							5 => 'Communication',
							6 => 'Description',
							7 => 'Client'
						);
		
		echo '<table>
			   <tr>';
		// SI on est admin on affiche 3 colonnes vide pour la suppression, la modif ou le detail.
		//if( $_SESSION['admin'] )
			echo '<th ></th>
					<th ></th>
					<th ></th>';
			
			
		echo '<th>';
		// Filtre Activité
		if( @$_POST['asc'] == 'activite' )
			echo '<a href="javascript:repost_desc(\'activite\');">';
			//echo '<a href="viewer.php?desc=activite" onclick="repost();">';
		else
			echo '<a href="javascript:repost_asc(\'activite\');">';
			//echo '<a href="viewer.php?asc=activite" onclick="repost();">';
		echo 		'Activité</a></th>';
		// Filtre Date
		echo 	   	'<th>';
		if( @$_POST['asc'] == 'date' )
			echo '<a href="javascript:repost_desc(\'date\');">';
		else
			echo '<a href="javascript:repost_asc(\'date\');">';
		echo		'Date</a></th>';
		// filtre heure
		echo	   	'<th>';
		if( @$_POST['asc'] == 'heure' )
			echo '<a href="javascript:repost_desc(\'heure\');">';
		else
			echo '<a href="javascript:repost_asc(\'heure\');">';
		echo		'Heure</th>';
		// Filtre Auteur
		echo 		'<th>';
		if( @$_POST['asc'] == 'auteur' )
			echo '<a href="javascript:repost_desc(\'auteur\');">';
		else
			echo '<a href="javascript:repost_asc(\'auteur\');">';
		echo 		'Auteur<a/></th>';
		// Filtre Evènement
		echo 		'<th>';
		if( @$_POST['asc'] == 'evenement' )
			echo '<a href="javascript:repost_desc(\'evenement\');">';
		else
			echo '<a href="javascript:repost_asc(\'evenement\');">';
		echo 		'Evènement<a/></th>';
		// Filtre Communication
		echo 		'<th>';
		if( @$_POST['asc'] == 'communication' )
			echo '<a href="javascript:repost_desc(\'communication\');">';
		else
			echo '<a href="javascript:repost_asc(\'communication\');">';
		echo 		'Communication<a/></th>';
		// Filtre Description
		echo 		'<th>';
		if( @$_POST['asc'] == 'description' )
			echo '<a href="javascript:repost_desc(\'description\');">';
		else
			echo '<a href="javascript:repost_asc(\'description\');">';
		echo 		'Description<a/></th>';
		// Filtre Client
		echo 		'<th>';
		if( @$_POST['asc'] == 'client' )
			echo '<a href="javascript:repost_desc(\'client\');">';
		else
			echo '<a href="javascript:repost_asc(\'client\');">';
		echo 		'Client<a/></th>';
		
		// 2eme partie : Affichage des options spécifiques de l'activite
		if( @$_POST['activite'] != 'tout' or isset( $_GET['id'] )){
		
			if( isset( $_GET['id'] ) ){
				// On recherche de quelle activité fait partie l'ID.
				$temp_data = mysql_fetch_array( mysql_query( "SELECT activite FROM jdb_evenements WHERE id='".$_GET['id']."'" ) );
				// On récupère le nom des colonnes
				$reqLine = "SELECT nom, correspondance FROM jdb_champs WHERE activite='".$temp_data['activite']."'";
				// Pour afficher les valeurs
				$_POST['activite'] = $temp_data['activite'];
			}else
				$reqLine = "SELECT nom, correspondance FROM jdb_champs WHERE activite = '".$_POST['activite']."' AND visible = '1' ORDER BY correspondance ASC";
			
			if( $debug )
				echo $reqLine.'<br/>';
			
			$req = mysql_query( $reqLine );
			$i = 8; // Valeur de l'entete !!! pour le fichier excel.
			while( $data = mysql_fetch_array( $req )){
				//echo '<th>'.$data['nom'].'</th>';
				
				echo 		'<th>';
				if( $_POST['asc'] == $data['correspondance'] )
					echo '<a href="javascript:repost_asc(\''.$data['correspondance'].'\');">';
				else
					echo '<a href="javascript:repost_desc(\''.$data['correspondance'].'\');">';
				echo 		$data['nom'].'<a/></th>';
				
				$header[$i] = $data['nom'];
				$i++;
			}
		}
			echo '<th></th>';
		echo '</tr>';
		
		
		// Affichage des données.
		// Préparation de la requete.
		$reqLine = "";
		if( isset( $_GET['id'] ) ){
			$reqLine .= "SELECT * FROM jdb_evenements WHERE id='".$_GET['id']."'";
		}else if( $_POST['activite'] != 'tout' ){
			$reqLine .= 'SELECT * FROM jdb_evenements';
		}else{
			$reqLine .= 'SELECT * FROM jdb_evenements';
		}
		
		// Vérif s'il y a un filtre
		$compte = 0;
		if( !isset( $_GET['id'] ) )
		foreach( $_POST as $key => $value  ){
			if( $value != 'tout' ){
				switch( $key ){
					case 'activite' :
					case 'auteur' :
					case 'evenement' :
					case 'communication' :
					case 'client' :
						if( $compte == 0 )
							$reqLine .= ' WHERE ';
						else
							$reqLine .= ' AND ';
						$reqLine .= $key." = '".$value."'";
						$compte++;
						break;
					default :
						break;
				}
			}
		} 	
		
		// Gestion des dates:
		if( isset( $_POST['date1'] ) )
		if( $_POST['date1'] != 'tout' and $_POST['date1'] != ''){
			if( $compte == 0 )
				$reqLine .= ' WHERE ';
			else
				$reqLine .= ' AND ';
				
			if( $_POST['use_date2'] and $_POST['date2'] != ''){
				$reqLine .= 'date BETWEEN \''.reverse_date( $_POST['date1'] ).'\' AND \''.reverse_date( $_POST['date2'] ).'\'';
			}else
				$reqLine .= 'date = \''.reverse_date( $_POST['date1'] ).'\'';
		}
		
		// Gestion du/des (?) mot(s) clé(s)
		if( @$_POST['finder'] != '' ){
			if( $compte == 0 )
				$reqLine .= ' WHERE ';
			else
				$reqLine .= ' AND ';
				
			//"
			$reqLine .= " CONCAT_WS( description, ' ', `0`, ' ', `1`, ' ', `2`, ' ', `3`, ' ', `4`, ' ', `5`, ' ', `6`, ' ', `7`, ' ', `8`, ' ', `9`)
							LIKE '%".$_POST['finder']."%' ";
		}
		
		
		// Order By ....
		foreach( $_POST as $key => $value  ){
			if( $key == 'asc' ){
				if( $value != '' )
					$reqLine .= ' ORDER BY `'.$value.'` ASC';
			}else if( $key == 'desc' ){
				if( $value != '' )
					$reqLine .= ' ORDER BY `'.$value.'` DESC';
			}
		} 
		
		if( $debug )
			echo "reqLine : $reqLine<br/>";
		
		

		// Variables pour le traitement de l'extract.
		$_SESSION['reqLine'] = $reqLine;
		$_SESSION['header'] = $header;
		
		// 4eme Partie : Affichage des données.
		$count_line = 0;
		if( $req = mysql_query( $reqLine ) )
		while( $data = mysql_fetch_array( $req )){
			$is_pair = is_pair( $count_line++ );
			echo '<tr>';
			// SI on est admin on affiche Les colonnes pour la suppression et la modif pour toute les entrées.
			// sinon on ne peut modifier que les entrées réalisées par l'user logué.
			if( $_SESSION['admin'] or $_SESSION['name'] == $data['auteur'] ){
				echo '<td style="border:none;'; if( $is_pair ) echo 'background-color:#E2EAEB;'; echo '"><a href="viewer.php?conf_suppr='.$data['id'].'" title="Supprimer"><img src="'.$_SESSION['folder_images'].'delete.gif"></a></td>';
				echo '<td style="border:none;';	if( $is_pair ) echo 'background-color:#E2EAEB;'; echo '"><a href="insert.php?modif='.$data['id'].'" title="Editer"><img src="'.$_SESSION['folder_images'].'edit.gif" /></a></td>';
			}else
				echo '<th style="border:none; background:none;"></th>
					<th style="border:none; background:none;"></th>';
			// Pour tout le monde : Possibilité de voir le detail.
			echo '<td style="border:none;';	if( $is_pair ) echo 'background-color:#E2EAEB;'; echo '"><a href="viewer.php?id='.$data['id'].'" title="Détail"><img src="'.$_SESSION['folder_images'].'view.gif" /></a></td>';
			
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['activite'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.reverse_date($data['date']).'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['heure'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['auteur'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['evenement'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['communication'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.str_replace( "\r\n", "<br/>", $data['description']).'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['client'].'</td>';
			
			// Si on affiche qu'une activité alors il faut afficher le detail.
			if( $_POST['activite'] != 'tout' or isset( $_GET['id'] ) ){
				$reqLine = "SELECT correspondance FROM jdb_champs WHERE activite = '".$_POST['activite']."' AND visible='1' ";
				$req1 = mysql_query( $reqLine );
				while( $data1 = mysql_fetch_array( $req1 )){
					//echo 'Corresp : '.$data1['correspondance'].'<br/>';
					
					echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data[$data1['correspondance']].'</td>';
				}
			}
			
			// Y a-t-il un alerting sur cet evenement?
			if( $data['alerting'] == 1 ){
				echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';
					if( $data['alerting_fin'] == 1)
						echo get_img_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'], $data['alerte_date_fin'], $data['alerte_heure_fin'] );
					else
						echo get_img_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'] );
				echo '</td>';
			}
			echo '</tr>';
		}
		else
			if( $debug )
				echo "Erreur SQL : ".mysql_error()."<br/>";

		echo '</table>';
		echo '</div >';
		echo '</div >';
	}
	
	
	
	
	if( isset( $_GET['conf_suppr'] ) ){
		echo '<div class = "corps_general">';
		echo '<div class = "corps_titre">';
		echo "Confirmation :<br/>";
		echo '</div>';

		echo '<div class = "corps_corps">';
		
		$reqLine = "SELECT * FROM jdb_evenements WHERE id='".$_GET['conf_suppr']."'";
		$data = mysql_fetch_array( mysql_query( $reqLine ) );
			echo "Etes-vous sur de vouloir supprimer?<br/><br/>";
			
			echo '<table>';
				echo '<tr>';
					echo '<th> Activité </th>';
					echo '<th> Date </th>';
					echo '<th> Heure </th>';
					echo '<th> Auteur </th>';
					echo '<th> Evènement </th>';
					echo '<th> Communication </th>';
					echo '<th> Description </th>';
				echo '</tr>';
				echo '<tr>';
					echo '<td> '.$data['activite'].' </td>';
					echo '<td> '.$data['date'].' </td>';
					echo '<td> '.$data['heure'].' </td>';
					echo '<td> '.$data['auteur'].' </td>';
					echo '<td> '.$data['evenement'].' </td>';
					echo '<td> '.$data['communication'].' </td>';
					echo '<td> '.$data['description'].' </td>';
				echo '</tr>';
			echo '</table>';
			
			
			echo '<br/><a href="viewer.php?suppr='.$_GET['conf_suppr'].'">OUI</a> ou <a href="viewer.php">NON</a>';
		echo '</div>';
		
	}
	
	if( isset( $_GET['suppr'] ) ){
		echo '<div class = "corps_general">';
		echo '<div class = "corps_titre">';
		echo "Confirmation :<br/>";
		echo '</div>';

		echo '<div class = "corps_corps">';
		
		$reqLine = "DELETE FROM `jdb_evenements` WHERE `id` = ".$_GET['suppr'];
		mysql_query( $reqLine ) 
						or die( "Erreur lors de la suppression<br/>".mysql_error() );
			
		echo "L'évènement a été supprimé.";
		
		echo '</div>';
		
	}
	
	
	
	
	echo '</div class="corps_background">';
	affiche_pied();

?>

</body>
</html>