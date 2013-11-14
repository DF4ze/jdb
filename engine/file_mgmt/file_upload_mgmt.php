<?php

/********************************************************************\ 
	ca.ortiz 25/07/2012
	file_upload_mgmt.php
	v1
 *********************************************************************
 Gestion des uploads
	fournir le fichier receptionner
	- filtrage sur l'extension
	- le d�placera dans le dossier souhait� avec un post/pr�fixe souhait�.
 \*******************************************************************/
 
require_once( "file_system_mgmt.php" );

// Va permettre de r�cup�rer un fichier upload�
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
		$temp_error[1] = "Le chemin d'acc�s {} n'a pas �t� initialis�";
		$temp_error[2] = "PATH_NOTINIT";
		$this->add_error_code( $temp_error );

		// $temp_error[0] = FILE_NOTMODIFIED;
		// $temp_error[1] = "L'action demand�e {} sur le fichier n'a pu �tre r�alis�e";
		// $temp_error[2] = "FILE_NOTMODIFIED";
		// $this->add_error_code( $temp_error );
	}
}
?>