<?php

class virtual{
	
}

class init extends virtual{
	private $channel_title;
	private $channel_link;
	private $channel_description;
	
	public function __construct( $title, $link, $description ){ 
		$this->set_channel_title( $title );
		$this->set_channel_link( $link );
		$this->set_channel_description( $description );
	}
	
	public function get_channel_title(){
		return $this->channel_title;
	}
	public function get_channel_link(){
		return $this->channel_link;
	}
	public function get_channel_description(){
		return $this->channel_description;
	}
	
	public function set_channel_title( $title ){
		$this->channel_title = $title;
	}
	public function set_channel_link( $link ){
		$this->channel_link = $link;
	}
	public function set_channel_description( $description ){
		$this->channel_description = $description;
	}
}

class concat extends init {
	private $text_rss;
	
	public function __construct( $title, $link, $description ){
		parent::__construct( $title, $link, $description );
		
		$this->init_head();
	}
	
	public function get_text_rss(){
		return $this->text_rss;
	}
	
	private function init_head(){
		// édition du début du fichier XML
		$this->text_rss = '<?xml version="1.0" encoding="iso-8859-1"?><rss version="2.0">';
		$this->text_rss .= '<channel>'; 
		$this->text_rss .= '<title>'.$this->get_channel_title().'</title>';
		$this->text_rss .= '<link>'.$this->get_channel_link().'</link>';
		$this->text_rss .= '<description>'.$this->get_channel_description().'</description>';
	}
	public function add_item( $title, $lien, $date, $description ){		
		$this->text_rss .= '<item>';
		$this->text_rss .= '<title>'.$title.'</title>';
		$this->text_rss .= '<link>'.$lien.'</link>';
		$this->text_rss .= '<pubDate>'.date("D, d M Y H:i:s", strtotime(reverse_date($date))).' GMT</pubDate>'; 
		$this->text_rss .= '<description>'.$description.'</description>';
		$this->text_rss .= '</item>';	
	}
	public function close(){
		// édition de la fin du fichier XML
		$this->text_rss .= '</channel>';
		$this->text_rss .= '</rss>';
	}
}

class write extends concat {
	private $path;

	public function __construct( $title, $link, $description, $path ){
		parent::__construct( $title, $link, $description );
		
		$this->set_path( $path );
	}
	
	public function get_path(){
		return $this->path;
	}
	public function set_path( $path ){
		$this->path = $path;
	}
	
	public function close(){
		parent::close();
		
		// écriture dans le fichier
		$fp = fopen( $this->get_path(), 'w+');
		fputs($fp, $this->get_text_rss());
		fclose($fp);
	}

}


class phpmyrss extends write{
	// Classe parent qui hérite de tt les autres : plus simple pour appeler la classe.
}
?>