<?php
require "../config.php";
$db = new Handler_db($conn);
$totalData = $db->countRows("SELECT * FROM article ORDER BY title ASC");
$dataPerHal = 6;
$selisihHal = 5;
$halActive = ( isset($_GET["p"]) ) ? $_GET["p"] : 1;
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
$rows = $db->assoc("SELECT * FROM article ORDER BY visited DESC LIMIT $awalData, $dataPerHal");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <meta name="description" content="<?= $blogDesc; ?>" />
    <meta name="author" content="<?= $blogOwner; ?>" />
    <link rel="shortcut icon" href="<?= $iconBlog; ?>" type="image/x-icon" />
    <title>most popular</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>framework/popular.css" type="text/css" media="all" />
    <script src="<?= base_url();?>framework/jquery-3.6.0.min.js" type="text/javascript" charset="utf-8"></script>
  </head>
  <body>
    <div class="container">
      <?php include "../elements/preload.php"; ?>
      <nav>
        <div class="main-menu">
          <h1>News.id</h1>
          <div class="right">
            <i class="theme-icon fa fa-sun"></i>
            <label class="switch" for="theme">
             <input type="checkbox" id="theme">
             <span class="slider round"></span>
           </label>
            <i class="burger-menu fa fa-bars"></i>
          </div>
        </div>
        <div class="sub-menu">
          <ul>
            <li><a href="<?= base_url();?>">home</a></li>
            <li><a href="<?= base_url();?>latest/">terbaru</a></li>
            <li><a href="<?= base_url();?>recomendation/">rekomendasi</a></li>
            <li><a class="active" href="<?= base_url();?>popular/">terpopuler</a></li>
            <li><a href="<?= base_url();?>video/">video</a></li>
          </ul>
        </div>
      </nav>
      <div class="container-post">
        <div class="post-content">
        <h1 class="header-title">most popular</h1>
        <?php if(count($rows) < 1):?>
        <?php include "../elements/no-post.php"; ?>
        <?php else: ?>
        <div class="recomendation">
          <?php foreach($rows as $row):?>
            <div class="post">
              <div class="hero">
                <img class="thumbnail" src="<?= base_url(); ?>admin/upload/images/<?= $row["thumbnail"]; ?>" alt="" />
              </div>
              <p class="title">
                <a href="<?= base_url(). "article/" .$row["link"]; ?>">
                  <?= $row["title"]; ?></p>
                </a>
              <p class="content"><?= $row["visited"]; ?> view</p>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
        
      </div>
        <?php endif; ?>
       <?php if($totalHal > 1): ?>
      <div class="pagination">
       <p><?= $halActive." of ".$totalHal." pages"; ?></p>
       <div class="list-page">
        <?php if($halActive - $selisihHal > 0): ?>
          <a class="fa fa-arrow-left" href="?<?= $prefix; ?>=<?= $get; ?>&p=<?= $halActive - $selisihHal; ?>"></a>
        <?php elseif($halActive > 1): ?>
          <a class="fa fa-arrow-left" href="?<?= $prefix; ?>=<?= $get; ?>&p=<?= $halActive - 1; ?>"></a>
        <?php else: ?>
        <?php endif; ?>
        <?php for($i = $halActive; $i <= $halActive + $selisihHal; $i++): ?>
          <?php if($i == $halActive): ?>
            <a class="active" href="?<?= $prefix; ?>=<?= $get; ?>&p=<?= $i; ?>"><?= $i; ?></a>
          <?php else: ?>
            <?php if ($i <= $totalHal): ?>
              <a href="?<?= $prefix; ?>=<?= $get; ?>&p=<?= $i; ?>"><?= $i; ?></a>
            <?php endif; ?>
            
          <?php endif; ?>
          <?php endfor; ?>
          <?php if($halActive < $totalHal && $halActive + $selisihHal < $totalHal): ?>
            <a class="fa fa-arrow-right" href="?<?= $prefix; ?>=<?= $get; ?>&p=<?= $halActive + $selisihHal; ?>"></a>
          <?php endif; ?>
         </div>
      </div>
      <?php endif; ?>
        <?php include "../elements/footer.php"; ?>
      </div>
  </body>
</html>