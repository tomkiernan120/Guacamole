<?php
namespace Guacamole\Helper;

/**
 * File Helper Functions
 */
trait File
{
    /**
     * summary
     */
    public function fileExists( string $filepath )
    {
     return file_exists( $filepath );   
    }
}