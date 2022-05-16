$(".video-player").on("click", function(){
  $(this).attr("controls", true);
})
function MediaOn(){
  
}
function MediaOff(){
  $(".video-player").attr("controls", false);
}