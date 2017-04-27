
var CarteMaintenu = false;

$(document).ready(function(){
    ResizeBlocs();
    

    if (window.addEventListener)
        window.addEventListener('DOMMouseScroll', wheel, false);

    
    $("#carte").onmousewheel = document.onmousewheel = wheel;

    
    var today = new Date();
    getActualite(today.getFullYear()+"/01/01",today.getFullYear()+"/12/31","","");
    
    
    //$("#dateEnd").hide();
    $("#alertDates").hide();
    $("#buttonSearch").hide();
    
    
    $.fn.datepicker.defaults.format = "yyyy/mm/dd";
    $( "#dateStart" ).datepicker('update', today.getFullYear()+"/01/01");
    $( "#dateEnd" ).datepicker('update', today.getFullYear()+"/12/31");
    $( "#dateActualite" ).datepicker('update', today);
    
    $( "#carte" ).draggable();
    $("#Admin1").hide();
    $("#LabelsAdmin1").hide();
    $("#Admin1 polygon").css({"stroke":"#ccebff","stroke-width":0.2});
    $("#carte").fadeIn("slow");
    
    
    //RECUPERATION DES VALEURS DE SELECT
    var x = document.getElementById("Pays").options;
    for(i=0; i < x.length; i++){
        listePays.push([x[i].value,x[i].label]);
    }
    var x = document.getElementById("typeActu").options;
    for(i=0; i < x.length; i++){
        listeThemes.push([x[i].value,x[i].label]);
    }
});



