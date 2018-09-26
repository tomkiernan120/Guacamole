<?php
namespace Guacamole\Helper;

/**
 * Cleaning Class
 */
trait Clean
{
    /**
     * summary
     */
    public function clean( $var )
    {
	   $type = gettype( $var );  
	   if( $type && method_exists( $this, $type ) ){
	   		return $this->{$type}( $var );
	   }
    }

    public function string( string $string ){
    	// if( $string != strip_tags( $string ) ){
    	// 	return trim( $tring );
    	// }
    	return htmlspecialchars( trim( $string ), ENT_QUOTES, 'UTF-8' );
    }

    public function int( int $int ){
    	return (int)$int;
    }

    public function float( float $float ){
    	return (float)$float;
    }

    public function array( array $array ){
    	if( !empty( $array ) ){
    		foreach( $array as $k => $v ){
    			$array[$k] = $this->clean( $v );
    		}
    	}
    	return $array;
    }

    public function object( array $obj ){
    	return $obj;
    }

    public function NULL(){
    	return null;
    }

    public function boolean( $bool ){
    	return $bool;
    }
}