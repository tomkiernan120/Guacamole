<?php

namespace Guacamole;

/**
 * summary
 */
class Clean
{

	/**
	 * [clean description]
	 * @param  [type] $var [description]
	 * @return [type]      [description]
	 */
	  public static function clean( $var )
	  {

	      if( is_array( $var ) && !empty( $var ) ){
	          foreach( $var as $key => $value ){
	              $var[$key] = self::clean( $value );
	          }
	      }

	      $type = gettype( $var );
	      if( method_exists( __CLASS__, $type ) ){
	          return self::{$type}( $var );
	      }
	      else {
	          return $var;
	      }
	      return self::{$type}( $var );
	  }

	  /**
	   * [text description]
	   * @param  [type] $variable [description]
	   * @return [type]           [description]
	   */
    public static function text( $variable ){
        return trim( str_replace( "\n", "", htmlspecialchars( strip_tags( trim( $variable ) ), ENT_QUOTES, "UTF-8" ) ) );
    }

}
