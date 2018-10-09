<?php

namespace Guacamole;

/**
 * summary
 */
class Util
{
    /**
     * summary
     */
    public function __construct()
    {
        
    }

    /**
     * [fileExists description]
     * @param  string $path [description]
     * @param  string $ext  [description]
     * @return [type]       [description]
     */
    public static function fileExists( string $path,  string $ext = "php" )
    {
      return (bool)file_exists( $path . "." . $ext );
    }

    /**
     * [getFileContents description]
     * @param  string $path [description]
     * @param  string $ext  [description]
     * @return [type]       [description]
     */
    public static function getFileContents( string $path, string $ext = "php" )
    {
        return file_get_contents( "{$path}.{$ext}" ); 
    }

    /**
     * [isString description]
     * @param  [type]  $variable [description]
     * @return boolean           [description]
     */
    public static function isString( $variable ) :bool
    {
        return (bool)is_string( $variable );
    }

    /**
     * [isArray description]
     * @param  [type]  $variable [description]
     * @return boolean           [description]
     */
    public static function isArray( $variable ) :bool
    {
        return (bool)is_array( $variable );
    }

    public static function findFile( string $file, $directory = "." )
    {
        $files = scandir( $directory );

        foreach( $files as $key => $value ){
            $path = realpath( $directory.DIRECTORY_SEPARATOR.$value );

            if( !is_dir( $path ) ){
                if( $file == $value ){
                    return $path;
                }
            } 
            else if( $value != "." && $value != ".." ){
                self::findFile( $file, $path );
            }
        }
    }

}
