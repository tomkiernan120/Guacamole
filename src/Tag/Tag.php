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


  public $tags = array();
  public $guacamole;

  /**
   * summary
   */
  public function __construct( \Guacamole\Guacamole $Guacamole )
  {
     $this->guacamole = $Guacamole;
  }

  /**
   * [setTag description]
   * @param string $tag    [description]
   * @param [type] $params [description]
   */
  public function setTag( string $tag, $params = null ): void
  {
    $this->tags[ strtolower( $tag )] = $params;
  }


  /**
  * set the tags propery ( used to make parts of the template )
  * @param array $tags array of tags array( string "key" => mixed params );
  * @example  Guacamole\Guacamole::setTags( array( "customTags" => "test" ) );
  * @example  Guacamole\Guacamole::setTags( array( "customTags" => function() { return "test"; } ) );
  */
  public function setTags( array $tags ) : void
  {
    if( is_array( $tags ) && !empty( $tags ) ){
        foreach( $tags as $tk => $tv ){
            $this->setTag( $tk, $tv );
        }
    }
  }

  /**
   * addTag Alias for set tag
   * @param string $tag    is string for custom tag
   * @param string/array $params optional string/array/object/closure
   */
  public function addTag( string $tag, $params = null ) :void
  {
      $this->setTag( $tag, $params );
  }

  /**
   * [getTags description]
   * @return [type] [description]
   */
  public function getTags() :array
  {
      return $this->tags;
  }

  /**
   * [getTag description]
   * @param  string $tag [description]
   * @return [type]      [description]
   */
  public function getTag( string $tag ) :string
  {
      return $this->tags[$tag];
  }

  /**
   * [tagExists description]
   * @param  string $tag      [description]
   * @param  [type] $template [description]
   * @return [type]           [description]
   */
  public function tagExists( string $tag, $template = null ) :bool
  {
    if( !$template  && $this->guacamole->template->getTemplate() ){
      $template = $this->guacamole->template->getTemplate();
    }

    return (bool)( false !== stripos( $template, "<{$tag}>" ) );
  }

  /**
   * [proccessTag description]
   * @param  [type] $tag    [description]
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function proccessTag( $tag, $params = null ) // TODO: move params check to be a bit more intuitive
  {   

      $params = Clean::clean( $params );

      $data = array();

      if( $this->tagExists( $tag ) ){

          if( Util::isString( $params ) ){
            $data = $this->processString( $params );
          }

          if( is_array( $params ) && !empty( $params ) ){
            $data = $this->processArray( $params );
          }

          if( isset( $params["path"] ) && Util::fileExists( $params["path"].$tag ) ){
            ob_start();
            require "{$params["path"]}{$tag}.php";
            $string = ob_get_clean();
            $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $string ), $this->guacamole->template->getTemplate() ) );
          }        

      }
  }


  /**
   * [process description]
   * @return [type] [description]
   */
  public function process() :void
  {   
      if( !empty( $this->tags )  ){

          $tags = $this->getTags();
          foreach( $tags as $tk => $tv ){
              $this->proccessTag( $tk, $tv );
          }
      }
  }

  public function processString( string $string )
  {
    if( Util::isString( $string ) ){
        $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $params ),  $this->guacamole->template->getTemplate() ) );
    }
  }

  public function processArray( array $array )
  {
    $data = array();
    $returnData = array();

    if( is_array( $array ) && !empty( $array ) ){

      if( isset( $array["function"] ) && is_callable( $array["function"] ) ){
        $parameters = ( ( isset( $array["parameters"] ) && is_array( $array["parameters"] ) ) ? $array["parameters"] : array());
        $data = call_user_func_array( $array["function"], $parameters );
        $returnData = array_merge( $returnData, is_array( $data ) ? $data : array( $data ) );
      }

      if( isset( $array["controller"] ) && isset( $array["method"] ) ){
        if( class_exists( $array["controller"] ) ) {
          $controllerText = $array["controller"];
          $controller = new $controllerText( @$array["controllerPassin"] );

          if( method_exists( $controller, $array["method"] ) ){
            $data = $controller->{$array["method"]}( @$array["passin"] );
            $returnData = array_merge( $returnData, is_array( $data ) ? $data : array( $data ) );
          }
        }
      }
    }
    return $returnData;
  }

}