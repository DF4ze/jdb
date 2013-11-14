<?php
	session_start();
	require_once( 'engine/conf.php' );
	require_once( $_SESSION['folder_engine'].'functions.php' );


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
		foreach( $_GET as $key => $value  ){
			echo '$_GET['.$key.'] = '.$value.'<br/>';
		} 
		echo '<br/>';
	}
	
	
	
	
	
	connect_bdd();	
	
	
	
	
	/////////////////////////////////////////
	// Réception des variables
	
	///////////////
	// Dans le cas dune modification : Préparation des $_POST
	if( isset( $_GET['modif'] )){
		// On récupère les données depuis la BDD
		$temp_data = mysql_fetch_array( mysql_query( "SELECT * FROM jdb_evenements WHERE id='".$_GET['modif']."'" ) );
		if( $debug ){
			echo "Get[modif] : ".$_GET['modif'].'<br/>';
		
			foreach( $temp_data as $key => $value  ){
				echo "Key : $key | Value : $value <br/>";
			} 
		}
		// La boite a cocher.
		$_POST[ $temp_data['activite'] ] = 'on';
		$nb_checked = 1; // pour déclencher l'affichage de la 2nd partie.
		// date
		$_POST['date'] = $temp_data['date'];
		$_POST['heure'] = $temp_data['heure'];
		$_POST['evenement'] = $temp_data['evenement'];
		$_POST['communication'] = $temp_data['communication'];
		$_POST['nomclient'] = $temp_data['client'];
		$_POST['description'] = $temp_data['description'];
		if( $temp_data['alerting'] == 1 )
			$_POST['checkbox_alerting'] = 'on';
		$_POST['alerte_date'] = $temp_data['alerte_date'];
		$_POST['alerte_heure'] = $temp_data['alerte_heure'];
		
		if( $temp_data['alerting_fin'] == 1 )
			$_POST['checkbox_alerting_stop'] = 'on';
		$_POST['alerte_date_fin'] = $temp_data['alerte_date_fin'];
		$_POST['alerte_heure_fin'] = $temp_data['alerte_heure_fin'];
		
		for( $i=0; $i < $nb_options_max; $i++ ){
			$_POST[$i] = $temp_data[$i];
		} 
	}
	
	
	//////// Réception du GO pour les activités
	// On compte le nombre d'items cochés.
	// on fait le tour des checkbox pour compter combien il y en a de cochées.
	$nb_checked = 0;
		
	// Tour de la BDD,
	$reqLine = "SELECT activite, activite_nom FROM jdb_activites ORDER BY activite_nom";
	$req = mysql_query( $reqLine );
	// On en profite pour enregistrer cette requete pour éviter de consulter la BDD a chaque fois qu'on a besoin de la liste d'activité.
	$tab_activite = array();
	$checked_activity = array();
	while( $data = mysql_fetch_array( $req )){
		// On met dans le tableau d'activité comme ca plus besoin de redemander cette requete.
		$tab_activite[ $data['activite'] ] = $data['activite_nom'];
			
		// S'il y a eu un POST : Element coché.
		if( isset( $_POST[ $data['activite'] ] )){
			$nb_checked++;
			$checked_activity[count( $checked_activity )] = $data['activite'];
		}
	}
	if( $debug ) 
		echo "<br/>Nb Checked : ".$nb_checked.'<br/>';
	
	
	
	
	
	//////// Réception du GO pour lajout ou modif d'un evenement.
	if( isset( $_POST['valid'] ) ){
		// vérif que les champs obligatoires sont bien saisi
		$list_error = verif_champs_obligatoires( $checked_activity );
		// Si ok, list_error === TRUE sinon list_error donne les champs qu'il faut remplir.
		if( $list_error === true ){
			// Tour des activités
			foreach( $checked_activity as $key => $activite ){
				if( isset( $_POST['modifing'] )){ // Il s'agit d'une modif
					$reqLine = "UPDATE jdb_evenements SET
							date = '".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['date'])))."',
							heure = '".mysql_real_escape_string(htmlspecialchars($_POST['heure']))."',
							client = '".mysql_real_escape_string(htmlspecialchars($_POST['nomclient']))."',
							description = '".mysql_real_escape_string(htmlspecialchars($_POST['description']))."',
							alerte_date = '".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['alerte_date'])))."',
							alerte_heure = '".mysql_real_escape_string(htmlspecialchars($_POST['alerte_heure']))."',
							alerte_date_fin = '".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['alerte_date_fin'])))."',
							alerte_heure_fin = '".mysql_real_escape_string(htmlspecialchars($_POST['alerte_heure_fin']))."',
							evenement = '".$_POST['evenement']."',
							communication = '".$_POST['communication']."',
							";
					if( isset( $_POST['checkbox_alerting'] ) )
						$reqLine .= "alerting = '1',";
					else
						$reqLine .= "alerting = '0',";
					
					if( isset( $_POST['checkbox_alerting_stop'] ) )
						$reqLine .= "alerting_fin = '1',";
					else
						$reqLine .= "alerting_fin = '0',";

					for( $i=0; $i< $nb_options_max ; $i++ ){
						if( isset( $_POST[$i] ) )
							$reqLine .= "`".$i."` = '".mysql_real_escape_string(htmlspecialchars($_POST[$i]))."'";
						else
							$reqLine .= "`".$i."` = ' '";
							
						if( $i != $nb_options_max -1 )
							$reqLine .= ',';
					} 
					$reqLine .= " WHERE id = '".$_POST['modifing']."'";
					if( $debug )
						echo '<br/>Ligne de requete : <br/>'.$reqLine.'<br/>';
						
					// Exécution de la requete
					if( mysql_query( $reqLine ) )
						$_SESSION['message'] = "Modification avec succes.<br/>";
					else
						$_SESSION['message'] = "<strong>Erreur lors de la modification de l'évènement</strong><br/>".mysql_error();

				}else{ // Il s'agit d'une création.
					// 1ere partie : Générale
					$reqLine = "INSERT INTO jdb_evenements VALUES
							('','";
					if( isset( $_POST['visible'] ))
						$reqLine .= "1";
					else
						$reqLine .= "0";
							
					$reqLine .= "',
							'".$activite."',
							'".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['date'])))."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['heure']))."',
							'".mysql_real_escape_string(htmlspecialchars($_SESSION['name']))."',
							'".$_POST['evenement']."',
							'".$_POST['communication']."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['description']))."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['nomclient']))."',
							";
					if( isset( $_POST['checkbox_alerting'] ) ){
						$reqLine .= "'1', '".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['alerte_date'])))."', '".mysql_real_escape_string(htmlspecialchars($_POST['alerte_heure']))."', ";
						
					}else
						$reqLine .= "'0', '', '', ";
							
					if( isset( $_POST['checkbox_alerting_stop'] ) ){
						$reqLine .= "'1', '".mysql_real_escape_string(htmlspecialchars(reverse_date($_POST['alerte_date_fin'])))."', '".mysql_real_escape_string(htmlspecialchars($_POST['alerte_heure_fin']))."', '', '', '', '', ";
						
					}else
						$reqLine .= "'0', '', '', '', '',  '', '', ";
							
					// 2eme Partie les Options
					// Si qu'un item .. alors on detaille
					if( $nb_checked == 1 ){
						for( $i=0; $i< $nb_options_max ; $i++ ){
							if( isset( $_POST[$i] ) )
								$reqLine .= "'".mysql_real_escape_string(htmlspecialchars($_POST[$i]))."'";
							else
								$reqLine .= "' '";
								
							if( $i != $nb_options_max -1 )
								$reqLine .= ',';
						} 				 
					}else{
						// Sinon on met que des champs vident.
						for( $i=0; $i< $nb_options_max ; $i++ ){
							$reqLine .= "''";
							if( $i != $nb_options_max -1 )
								$reqLine .= ',';
						} 
					}
					$reqLine .= ')';
					
					//$reqLine = mysql_real_escape_string(htmlspecialchars($reqLine));
					if( $debug )
						echo '<br/>Ligne de requete : <br/>'.$reqLine.'<br/>';
						
					// Exécution de la requete
					if( mysql_query( $reqLine ) )
						$_SESSION['message'] = "Création avec succes.<br/>";
					else
						$_SESSION['message'] = "<strong>Erreur de création de l'évènement</strong><br/>".mysql_error();
						
						//	or $_SESSION['message'] = "Erreur de création de l'évènement<br/>";
					//		or die( "Erreur de création de l'évènement<br/>" );
							
					//$_SESSION['message'] = "Création avec succes.<br/>";
				}
			//}
			}
		}else{
			$_SESSION['message'] = "<strong>Votre entrée n'a pas été enregistrée.</strong><br/><br/>Vous devez renseigner ";
			for( $i=0; $i < count( $list_error ) ; $i++ ){
				
				$_SESSION['message'] .= "le champ <strong>".$list_error[$i]."</strong><br/>";
				if( $i < count( $list_error ) -1 )
					$_SESSION['message'] .= "et ";
			} 
			if( isset( $_POST['modifing'] ) ){
				$_GET['modif'] = $_POST['modifing']; // Permet de reloader tout en restant en mode modification
				$recharge = true; // Probleme avec la date qu'on inverse alors qu'il ne faut pas.
			}else
				$_GET['modif'] = true; // permet de reloader la saisie actuelle. en restant en mode création
		}
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_SESSION['folder_images']; ?>jdbico.ico" />
	<title><?php echo "Journaux de Bord CSS" ?></title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	<?php
		if( isset( $_SESSION['logued'] ) ){
			if( !$_SESSION['logued']  )
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
		}else
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
				
	?>

	<script type="text/javascript">
		function montre_div(nom_div)
		{
			if( document.getElementById(nom_div).style.display == "none" )
				document.getElementById(nom_div).style.display="block";
			else
				document.getElementById(nom_div).style.display="none";
		}
		function montre_ligne(nom_div)
		{
			if( document.getElementById(nom_div).style.display == "none" )
				document.getElementById(nom_div).style.display="inline";
			else
				document.getElementById(nom_div).style.display="none";
		}
		function valid_form(){
			document.forms['form_activite'].submit(); 
		}
	</script> 
