<?php
require "../config.php";
$db = new Handler_db($conn);
$rows = $db->field("SELECT * FROM article ORDER BY ID DESC LIMIT 1");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <meta name="description" content="<?= $blogDesc; ?>" />
    <meta name="author" content="<?= $blogOwner; ?>" />
    <link rel="shortcut icon" href="<?= $iconBlog; ?>" type="image/x-icon" />
    <title><?= $rows["title"]; ?></title>
    <link rel="shortcut icon" href="<?= base_url(); ?>admin/upload/images/<?= $rows["thumbnail"]; ?>" type="image/x-icon" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>framework/article.css" type="text/css" media="all" />
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
            <li><a class="active" href="<?= base_url();?>latest/">terbaru</a></li>
            <li><a href="<?= base_url();?>recomendation/">rekomendasi</a></li>
            <li><a href="<?= base_url();?>popular/">terpopuler</a></li>
            <li><a href="<?= base_url();?>video/">video</a></li>
          </ul>
        </div>
      </nav>
      <div class="container-post">
      <?php if($db->countRows() > 0): ?>
      <div class="article">
        <div class="header">
        <h1 class="title"><?= $rows["title"]; ?></h1>
        <p class="writer"><?= $rows["writer"]; ?> - <a href="<?= base_url(); ?>">news.id</a></p>
        <p class="date"><?= $rows["date"]; ?></p>
        </div>
        <div class="content">
          <img class="hero" src="<?= base_url(); ?>admin/upload/images/<?= $rows["thumbnail"]; ?>" alt="" />
          <p><b>news.id - </b><?= str_replace("\n", "<br/>", $rows["content"]); ?>
          </p>
        </div>
      </div>
      <?php endif; ?>
      </div>
      <?php include "../elements/footer.php"; ?>
    </div>
  </body>
</html>