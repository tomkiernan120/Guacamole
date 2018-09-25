<?php

namespace Gaucamole; 

final class Gaucamole {

	CONST VERSION = "1.0";
	CONST REV = 1;

	use File\FileHelper;

	private $templateDirectory;
	private $options;
	public $globals;

	/**
	 * @param string/null
	 * @param array
	 * @param array
	 */
	public function __construct( $templateDirectory = null, array $options = array(), array $globals = array() )
	{
	    $this->templateDirectory = $templateDirectory;
	    $this->options = $options;
	    $this->globals = $globals;
	}

	/**
	 * @param  string
	 * @param  array
	 * @param  boolean
	 * @return boolean/template
	 */
	public function load( string $template, array $data = array(), $return = null )
	{
		
		if( $this->fileExists(  $this->templateDirectory.$template.".php" ) ){

			if( count( $this->globals ) ){
				foreach( $this->globals as $key  => $value) {
					$$key = $value;
				}
			}


			if( count( $data ) ){
				foreach( $data as $key => $value ){
					$$key = $value;
				}
			unset( $data );
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
		else {
			trigger_error( "Could not find file {$this->templateDirectory}{$template}.php", E_USER_ERROR );
			return false;
		}

	}

}