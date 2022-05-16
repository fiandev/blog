<?php
require "./config.php";
$db = new Handler_db($conn);
$totalData = $db->countRows("SELECT * FROM article ORDER BY visited DESC");
$dataPerHal = 6;
$selisihHal = 5;
$halActive = ( isset($_GET["p"]) ) ? $_GET["p"] : 1;
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
$rows = $db->assoc("SELECT * FROM article ORDER BY visited DESC LIMIT $awalData, $dataPerHal");
?>
<div class="post-content">
  <h1 class="header-title">most popular</h1>
  <?php if(count($rows) < 1):?>
  <?php include "./elements/no-post.php"; ?>
  <?php else: ?>
  <div class="list" style="width:<?= count($rows) * 170;?>px;">
    <?php foreach($rows as $row):?>
      <div class="post cf">
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
  <?php endif; ?>