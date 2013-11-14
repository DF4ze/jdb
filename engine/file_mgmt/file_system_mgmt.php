<?php
/********************************************************************\ 
	ca.ortiz 25/07/2012
	file_system_mgmt.php
	v1
 *********************************************************************
 Gestion des fichiers
 - Initialiser un nom de fichier existant
	- modifier le nom selon les critères:
		- Le chemin
		- Le nom simple
		- l'extension
	le tout avec une analyse des séparateurs de dossier ( '\' ou '/')
	
	Modifie en réel le fichier initialisé.
	info : UNIQUEMENT FICHIER EN LOCAL
 \*******************************************************************/
 
require_once( "file_name_mgmt.php" );

///////////////////////////////////////
// Redef des functions de "file_name" de facon a ce que ca ait un impact sur le fichier concerné.
// nb : Si la manipulation est appliquée, alors le "file" est modifié,
//		sinon retourne false.
class file_sys_simple extends file_name
{
/*  	public function __construct( $_Params = '' ){
		
		$r = $this->set_file( $_Params['file'] );
		if( $r === false )
			return $r;
	} */
	
	public function set_file( $file ){
		if( $file != '' ){
			if( !file_exists( $file ) )
				return $this->set_error( FILE_NOTEXIST, $file );
		}
		return parent::set_file( $file );
	}	
	public function set_basename( $basename ){
		if( @rename( $this->get_file(), $this->get_dirname().$this->get_dirseparator().$basename ) )
			return parent::set_file( $this->get_dirname().$this->get_dirseparator().$basename );
		else
			return $this->set_error( FILE_NOTMODIFIED, 'set_basename' );
	}
	public function set_extension( $extension ){
		if( @rename( $this->get_file(), $this->get_dirname().$this->get_dirseparator().$this->get_filename().'.'.$extension ))
			return $this->set_file( $this->get_dirname().$this->get_dirseparator().$this->get_filename().'.'.$extension );
		else
			return $this->set_error( FILE_NOTMODIFIED, 'set_extension' );
	}
	public function set_dirname( $dirname ){
		$temp = new file_name( $dirname );
		if( @rename( $this->get_file(), $dirname.$temp->get_dirseparator().$this->get_basename() ))
			return $this->set_file( $dirname.$temp->get_dirseparator().$this->get_basename() );
		else
			return $this->set_error( FILE_NOTMODIFIED, 'set_dirname' );
	}
	public function set_filename( $filename ){
		if( @rename( $this->get_file(), $this->get_dirname().$this->get_dirseparator().$filename.'.'.$this->get_extension() ))
			return $this->set_file( $this->get_dirname().$this->get_dirseparator().$filename.'.'.$this->get_extension() );
		else
			return $this->set_error( FILE_NOTMODIFIED, 'set_filename' );
	}
	
	public function clean_file(){
		return $this->set_basename( parent::clean_file( $this->get_basename() ));
	}
}

/////////////////////////////////////////////////
// Permet d'ajout d'un préfix ou postfix.
class file_sys_complement extends file_sys_simple
{
	private $fix; // texte qui sera mis en pré/postfix
	private $location; // pré ou post fix
	private $fixseparator; // séparateur du fix
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params['file'] );
		
		$this->set_fix( $_Params['fix'] );
		$this->set_fixseparator( $_Params['fixseparator'] );
		$this->set_location( $_Params['location'] );
		
		if( isset( $_Params['fix'] ) )
		if( $_Params['fix'] != '' )
			$this->apply_fix();
	}
	
	public function get_fix(){
		return $this->fix;
	}
	public function get_location(){
		return $this->location;
	}
	public function get_fixseparator(){
		return $this->fixseparator;
	}
	
	public function set_fix( $fix = '' ){
		$this->fix = $fix;
	}
	public function set_location( $location = '' ){
		$this->location = $location;
	}
	public function set_fixseparator( $fixseparator = '' ){
		$this->fixseparator = $fixseparator;
	}
	
	public function apply_fix(){
		switch( $this->location ){
			case 'END' :
				if( $this->set_filename( $this->get_filename().$this->get_fixseparator().$this->get_fix() ) )
					return $this->get_file();
				else
					return false;
				break;
				
			case 'BEGIN' : 	
			
			default :
				if( $this->set_filename( $this->get_fix().$this->get_fixseparator().$this->get_filename() ) )
					return $this->get_file();
				else
					return false;
				break;
		}
	}
}


// Class Maitre
class file_system extends file_sys_complement
{
	const SEPARATOR_NOTFOUND = 2;
	const FILE_NOTMODIFIED = 3;
	const FILE_NOTEXIST = 5;

	private function set_tab_error(){	
		parent::set_tab_error();
		
		$temp_error[0] = 2;
		$temp_error[1] = "Pas de séparateur de dossier {} dans la chaine fournie";
		$temp_error[2] = "SEPARATOR_NOTFOUND";
		$this->add_error_code( $temp_error );

		$temp_error[0] = 3;
		$temp_error[1] = "L'action demandée {} sur le fichier n'a pu être réalisée";
		$temp_error[2] = "FILE_NOTMODIFIED";
		$this->add_error_code( $temp_error );
		
		$temp_error[0] = 5;
		$temp_error[1] = "Le fichier {} n'existe pas";
		$temp_error[2] = "FILE_NOTEXIST";
		$this->add_error_code( $temp_error );
	}
}
?>