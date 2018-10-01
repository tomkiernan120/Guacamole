<?php
namespace Guacamole;

/**
 * Guacamole Object
 */
class Guacamole
{

    private $tags = array();
    private $template;

    /**
     * Guacamole Cons
     */
    public function __construct( array $config = null )
    {

    }

    public function setTags( $tags )
    {
        if( is_array( $tags ) && !empty( $tags ) ){
            foreach( $tags as $tk => $tv ){
                $this->tags[ strtolower( $tk ) ] = $tv;
            }
        }
    }

    public function setTag( string $tag, mixed $params = null )
    {
        $this->tags[$tag] = $params;
    }

    public function getTags() :array
    {
        return $this->tags;
    }

    public function getTag( string $tag ){
        return $this->tags[$tag];
    }

    public function getTemplate() :string
    {
        return $this->template;
    }

    public function setTemplate( string $template )
    {
        $this->template = $template;
        return $this;
    }

    public function proccessTag( $tag, $params = null )
    {
        if( false !== stripos( $this->getTemplate(), "<{$tag}>" ) ){

            if( is_string( $params ) ){
                $this->setTemplate( str_ireplace( "<{$tag}>", trim($params),  $this->getTemplate() ) );
            }
            else if( is_callable( $params ) ){
                $this->setTemplate( str_ireplace( "<{$tag}>", call_user_func_array( $params , $params_arr = array() ), $this->getTemplate() ) );
            }
            else {
                $this->setTemplate( str_ireplace( "<{$tag}>", "",  $this->getTemplate() ) );
            }

        }
    }

    public function process() :void
    {

        if( !empty( $this->tags )  ){

            $tags = $this->getTags();
            foreach( $tags as $tk => $tv ){
                $this->proccessTag( $tk, $tv );
            }

        }

    }

    public function render( string $templateString, $params = null ) : string
    {

        if( is_array( $params ) && !empty( $params ) ){
            if( isset( $params["tags"] ) ){
                $this->setTags( $params["tags"] );
            }
        }


        if( $this->fileExists( $templateString ) ){
            $templateString = $this->getFileContets( $templateString );
        }

        $this->setTemplate( $templateString );

        $this->process();

        return $this->getTemplate();
    }

    public function fileExists( string $path, string $ext = "php" ) : bool
    {
        return (bool) file_exists( $path . "." . $ext );
    }

    public function getFileContets( $path, $ext = "php" ){
        return file_get_contents( $path . "." . $ext );
    }

    public function clean( $var )
    {
        $type = gettype( $var );
        return self:{$type}( $var );
    }

}
