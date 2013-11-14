<?php
	session_start();
	require_once( "engine/conf.php" );
	require_once( $_SESSION['folder_engine']."functions.php" );
	
	// Ici on ne peut pas récupérer la variable qu'il y a en BDD ... car on n'est pas encore connecté...
	$debug = false;
	
	// Si on a demandé de sortir ... on kill les variables de sessions.
	if( isset( $_GET['logout'] ) ){
		session_destroy();
	}
	
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

	// Permettra de savoir la raison de l'echec de connexion.
	$raison = '';
	// Si on a submit
	if( isset( $_POST['envoyer'] ) ){
		// Si il y a une ligne qui correspond aux login et pwd cherché.
		if( mysql_num_rows( mysql_query("SELECT * FROM jdb_logins WHERE user='".$_POST['user']."' AND pwd='".$_POST['pwd']."'"))){
			// On récup les infos du user.
			$data = mysql_fetch_array( mysql_query("SELECT * FROM jdb_logins WHERE user='".$_POST['user']."' AND pwd='".$_POST['pwd']."'"));
			$_SESSION['user'] = $data['user'];
			$_SESSION['name'] = $data['name'];
			$_SESSION['pwd'] = $data['pwd'];
			$_SESSION['admin'] = $data['admin'];
			$_SESSION['actived'] = $data['actived'];
			
			// Vérif si le compte est actif.
			if( $_SESSION['actived'] == '1' ){
				$_SESSION['logued'] = true;
				if( $debug )
					echo "Logued!<br/>";
			}else{ // Si compte desactivé
				$_SESSION['logued'] = false;
				$raison = "Compte desactivé";
				if( $debug )
					echo "Desactived!!<br/>";
			}
		}else{ // Si pas de correspondance USER/PWD
			$_SESSION['logued'] = false;
			$raison = "Nom d'utilisateur ou mot de passe incorrect";
			if( $debug )
				echo "User ou mdp!!!<br/>";
		}
		
		// Si logué, 
		if( $_SESSION['logued'] ){
			// on init les variables générales.
			init();

			// On enregistre la date de connexion.
			$reqLine = "UPDATE jdb_logins SET last_connect = '".date("d/m/Y H:i")."' WHERE user ='".$_SESSION['user']."'";
			mysql_query( $reqLine );
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_SESSION['folder_images'] ?>jdbico.ico" />
	<title><?php echo "Journaux de Bord CSS" ?></title>
	<?php
		if( isset( $_SESSION['logued'] ) )
			if( $_SESSION['logued']  )
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=accueil.php">';
	?>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
 
</head>
<body>	



<?php
	echo '<div class="corps_background">';
	echo '<div class="bidon">';
	echo '<div class="div_login">';
	
	if( isset( $_SESSION['logued'] ) )
		if( $_SESSION['logued']  )
			echo "Vous vous êtes correctement identifer,<br/>Veuillez patienter...<br/>";
		else{
			echo '<div class="red">'.$raison.'</div>';	
		}
	echo '<form method="post" id="login" name="login" action="index.php">';
		echo '<br/>Utilisateur : <br/><input type="text" name="user" autofocus/><br/><br/>';
		echo 'Mot de passe :<br/> <input type="password" name="pwd"/><br/><br/>';
		echo '<input type="submit" name="envoyer" value="Login" style="float:right"/><br/>';
	echo '</form>';
	
	echo '</div>';
	echo '</div>';
	echo '</div>';
	
	affiche_pied(__FILE__);
?>

</body>
</html>