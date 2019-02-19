<?php

/**
 * 
 */
namespace Guacamole\Tag;


use Guacamole\Clean as Clean;
use Guacamole\Util as Util;

/**
 * summary
 */
class Tag
{


  public $tags = [];
  public $guacamole;

  /**
   * summary
   */
  public function __construct( $Guacamole )
  {
    $this->guacamole = $Guacamole;
  }

  /**
   * [setTag description]
   * @param string $tag    [description]
   * @param [type] $params [description]
   */
  public function setTag( $tag, $params = null )
  {
    $this->tags[strtolower($tag)] = $params;
  }


  /**
  * set the tags propery ( used to make parts of the template )
  * @param array $tags array of tags array( string "key" => mixed params );
  * @example  Guacamole\Guacamole::setTags( array( "customTags" => "test" ) );
  * @example  Guacamole\Guacamole::setTags( array( "customTags" => function() { return "test"; } ) );
  */
  public function setTags( $tags )
  {
    if (is_array($tags) && !empty($tags)) {
      foreach ($tags as $tk => $tv) {
        $this->setTag($tk, $tv);
      }
    }
  }

  /**
   * addTag Alias for set tag
   * @param string $tag    is string for custom tag
   * @param string/array $params optional string/array/object/closure
   */
  public function addTag( $tag, $params = null )
  {
    $this->setTag($tag, $params);
  }

  /**
   * [getTags description]
   * @return [type] [description]
   */
  public function getTags()
  {
    return $this->tags;
  }

  /**
   * [getTag description]
   * @param  string $tag [description]
   * @return [type]      [description]
   */
  public function getTag( $tag )
  {
    return $this->tags[$tag];
  }

  /**
   * [tagExists description]
   * @param  string $tag      [description]
   * @param  [type] $template [description]
   * @return [type]           [description]
   */
  public function tagExists( $tag, $template = null )
  {
    if (!$template && $this->guacamole->template->getTemplate()) {
      $template = $this->guacamole->template->getTemplate();
    }

    return (bool)(false !== stripos($template, "<{$tag}>"));
  }

  /**
   * [proccessTag description]
   * @param  [type] $tag    [description]
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function proccessTag($tag, $params = null) // TODO: move params check to be a bit more intuitive
  {

    $params = Clean::clean($params);

    $data = [];

    if ($this->tagExists($tag)) {

      if (Util::isString($params)) {
        $this->processString( $tag, $params );
      }

      if (is_array($params) && !empty($params)) {
        $data = $this->processArray($params);
      }

      if( is_object( $params ) && is_callable( $params ) ) {
        ob_start();
        $string = call_user_func_array( $params, [] );
        $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $string ), $this->guacamole->template->getTemplate() ) );        
      }
      else if (isset($params["path"]) && Util::fileExists($params["path"] . $tag)) {
        ob_start();

        if (isset($params["controller"]) && class_exists($params["controller"]) && isset($params["controllerMethod"])) {
          $controller = new $params["controller"](@$params["controllePassin"]);

          if (method_exists($controller, $params["controllerMethod"])) {
            $data = $controller->{$params["controllerMethod"]}(@$params["controllerPassin"]);
          } else {
            throw new \Exception("Could not find Method: {$params["controller"]}::{$params["controllerMethod"]}");
          }

          if( isset( $params["path"] ) && Util::fileExists( $params["path"].$tag ) ) {
            ob_start();

            if( isset( $params["controller"] ) && class_exists( $params["controller"] ) && isset( $params["controllerMethod"] ) ){
              $controller = new $params["controller"]( @$params["controllePassin"] );

              if( method_exists( $controller, $params["controllerMethod"] ) ){
                $data = $controller->{$params["controllerMethod"]}( @$params["controllerMethod"] );
              }
              else {
                throw new Exception('Counld not find Method: {$params["controller"]}::{$params["controllerMethod"]}' );
              }
            }

            require "{$params["path"]}{$tag}.php";
            $string = ob_get_clean();
            $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $string ), $this->guacamole->template->getTemplate() ) );
          }

        }

        require "{$params["path"]}{$tag}.php";
        $string = ob_get_clean();
        $this->guacamole->template->setTemplate(str_ireplace("<{$tag}>", trim($string), $this->guacamole->template->getTemplate()));
      }
    }
  }


  /**
   * [process description]
   * @return [type] [description]
   */
  public function process()
  {   
    if( !empty( $this->tags )  ){

      $tags = $this->getTags();

      for( $i = 0; $i < 3; $i++ ){
        foreach( $tags as $tk => $tv ){
          $this->proccessTag( $tk, $tv );
        }
      }

    }
  }



  public function processString( $tag, $string )
  {
    if (Util::isString($string)) {
      $this->guacamole->template->setTemplate(str_ireplace("<{$tag}>", trim($string), $this->guacamole->template->getTemplate()));
      return true;
    }
    return false;
  }

  public function processArray( $array )
  {
    $data = [];
    $returnData = [];

    if (is_array($array) && !empty($array)) {

      if (isset($array["function"]) && is_callable($array["function"])) {
        $parameters = ((isset($array["parameters"]) && is_array($array["parameters"])) ? $array["parameters"] : [] );
        $data = call_user_func_array($array["function"], $parameters);
        $returnData = array_merge( $returnData, is_array( $data ) ? $data : [$data] );
      }

      if (isset($array["controller"]) && isset($array["method"])) {

        if( is_string( $array["controller"] ) && class_exists( $array["controller"] ) ) {
          $controlerText = $array["controller"];
          $controller = new $controllerText( @$array["controllerPassin"] );
        }
        else if( is_object( $array["controller"] ) && is_callable( $array["controller"] ) ){
          $controller = $array["controller"];
        }

        if (method_exists($controller, $array["method"])) {
          $data = $controller->{$array["method"]}(@$array["passin"]);
          $returnData = array_merge($returnData, is_array($data) ? $data : [$data] );
        }
      }
    }
    return $returnData;
  }

}