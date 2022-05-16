<?php
require "./config.php";
$db = new Handler_db($conn);
function getDurate($filename){
  include_once('./framework/getid3/getid3/getid3.php');
  $getID3 = new getID3;
  $file = $getID3->analyze("./admin/upload/videos/$filename");
  return $file["playtime_string"];
}
$totalData = $db->countRows("SELECT * FROM posts_videos");
$selisihHal = 3;
$dataPerHal = 10;
$halActive = ( isset($_GET["p"]) ) ? $_GET["p"] : 1;
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
$articles = $db->assoc("SELECT * FROM posts_videos ORDER BY id DESC LIMIT $awalData, $dataPerHal");
?>
<?php if(ceil($totalData / $dataPerHal) >= $halActive): ?>
<div class="post-content">
  <h1 class="header-title">video</h1>
  <?php if($db->countRows() < 1):?>
  <?php include "./elements/no-post.php"; ?>
  <?php else: ?>
  <div class="list" style="width:<?= count($articles) * 50;?>%;">
    <?php foreach($articles as $row):?>
      <div class="post cf">
        <div class="hero">
          <img class="thumbnail" src="<?= base_url(); ?>admin/upload/images/<?= $row["thumbnail"]; ?>" alt="" />
          <p class="duration-video">
              <i class="fa-solid fa-video"></i>
              <?= getDurate($row["video"]); ?>
            </p>
        </div>
        <p class="title">
          <a href="<?= base_url(). "article/" .$row["link"]; ?>">
            <?= $row["title"]; ?></p>
          </a>
        <p class="content"><?= $row["visited"]; ?> view</p>
      </div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>
  </div>
<?php endif; ?>