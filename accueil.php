<?php session_start();
	// $debug = false;
	$debug = $_SESSION['debug'];
	$nb_options_max = $_SESSION['nb_options_max']; //fleme de replacer dans le code...

	require_once( "engine/conf.php" );
	require_once( $_SESSION['folder_engine']."functions.php" );
	require_once( $_SESSION['folder_phpmyrss']."class_phpmyrss.php" );
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


	echo '<div class="corps_background">';
	
	echo '<div class="titre_general">
			<div class="titre_centre">
				Bonjour, <br/>Bienvenue sur le Journal de Bord du CSS
			</div>
		</div class="titre_general">';



	////////////////////////////////////////////////
	////////// 		Evmt Journée		////////////
	////////////////////////////////////////////////

	// Init du flux RSS en rapport.
	$rss = new phpmyrss( 'JDB - Evènements de la journée', 'http://localhost', 'Les évènements qui ont été saisie dans la journée', $_SESSION['folder_rss'].'rss_daily.xml' );

	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo '<a href="'.$_SESSION['folder_rss'].'rss_daily.xml"><img src="'.$_SESSION['folder_images'].'logo_rss.png" style="vertical-align: middle;"></a> ';
	echo "Evènements de la journée : <br/>";
	echo '</div>';

	echo '<div class = "corps_corps">';

	
	// On récupère les différentes activités
	$reqLine = "SELECT activite_nom, activite 
							FROM jdb_activites
							ORDER BY activite_nom";
	if( $debug )
		echo $reqLine.'<br/>';
			

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

			$reqLine = "SELECT id, evenement, description, date 
								FROM jdb_evenements
								WHERE
									activite = '".$data['activite']."'
								AND date = '".date("Y/m/d")."'
								ORDER BY heure";
			if( $debug )
				echo $reqLine.'<br/>';
					
			$req_detail = mysql_query( $reqLine );
			while( $data_detail = mysql_fetch_array( $req_detail )){
				// Feed du RSS
				$lien = $_SESSION['viewer_evmt'].$data_detail['id'];
				$rss->add_item( $data['activite']/* $title */, $lien, $data_detail['date'], $data_detail['description'] );

				echo '<a href="viewer.php?id='.$data_detail['id'].'">'.truncate_text(' - '.$data_detail['evenement'].' : '.$data_detail['description'], 'accueil')."</a><br/>";
			} 
		}
	} 

	if( $compte == 0 )
		echo "Aucun nouvel évènement aujourd'hui.";
	
	echo '</div></div>';
	$rss->close();
	
	
	
	
	
	
	
	
	////////////////////////////////////////////////
	////////// 		10 derniere Evmt 	////////////
	////////////////////////////////////////////////

	// Init du flux RSS en rapport.
	$rss = new phpmyrss( 'JDB - 10 Derniers Evènements', 'http://localhost', 'Les 10 derniers évènements qui ont été saisie', $_SESSION['folder_rss'].'rss_10last.xml' );

	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo '<a href="'.$_SESSION['folder_rss'].'rss_10last.xml"><img src="'.$_SESSION['folder_images'].'logo_rss.png" style="vertical-align: middle;"></a> ';
	echo "Les 10 derniers Evènements : <br/>";
	echo '</div>';

	echo '<div class = "corps_corps">';
	
	// On récupère les différentes activités
	$reqLine = "SELECT id, evenement, description, activite, date 
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
		$tab_evenements[ $data['activite'] ][ $offset ]['date'] = $data['date'];
	
		//echo ' - <a href="viewer.php?id='.$data['id'].'"> '.$data['activite'].' : '.$data['evenement'].' : '.$data['description']."</a><br/>";
	} 	
	
	$count = 0;
	foreach( $tab_evenements as $activite => $evenements  ){
		if( $count ++ != 0 ) echo "<br/>";
		echo '<strong>'.$activite.' : </strong>('.count( $evenements ).')<br/>';
		foreach( $evenements as $key => $evenement  ){
			//echo ' - <a href="viewer.php?id='.$evenement['id'].'"> '.$evenement['evenement']." : ".truncate_text($evenement['description'], 'accueil').'</a><br/>';
			echo '<a href="viewer.php?id='.$evenement['id'].'">'.truncate_text(' - '.$evenement['evenement'].' : '.$evenement['description'], 'accueil')."</a><br/>";
				
			// Feed du RSS
			$lien = $_SESSION['viewer_evmt'].$evenement['id'];
			$rss->add_item( $activite/* $title */, $lien, $evenement['date'], $evenement['description'] );
		} 
	} 

	echo '</div></div>';
	$rss->close();


	
	
	////////////////////////////////////////////////
	////////// 		Alertes en cours	////////////
	////////////////////////////////////////////////

	// Init du flux RSS en rapport.
	$rss = new phpmyrss( 'JDB - Alertes en cours', 'http://localhost', 'Liste des alertes en cours', $_SESSION['folder_rss'].'rss_running.xml' );

	// on ne récupère que les evenements qui ont un alerting et qui ne sont pas acquités
	$reqLine = "SELECT * FROM jdb_evenements WHERE is_acquit != '1' AND alerting = '1'";
	$req=mysql_query($reqLine);
	
	// On se créé un tableau de résultat : tab[ vert/jaune/rouge ][ offset de l'item ][ lien ]
	//																			  [ id ]
	//																			  [ description ]
	//																			  [ date ]
	$tab_alert = array();
	while( $data = mysql_fetch_array( $req ) ){
		// On récupère leur status : Vert Jaune ou Rouge
		$status = get_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'],  $data['alerte_date_fin'], $data['alerte_heure_fin']);
		
		// On créé le "lien" : Image clicable + description tronquée clicable
		$offset = @count( $tab_alert[$status] );
		$tab_alert[$status][$offset]['lien'] = get_img_status_alerting( $data['id'], $data['alerte_date'], $data['alerte_heure'],  $data['alerte_date_fin'], $data['alerte_heure_fin'] ).
												'<a href="viewer.php?id='.$data['id'].'"> - '.
												truncate_text( $data['client']." : ".$data['description'], 'accueil' ).
												'</a>';
		// On récup également l'ID
		$tab_alert[$status][$offset]['id'] = $data['id'];
		
		// Pour le RSS :
		$tab_alert[$status][$offset]['description'] = $data['description'];
		$tab_alert[$status][$offset]['date'] = $data['date'];
		$tab_alert[$status][$offset]['client'] = $data['client'];
	}

	echo '<div class = "corps_general_accueil">';
	echo '<div class = "corps_titre">';
	echo '<a href="'.$_SESSION['folder_rss'].'rss_running.xml"><img src="'.$_SESSION['folder_images'].'logo_rss.png" style="vertical-align: middle;"></a> ';
	echo 'Les alertes en cours ('.count($tab_alert['rouge']).') : <br/>';
	echo '</div>';

	echo '<div class = "corps_corps">';
	if( count( $tab_alert['rouge'] ) != 0 ){
		foreach( $tab_alert['rouge'] as $key => $evenement ){
			
			echo $evenement['lien'].'<br/>';
			
			// Feed du RSS
			$lien = $_SESSION['viewer_evmt'].$evenement['id'];
			$rss->add_item( $evenement['client'], $lien, $evenement['date'], $evenement['description'] );
		}
		
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
	$rss->close();

	
	////////////////////////////////////////////////
	////////// 		Alertes à venir		////////////
	////////////////////////////////////////////////

	// Init du flux RSS en rapport.
	$rss_day = new phpmyrss( 'JDB - Alertes à venir - Aujourd\'hui', 'http://localhost', 'Liste des alertes à venir dans la journée', $_SESSION['folder_rss'].'rss_comingday.xml' );
	$rss_coming = new phpmyrss( 'JDB - Alertes à venir - Plus Tard', 'http://localhost', 'Liste des alertes à venir plus tard', $_SESSION['folder_rss'].'rss_cominglater.xml' );

	if( @count( $tab_alert['jaune'] ) != 0 OR  @count( $tab_alert['vert'] ) != 0 ){

		echo '<div class = "corps_general_accueil">';
		echo '<div class = "corps_titre">';
		echo "Les alertes à venir : <br/>";
		echo '</div>';

		echo '<div class = "corps_corps">';
		
		if( count( $tab_alert['jaune'] ) != 0 ){
			echo '<a href="'.$_SESSION['folder_rss'].'rss_comingday.xml"><img src="'.$_SESSION['folder_images'].'logo_rss.png" style="vertical-align: middle;"></a> ';
			echo '<strong>Alertes à venir dans la journée ('.count($tab_alert['jaune']).') : </strong><br/>';
			foreach( $tab_alert['jaune'] as $key => $evenement ){
				echo $evenement['lien'].'<br/>';
				// Feed du RSS
				$lien = $_SESSION['viewer_evmt'].$evenement['id'];
				$rss_day->add_item( $evenement['client'], $lien, $evenement['date'], $evenement['description'] );
			}
			echo "<br/>";
		}	
		if( count( $tab_alert['vert'] ) != 0 ){
			echo '<a href="'.$_SESSION['folder_rss'].'rss_cominglater.xml"><img src="'.$_SESSION['folder_images'].'logo_rss.png" style="vertical-align: middle;"></a> ';
			echo '<strong>Alertes à venir ('.count($tab_alert['vert']).') : </strong><br/>';
			foreach( $tab_alert['vert'] as $key => $evenement ){
				echo $evenement['lien'].'<br/>';
				// Feed du RSS
				$lien = $_SESSION['viewer_evmt'].$evenement['id'];
				$rss_coming->add_item( $evenement['client'], $lien, $evenement['date'], $evenement['description'] );
			}
		}
		echo '</div></div>';
	}
	$rss_day->close();
	$rss_coming->close();

	
	
	
	echo '</div class="BackGround">';
	affiche_pied();
?>
</body>
</html>