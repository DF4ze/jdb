<?php session_start();
	// $debug = false;
	$debug = $_SESSION['debug'];
	$nb_options_max = $_SESSION['nb_options_max']; //fleme de replacer dans le code...

	require_once( "engine/conf.php" );
	require_once( $_SESSION['folder_engine']."functions.php" );
	require_once( $_SESSION['folder_rss_write']."rss_write.inc.php" );
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_SESSION['folder_images']; ?>jdbico.ico" />
	<title><?php echo "Journaux de Bord CSS" ?></title>
	<META HTTP-EQUIV="Refresh" CONTENT="<?php echo $_SESSION['time_refresh'] ?>;URL=accueil.php">
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
		// fenetreA=window.open(null,null,null);
		fenetreA.focus();
	} 	
	function popalert() 
	{
		fenetreA=window.open("alert_pop.php", "Alerte Infos", "status=no,location=no,toolbar=no,directories=no,resizable=no,left=100,top=10,width=620,height=210,top=100,left=100" );
		// fenetreA=window.open(null,null,null);
		fenetreA.focus();
	} 

	</script >  
</head>
<body>	
<?php


	
	///////////////////////////
	// Variables Réceptionnées
	if( $debug ){
		echo "Variables : <br/>";
		foreach( $_POST as $key => $value  ){
			echo '$_POST['.$key.'] = '.$value.'<br/>';
		} 
		echo '<br/>';
		
		echo '<a href="javascript:popalert();">clic to pop</a><br/>';
	}
	

	
	connect_bdd();

	affiche_menu( __FILE__ );	

	// Préparation du flux RSS pour les evenements du jour.
	$rss = new rss_write();
	$rss->rss( "ISO-8859-1", 'fr' );
	
	echo '<div class="corps_background">';
	
	echo '<div class="titre_general">
			<div class="titre_centre">
				Bonjour, <br/>Bienvenue sur le Journal de Bord du CSS
			</div>
		</div class="titre_general">';



	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo "Evènements de la journée : <br/>";
	echo '</div>';

	echo '<div class = "corps_corps">';

	
	// On récupère les différentes activités
	$reqLine = "SELECT activite_nom, activite 
							FROM jdb_activites
							ORDER BY activite_nom";
	if( $debug )
		echo $reqLine.'<br/>';
			
	// Préparation du flux RSS pour les evenements du jour.
	$rss_jour = new rss_write();
	$rss_jour->rss( "ISO-8859-1", 'fr' );
	$rss_jour->channel( "JDB Evènements du jour", "http://10.210.137.164/jdb/", "Flux sur les évènements du jour" );

	// On fait le tour
	$compte = 0; // Compte le nombre d'evenement
	$count = 0; // Compte le nombre d'activité : pour afficher un espace entre elles.
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		
		// On compte le nombre d'évènements
		$reqLine = "SELECT COUNT( evenement ) AS nb 
							FROM jdb_evenements
							WHERE
								activite = '".$data['activite']."'
							AND date = '".date("Y/m/d")."'
							AND visible = '1' 
							ORDER BY heure";
		if( $debug )
			echo $reqLine.'<br/>';
			
		$data_temp = mysql_fetch_array( mysql_query( $reqLine ) )or die( mysql_error() );
		$compte += $data_temp['nb'];
		
		if( $debug )
			echo "NB : ".$data_temp['nb']."<br/>";
		
		if( $data_temp['nb'] != 0 ){
			if( $count ++ != 0 ) echo "<br/>";

			echo '<strong>'.$data['activite_nom'].' ('.$data_temp['nb'].') : </strong><br/>';

			$reqLine = "SELECT id, evenement, description 
								FROM jdb_evenements
								WHERE
									activite = '".$data['activite']."'
								AND date = '".date("Y/m/d")."'
								ORDER BY heure";
			if( $debug )
				echo $reqLine.'<br/>';
					
			$req_detail = mysql_query( $reqLine );
			while( $data_detail = mysql_fetch_array( $req_detail )){
				echo '<a href="viewer.php?id='.$data_detail['id'].'">'.truncate_text(' - '.$data_detail['evenement'].' : '.$data_detail['description'], 'accueil')."</a><br/>";
				
				// Feed le RSS
				$rss_jour->item_element( 'title', $data['activite_nom'].' - '.$data_detail['evenement'] );
				$rss_jour->item_element( 'link', "http://10.210.136.61/jdb/viewer.php?id=".$data_detail['id'] );
				//$rss_jour->item_element( 'description', "http://10.210.136.61/jdb/viewer.php?id=".$data_detail['id'] );
			} 
		}
	} 
	
	// Création du RSS_JOUR
	$res = $rss_jour -> generate('rdf10',$erreur);
	if ($res) {
		$res = htmlentities($res);
		echo str_replace("\n",'<br />', $res);
	} else {
		echo "<strong>Erreur RSS : </strong><br/>".$erreur.'<br/>';
	}
	$res = @$rss_jour->save( "rss_jour.xml", rdf10, $erreur );
	if ($res) {
		if( $debug ) echo 'flux ok';
	} else {
		if( $debug ) echo "<strong>Erreur RSS : </strong><br/>".$erreur.'<br/>';
	}

	if( $compte == 0 )
		echo "Aucun nouvel évènement aujourd'hui.";
	
	echo '</div></div>';
	
	
	
	
	
	
	
	
	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo "Les 10 derniers Evènements : <br/>";
	echo '</div>';

	echo '<div class = "corps_corps">';
	
	// On récupère les différentes activités
	$reqLine = "SELECT id, evenement, description, activite 
							FROM jdb_evenements
							ORDER BY date DESC
							LIMIT 0, 10";
	if( $debug )
		echo $reqLine.'<br/>';
	
	// On fait le tour 
	$tab_evenements = array();
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		$offset = @count( $tab_evenements[ $data['activite'] ] );
		$tab_evenements[ $data['activite'] ][ $offset ]['id'] = $data['id'];
		$tab_evenements[ $data['activite'] ][ $offset ]['description'] = $data['description'];
		$tab_evenements[ $data['activite'] ][ $offset ]['evenement'] = $data['evenement'];
	
		//echo ' - <a href="viewer.php?id='.$data['id'].'"> '.$data['activite'].' : '.$data['evenement'].' : '.$data['description']."</a><br/>";
	} 	
	
	$count = 0;
	foreach( $tab_evenements as $activite => $evenements  ){
		if( $count ++ != 0 ) echo "<br/>";
		echo '<strong>'.$activite.' : </strong>('.count( $evenements ).')<br/>';
		foreach( $evenements as $key => $evenement  ){
			//echo ' - <a href="viewer.php?id='.$evenement['id'].'"> '.$evenement['evenement']." : ".truncate_text($evenement['description'], 'accueil').'</a><br/>';
			echo '<a href="viewer.php?id='.$evenement['id'].'">'.truncate_text(' - '.$evenement['evenement'].' : '.$evenement['description'], 'accueil')."</a><br/>";
		} 
	} 

	echo '</div></div>';


	
	
	
	// on ne récupère que les evenements qui ont un alerting et qui ne sont pas acquités
	$reqLine = "SELECT * FROM jdb_evenements WHERE is_acquit != '1' AND alerting = '1'";
	$req=mysql_query($reqLine);
	
	$tab_alert = array();
	while( $data = mysql_fetch_array( $req ) ){
		$status = get_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'],  $data['alerte_date_fin'], $data['alerte_heure_fin']);
		$offset = @count( $tab_alert[$status] );
		$tab_alert[$status][$offset]['lien'] = get_img_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'],  $data['alerte_date_fin'], $data['alerte_heure_fin'] ).
												'<a href="viewer.php?id='.$data['id'].'"> - '.
												truncate_text( $data['client']." : ".$data['description'], 'accueil' ).
												'</a>';
		$tab_alert[$status][$offset]['id'] = $data['id'];
	}

	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo 'Les alertes en cours ('.count($tab_alert['rouge']).') : <br/>';
	echo '</div>';

	echo '<div class = "corps_corps">';
	if( count( $tab_alert['rouge'] ) != 0 ){
		foreach( $tab_alert['rouge'] as $key => $evenement )
			echo $evenement['lien'].'<br/>';
		
		// Y a-t-il un élément qui n'a pas été popedup?
			// $_SESSION['result'] sera récupéré par la popup ... pas propre mais plus facile pour passer la totalité des infos.
		$_SESSION['result_new'] = who_pop( $tab_alert['rouge'] );
		
		if( count( $_SESSION['result_new'] ) ){
			// déclencher la popup // Ne pas supprimer les retours a la ligne sinon ca ne marche plus ......?!?!?!!
			echo '<script type="text/javascript">
					<!--
						popalert();
					-->
				</script><br/>';
				
			if( $_SESSION['debug'] )echo "heuuuuuu .... ca popup !!!!<br/>";
		}
		else if( $_SESSION['debug'] )echo "heuuuuuu .... ca popup pas....<br/>";
		
		$_SESSION['tab_haspop'] = $tab_alert['rouge'];
		
	}else
		echo "Il n'y a pas d'alerte en cours actuellement.<br/>";

	echo '</div></div>';

	
	
	if( @count( $tab_alert['jaune'] ) != 0 OR  @count( $tab_alert['vert'] ) != 0 ){
		echo '<div class = "corps_general_accueil">';
		echo '<div class = "corps_titre">';
		echo "Les alertes à venir : <br/>";
		echo '</div>';

		echo '<div class = "corps_corps">';
		
		if( count( $tab_alert['jaune'] ) != 0 ){
			echo '<strong>Alertes à venir dans la journée ('.count($tab_alert['jaune']).') : </strong><br/>';
			foreach( $tab_alert['jaune'] as $key => $evenement )
				echo $evenement['lien'].'<br/>';
				echo "<br/>";
		}	
		if( count( $tab_alert['vert'] ) != 0 ){
			echo '<strong>Alertes à venir ('.count($tab_alert['vert']).') : </strong><br/>';
			foreach( $tab_alert['vert'] as $key => $evenement )
				echo $evenement['lien'].'<br/>';
		}
		echo '</div></div>';
	}

	
	
	
	echo '</div class="BackGround">';
	affiche_pied();
?>
</body>
</html>