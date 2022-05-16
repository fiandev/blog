<?php

require "../../config.php";$res = mysqli_query($conn, "SELECT * FROM article WHERE title='spam_62793779f3435'");$rows = mysqli_fetch_assoc($res);if(isset($_GET)){$id = $rows["id"];$visit = $rows["visited"];$visit = $visit + 1;mysqli_query($conn, "UPDATE article SET visited=$visit WHERE id=$id ");}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale°1"><title>spam_62793779f3435</title>

<link rel="stylesheet" href="<?= base_url();?>framework/article.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
</head>
<body>
<div class="container">
         <nav>
         <?php
 include base_path()."/elements/preload.php"; ?>
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
              <li><a href="<?= base_url(); ?>">home</a></li>
              <li><a href="<?= base_url(); ?>latest/">terbaru</a></li>
              <li><a href="<?= base_url(); ?>recomendation/">rekomendasi</a></li>
              <li><a href="<?= base_url(); ?>popular/">terpopuler</a></li>
              <li><a href="<?= base_url(); ?>video/">video</a></li>
            </ul>
          </div>
        </nav>
        <div class="article">
          <div class="header">
          <div class="page-info">
          <i class="fa-solid fa-folder-open"></i>
          <a href="<?= base_url(); ?>">home</a>
          <i class="fa-solid fa-folder-open"></i>
          <a href="<?= base_url()."search?category=".$rows["category"]; ?>"><?= $rows["category"]; ?></a>
          <i class="fa-solid fa-folder-open"></i>
          <a href="<?= base_url()."article/".$rows["link"]; ?>"><?= $rows["link"]; ?></a>
          </div>
          <h1 class="title"><?= $rows["title"]; ?></h1>
          <p class="writer"><?= $rows["writer"]; ?> - <a href="news.id">news.id</a></p>
          <p class="date"><?= $rows["date"]; ?></p>
          </div>
          <div class="content">
            <img class="hero" src="<?= base_url(); ?>admin/upload/images/<?= $rows["thumbnail"]; ?>" alt="" />
            <p><b>news.id - </b><?= str_replace("\n", "<br/>", $rows["content"]); ?>
            </p>
          </div>
        </div>
        <?php
 include "../../elements/footer.php"; ?>
      </div></body>
</html>