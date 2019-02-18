<?php
/**
 * 
 */
namespace Salsa;

use Salsa\Http\HTTPHandler as HTTP;
use Salsa\Regex\RegexHandler as Regex;
use Salsa\Error\ErrorHandler as Error;
use Salsa\Data\DataHandler as Data;

/**
 * 
 */
final class Salsa 
{

	private $config;
	private $baseRoute;
	private $routes;
	private $currentRoute;

  public $currentMatchedRoute;
  public $http;
  public $regex;
	public $data;
	public $params = [];

  use Util;

  /**
   * [__construct description]
   * @param array $config [description]
   */
	public function __construct( array $config = array() )
	{
		$this->setConfig( $config );
		if( isset( $this->config["baseRoute"] ) ){
			$this->setBaseRoute($this->config["baseRoute"]);
		}
    $this->http = new HTTP;
    $this->regex = new Regex;
    $this->data = new Data( $this );
	}

	/**
	 * [setConfig description]
	 * @param array $config [description]
	 */
	public function setConfig( $config = array() )
	{
		$this->config = $config;
		return $this;
	}

	/**
	 * [getConfig description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function getConfig( $name = null )
	{
		if( !$name || !is_string( $name ) ){
			return $this->name;
		}
		else if( is_string( $name ) ){
			return isset( $this->config[$name] ) ? $this->config[$name] : false;
		}
	}

	/**
	 * [setBaseRoute description]
	 * @param string $base [description]
	 */
	public function setBaseRoute( $base = "" )
	{
		$this->baseRoute = strtolower($base);
    return $this;
	}

	/**
	 * [getBaseRoute description]
	 * @return [type] [description]
	 */
	public function getBaseRoute()
	{
		return $this->baseRoute;
	}

	/**
	 * [setCurrentRoute description]
	 */
	public function setCurrentRoute()
	{
    $this->http->setMethod();
    $dirname = trim( dirname( $_SERVER["PHP_SELF"] ), "/" ) . "/";
		$this->currentRoute = str_replace( array($this->getBaseRoute(), $dirname ), array(""), strtolower( strtok( $_SERVER["REQUEST_URI"], "?" ) ) );
		if( $this->currentRoute != "/" ){
			$this->currentRoute = ltrim( $this->currentRoute, "/" );
		} 

		return $this->currentRoute;
	}

	/**
	 * [getCurrentRoute description]
	 * @return [type] [description]
	 */
	public function getCurrentRoute()
	{
		return $this->currentRoute;
	}

	/**
	 * [setCurrentMatchedRoute description]
	 * @param [type] $route [description]
	 */
  public function setCurrentMatchedRoute( $route )
  {
    $this->currentMatchedRoute = $route;
  }

  /**
   * [getCurrentMatchedRoute description]
   * @return [type] [description]
   */
  public function getCurrentMatchedRoute()
  {
    return $this->currentMatchedRoute;
  }

  /**
   * [addRoute description];
   */
	public function addRoute( $route, $parameter, $method = HTTP::ALL_METHODS )
	{
		$this->routes[Util::generateUrl(strtolower($route))][$method] = $parameter;
    return $this;
	}

	/**
	 * [checkRoute description]
	 * @return [type] [description]
	 */
  public function checkRoute()
  {

		if( null == $this->getCurrentRoute() ){
			return false;
    }

    if( !isset( $this->routes ) || !count( $this->routes ) ){
      return false;
    }

    foreach( $this->routes as $route => $methods ){
      
      foreach( $methods as $methodsString => $options ){
        
        $methodArray = explode( "|", $methodsString );

        if( in_array( $this->http->getMethod(), $methodArray ) ){
          
          $this->regex->converToRegex( $route );

          $return = $this->regex->parseRegex( $this->getCurrentRoute() );
          
          if( $return !== false ){
            $this->setCurrentMatchedRoute( $this->routes[$route] ); 
            $this->data->setHandler( $options );
            return $return;
          }
        }
      }
    }
  }

  /**
   * [run description]
   * @return [type] [description]
   */
	public function run()
	{
    $this->setCurrentRoute();
		$this->checkRoute();
		

    if( null === $this->getCurrentMatchedRoute() ){
      Error::error();
    }

		if( null !== $this->data->getHandler() ) {
			$this->data->process();
		}
		else {
      Error::error();
		}
	}

	public function redirect( $url, $statusCode = 303 )
	{
		if ( !headers_sent() ) {		
	   	header('Location: ' . $url, true, $statusCode);
			header("Connection: close");
	   	exit;
		}
	}

}
