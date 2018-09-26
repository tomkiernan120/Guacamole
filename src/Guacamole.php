<?php

namespace Guacamole; 

use Guacamole\Util\HTMLCollection as HTMLCollection;
use Guacamole\Engine\Renderer as Renderer;

class Guacamole {

	CONST VERSION = "1.0";

	use Helper\File;
	use Helper\Clean;

	private $templateDirectory;
	private $options;
	public $globals;

	public $root;

	protected $renderer;

	public function __construct( $templateDirectory = null, array $options = array(), array $globals = array() )
	{
			$this->setTemplateDirectory( $templateDirectory );
	    $this->options = $options;
	    $this->globals = $globals;
	    $this->HTMLCollection = new HTMLCollection();
	    $this->renderer = new Renderer( $globals, $this->HTMLCollection );
	}

	public function setTemplateDirectory( string $templateDirectory = null ){
		$this->templateDirectory = $templateDirectory;		
	}

	public function getTemplateDirectory(){
		return $this->templateDirectory;
	}

	public function setOptions( array $options = array() ){
		$this->options = $options;
		return $this;
	}

	public function getOptions(){
		return $this->options;
	}

	public function setGlobals( array $globals = array() ) {
		$this->globals = $globals;
		return $this;
	}

	public function getGlobals() {
		return $this->globals;
	}


	public function process( string $template, $data = array(), $return = null )
	{
		if( $this->fileExists( $this->getTemplateDirectory().$template.".php" ) ){
			$this->load( $template, $data, $return );
		}
		else {
			$template = $this->HTMLCollection->processHTML( $template );
			echo eval( addslashes( $template ) );
		}
	}

	public function load( string $template, array $data = array(), $return = null )
	{
		
		$file = false;

		if( $this->fileExists(  $this->templateDirectory.$template.".php" ) ){
			$file = true;
		}
		else {
			trigger_error( "Could not find file {$this->templateDirectory}{$template}.php", E_USER_ERROR );
			return false;
		}

		if( count( $this->globals ) ){
			foreach( $this->globals as $key  => $value) {
				$$key = $this->clean($value);
			}
		}

		if( count( $data ) ){
			foreach( $data as $key => $value ){
				$$key = $this->clean( $value );
			}
			unset( $data );
		}


		ob_start();
		require_once $this->templateDirectory.$template.".php";
		$template = $this->renderer->process( ob_get_contents() );
		ob_get_clean();

		if( $return === null ){
			echo $template;
			return;
		}
		return $template;

	}

}