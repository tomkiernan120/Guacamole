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
     * [__construct description]
     * @param array $config config settings for Guacamole;
     */
    public function __construct( $config = array() )
    {
        $this->setConfig( $config );
        $this->tag = isset( $this->tag ) ?: new Tag( $this );
        $this->template = isset( $this->template ) ?: new Template( $this );
    }

    /**
     * config setter
     * @param array $config array of config options
     */
    public function setConfig( array $config )
    {
        $this->config = $config;
    }

    /**
     * config getter
     * @param  mixed $name Optional name parameter can be used to return one config setting
     * @return mixed       returns whole config array or singular config option
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
     * Render a template
     * @param  string $templateString  A template string to render
     * @param  array $params           optional array of parameters to pass through with the template
     * @return string                  return the template string after render and processs
     */
    public function render( $templateString, $params = null )
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