</head>
<body>	

<?php 
	affiche_menu( __FILE__ );
	
	echo '<div class="corps_background">';

	
	
	
	echo '<div class="titre_general">
	<div class="titre">';
		if( isset( $_GET['modif'] ) ){
			if( $_GET['modif'] !== true)
				echo "Modification d'un évènement :";
			else
				echo 'Insertion d\'un nouvel évènement :';
		}else
			echo 'Insertion d\'un nouvel évènement :';
	echo '</div class="titre">
</div class="titre_general">';	




	
	
	
	////////////////////////////////////////
	// Affichage des options d'activité
	echo '<div class="menu_options_general">';
	echo '<div class="menu_options_titre">';
	echo 'Sélectionnez une ou plusieurs activités<br />';
	echo '</div>';
	
	echo '<form method="post" action="insert.php" id="form_activite" name="form_activite">';

	
	
	echo '<div class="menu_options_corps" style="height:20px;">';
	foreach( $tab_activite as $activite => $activite_nom  ){

		echo '<div class="petit_menu">';
		echo '<input type="checkbox" name="'.$activite.'" id="'.$activite.'" ';
		if( isset($_POST[$activite]) )
			echo 'checked';
		if( isset($_GET['modif']) )
			if( $_GET['modif'] !== true  )
				echo ' disabled ';
		echo '/ onclick="valid_form();">'.$activite_nom.'<br/>';
		echo '</div class="petit_menu">';

	}
	
	//echo '<input type="submit" name="go" value="Go!" />';
	echo '</form>';
	echo '</div class="corps_menu">';
	echo '</div class="grand_menu">';
	
	


	////////////////////////////////////////
	// Affichage des options Alerting et Upload Fichier.
