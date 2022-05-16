let base_url = window.location.origin;
const cookie = localStorage;
$(document).ready(function(){
   $.get(`${base_url}/elements/list-category.php`, function(data){
    $("footer h1").after(data);
  })
  $.get(`${base_url}/elements/sidenav.php`, function(data){
      let h = $(".container").height();
      $(".container-post").append(data);
      $(".btn-close").on("click", function(){
        $(this)[0].parentElement.parentElement.style.display="none";
      })
      controlTheme()
    })
  let hnav = $("nav").height();
  $(".container").css("margin-top", `calc(${hnav}px + 10px)`);
  $(".burger-menu").on("click",function(){
    $(".sidenav").css("display", "flex");
    setTimeout(function() {
      $(".sidenav").css("transform", "translateX(0)");
    }, 100);
    $(".btn-close").on("click", function(){
      let self = $(this)[0];
        $(this)[0].parentElement.parentElement.style.transform="translateX(200%)";
      })
  })
  $("img").on("click", function(){
    $(".container").append(`
    <div class="view-photo">
      <i class="fa fa-xmark btn-close"></i>
      <img src="${ $(this).attr("src") }" class="photo"/>
      <a target="_blank" download="${ $(this).attr("alt") }" class="btn-dl" href="${ $(this).attr("src") }">
      <i class="fa-solid fa-cloud-arrow-down"></i>
      download
      </a>
      <div class="anu"></div>
    </div>
    `);
    $(".anu, .btn-close").on("click", function(){
      $(this)[0].parentElement.remove()
    })
  })
  document.querySelectorAll("img").forEach(_ => {
    _.addEventListener("error", function() {
      _.setAttribute("src",`${base_url}/res/error-img.png`)
    })
  })
  $("video").attr("controlsList", "nodownload");
  document.addEventListener("contextmenu", function(e){
    e.preventDefault();
  }, false);
  
  
  /* remove preload */
  $(".preload").remove();
})

function controlTheme(){
  /* theme */
  /* event button */
  $("#theme").on("change", function(){
    let v = $(this)[0].checked;
    /* set dark */
    if (v) {
      $("*:not( i, a, img .hero, nav *, .header-title, .title, .desc, .duration-video, footer h1, .search-result)").css({
        "background-color":"var(--black)",
        "color":"var(--white)"
      });
      cookie.setItem("theme", "dark")
    } else {
      $("*:not( i, a, img, .hero,  nav *, .header-title, .title, .desc, .duration-video, footer h1, .search-result)").css({
        "background-color":"var(--white)",
        "color":"var(--black)"
      });
      cookie.setItem("theme", "light")
    }
    /* change icon */
    $(".theme-icon").toggleClass("fa-sun");
    $(".theme-icon").toggleClass("fa-moon");
  })
  let theme = cookie.getItem("theme")
  if (theme) {
    if (theme == "dark") {
      $(".switch").click()
      console.log("theme changed!");
    }
  }
}