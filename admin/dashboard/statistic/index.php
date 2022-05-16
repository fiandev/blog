<?php
require "../../../config.php";
session_start();
$db = new Handler_db($conn);

if (!isset($_SESSION["logged"])) {
    header("Location: ".base_url()."admin");
    exit;
}
/* pagination */
$totalData = $db->countRows("SELECT * FROM article");
$dataPerHal = 10;
$selisihHal = 5;
$halActive = ( isset($_GET["p"] ) ) ? $_GET["p"] : 1;
$totalHal = ceil($totalData / $dataPerHal);
$awalData = ($dataPerHal * $halActive) - $dataPerHal;
if ($totalHal > 1) {
  if ($halActive < 1) {
    header("location: ?p=1");
  }
  if ($halActive > $totalHal) {
    header("location: ?p=$totalHal");
  }
}
$all = [];
$fromArticle = $db->assoc("SELECT * FROM article ORDER BY date LIMIT $awalData, $dataPerHal");
$fromVideo = $db->assoc("SELECT * FROM posts_videos ORDER BY date LIMIT $awalData, $dataPerHal");
foreach ($fromArticle as $p) {
  $p["content"] = htmlspecialchars($p["content"], ENT_QUOTES, "utf-8");
  array_push($all, $p);
}

foreach ($fromVideo as $p) {
  $p["description"] = htmlspecialchars($p["description"], ENT_QUOTES, "utf-8");
  array_push($all, $p);
}
$dump = [];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin dashboard</title>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url(); ?>framework/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?= base_url(); ?>asset/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" type="text/css" media="all" />
    <script src="<?= base_url(); ?>framework/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    
<header class="navbar navbar-light sticky-top bg-info flex-md-nowrap py-2 shadow">
  <a class="py-3 px-3 text-dark text-decoration-none bg-info col-md-3 col-lg-2 me-0 px-3" href="#"><?= $brand; ?></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <form class="w-100 w-md-30 mx-2 my-1" action="<?= base_url(); ?>admin/dashboard/search" method="get" accept-charset="utf-8">
    <input name="q" class="form-control " type="text" placeholder="Search" aria-label="Search">
  </form>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse navbar-collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= base_url(); ?>admin/dashboard/">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>admin/dashboard/upload.php">
              <span data-feather="file"></span>
              post article
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>admin/dashboard/upload-video.php">
              <span data-feather="video"></span>
              post video
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="<?= base_url(); ?>admin/dashboard/statistic/">
              <span data-feather="layers"></span>
              statistic
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link link-confirm" link-href="<?= base_url()."admin/dashboard/action.php?logout=".time(); ?>">
              <span data-feather="users"></span>
              log out
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <?php if($totalHal > 0): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
          <select class="form-control" name="page" id="page">
          <?php for($i = 0; $i < $totalHal; $i++): ?>
            <?php if($halActive == $i + 1): ?>
            <option selected value="<?= $halActive; ?>">Page <?= $halActive; ?> of <?= $totalHal; ?></option>
            <?php else: ?>
            <option value="<?= $i + 1; ?>">Page <?= $i + 1; ?></option>
            <?php endif; ?>
          <?php endfor; ?>
          </select>
        </div>
        <?php endif; ?>
      </div>
      <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
      <canvas class="my-4 w-100" id="myChart2" width="900" height="380"></canvas>
      <canvas class="my-4 w-100" id="myChart3" width="900" height="380"></canvas>
    </main>
  </div>
</div>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
      <script src="<?= base_url(); ?>admin/dashboard/script.js"></script>
      <script type="text/javascript" charset="utf-8">
        let alldata = <?= json_encode($all); ?>;
        
        let time = [];
        let visitors = [];
        let titles = [];
        let words = [];
        let dump = [];
        let jumlahCategory = [];
        let namaCategory = [];
        
        alldata.forEach((d) => {
          let date = new Date(d.date).getDate();
          let m = new Date(d.date).getMonth() + 1;
          let y = new Date(d.date).getFullYear();
          m = (m.toString().length < 2) ? "0" + m : m;
          y = (y.toString().length < 2) ? "0" + y : y;
          date = (date.toString().length < 2) ? "0" + date : date;
          time.push(`${date}-${m}-${y}`);
          visitors.push(d.visited);
          titles.push(d.title.slice(0, 20));
          if (d.content) {
            words.push(d.content.length);
          } else {
            words.push(d.description.length);
          }
          if(!dump.includes(d.category, 0)){
            dump.push(d.category);
            jumlahCategory.push(getEachTags(d.category));
            namaCategory.push(d.category);
          }
        })
        function getEachTags(cat){
        let countTags = 0;
          alldata.forEach((tag) => {
            if(cat == tag.category){
              countTags += 1;
            }
          })
          return countTags
        }
        
          feather.replace({ 'aria-hidden': 'true' })
          // Graphs
          var ctx = document.getElementById('myChart')
          var ctx2 = document.getElementById('myChart2')
          var ctx3 = document.getElementById('myChart3')
          // eslint-disable-next-line no-unused-vars
          
          var myChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: time,
              datasets: [{
                label: "visitor",
                data: visitors,
                lineTension: 0,
                backgroundColor: 'transparent',
                borderColor: `#1e55ff`,
                borderWidth: 1,
                pointBackgroundColor: `#5fdfff`
              }]
            },
            options: {
              plugins: {
                    title: {
                        display: true,
                        text: 'Count visitor'
                    }
                },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: false
                  }
                }]
              },
              legend: {
                display: false
              }
            }
          })
          var myChart2 = new Chart(ctx2, {
            type: "radar",
            data: {
              labels: titles,
              datasets: [{
                  label: 'words',
                  data: words,
                  backgroundColor: randomColor(titles.length)
                }]
              
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'words'
                    }
                }
            }
          })
          const data = {
            labels: namaCategory,
            datasets: [{
              label: 'Category',
              data: jumlahCategory,
              backgroundColor: randomColor(namaCategory.length)
            }]
          };
          const config = {
            type: 'bar',
            data: data,
            options: {
              plugins: {
                    title: {
                        display: true,
                        text: 'Category'
                    }
                },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          };
          var myChart3 = new Chart(ctx3, config);
          
          function randomColor(each = 1){
            let arr = []
            if (each > 1) {
              for(let i = 0; i < each; i++) {
                arr.push(`hsl(${Math.random() * 180}, 50%, 50%)`)
              }
            } else {
               arr.push(`hsl(${Math.random() * 360}, 50%, 50%)`)
            }
            return arr
          }
      </script>
        <script src='//cdn.jsdelivr.net/npm/eruda'></script>
  <script>eruda.init();</script>
  </body>
</html>
