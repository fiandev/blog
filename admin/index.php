<?php
require "../config.php";
session_start();
$db = new Handler_db($conn);
if (isset($_COOKIE["id"]) && 
    isset($_COOKIE["username"]) &&
    isset($_COOKIE["password"]) &&
    !isset($_SESSION["logged"])) {
  $id = $_COOKIE["id"];
  $user = $_COOKIE["username"];
  $pass = $_COOKIE["password"];
  $rows = $db->query("SELECT * FROM admin WHERE id=$id");
  if ($user == hash("sha256", $rows["username"]) &&
      $pass == hash("sha256", $rows["password"])) {
    $_SESSION["logged"] = true;
    header("location: ".base_url()."admin/dashboard/");
  } else {
    $error = "invalid session value";
  }
}

if (isset($_SESSION["logged"])) {
  header("location: ".base_url()."admin/dashboard/");
  exit;
}

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $rows = $db->query("SELECT * FROM admin WHERE username='$username'");
   if ($db->countRows() == 1) {
     if ($username == $rows["username"] && $password == $rows["password"]) {
       if (isset($_POST["remember"])) {
           $id = $rows["id"];
           $user = hash("sha256",$rows["username"]);
           $pass = hash("sha256",$rows["password"]);
           setcookie("id", $id, time() + 60*5);
           setcookie("username", $user, time() + 60*5);
           setcookie("password", $pass, time() + 60*5);
       }
       $_SESSION["logged"] = true;
       header("location: ".base_url()."admin/dashboard/");
     } else {
       $error = "incorrect username or password!";
     }
   } else {
     $error = "incorrect username or password!";
   }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <title>halaman login</title>
    <link rel="stylesheet" href="<?= base_url();?>framework/bootstrap.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <script src="<?= base_url();?>framework/bootstrap.bundle.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?= base_url();?>framework/jquery-3.6.0.min.js" type="text/javascript" charset="utf-8"></script>
  </head>
  <body>
    <div class="container">
      <h1 class="text-center my-1">halaman login</h1>
      <?php if(isset($error)): ?>
      <div class="alert-form alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
        <button onclick="this.parentElement.classList.toggle('d-none')" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>
      <?php endif; ?>
      <form action="" method="post" accept-charset="utf-8">
            <label for="username">username :</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fa fa-user py-1 px-1"></i>
                </div>
              </div>
              <input required class="form-control" type="text" name="username" id="username" value="" />
            </div>
            <label for="password">password :</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text" id="switchTypeInput">
                  <i class="fa fa-eye py-1 px-1"></i>
                </div>
              </div>
              <input required class="form-control" type="password" name="password" id="password" value="" />
            </div>
            <div class="form-group my-1">
              <label for="remember">remember me</label>
              <input class="form-group" type="checkbox" name="remember" id="remember" value="" />
            </div>
            <button class="my-1 btn btn-primary" type="submit" name="login">login!</button>
      </form>
    </div>
    <script type="text/javascript" charset="utf-8">
      $("#switchTypeInput i").on("click", function(){
        if( $(this).hasClass("fa-eye") ){
          $("#password").attr("type", "text")
          $(this).removeClass("fa-eye");
          $(this).toggleClass("fa-lock");
        } else {
          $("#password").attr("type", "password")
          $(this).toggleClass("fa-eye");
          $(this).toggleClass("fa-lock");
        }
      })
    </script>
  </body>
</html>