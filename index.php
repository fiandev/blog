<?php
require "./config.php";
/* DB */
$db = new Handler_db($conn);
//$db->setDatabase("blog");
/* pagination */
$totalData = $db->countRows("SELECT * FROM article");
$dataPerHal = 5;
$selisihHal = 5;
$halActive = ( isset($_GET["p"] ) ) ? $_GET["p"] : 1;
if ($halActive < 1) {
  header("location: ?p=1");
}
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;

$articles = $db->assoc("SELECT * FROM article ORDER BY id DESC LIMIT $awalData, $dataPerHal");
//var_dump($articles);die();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <meta name="description" content="<?= $blogDesc; ?>" />
    <meta name="author" content="<?= $blogOwner; ?>" />
    <meta name="keyword" content="<?= $blogKeyword; ?>" />
    <link rel="shortcut icon" href="<?= $iconBlog; ?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?= base_url(); ?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <script src="<?= base_url(); ?>framework/jquery-3.6.0.min.js" type="text/javascript" charset="utf-8"></script>
    <title>news.id</title>
  </head>
  <body>
    <div class="container">
      <?php include "./elements/preload.php"; ?>
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
            <li><a class="active" href="#">home</a></li>
            <li><a href="/latest/">terbaru</a></li>
            <li><a href="/recomendation/">rekomendasi</a></li>
            <li><a href="/popular/">terpopuler</a></li>
            <li><a href="/video/">video</a></li>
          </ul>
        </div>
      </nav>
      <div class="container-post">
        <div class="post">
          <h1 class="header-title">article</h1>
       <?php if(count($articles) < 1):?>
        <?php include "./elements/no-post.php"; ?>
        <?php else: ?>
          <?php foreach($articles as $i => $article): ?>
            <?php if($i == 0 && $halActive == 1): ?>
             <a href="article/<?= $article["link"]; ?>">
              <div class="main-news">
                <img src="admin/upload/images/<?= $article["thumbnail"]; ?>" class="hero" alt="<?= $article["title"];?>" />
                  <p class="desc"><?= $article["title"]; ?></p>
              </div>
             </a>
            <?php else: ?>
            <div class="news">
            <img src="admin/upload/images/<?= $article["thumbnail"]; ?>" class="hero" alt="" />
            <div class="desc">
             <a href="<?= base_url(); ?>article/<?= $article["link"]; ?>">
              <h1 class="title"><?= $article["title"]; ?></h1>
              </a>
              <p class="time-upload" data-date="<?= $article["date"]; ?>"></p>
            </div>
          </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif;?>
        </div>
        <?php include "elements/popular.php"; ?>
        <?php include "elements/recomendation.php"; ?>
        <?php include "elements/video.php"; ?>
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
      <?php include "elements/footer.php"; ?>
      <script src="<?= base_url(); ?>js/recomendation.js" type="text/javascript" charset="utf-8"></script>
    </div>
  </body>
</html>