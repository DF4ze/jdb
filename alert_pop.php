<?php
	session_start();
	require_once( 'engine/conf.php' );
	require_once( $_SESSION['folder_engine'].'functions.php' );

	///////////////////////////
	// Variables Réceptionnées
	if( $_SESSION['debug'] ){
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
				
				
		// Pour le debug :
		if( $_SESSION['debug'] )
		if( isset( $_SESSION["result_new"] ) ){
			echo "Result New : <br/>";
			foreach( $_SESSION["result_new"] as $key => $id )
				echo "Key : $key ID : ".$id['id']."<br/>";
			
			echo "Result Old : <br/>";
			foreach( $_SESSION['tab_haspop'] as $key => $id )
				echo "Key : $key ID : ".$id['id']."<br/>";
		}
		
		// Plus qu'a retirer les New des OLD et nous avons les "en cours"
		if( isset( $_SESSION["result_new"] ) ){
			echo '<div class = "corps_general_accueil">';
			echo '<div class = "corps_titre">';
			echo 'Nouvelles Alertes : <br/>';
			echo '</div>';
			
			echo '<div class = "corps_corps">';
			foreach( $_SESSION["result_new"] as $key => $id )
				echo $id['lien']."<br/>";
			echo '</div>';
			echo '</div>';
			
			
			
			
			echo '<div class = "corps_general_accueil">';
			echo '<div class = "corps_titre">';
			echo 'Alertes en cours : <br/>';
			echo '</div>';
			
			echo '<div class = "corps_corps">';
			foreach( $_SESSION['tab_haspop'] as $key => $id )
				echo $id['lien']."<br/>";
			
			echo '</div>';
			echo '</div>';
		}
	?>
</head>

<body>




</body>

</html>