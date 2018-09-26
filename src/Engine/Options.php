<?php
namespace Guacamole\Engine;

abstract class Options extends OptionsHandler {

	public function setOptions( array $options = array() ){
		$this->options = $options;
	}

	public function getOptions(){
		return $this->options;
	}

	public function getOption( string $name ){
		return isset($this->options[$name]) ? $this->options[$name] : null;
 	}
	
	
}