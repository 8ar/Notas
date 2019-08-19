$(document).ready(function() {
  console.log('jQuery funciona')
  $('#search').keyup(function(){
    let search = $('#search').val();
    console.log(search);
  })

});
