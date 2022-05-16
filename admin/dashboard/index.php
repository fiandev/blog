<?php
require "../../config.php";
session_start();
$db = new Handler_db($conn);
if (!isset($_SESSION["logged"])) {
    header("Location: ".base_url()."admin");
    exit;
}

/* pagination */
$totalData = $db->countRows("SELECT * FROM article");
$dataPerHal = 5;
$selisihHal = 5;
$halActive = ( isset($_GET["p"] ) ) ? $_GET["p"] : 1;
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
if ($totalHal > 1) {
  if ($halActive < 1) {
    header("location: ?p=1");
  }
  if ($halActive > $totalHal) {
    header("location: ?p=$totalHal");
  }
}

$rows = $db->assoc("SELECT * FROM article ORDER BY  id DESC LIMIT $awalData, $dataPerHal");
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
            <a class="nav-link active" aria-current="page" href="<?= base_url(); ?>admin/dashboard/">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>admin/dashboard/upload.php">
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
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <?php if($totalHal > 0): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
          <select class="form-control" name="page" id="page">
          <?php for($i = 0; $i < $totalHal; $i++): ?>
            <?php if($halActive == $i + 1): ?>
            <option selected value="<?= $halActive; ?>">Page <?= $halActive; ?> of <?= $totalHal; ?></option>
            <?php else: ?>
            <option value="<?= $i + 1; ?>">Page <?= $i + 1; ?></option>
            <?php endif; ?>
          <?php endfor; ?>
          </select>
        </div>
        <?php endif; ?>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr class="text-center">
              <th scope="col">no</th>
              <th scope="col">thumbnail</th>
              <th scope="col">tanggal</th>
              <th scope="col">judul</th>
              <th scope="col">kategori</th>
              <th scope="col">pengunjung</th>
              <th scope="col">aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($rows as $i => $row): ?>
            <tr class="text-center">
              <td><?= $i + 1 + $awalData; ?></td>
              <td><img class="img-fluid" style="max-height: 20vh" src="<?= base_url()."admin/upload/images/".$row["thumbnail"];?>"></td>
              <td><?= $row["date"];?></td>
              <td class="title-post"><?= $row["title"];?></td>
              <td><?= $row["category"];?></td>
              <td><?= $row["visited"];?></td>
              <td class="d-flex gap-2">
                <a class="link-confirm" link-href="<?= base_url(); ?>admin/dashboard/action.php?action=delete&id=<?= $row["id"]; ?>">
                  <button class="btn btn-danger">delete</button>
                </a>
                <a href="<?= base_url(); ?>admin/dashboard/action.php?action=update&id=<?= $row["id"]; ?>">
                  <button class="btn btn-primary">update</button>
                </a>
                <a class="link-confirm" link-href="<?= base_url(); ?>article/<?= $row["link"]; ?>">
                  <button class="btn btn-info">visit</button>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>


      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="<?= base_url(); ?>asset/dashboard.js"></script>
      <script src="<?= base_url(); ?>admin/dashboard/script.js"></script>
        <script src='//cdn.jsdelivr.net/npm/eruda'></script>
  <script>eruda.init();</script>
  </body>
</html>