/* 	echo '<div class="menu_options_general">';
	echo '<div class="menu_options_titre">';
	echo 'Options sur l\'évènement<br />';
	echo '</div>';
	echo '<div class="menu_options_corps" >';
	

	
	echo '</div class="menu_options_corps">';	
	echo '</div>';

	 */
	
	
	/////////////////////////////////////////////
	// Affichage du tableau de saisie.
	
	// 1ere partie, la générale
	
	if( $nb_checked > 0 ){
	echo '<div class="corps_general">';
	echo '<div class="corps_titre">';
	if(isset( $_GET['modif'] )){
		if( $_GET['modif'] !== true )
			echo "Modification d'une entrée <br/>";
		else
			echo 'Saisie d\'une nouvelle entrée :';
	}else
		echo 'Saisie d\'une nouvelle entrée <br/>';
	echo '</div>';
	echo '<div class="corps_corps">';
	
	
		// Partie "Options"
			// Affichage des icones :
			echo '<a href="#" onClick="montre_div(\'alerting\');"><img src="'.$_SESSION['folder_images'].'reveil_nvl_entree_small.png"></a> ';
			echo '<img src="'.$_SESSION['folder_images'].'logo_fichier.jpg">';
		
		
		
			// L'alerting
			echo '<div class="alerting" id="alerting" style="display:none">';
			echo '<form method="post" action="insert.php">
				<input type="hidden" id="visible" name="visible" value="on"/>
				<input type="checkbox" id="checkbox_alerting" name="checkbox_alerting" onChange="montre_div(\'debut\');montre_ligne(\'planque\')"';
				if(isset( $_GET['modif'] )){
					if( isset( $_POST['checkbox_alerting'] ) )
						echo ' checked ';
				}
				echo '/>Activer l\'alerting ?<br/>
				
				<div id="debut" style="display:none"> 
				Début : <input type="text" id="alerte_date" name="alerte_date" value="';
				if(isset( $_GET['modif'] )){ if( $_GET['modif'] === true) echo $_POST['alerte_date'];
					else{ if( isset( $recharge )) echo $_POST['alerte_date'];
						else echo reverse_date( $_POST['alerte_date'] );
					}}else{	echo date("d/m/Y");	}
				echo '" class="input_date" ';
				if(isset( $_GET['modif'] )){
					if( !isset( $_POST['checkbox_alerting'] ) )
						echo ' style="display:none" ';
				}else
					;//echo ' style="display:none" ';
				echo ' placeholder="Date Alerting" title="Date Alerting">
				
				<input type="text" id="alerte_heure" name="alerte_heure" value="';
				if(isset( $_GET['modif'] ))
					echo $_POST['alerte_heure'];
				else
					echo date("H:i");
				echo '" class="input_heure" ';
				if(isset( $_GET['modif'] )){
					if( !isset( $_POST['checkbox_alerting'] ) )
						echo ' style="display:none" ';
				}else
					;//echo ' style="display:none" ';
				echo ' placeholder="Heure Alerting" title="Date Alerting"> 
				</div >
				
				<span id="planque" ';if( !isset( $_POST['checkbox_alerting'] ) ) echo 'style="display:none;"'; echo '>
				<input type="checkbox" id="checkbox_alerting_stop" name="checkbox_alerting_stop" onChange="montre_ligne(\'alerte_date_fin\');montre_ligne(\'alerte_heure_fin\');" ';
				if(isset( $_GET['modif'] )){
					if( isset( $_POST['checkbox_alerting_stop'] ) )
						echo ' checked ';
				}
				echo '/> Définir une date fin ? <br/>
				<input class="input_date" type="text" id="alerte_date_fin" name="alerte_date_fin" '; if( !isset( $_POST['checkbox_alerting_stop'] ) ) echo 'style="display:none" '; echo ' value="';
				if(isset( $_GET['modif'] )){ if( $_GET['modif'] === true) echo $_POST['alerte_date_fin'];
					else{ if( isset( $recharge )) echo $_POST['alerte_date_fin'];
						else echo reverse_date( $_POST['alerte_date_fin'] );
					}}else{	echo date("d/m/Y");	}
				echo '"/>
				<input class="input_heure" type="text" id="alerte_heure_fin" name="alerte_heure_fin" '; if( !isset( $_POST['checkbox_alerting_stop'] ) ) echo 'style="display:none" '; echo ' value="';
				if(isset( $_GET['modif'] ))
					echo $_POST['alerte_heure'];
				else
					echo date("H:i");
				echo '"/>
				</span>
				</div><br/>';
				
				
				
				
	
	
		// Partie "Saisie Evenement".
		echo '<input type="date" name="date" placeholder="Date" class="input_date" value="';
				if(isset( $_GET['modif'] )){ if( $_GET['modif'] === true) echo $_POST['date'];
					else{ if( isset( $recharge )) echo $_POST['date'];
						else echo reverse_date( $_POST['date'] );
					}}else{ echo date("d/m/Y"); }
				echo '" title="Date de l\'évènement" required />
				
				<input type="time" name="heure" placeholder="Heure" class="input_heure" value="';
				if(isset( $_GET['modif'] ))
					echo $_POST['heure'];
				else
					echo date("H:i");
				echo '" title="Heure de l\'évènement" required />
				
				<input type="text" name="auteur_affiche" placeholder="Auteur" value="'.$_SESSION['name'].'" disabled />
				
				<input type="hidden" name="auteur" value="'.$_SESSION['name'].'" />
				<select name="evenement" id="evenement" title="Type d\'évènement" required >
					<option value="-1">Evènement</option>
					<option value="Ajout" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Ajout' )
							echo 'selected';
					echo '>Ajout</option>
					<option value="Suppression" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Suppression' )
							echo 'selected';
					echo '>Suppression</option>
					<option value="Modification" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Modification' )
							echo 'selected';
					echo '>Modification</option>
					<option value="Intégration" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Intégration' )
							echo 'selected';
					echo '>Intégration</option>
					<option value="Incident Majeur" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Incident Majeur' )
							echo 'selected';
					echo '>Incident Majeur</option>
					<option value="Fait Marquant" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Fait Marquant' )
							echo 'selected';
					echo '>Fait Marquant</option>
					<option value="Information" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Information' )
							echo 'selected';
					echo '>Information</option>
					<option value="Maintenance" ';
					if( isset($_GET['modif']) )
						if( $_POST['evenement'] == 'Maintenance' )
							echo 'selected';
					echo '>Maintenance</option>
				</select>
				<select name="communication" id="communication" title="L\'évènement a-t-il été communiqué?" required >
					<option value="-1">Communication</option>
					<option value="Non" ';
					if( isset($_GET['modif']) )
						if( $_POST['communication'] == 'Non' )
							echo 'selected';
					echo '>Non</option>
					<option value="Chef Service" ';
					if( isset($_GET['modif']) )
						if( $_POST['communication'] == 'Chef Service' )
							echo 'selected';
					echo '>Chef Service</option>
					<option value="CS + Equipe" ';
					if( isset($_GET['modif']) )
						if( $_POST['communication'] == 'CS + Equipe' )
							echo 'selected';
					echo '>CS + Equipe</option>
					<option value="CS + Equipe + ROC" ';
					if( isset($_GET['modif']) )
						if( $_POST['communication'] == 'CS + Equipe + ROC' )
							echo 'selected';
					echo '>CS + Equipe + ROC</option>
				</select>
				<input type="text" name="nomclient" ';
				if( isset( $_GET['modif'] ) )
					echo 'value="'.$_POST['nomclient'].'" ';
				echo 'placeholder="Nom du Client" title="Nom du Client" required/>
				<textarea name="description" rows="10" cols="104" placeholder="Description" title="Description de l\'évènement" required >';
				if( isset( $_GET['modif'] ) )
					echo $_POST['description'];
				echo '</textarea></br>';
		
		// On repost les éléments cochés pour qu'ils restent cochés apres refresh.
		foreach( $tab_activite as $activite => $activite_nom  ){
			if( isset( $_POST[ $activite ] )){
				echo '<input type="hidden" name="'.$activite.'" value="on"/>';
			}
		} 
		echo '<input type="hidden" name="go" value="maisgo!"/>';
		
		// Si qu'une seule activité alors on affiche les options détaillées.
		if( $nb_checked == 1 ){
			// On fait le tour des options
			foreach( $tab_activite as $activite => $activite_nom  ){
				if( @$_POST[ $activite ] ){
					// On questionne la table des champs
					$reqLine = "SELECT nom, correspondance, visible, type, menu FROM jdb_champs WHERE activite = '$activite'";
					$req = mysql_query( $reqLine );
					while( $data = mysql_fetch_array( $req )){
						// Et on affiche le champ, s'il est visible
						if( $data['visible'] ){
							// Quel type de champs?
							switch( $data['type'] ){
								case "text" : 
									echo '<input type="text" name="'.$data['correspondance'].'" ';
									if( isset( $_GET['modif'] ) )
										echo 'value="'.$_POST[ $data['correspondance'] ].'" ';
									echo 'placeholder="'.$data['nom'].'" title="'.$data['nom'].'" />';
									break;
									
								case "textarea" : 
									echo '<br/><textarea name="'.$data['correspondance'].'" rows="10" cols="104" placeholder="'.$data['nom'].'" title="'.$data['nom'].'">';
									if( isset( $_GET['modif'] ) )
										echo $_POST[$data['correspondance']];
									echo '</textarea><br/>';
									break;

								case "select" : 
									//////////////
									// détaillage des différents éléments du menu déroulant.
									
									// longueur de la chaine :
									$taille = strlen( $data['menu'] );
									
									//recherche du ';'
									$temp = '';
									$tab_menu = array();
									for( $i=0; $i < $taille; $i++ ){ 
										// On scan la chaine de caractère.
										// Tanque différent de ';' on concatène.				
										if( $data['menu'][$i] == ';' or $data['menu'][$i] == ','){
											// == ';' donc on stocke la précédente chaine dans le tableau.
											// On reset le temp.
											$tab_menu[count($tab_menu)] = $temp;
											$temp = '';
										}else
											$temp .= $data['menu'][$i];
									} 
									// Comme il n'y a pas de séparateur en fin de chaine, on stocke ce qui a été mémorisé en dernier
									$tab_menu[count($tab_menu)] = $temp;
									
									echo '<select name="'.$data['correspondance'].'" id="'.$data['correspondance'].'" title="'.$data['nom'].'">';
										foreach( $tab_menu as $key => $menu  ){
											echo '<option value="'.$menu.'" ';
											if( isset( $_GET['modif'] ) )
												if( $menu == $_POST[ $data['correspondance'] ] )
													echo 'selected';
											echo '>'.$menu.'</option>';
										} 
										
									echo '</select>';
									
									//echo '<input type="text" name="'.$data['correspondance'].'" value="'.$data['menu'].'"/>';
									break;
							}
						}
					}
				} 				
			}
		}
		if( isset( $_GET['modif'] ) )
			if( $_GET['modif'] !== true )
				echo '<input type="hidden" name="modifing" value="'.$_GET['modif'].'"/>';
			
		echo '	<br/><input type="submit" name="valid" value="Go!" class="create"/>
			
				</form>';
		echo '</div class="corps_menu">';
		echo '</div class="grand_menu">';

	}
	


	if( @$_SESSION['message'] != '' ){
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