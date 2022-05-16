<?php
date_default_timezone_set("Asia/Jakarta");

if (!function_exists('base_path')) {
  function base_path(){
    $path = __DIR__;
    return $path;
  }
}
if (!function_exists('base_url')) {
    function base_url(){
      $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
      $domain = $_SERVER["HTTP_HOST"];
      $path_request = $_SERVER["REQUEST_URI"];
      $baseUrl = $protocol.$domain."/";
      return $baseUrl;
    }
}
if (!function_exists('setExpiredCookie')) {
  function setExpiredCookie($longTime){
    $minute = time() + (60 * $longTime);
    return $minute;
  }
}

if (!function_exists('update')) {
  function update($data){
    global $conn;
    $db = new Handler_db($conn);
    $title = htmlspecialchars($data["title"]);
    $category = htmlspecialchars(strtolower($data["category"]));
    $id = htmlspecialchars(strtolower($data["id"]));
    $linkArticle = str_replace(" ", "-", $title);
    $linkArticle = str_replace(" ? ", "", $linkArticle);
    $linkArticle = str_replace(".", "", $linkArticle);
    $linkArticle = str_replace("?", "", $linkArticle);
    $linkArticle = str_replace("!", "", $linkArticle);
    $linkArticle = str_replace(",", "", $linkArticle);
    $linkArticle = strtolower($linkArticle);
    $content = htmlspecialchars($data["content"]);
    $writer = htmlspecialchars($data["writer"]);
    $timeNow = date("D, d M Y h:i:s A");
    if ($_FILES["thumbnail"]["name"] != "") {
      $key = "thumbnail";
      $FileToUpload = $_FILES[$key]["tmp_name"];
      
      if($FileToUpload == ""){
        $FileToUpload = $_FILES[$key]["full_path"];
      }
      $pathFile = $_FILES[$key]["name"];
      $name = uniqid();
      $eks = pathinfo($pathFile,PATHINFO_EXTENSION);
      $folderDest = "images";
      $acceptExtensions = ["png","jpg","jpeg","gif","webp", "mp4", "mkv"];
      foreach ($acceptExtensions as $extension) {
        if($eks == $extension){
          $TargetDirectory = __DIR__."/admin/upload/$folderDest/".$name.".".$eks;
          $thumbnail = $name.".".$eks;
          move_uploaded_file($FileToUpload,$TargetDirectory);
          $db->query("UPDATE article 
          SET
            title='$title',
            writer='$writer',
            category='$category',
            content='$content',
            thumbnail='$thumbnail',
            date='$timeNow',
            link='$linkArticle'
            WHERE id=$id
            ");
          if (!$db->affectedRows() > 0) {
            return false;
          }
        }
      }
    } else {
      $db->query("UPDATE article 
          SET
            title='$title',
            writer='$writer',
            category='$category',
            content='$content',
            date='$timeNow',
            link='$linkArticle'
            WHERE id=$id
            ;");
      if (!$db->affectedRows() > 0) {
        return false;
      }
    }
    return true;
  }
}
if (!function_exists('upload')) {
  function upload($data){
    global $conn;
    $db = new Handler_db($conn);
    $title = htmlspecialchars($data["title"]);
    $category = htmlspecialchars(strtolower($data["category"]));
    $linkArticle = str_replace(" ", "-", $title);
    $linkArticle = str_replace(" ? ", "", $linkArticle);
    $linkArticle = str_replace(".", "", $linkArticle);
    $linkArticle = str_replace("?", "", $linkArticle);
    $linkArticle = str_replace("!", "", $linkArticle);
    $linkArticle = str_replace(",", "", $linkArticle);
    $linkArticle = strtolower($linkArticle);
    $content = htmlspecialchars($data["content"]);
    $writer = htmlspecialchars($data["writer"]);
    if ($_FILES["thumbnail"]["name"] != "") {
      $key = "thumbnail";
      $FileToUpload = $_FILES[$key]["tmp_name"];
      
      if($FileToUpload == ""){
        $FileToUpload = $_FILES[$key]["full_path"];
      }
      $pathFile = $_FILES[$key]["name"];
      $name = uniqid();
      $eks = pathinfo($pathFile,PATHINFO_EXTENSION);
      $folderDest = "images";
      $acceptExtensions = ["png","jpg","jpeg","gif","webp", "mp4", "mkv"];
      foreach ($acceptExtensions as $extension) {
        if($eks == $extension){
          if (!is_dir("../../article")) {
            mkdir("../../article");
          }
          if (is_dir("../../article/".$linkArticle)) {
            $linkArticle = $linkArticle."_clone_";
            mkdir("../../article/".$linkArticle);
          } else {
            mkdir("../../article/".$linkArticle);
          }
          $file = fopen("../../article/$linkArticle/index.php", "w") or die("can't open file at article/$linkArticle");
          fwrite($file, createPage($title));
          fclose($file);
          $timeNow = date("D, d M Y h:i:s A");
          $TargetDirectory = __DIR__."/admin/upload/$folderDest/".$name.".".$eks;
          $thumbnail = $name.".".$eks;
          move_uploaded_file($FileToUpload,$TargetDirectory);
          $db->query("INSERT INTO article 
          VALUES(
            NULL,
            '$title',
            '$writer',
            '$content',
            '$thumbnail',
            '$timeNow',
            '$linkArticle',
            0,
            '$category'
            )");
          if ($db->affectedRows() > 0) {
            return true;
          }
        }
      }
    }
  }
}
if (!function_exists('upload_video')) {
  function upload_video($data, $ffmpeg){
    global $conn, $conn_postVid;
    $db = new Handler_db($conn);
    $title = htmlspecialchars($data["title"]);
    $uniqid = uniqid();
    $category = strtolower($data["category"]);
    $linkArticle = str_replace(" ", "-", $title);
    $linkArticle = str_replace(" ? ", "", $linkArticle);
    $linkArticle = str_replace(".", "", $linkArticle);
    $linkArticle = str_replace("?", "", $linkArticle);
    $linkArticle = str_replace("!", "", $linkArticle);
    $linkArticle = strtolower($linkArticle);
    $content = htmlspecialchars($data["content"]);
    $writer = htmlspecialchars($data["writer"]);
    if ($_FILES["thumbnail"]["name"] != "") {
      $key = "thumbnail";
      $FileToUpload = $_FILES[$key]["tmp_name"];
      
      if($FileToUpload == ""){
        $FileToUpload = $_FILES[$key]["full_path"];
      }
      $thumbnail = uniqid().".jpg";
      /* buat thumbnail dari video */
      
      $video = $ffmpeg->open($FileToUpload);
      $video
          ->filters()
          ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
          ->synchronize();
      $video
          ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
          ->save("../upload/images/".$thumbnail);
      /* */
      $pathFile = $_FILES[$key]["name"];
      $name = uniqid();
      $eks = pathinfo($pathFile,PATHINFO_EXTENSION);
      $folderDest = "videos";
      $acceptExtensions = ["mp4", "mkv", "mov"];
      foreach ($acceptExtensions as $extension) {
        if($eks == $extension){
          if (!is_dir("../../article")) {
            mkdir("../../article");
          }
          if (is_dir("../../article/".$linkArticle)) {
            $linkArticle = $linkArticle."_clone_";
            mkdir("../../article/".$linkArticle);
          } else {
            mkdir("../../article/".$linkArticle);
          }
          $file = fopen("../../article/$linkArticle/index.php", "w") or die("can't open file at article/$linkArticle");
          fwrite($file, createPage2($uniqid));
          fclose($file);
          $timeNow = date("D, d M Y h:i:s A");
          $TargetDirectory = __DIR__."/admin/upload/$folderDest/".$name.".".$eks;
          $linkVideo = $name.".".$eks;
          move_uploaded_file($FileToUpload,$TargetDirectory);
          $db->query("INSERT INTO posts_videos 
          VALUES(
            NULL,
            '$uniqid',
            '$writer',
            '$timeNow',
            '$title',
            '$thumbnail',
            '$content',
            '$linkVideo',
            '$category',
            '$linkArticle',
            0
            )");
          if ($db->affectedRows() > 0) {
            return true;
          }
        }
      }
    }
  }
}
if (!function_exists('createPage')) {
  function createPage($title){
    $page = "<?php\n";
    $page .= 'require "../../config.php";';
    $page .= '$res = mysqli_query($conn, "SELECT * FROM article WHERE title='."'$title'".'");';
    $page .= '$rows = mysqli_fetch_assoc($res);';
    $page .= 'if(isset($_GET)){';
    $page .= '$id = $rows["id"];';
    $page .= '$visit = $rows["visited"];';
    $page .= '$visit = $visit + 1;';
    $page .= 'mysqli_query($conn, "UPDATE article SET visited=$visit WHERE id=$id ");';
    $page .= "}";
    $page .= "\n?>\n";
    $page .= "<!DOCTYPE html>\n";
    $page .= "<html>\n<head>\n";
    $page .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale°1">';
    $page .= "<title>$title</title>\n";
    $page .= '
<link rel="stylesheet" href="<?= base_url();?>framework/article.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
';
    $page .= "</head>\n<body>\n";
    $page .= '<div class="container">
         <nav>
         <?php include base_path()."/elements/preload.php"; ?>
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
        <?php include "../../elements/footer.php"; ?>
      </div>';
      $page .= "</body>\n</html>";
      $page = str_replace("<?php", "<?php\n", $page);
      return $page;
  }
}
if (!function_exists('createPage2')) {
  function createPage2($uniqid){
    $page = "<?php\n";
    $page .= 'require "../../config.php";';
    $page .= '$res = mysqli_query($conn, "SELECT * FROM posts_videos WHERE uniqid='."'$uniqid'".'");';
    $page .= '$rows = mysqli_fetch_assoc($res);';
    $page .= 'if(isset($_GET)){';
    $page .= '$id = $rows["id"];';
    $page .= '$visit = $rows["visited"];';
    $page .= '$visit = $visit + 1;';
    $page .= 'mysqli_query($conn, "UPDATE article SET visited=$visit WHERE id=$id ");';
    $page .= "}";
    $page .= "\n?>\n";
    $page .= "<!DOCTYPE html>\n";
    $page .= "<html>\n<head>\n";
    $page .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale°1">';
    $page .= '<title><?= $rows["title"]; ?></title>';
    $page .= '
<link rel="stylesheet" href="<?= base_url();?>framework/article.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?= base_url();?>css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
';
    $page .= "</head>\n<body>\n";
    $page .= '<div class="container">
    <?php include base_path()."/elements/preload.php"; ?>
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
            <video loop class="hero video-player" onplay="MediaOn()" onpause="MediaOff()" type="video/mp4" src="<?= base_url(); ?>admin/upload/videos/<?= $rows["video"]; ?>"></video>
            <p><b>news.id - </b><?= str_replace("\n", "<br/>", $rows["description"]); ?>
            </p>
          </div>
        </div>
        <?php include "../../elements/footer.php"; ?>
      </div>';
      $page .= '<script src="<?= base_url(); ?>js/video-player.js" type="text/javascript" charset="utf-8"></script>';
      $page .= "\n</body>\n</html>";
      $page = str_replace("<?php", "<?php\n", $page);
      return $page;
  }
}


if (!function_exists("actual_url")) {
  function actual_url(){
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  }
}
if (!function_exists("xhr")) {
  function xhr($url){
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);
    return $output;
  }
}
if (!function_exists("sortItemArray")) {
  function sortItemArray($items){
    $result = [];
    foreach ($items as $item){
      if (!in_array($item, $result)) {
        array_push($result, $item);
      }
    }
    return $result;
  }
}