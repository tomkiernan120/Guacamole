<?php
namespace Guacamole;

/**
 * Guacamole Object
 */
class Guacamole
{

    private $tags = array();

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

    public function proccessTag() :string
    {

    }

    public function process( string $template ) :string
    {

        if( !empty( $this->tags ) ){

            $tags = $this->getTags();

            foreach( $tags as $tk => $tv ){

                if( false !== stripos( $template , "<{$tk}>" ) ){

                    if( is_string( trim( $tv ) ) ){
                        $template = str_ireplace( "<{$tk}>", $tv,  $template );
                    }

                }

            }


        }

        return $template;
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


        $template = $this->process( $templateString );

        return $template;
    }

    public function fileExists( string $path, string $ext = "php" ) : bool
    {
        return (bool) file_exists( $path . "." . $ext );
    }

    public function getFileContets( $path, $ext = "php" ){
        return file_get_contents( $path . "." . $ext );
    }


}