//MENU
/* --- FORBIDDEN ---
$("#selectByDatePeriod").click(function(e){
    valeur = $("#selectByDatePeriod").html();
    valeur = valeur.replace('<span class="glyphicon glyphicon-search"></span> ',"");
    console.log(valeur);
    switch(valeur){
        case "By date":
            $("#selectByDatePeriod").html("<span class='glyphicon glyphicon-search'></span> By period");
            $("#dateEnd").hide();
            $("#alertDates").hide();
            break;
        case "By period":
            $("#selectByDatePeriod").html("<span class='glyphicon glyphicon-search'></span> By date");
            
            
            
             $("#dateEnd").show();
            ControleDates();
            break;
        default:
            break;
    }
});
*/
$("#menuPrint").click(function(){
    $('<canvas id="canvas" class="captureZone" width="500px" height="500px" style="position:absolute;z-index:0"></canvas>').appendTo('#board');
    
    //ZOOM CARTE
    width = $('#carte').css("width").replace('px','');
    height = $('#carte').css("height").replace('px','');
    width = width*10;
    height = height*10;
    $('#carte').css({"width":width+'px',"height":height+'px'});
    
    //COPIE DE LA CARTE
    canvg(document.getElementById('canvas'),$('#carte')[0].outerHTML);
    $('#carte').hide();
    //$('.menu').hide();
    $('#canvas').css({"width":width+'px',"height":height+'px'});
    $('#canvas').css({"left":$('#carte').css("left"),"top":$('#carte').css("top")});
    
	html2canvas(($("#canvas")), {
        onrendered: function(canvas) {
            canvas.toBlob(function(blob) {
                saveAs(blob, "Snapshotf.png");
            });
             $('#canvas').remove();
            //$('.menu').show();
            width = width/10;
            height = height/10;
            $('#carte').css({"width":width+'px',"height":height+'px'});
            $('#carte').show();
        },
    background:"#ffffff"
    });
});
$("#dateEnd,dateStart").change(function(){
	ControleDates();
});
$("#dateStart").change(function(){
    valeur = $("#selectByDatePeriod").html();
    if(valeur=="By period"){
        ControleDates();
    }
});
$("#menulogOut").click(function(){
    logOut();
});
$("#addNews").click(function(){
    postActualite();
});
$("#buttonExportExcel").click(function(){
    $("#ExportDetails").table2excel({
        name: "WeeklySnapshot",
        filename: "WHS"
    });
});
$("#buttonExportTxt").click(function(){
    var nbPages = 0;
    
    $('<canvas id="canvas" class="captureZone" width="0px" height="0px" style="position:absolute;z-index:0"></canvas>').appendTo('#board');
    
    //ZOOM CARTE
    width = $('#carte').css("width").replace('px','');
    height = $('#carte').css("height").replace('px','');
    width = width*4;
    height = height*4;
    $('#carte').css({"width":width+'px',"height":height+'px'});
    
    //COPIE DE LA CARTE
    canvg(document.getElementById('canvas'),$('#carte')[0].outerHTML);
    $('#carte').hide();
    $('.menuZoom').hide();
    $('#canvas').css({"width":width+'px',"height":height+'px'});
    $('#canvas').css({"left":$('#carte').css("left"),"top":$('#carte').css("top")});
    
    
	html2canvas(($("#canvas")), {
        onrendered: function(canvas) {
            
            //EXPORT PDF
            
            
            var doc = new jsPDF();
            var pos = 40;


            //AJOUT DE LA CARTE
            console.log(canvas.width);
            heightCarte = (canvas.height/(canvas.width/190));
            doc.addImage(canvas.toDataURL('image/png'), 'png', 10, 30, 190,heightCarte );
            pos = 40+heightCarte;

            //ECRITURE DE L'ENTETE
            doc.setTextColor("#ffffff");
            doc.setFillColor(0, 153, 255);
            doc.rect(10, 10, 190, 10, "F");
            doc.setFontSize(12);
            doc.text(15, 17, "Weekly humanitarian snapshot West and Central Africa");
            doc.text(182, 17, "OCHA");
            var imgData = DirectImgToData('images/un.png');
            doc.addImage(imgData, 'png', 174, 12, 7, 7);
            doc.setFontSize(8);
            doc.text(120, 17, $("#dateStart").val()+" to "+$("#dateEnd").val());
            doc.setTextColor("#000000");


            //AFFICHAGE DES FILTRES
            if(filtresPays.length==0){
                doc.text(10, 24, "All Countries");
            }else{
                doc.text(10, 24, "Countries : "+ArrayToVar2(filtresPays,listePays,", "));
            }
            if(filtresThemes.length==0){
                doc.text(10, 28, "All themes");
            }else{
                doc.text(10, 28, "themes : "+ArrayToVar2(filtresThemes,listeThemes,", "));
            }




            //ECRITURE DU PIED DE PAGE
            doc.setTextColor("#000000");
            doc.setFontSize(8);
            var today = new Date();
            doc.text(10, 285, "Creation date: "+DateToText(today)+"  Map data sources: UNCS, DevInfo, OCHA. Feedback: ocharowca@un.org Twitter: @OCHAROWCA");
            doc.text(10, 288, "The boundaries and names shown and the designations used on this map do not imply official endorsement or acceptance by the United Nations.");
            nbPages++;
            ni = 0;
            writeCountry = true;
            for(i=0; i < listeActualites.length; i++) {
                if(pos<250){
                    doc.setFontSize(12)
                    //PAYS ET REGION
                    ni = i+1;
                    var paysRegion = listeActualites[i][3]+" ("+listeActualites[i][7]+")";
                    var paysRegionNext = "";
                    if(ni<listeActualites.length){
                        paysRegionNext = listeActualites[ni][3]+" ("+listeActualites[ni][7]+")";
                    }

                    if(writeCountry){
                        doc.text(10, pos, paysRegion);
                    }

                    if(paysRegionNext!=paysRegion){
                        writeCountry = true;
                    }else{
                        writeCountry = false;
                    }


                    doc.setFontSize(8)
                    pos = pos+6;
                    doc.setFontSize(8)
                    doc.text(20, pos, listeActualites[i][12]);
                    doc.setFontSize(10);
                    doc.setTextColor("#0099ff");
                    doc.text(36, pos, listeActualites[i][10]);
                    var imgData = DirectImgToData('images/'+listeActualites[i][0]+listeActualites[i][13]+'.png');
                    doc.addImage(imgData, 'png', 10, (pos-5), 7, 7);
                    doc.setTextColor("#000000");
                    doc.setFontSize(8)
                    pos = pos+6;

                    var details = MakeToArray(listeActualites[i][11]);
                    console.log(details);
                    doc.text(10, pos, details);

                    pos = pos+(details.length * 4)+6;
                }else{
                    doc.addPage();

                    //ECRITURE DE L'ENTETE
                    doc.setTextColor("#ffffff");
                    doc.setFillColor(0, 153, 255);
                    doc.rect(10, 10, 190, 10, "F");
                    doc.setFontSize(12);
                    doc.text(15, 17, "Weekly humanitarian snapshot West and Central Africa");
                    doc.text(182, 17, "OCHA");
                    var imgData = DirectImgToData('images/un.png');
                    doc.addImage(imgData, 'png', 174, 12, 7, 7);
                    doc.setFontSize(8);
                    doc.text(120, 17, $("#dateStart").val()+" to "+$("#dateEnd").val());

                    //AFFICHAGE DES FILTRES
                    doc.setTextColor("#000000");

                    if(filtresPays.length==0){
                        doc.text(10, 24, "All Countries");
                    }else{
                        doc.text(10, 24, "Countries : "+ArrayToVar2(filtresPays,listePays,", "));
                    }
                    if(filtresThemes.length==0){
                        doc.text(10, 28, "All themes");
                    }else{
                        doc.text(10, 28, "themes : "+ArrayToVar2(filtresThemes,listeThemes,", "));
                    }

                    //ECRITURE DU PIED DE PAGE
                    doc.setFontSize(8);
                    var today = new Date();
                    doc.text(10, 285, "Creation date: "+DateToText(today)+"  Map data sources: UNCS, DevInfo, OCHA. Feedback: ocharowca@un.org Twitter: @OCHAROWCA");
                    doc.text(10, 288, "The boundaries and names shown and the designations used on this map do not imply official endorsement or acceptance by the United Nations.");
                    
                    nbPages++;
                    pos = 40;
                }



            }
            
            //ECRITURES DES NUMEROS DE PAGES
            for(i=1; i <= nbPages; i++) {
                doc.setPage(i);
                doc.text(180, 291, "page "+i+" of "+nbPages);
            }

            doc.save('autoprint.pdf');
            
            
            //FIN EXPORT PDF
            
            $('#canvas').remove();
            $('.menuZoom').show();
            width = width/4;
            height = height/4;
            $('#carte').css({"width":width+'px',"height":height+'px'});
            $('#carte').show();
        },
    background:"#ffffff",
        letterRendering:true
    });

});
$("#buttonSearch").click(function(){
    FiltrerActualite();
});
$("#zoomplus").click(function(){
    handle(4);
});
$("#zoommoins").click(function(){
    handle(-4);
});








