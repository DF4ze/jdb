<?php session_start(); 
	require_once( "engine/conf.php" );
	require_once( $_SESSION['folder_engine']."functions.php" );
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
	
	
	
	//////////////////////////////
	// Réception des variables
	if( isset( $_POST['modify'] ) ){
		if( mysql_num_rows( mysql_query("SELECT * FROM jdb_logins WHERE user='".$_SESSION['user']."' AND pwd='".$_POST['pwd_now']."'"))){	
			if( $_POST['pwd_1'] == $_POST['pwd_2']){
				$reqLine = "UPDATE jdb_logins SET
							pwd = '".$_POST['pwd_1']."' WHERE user='".$_SESSION['user']."'";
							
				if( $debug )
					echo $reqLine.'<br/>';

				if(	mysql_query( $reqLine ) ) 
					$_SESSION['message']= "Mise à jour avec succès.<br/>";
				else 
					$_SESSION['message']= "Erreur de modification du mot de passe<br/>".mysql_error() ;

			}else
				$_SESSION['message'] = "Les champs du nouveau mot de passe ne correspondent pas.";
		}else
			$_SESSION['message'] = "Mot de passe actuel incorrect pour ".$_SESSION['user'].".";
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	affiche_menu( __FILE__ );	

	echo '<div class="corps_background">';
	

	echo '<div class = "bidon">';
	echo '<div class = "corps_general">';
	echo '<div class = "corps_titre">';
	echo "Gstion de votre compte : <br/>";
	echo '</div>';

	echo '<div class = "corps_corps">';

	echo '<strong>Se déconnecter : </strong><a href="logout.php">ici</a><br/><br/><br/><br/>';
	
	echo '<table>';
	echo '<strong>Modifier mon mot de passe : </strong><br/>';
	echo '<form action="myaccount.php" method="post" id="form" name="form">';
	echo '<tr><td style="border:none">Mot de passe actuel : </td><td style="border:none"><input type="password" name="pwd_now"/></td></tr>';
	echo '<tr><td style="border:none">Nouveau mot de passe : </td><td style="border:none"><input type="password" name="pwd_1"/></td></tr>';
	echo '<tr><td style="border:none">Saisissez de nouveau : </td><td style="border:none"><input type="password" name="pwd_2"/></td>';
	echo '<td style="border:none"><input type="submit" name="modify" value="Modifier" class="create"> </td></tr>';
	echo '</form>';
	echo '</table>';
	
	echo '</div></div></div>';
	
	
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
	
	
	
	
	echo '</div class="BackGround">';
	affiche_pied();
?>
</body>
</html>