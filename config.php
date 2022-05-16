<?php
require __DIR__."/functions.php";
require __DIR__."/lib/autoload.php";
/* ====== Database ====== */
$host = "sql6.freesqldatabase.com";
$user = "sql6492588";
$pass = "rxrfKU4cu2";
$database = "sql6492588";
$db = new Connection($host, $user, $pass, $database);
$conn = $db->connect();
/* ========== page config ============ */
$url_icon = base_url()."admin/upload/images/1.webp";
$page = new Page();
$page->setTitle("News.id");
$page->setIcon($url_icon);
$iconBlog = $page->getIconUrl();
$brand = $page->getTitle();
$page->setDesc("$brand adalah website blog yang berisi informasi mengenai bidang teknologi dan materi pemerograman dasar, informasi update sosial media dan berbagai informasi menarik lainnya");
$blogDesc = $page->getDesc();
$blogOwner = "Ryuudev";
$blogKeyword = $page->getKeyword();
/* ========== */
