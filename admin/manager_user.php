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
	</script>
		  
</head>
<body>	
<?php

	// $debug = true;
	$debug = $_SESSION['debug'];
	$nb_options_max = $_SESSION['nb_options_max'];
	
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
		Gestion des utilisateurs :
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
	// Ajout/Modif 
	if( isset( $_POST['go'] ) ){
		$nick = normalyze_string( $_POST['name'] );
		
		if( $_POST['name'] != '' and $_POST['user'] != '' and $_POST['pwd'] != '' )
		if( $_POST['modif'] == ''){
			$admin=0;
			if( isset($_POST['admin']) )
				$admin = 1;
			$actived=0;
			if( isset($_POST['actived']) )
				$actived = 1;
		
			
			// Préparation de la requete d'insertion.
			$reqLine = "INSERT INTO jdb_logins VALUES
							('','".mysql_real_escape_string(htmlspecialchars($_POST['user']))."',
							'".mysql_real_escape_string(htmlspecialchars($_POST['name']))."',
							'".$_POST['pwd']."',
							'".$admin."',
							'".$actived."')";
				
			if( $debug )
				echo "Req Insertion : ".$reqLine.'<br/>';
			// Exécution de la requete.
			mysql_query( $reqLine ) 
							or $_SESSION['message']= "Erreur de création du champs<br/>".mysql_error();
			$_SESSION['message']= "Insertion avec succes.<br/>";
			
			
		}else{
			$reqLine = "UPDATE jdb_logins SET
							name = '".mysql_real_escape_string(htmlspecialchars($_POST['name']))."',
							user = '".mysql_real_escape_string(htmlspecialchars($_POST['user']))."',
							pwd = '".$_POST['pwd']."',
							admin = ";
			if( isset( $_POST['admin'] ) )
				$reqLine .= "'1'";
			else
				$reqLine .= "'0'";

			$reqLine .= ", actived = ";
			if( isset( $_POST['actived'] ) )
				$reqLine .= "'1'";
			else
				$reqLine .= "'0'";
				
			$reqLine .= " WHERE id = '".$_POST['modif']."'";
							
			if( $debug )
				echo $reqLine.'<br/>';

			if(	mysql_query( $reqLine ) ) 
				$_SESSION['message']= "Mise à jour avec succès.<br/>";
			else 
				$_SESSION['message']= "Erreur de modification du champs<br/>".mysql_error() ;
		}
	}

	//////////////////////////////////////
	// Suppression activité
	if( isset( $_GET['conf_suppr'] ) ){
		$_SESSION['message']= "Etes-vous sur de vouloir supprimer : ".$_GET['name'].' ? 
			<a href="manager_user.php?suppr='.$_GET['conf_suppr'].'">OUI</a> ou 
			<a href="manager_user.php">NON</a><br/>';
	}
	if( isset( $_GET['suppr'] ) ){
		$reqLine = "DELETE FROM `jdb_logins` WHERE `id` = ".$_GET['suppr'];
		mysql_query( $reqLine ) 
						or $_SESSION['message']= "Erreur lors de la suppression<br/>".mysql_error() ;
		$_SESSION['message']= "Suppression avec succès.<br/>";
	}

	
	
	
	
	
	
	
	//////////////////////////////////////////////////
	// Formulaire d'ajout d'une nouvelle activité.

	echo '<form method="post" id="form_filtre" name="form_filtre" action="manager_user.php">';
	
	if( isset( $_GET['modif'] ) ){
		$data = mysql_fetch_array(mysql_query( "SELECT * FROM jdb_logins WHERE id='".$_GET['modif']."'" ));
	}
	
		echo '<input type="text" name="name" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['name'];
		echo '" placeholder="Nom" title="Nom tel qu\'il apparaitra dans le JDB"/>';
		
		echo '<input type="text" name="user" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['user'];
		echo '" placeholder="Nom d\'utilisateur" title="Identifiant qui permettra d\'ouvrir l\'application"/>';

		
		echo '<input type="password" name="pwd" value="';
		if( isset( $_GET['modif'] ) )
			echo $data['pwd'];
		echo '" placeholder="Mot de passe"/>';

		echo '<span title="L\'utilisateur est-il administrateur?"><input type="checkbox" name="admin" ';
		if( isset( $_GET['modif'] ) )
			if( $data['admin'] == '1' )
				echo 'checked';
		echo ' />Administrateur?</span>';
		echo '<span title="Le compte est-il activé?"><input type="checkbox" name="actived" ';
		if( isset( $_GET['modif'] ) ){
			if( $data['actived'] == '1' )
				echo 'checked';
		}else
			echo 'checked';
		echo ' />Activé?</span>';

		echo '<input type="hidden" name="modif" value="';
		if( isset( $_GET['modif'] ) )
			echo $_GET['modif'];
		echo '"/>';
		
		echo '<input type="submit" name="go" value="Go!" class="create"/><br/>';
	
	echo '</form>';
	echo '</div>';
	echo '</div>';



	
	
	
	
	
	
	
	
	
	
	
	echo '<div class="corps_general">';
	echo '<div class="corps_titre">';
		echo "Listing";
	echo '</div>';

	echo '<div class="corps_corps">';
	


	///////////////////////////////////////////////
	// Listing des activité.
	
	echo '<table>';
	echo '<tr>';
		echo '<th > </th>';
		echo '<th > </th>';
		echo '<th> Nom </th>';
		echo '<th> User </th>';
		echo '<th> MDP </th>';
		echo '<th> Admin </th>';
		echo '<th> Actived </th>';
		if( $_SESSION['user'] == 'c.ortiz' )
			echo '<th> last_connect </th>';
	echo '</tr>';
	
	$reqLine = "SELECT * FROM jdb_logins";
		
	if( $debug )
		echo $reqLine.'<br/>';
		
	$req = mysql_query( $reqLine );
	$count_line = 0;
	while( $data = mysql_fetch_array( $req )){
		$is_pair = is_pair( $count_line++ );
		echo '<tr>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_user.php?modif='.$data['id'].'" title="Editer"><img src="../'.$_SESSION['folder_images'].'edit.gif" /></a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' ><a href="manager_user.php?conf_suppr='.$data['id'].'&name='.$data['name'].'" title="Supprimer"><img src="../'.$_SESSION['folder_images'].'delete.gif" /></a></td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['name'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['user'].'</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >***</td>';/* $data['pwd'] */
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';if( $data['admin'] == '1' ) echo '<img src="../'.$_SESSION['folder_images'].'true.gif">'; else echo '<img src="../'.$_SESSION['folder_images'].'false.gif">'; echo '</td>';
			echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >';if( $data['actived'] == '1' ) echo '<img src="../'.$_SESSION['folder_images'].'true.gif">'; else echo '<img src="../'.$_SESSION['folder_images'].'false.gif">'; echo '</td>';
			if( $_SESSION['user'] == 'c.ortiz' )
				echo '<td '; if( $is_pair ) echo 'style="background-color:#E2EAEB;"'; echo ' >'.$data['last_connect'].'</td>';
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