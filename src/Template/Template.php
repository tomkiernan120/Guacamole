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
    public function __construct( \Guacamole\Guacamole $guacamole )
    {
      $this->guacamole = $guacamole;
    }

    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    public function getTemplate() :string
    {
     	return (string)$this->template;
    }

    /**
     * [setTemplate description]
     * @param string $template [description]
     */
    public function setTemplate( string $template ) :string
    {
        return $this->template = $template;
    }

}
