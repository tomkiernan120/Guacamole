<?php
/**
 * 
 */
namespace Salsa\Http;

/**
 * summary
 */
class HTTPHandler 
{

    
    const ALL_METHODS   = "GET|POST|PUT|DELETE";
    const METHOD_GET    = "GET";
    const METHOD_POST   = "POST";
    const METHOD_PUT    = "PUT";
    const METHOD_DELETE = "DELETE";

    private $currentMethod;
    private $method;

    /**
     * [$methodsArray description]
     * @var array
     */
    public $methodsArray = array( self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE );

    /**
     * summary
     */
    public function __construct()
    {
        
    }

    /**
     * [getMethod description]
     * @return [type] [description]
     */
    public function getMethod()
    {
        return $this->currentMethod;
    }

    /**
     * [setMethod description]
     */
    public function setMethod()
    {
        $this->currentMethod = $_SERVER["REQUEST_METHOD"];
        return $this;
    }

}
