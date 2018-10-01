<?php
namespace Guacamole;

/**
 * Guacamole Object
 */
class Guacamole
{

    private $tags = array();
    private $template;
    private $config;
    private $templateDirectory = "/templates/";

    /**
     * Guacamole Cons
     */
    public function __construct( array $config = array() )
    {
        $this->setConfig( $config );
    }

    /**
     * set the tags propery ( used to make parts of the template )
     * @param array $tags array of tags array( string "key" => mixed params );
     * @example  Guacamole\Guacamole::setTags( array( "customTags" => "test" ) );
     * @example  Guacamole\Guacamole::setTags( array( "customTags" => function() { return "test"; } ) );
     */
    public function setTags( array $tags )
    {
        if( is_array( $tags ) && !empty( $tags ) ){
            foreach( $tags as $tk => $tv ){
                $this->setTag( $tk, $tv );
            }
        }
    }

    public function setConfig( array $config )
    {
        $this->config = $config;
    }

    public function getConfig( $name = null ){
        if( !$name ){
            return $this->config;
        }
        else if( is_string( $name ) ){
            return $this->config[$name];
        }
    }

    public function setTag( string $tag, $params = null )
    {
        $this->tags[ strtolower( $tag )] = $params;
    }

    /**
     * addTag Alias for set tag
     * @param string $tag    is string for custom tag
     * @param string/array $params optional string/array/object/closure
     */
    public function addTag( string $tag, $params = null )
    {
        $this->setTag( $tag, $params );
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

    public function proccessTag( $tag, $params = null ) // TODO: move params check to be a bit more intuitive
    {   

        $params = $this->clean( $params );

        if( false !== stripos( $this->getTemplate(), "<{$tag}>" ) ){

            if( is_string( $params ) ){
                $this->setTemplate( str_ireplace( "<{$tag}>", trim($params),  $this->getTemplate() ) );
                return;
            }
            else if( is_array( $params ) && !empty( $params ) ){

                if( isset( $params["function"] ) && is_callable( $params["function"] ) ){
                    $this->setTemplate( str_ireplace( "<{$tag}>", call_user_func_array( $params["function"], @$params["params"] ), $this->getTemplate() ) );
                    return;
                }

                if( isset( $params["controller"] ) && isset( $params["method"] ) ){

                    if( class_exists( $params["controller"] ) ){
                        $con = new $params["controller"];

                        if( method_exists( $con, $params["method"] ) ){
                            $this->setTemplate( str_replace( "<{$tag}>", $con->{$params["method"]}( @$params["passin"] ), $this->getTemplate() ) );
                            return;
                        }
                    }
                }

            }
            else if( is_callable( $params ) ){
                $this->setTemplate( str_ireplace( "<{$tag}>", call_user_func_array( $params , $params_arr = array() ), $this->getTemplate() ) );
                return;
            }
            else {
                $this->setTemplate( str_ireplace( "<{$tag}>", "",  $this->getTemplate() ) );
                return;
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

        if( is_array( $var ) && !empty( $var ) ){
            foreach( $var as $key => $value ){
                $var[$key] = $this->clean( $value );
            }
        }

        $type = gettype( $var );
        if( method_exists( $this, $type ) ){
            return self::{$type}( $var );
        }
        else {
            return $var;
        }
        return self::{$type}( $var );
    }

    public static function text( $variable ){
        return trim( str_replace( "\n", "", htmlspecialchars( strip_tags( trim( $variable ) ), ENT_QUOTES, "UTF-8" ) ) );
    }



}
