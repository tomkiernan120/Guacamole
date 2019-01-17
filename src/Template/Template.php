<?php

namespace Guacamole\Template;


/**
 * summary
 */
class Template
{
    public $template;
    public $guacamole;

    /**
     * summary
     */
    public function __construct( $guacamole )
    {
      $this->guacamole = $guacamole;
    }

    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    public function getTemplate()
    {
      return (string)$this->template;
    }

    /**
     * [setTemplate description]
     * @param string $template [description]
     */
    public function setTemplate( $template )
    {
        return $this->template = $template;
    }

}