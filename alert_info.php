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

	  
</head>
<body>	
<?php 
	connect_bdd();

	echo '<div class = "corps_general" >';
	echo '<div class = "corps_titre">';
	echo "Modifier l'alerting :<br/>";
	echo '</div>';
		
	echo '<div class = "corps_corps">';

	if( isset( $_GET['id'] ) ){
		$reqLine = "SELECT * FROM jdb_evenements WHERE id='".$_GET['id']."'";
		$req = mysql_query( $reqLine );
		$data = mysql_fetch_array( $req );

		if( $_SESSION['debug'] ){
			echo "Coucou id ".$_GET['id']."<br/>";
			echo "Req : $reqLine<br/><br/>";
		}
		
		if( $_SESSION['admin'] or $_SESSION['name'] == $data['auteur'] ){
			echo '<form action="alert_info.php" method="POST">';
				echo 'Début Alerte : <input type="text" name="alerte_date" class="input_date" value="'.$data['alerte_date'].'" placeholder="Date Début"/>';
				echo '<input type="text" name="alerte_heure" class="input_heure" value="'.$data['alerte_heure'].'" placeholder="Heure Début" /><br/>';
				if( $data['alerting_fin'] == 1){
					echo 'Fin Alerte : <input type="text" name="alerte_date_fin" class="input_date" value="'.$data['alerte_date_fin'].'" placeholder="Date Fin"/>';
					echo '<input name="alerte_heure_fin" type="text" class="input_heure" value="'.$data['alerte_heure_fin'].'" placeholder="Heure Fin" /><br/>';
				}
				echo 'Acquiter l\'alerte ? <input type="checkbox" name="is_acquit" '; if( $data['is_acquit'] == 1 ) echo " checked "; echo '/><br/><br/>';
				
				echo '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
				echo '<input type="submit" name="go" value="Modifier"/>';
			echo '</form>';
		}else
			echo "Vous n'avez pas les droits pour modifier cette alerte<br/>Elle ne peut etre modifier que par son propriétaire ou un administrateur.<br/>";


	}else if( isset( $_POST['go'] ) ){
		$reqLine = "UPDATE jdb_evenements SET alerte_date='".$_POST['alerte_date']."',
											alerte_heure='".$_POST['alerte_heure']."', 
											alerte_date_fin='".@$_POST['alerte_date_fin']."', 
											alerte_heure_fin='".@$_POST['alerte_heure_fin']."'";
		if( isset( $_POST['is_acquit'] ) ){
			$reqLine .= ", is_acquit = '1'";
			$reqLine .= ", alerte_date_acquit = '".date( 'Y/m/d' )."'";
			$reqLine .= ", alerte_heure_acquit = '".date( 'H:i' )."'";
			$reqLine .= ", user_acquit = '".$_SESSION['name']."'";
			
		}else
			$reqLine .= ", is_acquit = '0'";
		
		$reqLine .= " WHERE id='".$_POST['id']."'";
		
		if( $_SESSION['debug'] )
			echo "reqLine : $reqLine<br/>";
		
		if( mysql_query( $reqLine ) )
			echo "Modification avec succès de l'entrée.<br/>";
		else
			echo "Erreur lors de la modification l'entrée.<br/>";
		
	}else
		echo "Erreur d'appel de cette popup :)";
	
	echo '</div>';
	echo '</div>';

	

?>
</body>	
</html>