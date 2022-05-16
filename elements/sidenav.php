<?php
require "../config.php";
$db = new Handler_db($conn);
$table1 = $db->assoc("SELECT category FROM article ORDER BY category ASC");
$table2 = $db->assoc("SELECT category FROM posts_videos ORDER BY category ASC");
$dump = [];
$allCategory = [];
foreach ($table1 as $row){
  array_push($allCategory, $row["category"]);
}
foreach ($table2 as $row){
  array_push($allCategory, $row["category"]);
}
if (!function_exists("myFunc")) {
  function myFunc($cat){
    global $allCategory;
    $each = 0;
    foreach ($allCategory as $category){
      if ($cat == $category) {
        $each += 1;
      }
    }
    return $each;
  }
}
?>

<div class="sidenav">
  <div class="header">
    <h1>Search</h1>
    <i class="btn-close fa fa-xmark"></i>
  </div>
  <form action="<?= base_url(); ?>search" method="get" accept-charset="utf-8">
    <input autofocus="on" placeholder="search..." type="text" name="q" id="q" value="" />
  </form>
  <div class="category">
    <h1>category</h1>
    <?php if(count($allCategory) < 1):?>
    <?php include "./no-post.php"; ?>
    <?php else: ?>
    <ul>
      <?php foreach($allCategory as $row): ?>
      <?php if(!in_array($row, $dump)): ?>
      <?php array_push($dump, $row); ?>
      <li>
        <a href="<?= base_url(); ?>search?category=<?= $row; ?>"><?= $row; ?></a>
        <span>(<?= myFunc($row); ?>)</span>
        </li>
      <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</div>