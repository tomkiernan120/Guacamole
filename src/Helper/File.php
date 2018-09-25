<?php
namespace Gaucamole\File;

/**
 * File Helper Functions
 */
trait FileHelper
{
    /**
     * summary
     */
    public function fileExists( string $filepath )
    {
     return file_exists( $filepath );   
    }
}