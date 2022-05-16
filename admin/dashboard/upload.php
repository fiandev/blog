<?php
require "../../config.php";
session_start();
$db = new Handler_db($conn);

if (!isset($_SESSION["logged"])) {
    header("Location: ".base_url()."admin/dashboard/");
    exit;
}
if (isset($_POST["upload"]) && isset($_SESSION["logged"])) {
  /*
  for ($i = 0; $i < 100; $i++) {
    $title = uniqid("spam_");
     $_POST["title"] = $title;
     //var_dump($_POST);die();
     if (upload($_POST)) {
       echo "success post $title";
     }
  }
  */
  /* */
  if (upload($_POST)) {
    header("location:".base_url());
  } else {
    echo $db->error();
  }
}

$rows = $db->assoc("SELECT category FROM article ORDER BY category ASC");
$dump = [];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin dashboard</title>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url(); ?>framework/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?= base_url(); ?>asset/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <script src="<?= base_url(); ?>framework/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    
<header class="navbar navbar-light sticky-top bg-info flex-md-nowrap py-2 shadow">
  <a class="py-3 px-3 text-dark text-decoration-none bg-info col-md-3 col-lg-2 me-0 px-3" href="#"><?= $brand; ?></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<form class="w-100 w-md-30 mx-2 my-1" action="<?= base_url(); ?>admin/dashboard/search" method="get" accept-charset="utf-8">
    <input name="q" class="form-control " type="text" placeholder="Search" aria-label="Search">
  </form>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse navbar-collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= base_url(); ?>admin/dashboard/">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= base_url(); ?>admin/dashboard/upload.php">
              <span data-feather="file"></span>
              post article
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>admin/dashboard/upload-video.php">
              <span data-feather="video"></span>
              post video
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>admin/dashboard/statistic/">
              <span data-feather="layers"></span>
              statistic
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-confirm" link-href="<?= base_url()."admin/dashboard/action.php?logout=".time(); ?>">
              <span data-feather="users"></span>
              log out
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <h5 class="my-2">Post Article</h5>
      <form action="" method="post" enctype="multipart/form-data" class="form-control">
          <label for="writer">penulis:</label>
          <input required  class="form-control" type="text" name="writer" id="writer" value="" />
          <label for="title">judul artikel:</label>
          <input required  class="form-control" type="text" name="title" id="title" value="" />
          <label for="category" class="my-1 d-block">category <i class="btn-category fa fa-sliders"></i></label>
          <div id="categories"></div>
            <?php if(count($rows) > 0):?>
            <select class="input-category form-select" name="category" id="category">
             <option selected value="">pilih category</option>
              <?php foreach($rows as $row): ?>
              <?php if(!in_array($row, $dump)): ?>
              <?php array_push($dump, $row); ?>
              <option value="<?= $row["category"]; ?>"><?= $row["category"]; ?></option>
              <?php endif; ?>
              <?php endforeach; ?>
             </select>
            <input class="d-none input-category form-control" type="text" name="" id="" value="" />
            <?php else: ?>
            <input required class="input-category form-control" type="text" name="category" id="category" value="" />
            <?php endif; ?>
          <label for="thumbnail">thumbnail:</label>
          <input required  class="form-control" type="file" accept="image/*" name="thumbnail" id="thumbnail" value="" />
          <label for="content">content:</label>
          <textarea required style="height:40vh" class="form-control" name="content" id="content"></textarea>
          <button type="submit" class="btn btn-outline-primary my-2" name="upload">submit</button>
       </form>
    </main>
  </div>
</div>
    <script src="<?= base_url(); ?>admin/dashboard/script.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(".btn-logout").on("click", function(){
        window.location.href=`?logout=${ new Date().getTime() }`;
      })
    </script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="<?= base_url(); ?>asset/dashboard.js"></script>
        <script src='//cdn.jsdelivr.net/npm/eruda'></script>
  <script>eruda.init();</script>
  </body>
</html>