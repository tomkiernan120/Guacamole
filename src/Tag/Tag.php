
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

      if( $this->tagExists( $tag ) ){

          if( Util::isString( $params ) ){
              $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $params ),  $this->guacamole->template->getTemplate() ) );
              return;
          }
          else if( is_array( $params ) && !empty( $params ) ){

              if( isset( $params["function"] ) && is_callable( $params["function"] ) ){
                  $this->setTemplate( str_ireplace( "<{$tag}>", call_user_func_array( $params["function"], @$params["params"] ), $this->guacamole->template->getTemplate() ) );
                  return;
              }
          }

            if( isset( $params["controller"] ) && isset( $params["method"] ) ){


                if( class_exists( $params["controller"] ) ){
                    $controller = new $params["controller"]( @$params["controllerPassin"] );

                    if( method_exists( $controller, $params["method"] ) ){
                        $data = $controller->{$params["method"]}( @$params["passin"] );
                    }
                }
            }


          if( isset( $params["path"] ) && Util::fileExists( $params["path"].$tag ) ){
            
            ob_start();
            require "{$params["path"]}{$tag}.php";
            $string = ob_get_clean();
            $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", trim( $string ), $this->guacamole->template->getTemplate() ) );
            return;
          }


          if( is_callable( $params ) ){
              $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", call_user_func_array( $params , $params_arr = array() ), $this->guacamole->template->getTemplate() ) );
              return;
          }
          else {
              $this->guacamole->template->setTemplate( str_ireplace( "<{$tag}>", "",  $this->getTemplate() ) );
              return;
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

}
