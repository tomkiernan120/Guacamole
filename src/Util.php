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
     * Check if file exists
     * @param  string $path path to file will accept relative paths
     * @param  string $ext  optional extension does assume file is php
     * @return bool       [description]
     */
    public static function fileExists($path, $ext = "php")
    {
        return (bool)file_exists($path . "." . $ext);
    }

    /**
     * Get the contents of a file
     * @param  string $path path to file will accept relative paths
     * @param  string $ext  optional extension does assume file is php
     * @return mixed        returns files contents or false if file not found;
     */
    public static function getFileContents($path, $ext = "php")
    {
        if (self::fileExists($path, $ext)) {
            return file_get_contents("{$path}.{$ext}");
        }
        return false;
    }

    /**
     * check if variable is a string
     * @param  mixed  $variable  mixed variable to check
     * @return boolean           returns boolean
     */
    public static function isString($variable)
    {
        return (bool)is_string($variable);
    }

    /**
     * check if variable is array
     * @param  miaxed  $variable  mixed variable to check
     * @return boolean            returns boolean
     */
    public static function isArray($variable)
    {
        return (bool)is_array($variable);
    }

    /**
     * find a file in directorty
     * @param  string $file      file name
     * @param  string $directory directory path defaults to current working directory
     * @return string            if fount returns string path to file else return false
     * @example Util::findFile( 'Tag.php' ); //output './Tag/Tag.php'
     */
    public static function findFile($file, $directory = ".")
    {
        $files = scandir($directory);

        foreach ($files as $key => $value) {
            $path = realpath($directory . DIRECTORY_SEPARATOR . $value);

            if (!is_dir($path)) {
                if ($file == $value) {
                    return $path;
                }
            } else if ($value != "." && $value != "..") {
                self::findFile($file, $path);
            }
        }
        return false;
    }

}
