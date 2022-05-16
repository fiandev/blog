$.getScript("https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js");
eventLinkConfirm();
$(".btn-category").on("click",function(){
  document.querySelectorAll(".input-category").forEach((e) => {
    if( $(e).attr("name") != "" ){
      $(e).attr("name","");
      $(e).attr("id","");
    } else {
      $(e).attr("name","category");
      $(e).attr("id","category");
    }
  })
  $(".input-category").toggleClass("d-none");
})

$("#page").on("change", function(){
  let v = parseInt($(this).val());
  window.location.href=`?p=${v}`;
})
document.querySelectorAll(".title-post").forEach(_ => {
  _.innerHTML = _.innerHTML.slice(0, 20) + ".."
})
$("header a").attr("href", window.location.origin);

$("form input").attr("autocomplete", "off");
$("form").attr("method", "post");
$("form input").on("keyup", function(){
  let url = $(this).parent().attr("action");
  let name = $(this).attr("name");
  let value = $(this).val();
  $.get(`${ url }?${name}=${value}`, function(response){
    $(".table-responsive").html(response)
    eventLinkConfirm();
  })
})



function eventLinkConfirm(){
  $(".link-confirm").on("click", function(){
  let link = $(this).attr("link-href");
  swal({
      title: "Are you sure?",
      text: `you will be redirect to ${link}`,
      icon: "warning",
      buttons: [
        'No, cancel it!',
        'Yes, I am sure!'
      ],
      dangerMode: true,
    }).then(function(isConfirm) {
      if (isConfirm) {
        swal({
          title: 'Shortlisted!',
          text: 'you will be redirected',
          icon: 'success'
        }).then(function() {
          window.location.href=link;
        });
      } else {
        swal("Cancelled", "ok Cancelled :)", "error");
      }
    })
})
}