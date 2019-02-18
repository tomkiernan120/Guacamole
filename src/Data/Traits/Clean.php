<?php
/**
 * 
 */
namespace Salsa\Data\Traits;

/**
 * summary
 */
trait Clean
{
    /**
     * summary
     */
    public function sanitize( $input )
    {
     	if( !$input ){
     		return false;
     	}

     	$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);

     	if( is_string( $input ) && !\Salsa\Util::isHTML( $string ) ){
     		return htmlspecialchars( trim($input), ENT_QUOTES );
     	}
     	else if( is_string( $input ) && \Salsa\Util::isHTML( $string ) ){
     		return $purifier->purify( $input );
     	}
    }
}
