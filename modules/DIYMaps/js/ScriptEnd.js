$(document).ready(function(){
  
    /*
var jqxhr = $.get( "http://ors.ocharowca.info/api/v2/Projects/Projects.ashx?country=10&cluster=3&pid=1&isops=yes&isfunded=both&inclids=no&rtype=1&year=2017&format=xml&lng=fr", function() {
  alert( "success" );
})
  .done(function() {
    alert( "second success" );
  })
  .fail(function() {
    alert( "error" );
  })
  .always(function() {
    alert( "finished" );
  });
    */
    
    
    $("#drop").hide();
    $("#MapLabel").hide();
    $("#MapValue").hide();
    $("#buttonPlay").hide();
    $("#buttonStop").hide();
    $("#buttonDownloadGif").hide();
    $("#buttonDownloadJpg").hide();
    $("#IconTimeLabel").hide();
    $("#inputTime").hide();
    
});



$("#buttonPlay").click(function(){
    $("#buttonPlay").hide();
    $("#buttonStop").show();
    animateData(false);
});


$("#buttonDownloadJpg").click(function(){
    alert("ok");
    CaptureImage(false,"");
});

$("#buttonDownloadGif").click(function(){
    alert("ok gif");
    animateData(true);
});

$("#buttonStop").click(function(){
    $("#buttonStop").hide();
    $("#buttonPlay").show();
});









