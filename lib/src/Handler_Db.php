<?php
class Handler_db {
  private $countNumRows, $sql_query, $affected, $conn, $query;
  public function __construct($conn = null) {
    $this->conn = $conn;
  }
  public function setDatabase($db = null){
    if ($db == null) {
      throw new Exception("insert parameter name for database");
    }
    return mysqli_select_db($this->conn, $db);
  }
  public function countRows($query = null){
    if ($query != null) {
      $this->assoc($query);
      return $this->countNumRows;
    } else {
      if (!is_numeric($this->countNumRows) && $this->countNumRows == null) {
        throw new Exception("do a query before call this method!");
      } else {
        return $this->countNumRows;
      }
    }
  }
  public function affectedRows($query = null){
    if ($query != null) {
      $this->query($query);
      return $this->affected;
    } else {
      if ($this->affected == null) {
        throw new Exception("do a query before call this function!");
      } else {
        return $this->affected;
      }
    }
  }
  public function assoc($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->countNumRows = mysqli_num_rows($this->sql_query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($this->sql_query) ){
      array_push($rows, $row);
    }
    return $rows;
  }
  public function inArray($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->countNumRows = mysqli_num_rows($this->sql_query);
    $rows = [];
    while( $row = mysqli_fetch_array($this->sql_query) ){
      array_push($rows, $row);
    }
    return $rows;
  }
  public function fetchAll($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->countNumRows = mysqli_num_rows($this->sql_query);
    $rows = [];
    while( $row = mysqli_fetch_all($this->sql_query) ){
      array_push($rows, $row);
    }
    return $rows;
  }
  public function object($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->countNumRows = mysqli_num_rows($this->sql_query);
    if ($this->countNumRows > 1) {
      $rows = [];
      while( $row = mysqli_fetch_object($this->sql_query) ){
        array_push($rows, $row);
      }
      return $rows;
    } else {
      return mysqli_fetch_object($this->sql_query);
    }
  }
  public function field($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->countNumRows = mysqli_num_rows($this->sql_query);
    if ($this->countNumRows > 1) {
      $rows = [];
      while( $row = mysqli_fetch_assoc($this->sql_query) ){
        array_push($rows, $row);
      }
      return $rows;
    } else {
      return mysqli_fetch_assoc($this->sql_query);
    }
  }
  public function query($query = null){
    $this->query = $query;
    $this->sql_query = mysqli_query($this->conn, $this->query);
    $this->affected = mysqli_affected_rows($this->conn);
    /* check if query SELECT type */
    if (stripos($query, "select") !== false && stripos($query, "from") !== false) {
      if ($this->affected > 1) {
        return $this->assoc($query);
      } else {
        return $this->field($query);
      }
    }
    if ($this->affected > 0) {
      return $this->affected." rows affected!";
    } else {
      return $this->error();
    }
  }
  public function insert($table, $array){
    $str = "INSERT INTO $table VALUES";
    foreach ($array as $i => $item) {
      if ($i == 0) {
        $str .= "(";
      }
      
      if (is_string($item)) {
        $str .= "'$item'".",";
      } elseif (is_null($item)) {
        $str .= "NULL".",";
      } else {
        $str .= $item.",";
      }
      if ($i == count($array) - 1) {
        $str = substr($str, 0, -1).")";
        break;
      }
    }
   // return $str;
    $this->query($str);
  }
  /* private method */
  private function error(){
    return mysqli_error($this->conn);
  }
}