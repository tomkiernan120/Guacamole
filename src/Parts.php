<?php
namespace Guacamole;

class Parts implements MainInterace {

	protected $parts = array();

	public function __construct( array $parts ){
		$this->setParts( $parts );
	}

	public function setParts( array $parts = array() ){
		$this->parts = $parts;
		return $this;
	}

	public function getParts(){
		return $this->parts;
	}

}