<?php
class Page {
  private $title, $icon, $desc, $keyword, $log;
  public function setTitle($title){
    $this->title = $title;
  }
  public function setIcon($url){
    $this->icon = $url;
  }
  public function getTitle(){
    return $this->title;
  }
  public function getIconUrl(){
    return $this->icon;
  }
  public function _403($msg = null){
    readfile(base_path()."/elements/403.php");
    exit;
  }
  public function _404($msg = null){
    readfile(base_path()."/elements/404.php");
    exit;
  }
  public function _500($msg = null){
    readfile(base_path()."/elements/500.php");
    exit;
  }
  public function _soon($msg = null){
    readfile(base_path()."/elements/soon.php");
    exit;
  }
  public function setDesc($desc){
    $this->desc = $desc;
  }
  public function getDesc(){
    if ($this->desc == null) {
      throw new Exception("page description has'nt set up!");
    }
    return $this->desc;
  }
  public function setKeyword($arr){
    $keyword = "";
    foreach ($arr as $item){
      $keyword .= $item.",";
    }
    $keyword = substr($keyword, 0, -1);
    $this->keyword = $keyword;
  }
  public function getKeyword(){
    return $this->keyword;
  }
}