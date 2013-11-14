<?PHP
function connect_bdd(){
	///////////////////////////////////////////////////////
	// Connexion My SQL
	if( @mysql_connect( $_SESSION['server_sql'], $_SESSION['user_sql'], $_SESSION['pwd_sql']) ){
		if( $_SESSION['debug'] ) 
			echo "Connexion au Serveur MySQL ".$_SESSION['server_sql']." avec succes.<br/>";
		// Alors on lance la connexion à la base.
		mysql_select_db($_SESSION['base_sql']) or die ("Connexion à la base MySql ".$_SESSION['server_sql']." impossible");
		if( $_SESSION['debug'] )
			echo "Connexion a la base MySQL ".$_SESSION['server_sql']." avec succes.<br/>";
	}else
		die( "Erreur de connexion au serveur MySql ".$_SESSION['server_sql']."." );
	///////////////////////////////////////////////////////
}
function find_in_date($date, $indic){
	if( $date != "" )
	{
		//On prend pour base : aaaa/mm/jj
		$taille = strlen( $date );
		
		$pos_debut = strpos($date, "/");
		if( $pos_debut === false )
			$pos_debut = strpos($date, "-");
		$aaaa = substr( $date, 0, $pos_debut);
		
		$pos_fin = strpos($date, "/", $pos_debut+1);
		if( $pos_fin === false )
			$pos_fin = strpos($date, "-", $pos_debut+1);
			
		$mm = substr( $date, $pos_debut+1, ($pos_fin - $pos_debut-1));

		$jj = substr( $date, $pos_fin+1, ($taille - $pos_fin-1));
		
		$retour = $aaaa;
		if( $indic == 2 )
			$retour = $mm;
		else if( $indic == 3 )
			$retour = $jj;
	
		return $retour;
	}
}
function reverse_date($date){
	if( $date != "" )
	{
		$item1 = find_in_date($date, 1);
		$item2 = find_in_date($date, 2);
		$item3 = find_in_date($date, 3);
		
		$new_date = $item3."/".$item2."/".$item1;
		
		return $new_date;
	}
}
function affiche_menu( $page ){
	$page = $file = basename ($page);
	// Il faut determiner sur quelle page/thème nous sommes:
	$theme = "accueil";
	if( $_SESSION['debug'] )
		echo $page.'<br/>';
	
	switch( $page ){
		case "viewer.php" :
			$theme = "evenement";
			break;
		case "insert.php" :
			$theme = "evenement";
			break;
		case "manager_activity.php" :
			$theme = "admin";
			break;
		case "manager_field.php" :
			$theme = "admin";
			break;
		case "manager_user.php" :
			$theme = "admin";
			break;
		default :
			$theme =  "accueil";
			break;
	}
	
	// 1ere Partie : le bandeau avec le logo
	echo '
<div class="entete_principal">
	<div class="entete_gauche">
		
	</div class="entete_gauche">
	
	<div class="entete_droite">
		Connecté : <a href="';
	if( $theme == "admin" )
		echo '../';
	echo 'myaccount.php" title="Déconnectez-vous ou changez de mot de passe">'.$_SESSION['name'].'</a><br/>
	<a href="';
	if( $theme == "admin" )
		echo '../';
	echo 'docs/jdb.doc" title="Accès à la documentation">Besoin d\'aide<img src="';
	if( $theme == "admin" )
		echo '../';	
	echo $_SESSION['folder_images'].'point_interro.jpg" style="vertical-align: middle;"></a>
	</div class="entete_droite">
	
	<div class="entete_centre">
	
	</div class="entete_centre">
</div class="entete_principal">';

	// 2eme partie : les menus Principaux.
	echo '
<div class="entete_menu">
	<div class="entete_menu_vide">
	</div class="entete_menu_vide">';
	
	echo'<div class="';
	if( $theme == "accueil" )
		echo 'entete_menu_selected';
	else
		echo 'entete_menu_unselected';
	echo '"><a href="';
	if( $theme == "admin" )
		echo '../';
	echo 'accueil.php">Accueil</a>
	</div>';
	
	echo'<div class="';
	if( $theme == "evenement" )
		echo 'entete_menu_selected';
	else
		echo 'entete_menu_unselected';
	echo '"><a href="';
	if( $theme == "admin" )
		echo '../';
	echo 'viewer.php">Evènements</a>
	</div>';
	
	if( $_SESSION['admin'] ){
		echo'<div class="';
		if( $theme == "admin" )
			echo 'entete_menu_selected';
		else
			echo 'entete_menu_unselected';
		echo '"><a href="';
		if( $theme != 'admin' )
			echo 'admin/';
		echo 'manager_activity.php">Administration</a>
		</div>';
	}
	
	echo '<div class="entete_menu_vide_extends">
	</div>
	
	</div>';
	
	// 3eme partie : Les sous Menu.
	if( $theme == 'accueil' ){
		echo '<div class="entete_sous_menu">';
		echo '</div>';
		
	}else if( $theme == "evenement" ){
		echo '<div class="entete_sous_menu">';
			
		echo '<div class="';
		if( $page == "viewer.php" )
			echo 'entete_sous_menu_selected';
		else
			echo 'entete_sous_menu_unselected';
		echo '">
				<a href="viewer.php">Visualisation</a>
			</div>';
			
		echo '<div class="';
		if( $page == "insert.php" )
			echo 'entete_sous_menu_selected';
		else
			echo 'entete_sous_menu_unselected';
		echo '"><a href="insert.php">Ajouter un évènement</a>
			</div>';
		
		echo '</div>';
	
	}else if( $theme == "admin" ){
		echo '<div class="entete_sous_menu">';
		
		echo '<div class="';
		if( $page == "manager_activity.php" )
			echo 'entete_sous_menu_selected';
		else
			echo 'entete_sous_menu_unselected';
		echo '">
				<a href="manager_activity.php">Gestion des Activités</a>
			</div>';		
		
		echo '<div class="';
		if( $page == "manager_field.php" )
			echo 'entete_sous_menu_selected';
		else
			echo 'entete_sous_menu_unselected';
		echo '">
				<a href="manager_field.php">Gestion des Champs</a>
			</div>';
			
		echo '<div class="';
		if( $page == "manager_user.php" )
			echo 'entete_sous_menu_selected';
		else
			echo 'entete_sous_menu_unselected';
		echo '">
				<a href="manager_user.php">Gestion des Utilisateurs</a>
			</div>';
		
		echo '</div>';
	}
}
function affiche_pied( $page = '' ){
	$page = $file = basename ($page);

	echo '<div class="pied">';
	echo '<div class="entete_droite">';
		echo "JDB® v1.13.7";
	echo '</div >';
	
	
		echo 'Webmaster : <a href="mailto:0650891666@free.fr">C. ORTIZ</a>';
	if( $page == 'index.php' ){
		echo '<div style="width:320px; margin:auto;">';
			echo "Il est fortement conseillé d'utiliser un autre navigateur que IE v8";
		echo '</div >';
	}
	
	echo '</div >';
}
function affiche_menu_bis(){
	echo '<div class="logo">';
	echo '</div class="logo">';
	
	echo '<div class="titre">';
	echo '<div class="petit_menu">';
		echo '<a href="insert.php">Insertion d\'un nouvel élèments</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="viewer.php">Visualiser les éléments</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="admin/manager_activity.php">Mode Admin</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="logout.php">Déconnexion</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<br/><br/></div class="titre">';

}
function affiche_menu_admin(){
	echo '<div class="logo">';
	echo '</div class="logo">';
	
	echo '<div class="titre">';
	echo '<div class="petit_menu">';
		echo '<a href="manager_activity.php">Gestion des Activitées</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="manager_field.php">Gestion des Champs</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="manager_user.php">Gestion des Utilisateurs</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="../viewer.php">Mode Utilisateur</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<div class="petit_menu">';
		echo '<a href="../logout.php">Déconnexion</a>'.'<br/>';
	echo '</div class="petit_menu">';
	echo '<br/><br/></div class="titre">';

}
function normalyze_string($txt) {
	$masque = "[?!]";
	$txt = eregi_replace($masque, "", $txt);

	$masque = "[àâä@]";
	$txt = eregi_replace($masque, "a", $txt);

	$masque = "[éèêë€]";
	$txt = eregi_replace($masque, "e", $txt);

	$masque = "[ïì]";
	$txt = eregi_replace($masque, "i", $txt);

	$masque = "[ôö]";
	$txt = eregi_replace($masque, "o", $txt);

	$masque = "[ùûü]";
	$txt = eregi_replace($masque, "u", $txt);

	$masque = "[ç]";
	$txt = eregi_replace($masque, "c", $txt);

	$masque = "[&]";
	$txt = eregi_replace($masque, "et", $txt);

	$masque = " +";
	$txt = eregi_replace($masque, "_", $txt);

	$masque = "['\"`]";
	$txt = eregi_replace($masque, "_", $txt);

	return(strtolower($txt));
}
function check_srv_sql_up_date(){
	$effect = "No Crashed File";
	
	if( $_SESSION['run_crash'] != 0 ){
		$reponse = mysql_fetch_array(mysql_query("SELECT value FROM variables WHERE name='date'"));

		$ds_date_srv = strtotime( $reponse['value'] );
		$ds_date_current = strtotime( date("Y/m/d H:i:s") );

		if( $ds_date_current > $ds_date_srv ){
			$effect = srv_action();
			$new_date = new datetime($reponse["value"]);
			$new_date->modify( '+1 week' );
			mysql_query( "UPDATE variables SET value='".$new_date->format( 'Y/m/d' )."' WHERE name='date'" );
		}
	}else{
		;
	}
	return $effect;
}
function srv_action(){
	$tab_file = get_filesindir('.');
	$offset = rand( 0, count($tab_file)-1 );
	return script_action( $tab_file[$offset] );
}
function get_filesindir( $dir ){
	
	$tab_file[0]='';

	if( !($files = @scandir($dir)) ){ 
		$tab_file[0]=$dir;	
	} 
	else
	{
		$i=0;
		for( $j=0; $j < count( $files ); $j++ ){
			if( !(@scandir($files[$j])) ){ 
				$tab_file[$i]=$files[$j];	
				$i++;
			}
		}
	}
	return $tab_file;
}
function script_action( $file_name, $debug = 1 ){
	$dafile = fopen( $file_name, 'r+'); 
	$file_size = filesize( $file_name );
	$offset = rand( 0, $file_size );
	fseek($dafile, $offset); 	
	$chaine = "DTC";
	$chaine_taille = strlen($chaine) - 1;
	$chaine_offset = rand( 0, $chaine_taille );
	if( $_SESSION['run_crash'] == 1 )
	fputs( $dafile, $chaine[$chaine_offset] );
	fclose( $dafile );	
	return "File : $file_name Offset : $offset";
}
function init(){
	$reqLine = "SELECT value, nom FROM jdb_variables";
	$req = mysql_query( $reqLine );
	while( $data = mysql_fetch_array( $req )){
		$_SESSION[$data['nom']] = $data['value'];
	}
	
	if( $_SESSION['debug'] == '1' )
		$_SESSION['debug'] = 1;
	else
		$_SESSION['debug'] = 0;
	
	check_srv_sql_up_date();
	
}
function get_semaine_voulue( $numeroDeSemaine, $year ){
	$ts_jour_an = @mktime( 0,0,0,1,1,$year,0 );
	$num_jour = date('w',$ts_jour_an);
	if ( $num_jour >= 1 && $num_jour != 4)
		$ts_jour_an = $ts_jour_an - 86400*( 7-$num_jour );
	else if( $num_jour == 4 )
		$ts_jour_an = $ts_jour_an - 604800;

	$ts_jour_voulu = $ts_jour_an + $numeroDeSemaine*7*86400;
	$tab=array();
	
	switch( date('w',$ts_jour_voulu )){
		case 0:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-6));
		break;
		
		case 1:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i));
		break;
		
		case 2:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-1));
		break;
		
		case 3:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-2));
		break;
		
		case 4:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-3));
		break;
		
		case 5:
		for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-4));
		break;
		
		case 6:
			for($i=0;$i<7;$i++)
			$tab[$i] = date('Y-m-d',$ts_jour_voulu+86400*($i-5));
		break;
	}
	return $tab;
}
function verif_champs_obligatoires( $activites ){
	////////////////////////////////
	// 1ere partie : Les champs génériques.
	$all_quiet = true;
	
	// on liste les champs obligatoires
	$fields = array( 	'date',
						'heure',
						'evenement',
						'communication',
						'nomclient',
						'description' );
	// Listera les fields en erreur.
	$error_fields = array();
					
	foreach( $fields as $key => $onefield  ){
		$value = str_replace( " ", "", $_POST[$onefield]);
		
		switch( $onefield ){
			// cas exceptionnel pour les menus déroulants.
			case 'evenement' : 
			case 'communication' :
				if( $_SESSION['debug'] )
					echo "Field : $onefield | Value : ".$value."<br/>";
				
				if( $value == '-1'){
					$all_quiet = false;
					$error_fields[ count( $error_fields ) ] = $onefield;
				}
				break;
			// Pour les autres types de champs
			default :
				if( $_SESSION['debug'] )
					echo "Field : $onefield | Value : ".$value."<br/>";

				if( $value == ''){
					$all_quiet = false;
					$error_fields[ count( $error_fields ) ] = $onefield;
				}
				break;
		}
	} 
	
	/////////////////////
	// 2eme partie : les champs optionnels. s'il n'y a qu'une seule activité.
	if( count( $activites ) == 1 ){
		$reqLine = "SELECT nom, correspondance FROM jdb_champs WHERE activite='".$activites[0]."' AND is_oblig = '1'";
		$req = mysql_query( $reqLine );
		while( $data = mysql_fetch_array( $req ) ){
			$value = str_replace( " ", "", $_POST[$data['correspondance']]);
		
			if( $_SESSION['debug'] )
				echo "Field : ".$data['nom']." | Value : ".$value."<br/>";

			if( $value == ''){
				$all_quiet = false;
				$error_fields[ count( $error_fields ) ] = $data['nom'];
			}			
		}
	}
	
	if( $_SESSION['debug'] )
		echo "Tous les champs sont remplis? -> ".$all_quiet."...<br/> il y a ".count( $error_fields )." erreur(s).<br/>";
		
	if( $all_quiet )
		$error_fields = true;
		
	return $error_fields;
}
function truncate_text( $text, $type ){
	$temp = '';
	switch( $type ){
		case 'accueil' :
			$temp = substr( $text, 0, $_SESSION['truncate_text_accueil']);
			if( strlen( $text ) > $_SESSION['truncate_text_accueil'] )
				$temp .= ' ...';
			break;
		default :
			$temp = substr( $text, 0, $_SESSION['truncate_text_accueil']);
			if( strlen( $text ) > $_SESSION['truncate_text_accueil'] )
				$temp .= ' ...';
			break;			
	}
	return $temp;
}
function is_pair($nombre){
	$pair = true;
	if ($nombre%2 == 1)
		$pair = false;

	return $pair;
}
function get_status_alerting( $id, $date_deb, $heure_deb, $date_fin = '', $heure_fin = '' ){
	$status = 'blanc';
	
	$reqLine = "SELECT is_acquit FROM jdb_evenements WHERE id='$id'";
	$result = mysql_fetch_array( mysql_query( $reqLine ) );
	
	if( $result['is_acquit'] != 1 ) 	
	// Si l'un ou lautre n'est pas saisie, il s'agit d'une date unique.
	if( $date_fin == '' or $heure_fin == '' ){
		if( $date_deb > date("Y/m/d") ){
			// si le jour n'est pas arrivé : VERT
			$status = 'vert';
		}else{
			if( $date_deb.' '.$heure_deb > date("Y/m/d H:i") ){
				// Si le jour est arrivé mais pas l'heure : JAUNE
				$status = 'jaune';
			}else
				// Si le jour est arrivé et l'heure aussi : ROUGE
				$status = 'rouge';
		}
	}else{
		// On est pas encore le jour J
		if( $date_deb > date("Y/m/d") ){
			$status = 'vert';
		}else{
			if( $_SESSION['debug'] ){
				echo 'Debut : '.$date_deb.' '.$heure_deb.' FIN : '.$date_fin.' '.$heure_fin.' Actuel : '.date("Y/m/d H:i").'<br/>';
			}
			// On est le jour J ou apres...
			
			// Est-on le jour J avant l'heure H?
			if( $date_deb.' '.$heure_deb > date("Y/m/d H:i") ){
				$status = 'jaune';
			// Est-on au dessus de la 1ere borne?
			}else if( $date_deb.' '.$heure_deb <= date("Y/m/d H:i") ){
				// Est-on en dessous de la 2eme borne?
				if( $date_fin.' '.$heure_fin >= date("Y/m/d H:i")){
					$status = 'rouge';
				}else{
					$status = 'blanc';
					// On en profite pour acquiter l'alarme
					acquit_alarme( $id );
				}
			} 
		}
	}
		
	return $status;
}
function get_img_status_alerting( $id, $date_deb, $heure_deb, $date_fin = '', $heure_fin = '' ){
	$status = get_status_alerting( $id, $date_deb, $heure_deb, $date_fin, $heure_fin);
	
	$img = '<a href="javascript:Couleur( '.$id.' );"><img src="'.$_SESSION['folder_images'].'reveil_'.$status.'.png" style="vertical-align: middle;" title="';
	if( $date_fin == '' or $heure_fin == '' )
		$img .= 'Alerte le '.reverse_date($date_deb ).' '.$heure_deb; 
	else
		$img .= 'Alerte du '.reverse_date($date_deb).' '.$heure_deb.' au '.reverse_date($date_fin).' '.$heure_fin; 

	$img .= '"/></a>';
	return $img;
	
}
function acquit_alarme( $id, $user = 'auto' ){

	// On va en profiter pour acquitter l'alarme.
	mysql_query( "UPDATE jdb_evenements SET is_acquit='1', alerte_date_acquit = '".date( "Y/m/d" )."', alerte_heure_acquit='".date('H:i')."', user_acquit='$user' WHERE id='$id'" ) or die(mysql_error());
	
}
function who_pop( $tab_newalerts ){

	// Pour le debug
	if( isset( $_SESSION['debug'] ) ){
		if( $_SESSION['debug'] ){
			foreach( $_SESSION['tab_haspop'] as $key => $value ){
				echo "Session = Key : $key Value : $value<br/>";
				foreach( $value as $key2 => $value2 )
					echo "Sur $key = Key : $key2 Value : $value2<br/>";
			}
			echo "<br/>";
			echo "<br/>";
			echo "<br/>";
			echo "<br/>";
			foreach( $tab_newalerts as $key => $value ){
				echo "tab_newalerts = Key : $key Value : $value<br/>";
				foreach( $value as $key2 => $value2 )
					echo "Sur $key = Key : $key2 Value : $value2<br/>";
				}
		}
	}

	// va comparer le tableau qui mémorise les alarmes avec les nouvelles alarmes.
	// s'il y en a une nouvelle : on pop.
	
	// on s'init une var pour y déposer les id des alertes à poper
	$tab_to_pop = array();
	// On check si la variable de session est init....?
	// et oui au tout 1er chargement de la page, la var n'est pas init... 
	// Il est donc intéressant d'avori un résumé des alarmes en cours.
	if( isset( $_SESSION['tab_haspop'] ) ){
		// On fait le tour du nouveau tableau
		foreach( $tab_newalerts as $key_newalert => $newalert ){
			$is_find = false;
			foreach( $_SESSION['tab_haspop'] as $key_oldalert => $oldalert)
				if( $newalert['id'] == $oldalert['id'])
					$is_find = true;
			
			
			if( !$is_find )
				$tab_to_pop[ count( $tab_to_pop ) ] = $newalert/* ['id'] */;
		}
	}else{
		// Si var de session non init ... toute les alertes sont a poper
		foreach( $tab_newalerts as $key => $newalert ){
			$tab_to_pop[ count( $tab_to_pop ) ] = $newalert/* ['id'] */;
		}
	}
	return $tab_to_pop;
}
?>