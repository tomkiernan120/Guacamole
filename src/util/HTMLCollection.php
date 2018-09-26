<?php
namespace Guacamole\Util;

class HTMLCollection extends \PHPHtmlParser\Dom {

	protected $attributes = array();
	protected $html;
	public $dom;
	protected $tidy;

	protected $css;
	protected $js;
	protected $meta;

	public function __construct( array $attributes = array(), array $tidyoptions = array() ){
		$this->setAttributes( $attributes );
		$this->tidyoptions = $tidyoptions;
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
		$this->parseHTML();
		if( $this->dom ){
			return $this->dom->saveHTML();
		}
	}

	public function parseHTML(){
		if( $this->html ){
			$this->load( $this->getHTML() );
		}
	}


}