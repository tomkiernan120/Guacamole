<?php
namespace Gaucamole; 

 final class Gaucamole {

	CONST VERSION = "1.0";
	CONST REV = 1;

	private $templateDirectory;
	private $options;
	public $globals;

	public function __construct( $templateDirectory = null, array $options = array(), array $globals = array() )
	{
        $this->templateDirectory = $templateDirectory;
        $this->options = $options;
        $
	}

	public function load( $template, array $data = array(), $return = null ){

		if( file_exists(  $this->templateDirectory.$template.".php" ) ){
			if( count( $data ) ){
				foreach( $data as $key => $value ){
					$$key = $value;
				}
				unset( $data );

				foreach( $this->globals as $key  => $value) {
					$$key = $value;
				}
				ob_start();

				require_once $this->templateDirectory.$template.".php";
				$template = ob_get_contents();
				ob_get_clean();

				if( $return === null ){
					echo $template;
				}
				else {
					return $template;
				}

			}
		}
		else {
			trigger_error( "Could not find file {$this->templateDirectory.$template}.php", E_USER_ERROR );
			return false;
		}

	}

}