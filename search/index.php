<?php
require "../config.php";
$db = new Handler_db($conn);
$dataPerHal = 10;
$selisihHal = 5;
$halActive = ( isset($_GET["p"]) ) ? $_GET["p"] : 1;
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
$notFound = false;
if (isset($_GET)) {
  $get = $_GET;
  if($halActive < 1){
    echo $halActive;
    $page->_404();
  }
  if (isset($get["q"])) {
    $get = $get["q"];
    $prefix = "q";
    $articles = $db->assoc("SELECT * FROM article 
    where 
    title LIKE '%$get%' OR
    category LIKE '%$get%'
    LIMIT $awalData, $dataPerHal
    ");
    $totalData = $db->countRows();
    $totalHal = ceil($totalData / $dataPerHal);
  }
  if (isset($get["category"])) {
    $get = $get["category"];
    $prefix = "category";
    $articles = $db->assoc("SELECT * FROM article 
    where 
    category LIKE '$get'
    LIMIT $awalData, $dataPerHal
    ");
    $totalData = //$db->countRows();
    $totalHal = ceil($totalData / $dataPerHal);
  }
  /* jika di tabel article ngga ada */
  if (count($articles) < 1) {
    $totalHal = ceil($totalData / $dataPerHal);
    $articles = $db->assoc("SELECT * FROM posts_videos 
    where 
    category LIKE '$get'
    LIMIT $awalData, $dataPerHal
    ");
    $totalData = $db->countRows();
    if($totalData < 1) {
      $notFound = true;
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale°1">
    <meta name="description" content="search result for <?= $get; ?>" />
    <meta name="author" content="<?= $blogOwner; ?>" />
    <link rel="shortcut icon" href="<?= $iconBlog; ?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?= base_url(); ?>css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <script src="<?= base_url(); ?>framework/jquery-3.6.0.min.js" type="text/javascript" charset="utf-8"></script>
     <?php if(!isset($notFound)): ?>
       <title>hasil pencarian untuk <?= $get; ?></title>
      <?php else: ?>
       <title>tidak ditemukan hasil untuk <?= $get; ?></title>
      <?php endif; ?>
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
            <li><a href="<?= base_url();?>video/">video</a></li>
          </ul>
        </div>
      </nav>
      <div class="container-post">
        <div class="post container-search-result">
          <?php if(!$notFound): ?>
          <h1 class="search-result">hasil pencarian untuk <?= $get; ?></h1>
          <?php else: ?>
          <h1 class="search-result">tidak ditemukan hasil untuk '<?= $get; ?>'</h1>
          <?php include "../elements/not-found.php"; ?>
          <?php endif; ?>
          <?php foreach($articles as $article): ?>
            <div class="news">
            <img src="<?= base_url(); ?>admin/upload/images/<?= $article["thumbnail"]; ?>" class="hero" alt="" />
            <div class="desc">
             <a href="<?= base_url(); ?>article/<?= $article["link"]; ?>">
              <h1 class="title"><?= $article["title"]; ?></h1>
              </a>
              <p class="time-upload" data-date="<?= $article["date"]; ?>"></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if($totalHal >= 1): ?>
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