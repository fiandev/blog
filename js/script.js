$.getScript("https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js");
document.querySelectorAll(".time-upload").forEach((e) => {
  let date = $(e).attr("data-date");
  let timeUpload = new Date(date).getTime();
  let time_now = new Date().getTime();
  
  let selisihWaktu = time_now - timeUpload;
  var d = Math.round(selisihWaktu / (1000 * 60 * 60 * 24));
  var h = Math.floor((selisihWaktu % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var m = Math.floor((selisihWaktu % (1000 * 60 * 60)) / (1000 * 60));
  var s = Math.floor((selisihWaktu % (1000 * 60)) / 1000);
  var msg;
  if(s == 0 && m < 1 && d < 1){
    msg = `baru saja`;
  }else if(s < 60 && m < 1 && d < 1){
    msg = `${s} detik lalu`;
  } else if(m < 60 && h < 1 && d < 1) {
    msg = `${m} menit yang lalu`;
  } else if(m < 60 && d < 1){
    msg = `${h} jam yang lalu`;
  } else if(d >= 1 && d < 7){
    msg = `${d} hari yang lalu`;
  } else if(d >= 7 && d < 30){
    d = Math.floor(d / 7);
    msg = `${d} minggu yang lalu`;
  } else if(d >= 30 && d < 365){
    d = Math.floor(d / 30);
    msg = `${d} bulan yang lalu`;
  } else if(d >= 365 || d >= 366){
    d = Math.round(d / 365.25);
    msg = `${d} tahun yang lalu`;
  }
  $(e).html(msg);
})
$(document).ready(function(){
  /*
  $("footer").before(`
    <div class="ads">
      <i class="fa fa-xmark btn-close"></i>
      <img src="${base_url}/res/error-img.png" />
    </div>
  `);
  $(".ads .btn-close").on("click", function(){
    $(this)[0].parentElement.remove()
  })
  */
  document.querySelectorAll(".post .title a").forEach(e => {
    let text = e.innerHTML;
    e.innerHTML=text.substring(0, 45) + ".."
  })
})
function parseMd(md) {
  //blockquote
  md = md.replace(/^\>(.+)/gm, "<blockquote>$1</blockquote>");

  //h
  md = md.replace(/[\#]{6}(.+)/g, "<h6>$1</h6>");
  md = md.replace(/[\#]{5}(.+)/g, "<h5>$1</h5>");
  md = md.replace(/[\#]{4}(.+)/g, "<h4>$1</h4>");
  md = md.replace(/[\#]{3}(.+)/g, "<h3>$1</h3>");
  md = md.replace(/[\#]{2}(.+)/g, "<h2>$1</h2>");
  md = md.replace(/[\#]{1}(.+)/g, "<h1>$1</h1>");

  //alt h
  md = md.replace(/^(.+)\n\=+/gm, "<h1>$1</h1>");
  md = md.replace(/^(.+)\n\-+/gm, "<h2>$1</h2>");

  //images
  md = md.replace(/\!\[([^\]]+)\]\(([^\)]+)\)/g, '<img src="$2" alt="$1" class="insert-image" />');

  //links
  md = md.replace(
    /[\[]{1}([^\]]+)[\]]{1}[\(]{1}([^\)\"]+)(\"(.+)\")?[\)]{1}/g,
    '<a href="$2" class="link" title="$4">$1</a>'
  );

  //font styles
  md = md.replace(/[\*\_]{2}([^\*\_]+)[\*\_]{2}/g, "<b>$1</b>");
  md = md.replace(/[\*\_]{1}([^\*\_]+)[\*\_]{1}/g, "<i>$1</i>");
  md = md.replace(/[\~]{2}([^\~]+)[\~]{2}/g, "<del>$1</del>");

  //pre
  md = md.replace(/^\s*\n\`\`\`(([^\s]+))?/gm, '<pre class="$2">');
  md = md.replace(/^\`\`\`\s*\n/gm, "</pre>\n\n");

  //code
  md = md.replace(/[\`]{1}([^\`]+)[\`]{1}/g, "<pre>$1</pre>");

  //strip p from pre
  md = md.replace(/(\<pre.+\>)\s*\n\<p\>(.+)\<\/p\>/gm, "$1$2");

  return md;
}
document.querySelectorAll(".content p").forEach(_ => {
  _.innerHTML = parseMd(_.innerText);
  hljs.highlightAll()
  var aCodes = document.querySelectorAll("pre");
    for (var i=0; i < aCodes.length; i++) {
        hljs.highlightBlock(aCodes[i]);
    }
 $("pre").on("dblclick", function(){
   let code = $(this).text();
   let temp = $("<input>")
   $("body").append(temp);
   temp.val(code).select();
   document.execCommand("copy");
   swal({
      title: "text coppied",
      text: `text successfully copied to clipboard`,
      icon: "success",
      buttons: [
        "ok, thanks"
      ],
      dangerMode: false,
    })
   temp.remove();
 })
})