document.querySelectorAll(".post .content").forEach((e) => {
  if(e.innerHTML.length > 20){
    e.innerHTML=`${e.innerHTML.slice(0, 80)}..`;
  }
})