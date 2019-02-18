<?php
/**
 * 
 */
namespace Salsa\Regex;

/**
 * summary
 */
class RegexHandler
{

	const ALLOWED_CHARACTERS = '[a-zA-Z0-9\_\-]+';

	public $patternAsRegex;
	public $params = array();

    /**
     * summary
     */
    public function __construct()
    {
        
    }

    /**
     * [setPatternAsRegex description]
     * @param [type] $regex [description]
     */
    public function setPatternAsRegex( $regex )
    {
    	$this->patternAsRegex = $regex;
    } 

    /**
     * [getPatternAsRegex description]
     * @return [type] [description]
     */
    public function getPatternAsRegex()
    {
    	return $this->patternAsRegex;
    }

    /**
     * [setParams description]
     * @param [type] $params [description]
     */
    public function setParams( $params )
    {
    	$this->params = $params;
    }

    public function getParams()
    {
    	return $this->params;
    } 

    /**
     * Convert friendly routing url "/test/name/:name" "/test/word/:word(/)"
     * to a proper regex search. 
     * @param  [type] $pattern [description]
     * @return [type]          [description]
     */
    public function converToRegex( $pattern )
    {
    	if( !$this->isValid( $pattern ) ){
    		return false;
    	}

    	$pattern = $this->convertPossibleForwardSlash( $pattern );
    	$pattern = $this->convertParameter( $pattern );
    	$pattern = $this->buildCaptureGroup( $pattern );
    	$pattern = $this->matchStartAndEnd( $pattern );
    	$this->setPatternASRegex( $pattern );
    	return $pattern;
    }

    /**
     * [parseRegex description]
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
		public function parseRegex( $url )
		{

			if( $ok = !!$this->patternAsRegex ){

				if( $ok = preg_match( $this->patternAsRegex, $url, $matches ) ){
					// get elements with string keys from matches
					$params = array_intersect_key($matches, array_flip( array_filter( array_keys( $matches ), 'is_string' ) ) );

					$this->setParams( $params );
					return $params;
				}
				else {
					return false;
				} 
			}
		}

    /**
     * Conver possible "(/)" into "/?" allows for a bit friendlier routing
     * @param  string/regex $pattern regex for matchinhg routes
     * @return [type]          [description]
     */
    public function convertPossibleForwardSlash( $pattern )
    {
    	// turn "(/)" into "/?"
    	return preg_replace( '#\(/\)$#', '/?', $pattern );
    }

    /**
     * Conver :param
     * @param  string/regex $pattern [description]
     * @return string/regex          [description]
     */
    public function convertParameter( $pattern )
    {
    	return preg_replace(
      '/:('.self::ALLOWED_CHARACTERS.')/', // replace ":param"
      '(?<$1>'. self::ALLOWED_CHARACTERS .')', // with "(?<param>[a-zA-Z0-9\_\-]+)"
      $pattern);
    }

    /**
     * [buildCaptureGroup description]
     * @param  [type] $pattern [description]
     * @return [type]          [description]
     */
    public function buildCaptureGroup( $pattern )
    {
    	return preg_replace(
      '/{('.self::ALLOWED_CHARACTERS.')}/',
      '(?<$1>'.self::ALLOWED_CHARACTERS.')',
      $pattern);
    }

    /**
     * [matchStartAndEnd description]
     * @param  [type] $pattern [description]
     * @return [type]          [description]
     */
    public function matchStartAndEnd( $pattern )
    {
    	return "@^" . $pattern . "$@D";
    }

    /**
     * [validCharacters description]
     * @param  [type] $pattern [description]
     * @return [type]          [description]
     */
    public function isValid( $pattern )
    {
    	return !preg_match( '/[^-:\/_{}()a-zA-Z\d]/', $pattern );
    }

}
