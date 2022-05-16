<?php
class Connection {
  private $u, $p, $h, $conn, $db;
  public function __construct($h = "localhost", $u = "root", $p = "root", $db = null){
    $this->h= $h;
    $this->u = $u;
    $this->p = $p;
    $this->db = $db;
  }
  public function connect() {
   error_reporting(0);
   
    try {
     $conn = mysqli_connect($this->h, $this->u, $this->p, $this->db) or die("connecting failed!");
     // Check connection
      $this->conn = $conn;
      return $conn;
    } catch(\Exception $e) {
      $p = new Page();
      $p->_500();
    }
  }
}
