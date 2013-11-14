<?php

/********************************************************************\ 
	ca.ortiz 25/07/2012
	file_upload_mgmt.php
	v1
 *********************************************************************
 Gestion des uploads
	fournir le fichier receptionner
	- filtrage sur l'extension
	- le déplacera dans le dossier souhaité avec un post/préfixe souhaité.
 \*******************************************************************/
 
require_once( "file_system_mgmt.php" );

// Va permettre de récupérer un fichier uploadé
// et le ranger dans le dossier d'upload.
class up_and_class extends file_system
{
	private up_folder; // chemin relatif
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params );
		
		if( isset( $_Params['up_folder'] ) )
			$this->set_up_folder( $_Params['up_folder'] );
		else
			$this->set_up_folder( '' );
			
	}
	
	public function set_up_folder( $up_folder ){
		$this->up_folder = $up_folder;
	}
	public function get_up_folder(){
		return $this->up_folder;
	}

	public function class_uploaded_file(){
		
	}
	private function relative_to_absolute_folder( $relativefolder ){
		if( $this->up_folder != '' ){
			$absolutefolder = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
			
			return $absolutefolder;
		}else
			return $this->set_error( PATH_NOTINIT, '"upload"' );
	}
}


// class maitre
class file_upload
{
	const PATH_NOTINIT = 4;
	
	private function set_tab_error(){	
		parent::set_tab_error();
		
		$temp_error[0] = 4;
		$temp_error[1] = "Le chemin d'accès {} n'a pas été initialisé";
		$temp_error[2] = "PATH_NOTINIT";
		$this->add_error_code( $temp_error );

		// $temp_error[0] = FILE_NOTMODIFIED;
		// $temp_error[1] = "L'action demandée {} sur le fichier n'a pu être réalisée";
		// $temp_error[2] = "FILE_NOTMODIFIED";
		// $this->add_error_code( $temp_error );
	}
}
?>