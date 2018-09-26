<?php
namespace Guacamole\Engine;

use Guacamole\Util\HTMLCollection as HTMLCollection;


class Renderer extends Options {

	public $HTMLCollection;
	public $cssdirectory;
	public $root;


	public function __construct( array $options = array(), HTMLCollection $HTMLCollection ){
		$this->setOptions( $options );
		$this->HTMLCollection = $HTMLCollection;
		$this->root = isset($options["root"]) ? $options["root"] : "/"; 
	}

	public function process( $object ){

		if( !$cssdirectory = $this->getOption( "cssdirectory" ) ){
			$cssdirectory = $this->findStyleDirectory();
		}

		if( !$imagedirectory = $this->getOption( "imagedirectory" ) ){
			$imagedirectory = $this->findImageDirectory();
		}

		$this->HTMLCollection->processHTML( $object );

		$this->repairTags( "link rel=\"stylesheet\"", $cssdirectory );
		$this->repairTags( "img",  )

		return $this->HTMLCollection->outerHtml;
	}

	public function findStyleDirectory() {
		
		$possibleNames = array( "style", "css", "styles" );
		$currentDirectory = getcwd();

		foreach( $possibleNames as $name ){
			if( is_dir( $currentDirectory. DIRECTORY_SEPARATOR .$name ) ){
				continue;
			}
		}

		$cssdirectory = $name;

		return $name;
	}

	public function repairTags( string $type, $directory ){
		if( $type == "link rel=\"stylesheet\"" ){
			$this->repairLinks( $directory );
		}
		else if( $type == "img" ){
			$this->repairImgs( $imgs );
		}
	}

	public function repairLinks( $directory ){

		$links = $this->HTMLCollection->find( "link" );

		if( count( $links ) ){
			foreach( $links as $link ) {

				$attr = $link->getAttribute('href');

				if( strpos( $attr, $directory ) !== 0 ){
					$attr = rtrim($directory,"/")."/".$attr;
				}
				else if ( strpos( $attr, "/" ) !== 0 ){
					$attr = $attr;
				}

				if( strpos( $attr, $this->root ) !== 0 ){
					$attr .= ( rtrim( $this->root ) . $attr);
				}

				$link->setAttribute( "href", $attr );
			}
		}
	}


}