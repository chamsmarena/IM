
$(document).ready(function(){



    
});


$("img").mouseover(function(){
    var id = $(this).attr("id");
    var contenu = "";
    switch(id){
        case "reportNews":
            contenu = "View and report news in Ocha Orwca";
            break;
        case "diyMap":
            contenu = "Make your own interractive map";
            break;
        default:
            break;
    }
                          
    $('<div class="info_bulle">'+contenu+'</div>').appendTo('body');
    
}).mouseleave(function(){
    $('.info_bulle').remove();
}).mousemove(function(e){
    var mouseX = e.pageX,mouseY = e.pageY;
	$('.info_bulle').css({
	  top: mouseY-40,
	  left: mouseX - ($('.info_bulle').width() / 2)
	});
});


$('#reportNews').click(function (e) {
    window.open('modules/WHS/', 'name'); 
});
$('#diyMap').click(function (e) {
    window.open('modules/DIYMaps/', 'name'); 
});







