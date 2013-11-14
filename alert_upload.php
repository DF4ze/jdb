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

	//SI un ID d efichier est post� c'est qu'il est demand� de le supprimer.
/* 	if(isset($_POST['id_file']))
		if( deleteFile($_POST['id_file']) )
			echo "<h3>Fichier supprim�.</h3>";
		else
			echo "<h3>Une erreur s'est produite lors de la suppression du fichier.</h3>";
 */					
	
	// S'il n'y a pas de fichier envoy�, on initialise la variable avec un nom de fichier bidon.
	if( !isset($_FILES['monfichier']) )
		$_FILES['monfichier']="http://dtc.com/bienaufond.dtc";
		
	$nomOrigine = $_FILES['monfichier']['name'];
	//$elementsChemin = pathinfo($nomOrigine);
	$extensionFichier = pathinfo($nomOrigine,PATHINFO_EXTENSION);
	//$extensionFichier = $elementsChemin['EXTENSION'];
	$extensionsNonAutorisees = array("php", "php2", "php3", "aspx", "asp", "html", "htm", "xml", "css", "js", "ini", "ink", "exe", "bat", "msi", "cab");
	$cheminupload = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$cheminupload = str_replace("alert_upload.php","uploads/",$cheminupload);
			
				
	//s'il y a un fichier d'envoy�, on traite.
	if($nomOrigine != "" and $_FILES['monfichier']!= "http://dtc.com/bienaufond.dtc"){
		// Met l'extension en minuscule de facon a avoir une meilleure comparaison.
		$extensionFichier = strtolower($extensionFichier);
					
		if ((in_array($extensionFichier, $extensionsNonAutorisees)))
			echo "<h3>!! Ce type de fichier ne peut pas �tre envoy� !!</h3>";
		else{    
			//traitement du nom de fichier de facon � ce qu'il n'ait pas d'espace puis on ajoute le nom de l'utilisateur.
			$nomDestination = str_replace(" ", "_" , $nomOrigine);
			$nomDestination = $_SESSION['user']."_".$nomDestination;
			
			// Copie dans le repertoire du script avec un nom
			// incluant l'heure a la seconde pres 
			//$nomDestination = "fichier_du_".date("YmdHis").".".$extensionFichier;
			$repertoireDestination = dirname(__FILE__).'/uploads/';
			
			// Connexion a la base de donn�es.
			// mysql_connect($_SESSION['serveurName'], $_SESSION['serveurUserName'], $_SESSION['serveurUserPwd']) or die ("Connexion au serveur impossible");
			// mysql_select_db($_SESSION['serveurSqlBase']) or die ("Connexion a la base impossible");
						
			//On regarde si le fichier n'aurait pas deja �tait upload�
			if( true/* !mysql_num_rows( mysql_query("SELECT * FROM uploads WHERE nom_fichier='$nomDestination'")) */)
			{
				//Sinon on l'upload.
				//echo "chemin du fichier temporaire : ".$_FILES["monfichier"]["tmp_name"]."<br/>";
				if (move_uploaded_file($_FILES["monfichier"]["tmp_name"], $repertoireDestination.$nomDestination)) 
				{
					// Cr�ation du lien vers le fichier upload�.								
					$lien = '<a href="'.$cheminupload.$nomDestination.'" title="'.$nomDestination.'">'.$cheminupload.$nomDestination.'</a>';
					echo "<h3>!! Votre fichier a bien �tait envoy� !!</h3> Il s'appelle : ".$nomDestination."<br/>";
					echo "Il se trouve � l'adresse suivante : <br/>";
					echo $lien."<br/>";
								
					//creation d'une entr�e BD pour le nouveau fichier.
					$user = $_SESSION['user'];
					if( !mysql_query("INSERT INTO uploads VALUES('', '$nomDestination', '$user', '".date( 'Y/m/d' )."', '')")){
						echo "Une erreur s'est tout de m�me pr�sent�e, le fichier est pr�sent sur le serveur mais des erreurs peuvent survenir par la suite,<br/>Merci de pr�venir le WebMaster.<br/><br/>";
					}else{
						//pour test
						//echo "BD OK! <br/><br/>";
						//echo $repertoireDestination."<br/><br/>";
					}	
					
					echo "<br/>";
				}
				else
				{
					//Pour test
					//echo $repertoireDestination.$nomDestination."<br/>";
					echo "Le fichier n'a pas �t� upload� (doit �tre < 2Mo) ou ".
							"Le d�placement du fichier temporaire a �chou�".
							" v�rifiez l'existence du r�pertoire ".$repertoireDestination."<br/><br/>";
				}
			}
			else
			{
				echo "<h3>!! Vous avez d�j� envoy� ce fichier !!</h3>";
				
				$requete = mysql_query("SELECT * FROM upload WHERE nom='$nomDestination'");
				$donnees = mysql_fetch_array($requete);
				echo "Il s'appelle : ".$donnees['nom']."<br/>";
				echo "Il se trouve � l'adresse suivante : <br/>";
				echo $donnees['lien']."<br/><br/>";	
			}
					
			// On se d�connecte de MySQL
			mysql_close();
		}
	}
				
				
				
				
				
				
	
	echo '<div class = "corps_general" >';
	echo '<div class = "corps_titre">';
	echo "Uploader un fichier :<br/>";
	echo '</div>';
		
	echo '<div class = "corps_corps">';
		echo '<form enctype="multipart/form-data" action="alert_upload.php" method="post" >
				S�lectionnez le fichier � envoyer <input type="file" name="monfichier" />
				<input type="submit" value="Envoyer"/>
			</form>';
	
	echo '</div>';
	echo '</div>';

	

?>
</body>	
</html>