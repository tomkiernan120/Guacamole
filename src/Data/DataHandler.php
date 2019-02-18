<?php

/**
 * 
 */
namespace Salsa\Data;

/**
 * summary
 */

class DataHandler
{
  private $handler;
  private $returnData;
  private $salsa;

  use Traits\Clean;

  /**
   * summary
   */
  public function __construct($salsa)
  {
    $this->salsa = $salsa;
  }

  /**
   * [setHandler description]
   * @param [type] $handler [description]
   */
  public function setHandler($handler)
  {
    $this->handler = $handler;
  }

  /**
   * [getHandler description]
   * @return [type] [description]
   */
  public function getHandler()
  {
    return $this->handler;
  }

  /**
   * [setReturnData description]
   * @param [type] $data [description]
   */
  public function setReturnData($data)
  {
    $this->returnData = $data;
  }

  /**
   * [getReturnData description]
   * @return [type] [description]
   */
  public function getReturnData()
  {
    return $this->returnData;
  }

  /**
   * [getType description]
   * @return [type] [description]
   */
  public function getType()
  {
    return gettype($this->handler);
  }

  /**
   * [process description]
   * @return [type] [description]
   */
  public function process()
  {
    if (!isset($this->handler)) {
      return false;
    }

    $type = $this->getType();

    if ($type == "object" && is_callable($this->handler)) {
      $this->objectHandler();
    } else if ($type == "string") {
      $this->stringHandler();
    } else if ($type == "array") {
      $this->arrayHandler();
    }

    if( isset( $this->returnData ) && gettype( $this->returnData ) == "string" ) {
      echo $this->returnData;
    }
  }

  public function objectHandler()
  {
    if (!isset($this->handler)) {
      return false;
    }
    // TODO intercept and expose certain data etc.
    $this->callFunction();
  }

  public function callFunction()
  {
    $this->setReturnData(call_user_func_array($this->handler, $this->salsa->regex->params));
  }

  public function stringHandler()
  {
    if (!isset($this->handler)) {
      return false;
    }
    $this->outputString($this->handler);
  }

  public function outputString($string)
  {
    echo $string;
  }

  public function arrayHandler()
  {
    if (!isset($this->handler)) {
      return false;
    }
    if (isset($this->handler) && isset($this->handler)) {
      $this->callController($this->handler);
    }
  }

  public function callController( $data )
  {
    if ( isset( $data["controller"] ) && is_string( $data["controller"] ) && class_exists( $data["controller"] ) ) {
      $controller = new $data["controller"];
    }
    else if( is_object( $data["controller"] ) ){
      $controller = $data["controller"];
    }
    else {
      throw new \Exception( "Could not find class {$data["controller"]}" );
    }
    
    if ($controller && method_exists($controller, $data["method"])) {
      $params = array_merge($this->salsa->params, is_array($data["passin"]) ? $data["passin"] : [@$data["passin"]]);
      $returndata = call_user_func_array(array($controller, $data["method"]), $params);
      $this->setReturnData($returndata);
    }
  }
}