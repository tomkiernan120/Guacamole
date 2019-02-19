<?php

ini_set( "error_log", "../php-error.log" );

require "../vendor/autoload.php";

class Test {

  public function renderHeader() {
    return "test";
  }

}

$Test = new Test;

$Guacamole = new \Guacamole\Guacamole();

$Guacamole->tag->addTag( "TemplateHeaderInner", [
  "path" => "../example/"
]);


$Guacamole->tag->addTag( "TemplateHeader", [
  "path" => "../example/",
  "controller" => $Test,
  "method" => "renderHeader",
]);


echo $Guacamole->render( "../example/template", [
  "tags" => [
    "TemplateBody" => "Body",
    "TemplateFooter" => "Footer",
  ]
] );