//CARTE
/* --- FORBIDDEN ---
$("#cartevvvv").mousedown(function(e){
    clicking = true;
    var mouseXSart = e.pageX,mouseYSart = e.pageY;
    origineX  = mouseXSart;
    origineY  = mouseYSart;
    
}).mouseup(function(e){
    clicking = false;
}).mouseleave(function(e){
    clicking = false;
    
}).mousemove(function(e2){
    try {
        if(clicking == false) return;
    
        coef = 0.08;
        var mouseX = e2.pageX,mouseY = e2.pageY;
        topVal = Math.round($("#carte").css("top").replace("px",""));
        leftVal = Math.round($("#carte").css("left").replace("px",""));


        topVal = topVal+((mouseY-origineY)*coef);
        leftVal = leftVal+((mouseX-origineX)*coef);
        //console.log("OY:"+origineY+" - topVal:"+topVal+" - mouseY:"+mouseY);
        $("#carte").css({"top":topVal+"px","left":leftVal+"px"});
    }
    catch(err) {

    }
});
*/
/* --- FORBIDDEN ---
$("#splitBoard").click(function(e) {
    $('.bubbleMenu').remove();
    $("body").append("<div class='bubbleMenu'><img class='imgGrid' id='grid1' src='images/1.png'/><img class='imgGrid' id='grid1h2v' src='images/1h2v.png'/><img class='imgGrid' id='grid2h' src='images/2h.png'/><img id='grid2v' class='imgGrid' src='images/2v.png'/><img id='grid2v1h' class='imgGrid' src='images/2v1h.png'/><img id='grid3h' class='imgGrid' src='images/3h.png'/><img id='grid3v' src='images/3v.png'/></div>");
    
    var mouseX = e.pageX,mouseY = e.pageY;
    
    //REPOSITIONNER LA BULLE PAR RAPPORT A LA SOURIS
	$('.bubbleMenu').css({
	  top: $("#splitBoard").position().top,
	  left: 50
	});  
    
    
    $(".imgGrid").click(function(e) {
        idGrid = $(this).attr('id');
        $('#board').remove();
        $("body").append("<div id='board' class=row''></div>");
        
        
        switch(idGrid){
            case "grid1":
                $("#board").append("<div class='row' id='ligne1'></div>");
                SplitRow("ligne1",1);
                break;
            case "grid2h":
                $("#board").append("<div class='row' id='ligne1'></div>");
                $("#board").append("<div class='row' id='ligne2'></div>");
                SplitRow("ligne1",1);
                SplitRow("ligne2",1);
                
                
                break;
            case "grid2v":
                $("#board").append("<div class='row' id='ligne1'></div>");
                SplitRow("ligne1",2);
                break;
            case "grid1h2v":
                $("#board").append("<div class='row' id='ligne1'></div>");
                $("#board").append("<div class='row' id='ligne2'></div>");
                SplitRow("ligne1",1);
                SplitRow("ligne2",2);
                break;
            case "grid2v1h":
                $("#board").append("<div class='row' id='ligne1'></div>");
                $("#board").append("<div class='row' id='ligne2'></div>");
                SplitRow("ligne1",2);
                SplitRow("ligne2",1);
                break;
            case "grid3h":
                $("#board").append("<div class='row' id='ligne1'></div>");
                $("#board").append("<div class='row' id='ligne2'></div>");
                $("#board").append("<div class='row' id='ligne3'></div>");
                SplitRow("ligne1",1);
                SplitRow("ligne2",1);
                SplitRow("ligne3",1);
                break;
            case "grid3v":
                $("#board").append("<div class='row' id='ligne1'></div>");
                SplitRow("ligne1",3);
                break;
            default :
                break;
        }
    });
});
*/














