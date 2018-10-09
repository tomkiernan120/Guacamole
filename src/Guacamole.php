<?php
namespace Guacamole;

use Guacamole\Clean as Clean;
use Guacamole\Util as Util;

use Guacamole\Tag\Tag as Tag;
use Guacamole\Template\Template as Template;


/**
 * Guacamole Object
 */
class Guacamole
{

    private $config;
    private $templateDirectory = "/templates/";


    public $tag;
    public $template;

    /**
     * Guacamole Cons
     */
    public function __construct( array $config = array() )
    {
        $this->setConfig( $config );
        $this->tag = isset( $this->tag ) ?: new Tag( $this );
        $this->template = isset( $this->template ) ?: new Template( $this );
    }

    /**
     * [setConfig description]
     * @param array $config [description]
     */
    public function setConfig( array $config ) :void
    {
        $this->config = $config;
    }

    /**
     * [getConfig description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function getConfig( $name = null )
    {
        if( !$name ){
            return $this->config;
        }
        else if( is_string( $name ) ){
            return $this->config[$name];
        }
    }

    /**
     * [render description]
     * @param  string $templateString [description]
     * @param  [type] $params         [description]
     * @return [type]                 [description]
     */
    public function render( string $templateString, $params = null ) 
    {
        if( is_array( $params ) && !empty( $params ) ){
            if( isset( $params["tags"] ) ){
                $this->tag->setTags( $params["tags"] );
            }
        }

        if( Util::fileExists( $templateString ) ){
            
            ob_start();
            require $templateString . ".php";
            $templateString = ob_get_clean();
            $this->template->setTemplate( $templateString );
        }
        else {
            $this->template->setTemplate( $templateString );
        }

        $this->tag->process();
        header('Content-Type: text/html; charset=utf-8');
        return $this->template->getTemplate();
    }

}
