<?php


require_once( "file_name_mgmt.php" );
$file = "http://www.test.com/index.php";
$f = new file_name( $file );

echo "<strong> file_name_mgmt </strong><br/>";

echo 'get_file : '.$f->get_file().'<br/>';
echo 'get_basename : '.$f->get_basename().'<br/>';
echo 'get_filename : '.$f->get_filename().'<br/>';
echo 'get_dirname : '.$f->get_dirname().'<br/>';
echo 'get_extension : '.$f->get_extension().'<br/><br/>';

echo 'set_file : '.$f->set_file( 'https://www/test/com/in/dex.html' ).'<br/>';
echo 'Set_basename : '.$f->set_basename( 'dev.htm' ).'<br/>';
echo 'Set_filename : '.$f->set_filename( 'developpement' ).'<br/>';
echo 'Set_dirname : '.$f->set_dirname( 'www.test.com' ).'<br/>';
echo 'Set_extension : '.$f->set_extension( 'dev' ).'<br/><br/>';

echo 'set_file : '.$f->set_file( 'c:\\dossier test\fichier.test' ).'<br/>';
echo 'Set_basename : '.$f->set_basename( 'dev.htm' ).'<br/>';
echo 'Set_filename : '.$f->set_filename( 'developpement' ).'<br/>';
echo 'Set_dirname : '.$f->set_dirname( 'www.test.com/dossier' ).'<br/>';
echo 'Set_extension : '.$f->set_extension( 'dev' ).'<br/><br/>';


require_once( "file_system_mgmt.php" );
echo "<strong> file_name_mgmt </strong><br/>";

$_Params['file'] = "test/test.test";
$f = new file_system( $_Params );

if( $f !== false )	
	echo "Create SuccessFull : ".$f->get_file()."<br/>";
else
	echo "Erreur a l'init... :( <br/>";

/*
//echo 'set_file : '.$f->set_file( 'c:\\dossier test\fichier.test' ).'<br/>';
echo 'Set_basename : '.$f->set_basename( 'dev.htm' ).'<br/>';
echo 'Set_filename : '.$f->set_filename( 'developpement' ).'<br/>';
echo 'Set_dirname : '.$f->set_dirname( 'test/test/' ).'<br/>';
echo 'Set_extension : '.$f->set_extension( 'dev' ).'<br/><br/>';


$f->set_fix('123456');
echo 'set_fix : '.$f->apply_fix().'<br/>';
$f->set_fixseparator(' - ');
echo 'set_separator : '.$f->apply_fix().'<br/>';
$f->set_location('END');
echo 'set_location : '.$f->apply_fix().'<br/>';
echo 'file : '.$f."<br/><br/>";
 */
echo 'Set_basename : '.$f->set_basename('test')."<br/>";


$_Params['file'] = "test/test.test";
$_Params['fix'] = "fix";
$_Params['fixseparator'] = '_';
$_Params['location'] = 'END';

$f = new file_system( $_Params );
echo 'On Create : '.$f->get_file().'<br/>';
$f->set_basename('test');
 
?>