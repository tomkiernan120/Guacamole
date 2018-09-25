<?php
namespace Gaucamole\Util;

class HTMLCollection {

	protected $attributes = array();
	protected $html;

	public function __construct( array $attributes = array() ){
		$this->setAttributes( $attributes );
	}

	public function setHTML( string $html ){
		$this->html = $html;
		return $this;
	}

	public function getHTML(){
		return $this->html;
	}

	public function setAttributes( array $attributes ){
		$this->attributes =  $attributes;
		return $this;
	}

	public function getAttributes(){
		return $this->attributes;
	}

	public function processHTML( string $html ){
		$this->setHTML($html);

		
	}


}