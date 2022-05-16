<?php
require "../config.php";
$db = new Handler_db($conn);
function getDurate($filename){
  include_once('../framework/getid3/getid3/getid3.php');
  $getID3 = new getID3;
  $file = $getID3->analyze("../admin/upload/videos/$filename");
  return $file["playtime_string"];
}
$totalData = $db->countRows("SELECT * FROM posts_videos");
$selisihHal = 3;
$dataPerHal = 10;
$halActive = ( isset($_GET["p"]) ) ? $_GET["p"] : 1;
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
$articles = $db->assoc("SELECT * FROM posts_videos ORDER BY id DESC LIMIT $awalData, $dataPerHal");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <meta name="description" content="<?= $blogDesc; ?>" />
    <meta name="author" content="<?= $blogOwner; ?>" />
    <link rel="shortcut icon" href="<?= $iconBlog; ?>" type="image/x-icon" />
    <title>video</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= base_url();?>framework/video.css" type="text/css" media="all" />
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
            <li><a href="<?= base_url();?>popular/">terpopuler</a></li>
            <li><a class="active" href="<?= base_url();?>video/">video</a></li>
          </ul>
        </div>
      </nav>
      <div class="container-post">
        <div class="post-videos">
          <h1 class="header-title">video</h1>
          <?php if(count($articles) < 1):?>
          <?php include "../elements/no-post.php"; ?>
          <?php else: ?>
          <div class="post-container">
          <?php foreach ($articles as $article): ?>
          <div class="post">
            <a href="<?= base_url(); ?>article/<?= $article["link"]; ?>">
              <div class="hero">
              <img class="thumbnail" src="<?= base_url(); ?>admin/upload/images/<?= $article["thumbnail"]; ?>" alt="" />
              <p class="source">sumber : news.id</p>
              <p class="duration-video">
                <i class="fa-solid fa-video"></i>
                <?= getDurate($article["video"]); ?>
              </p>
            </div>
            </a>
            <p class="title"><?= $article["title"]; ?></p>
            <p class="desc-time time-upload" data-date="<?= $article["date"]; ?>">10 menit yang lalu</p>
          </div>
          <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
       <?php if($totalHal > 1): ?>
       <div class="pagination">
       <p><?= $halActive." of ".$totalHal." pages"; ?></p>
       <div class="list-page">
        <?php if($halActive - $selisihHal > 0): ?>
          <a class="fa fa-arrow-left" href="?p=<?= $halActive - $selisihHal; ?>"></a>
        <?php elseif($halActive > 1): ?>
          <a class="fa fa-arrow-left" href="?p=<?= $halActive - 1; ?>"></a>
        <?php else: ?>
        <?php endif; ?>
        <?php for($i = $halActive; $i <= $halActive + $selisihHal; $i++): ?>
          <?php if($i == $halActive): ?>
            <a class="active" href="?p=<?= $i; ?>"><?= $i; ?></a>
          <?php else: ?>
            <?php if ($i <= $totalHal): ?>
              <a href="?p=<?= $i; ?>"><?= $i; ?></a>
            <?php endif; ?>
            
          <?php endif; ?>
        <?php endfor; ?>
        <?php if($halActive < $totalHal && $halActive + $selisihHal < $totalHal): ?>
          <a class="fa fa-arrow-right" href="?p=<?= $halActive + $selisihHal; ?>"></a>
        <?php endif; ?>
         
       </div>
      </div>
       <?php endif; ?>
     <?php include "../elements/footer.php"; ?>
    </div>
  </body>
</html>