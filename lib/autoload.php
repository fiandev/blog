<?php
/*
spl_autoload_register(function ($class){
  require_once "src/".$class.".php";
});
*/
$path = __DIR__."/src/";
$files = array_diff(scandir($path), array('.', '..'));
//var_dump($files);die();
foreach ($files as $file){
  require_once "src/".$file;
}