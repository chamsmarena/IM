//FILE UPLOAD AND READING
function csvJSON(csv){
 
  var lines=csv.split("\n");
    var separator = ";";
  var result = [];
 
  var headers=lines[0].split(separator);
 
  for(var i=1;i<lines.length;i++){
 
	  var obj = {};
	  var currentline=lines[i].split(separator);
 
	  for(var j=0;j<headers.length;j++){
		  obj[headers[j]] = currentline[j];
	  }
 
	  result.push(obj);
 
  }
  
  //return result; //JavaScript object
  return JSON.stringify(result); //JSON
}

function getHeader(csv){
 
    var lines=csv.split("\n");
    var separator = ";";
    var result = [];
 
    var headers=lines[0].split(separator);
  
  //return result; //JavaScript object
  return headers //JSON
}

function getSecondLine(csv){
 
    var lines=csv.split("\n");
    var separator = ";";
    var result = [];
 
    var headers=lines[1].split(separator);
  
  //return result; //JavaScript object
  return headers //JSON
}

function upload(file){
    if( file.type.match(/text\/csv/) || file.type.match(/vnd\.ms-excel/) ){
    //if(file.type.match(/text\/csv/)){
        oFReader = new FileReader();
        oFReader.onloadend = function() {

            //console.log(csvJSON(this.result));

            var json = csvJSON(this.result);

            var blob = new Blob([json], {type: 'application/json'});
            var url = URL.createObjectURL(blob);
            //output.innerHTML = json;

            //console.log("donnes "+json);
            var headers = getHeader(this.result);
            var secondLine = getSecondLine(this.result);
            datas= JSON.parse(json);
            
            
            var selectMapLabel = document.getElementById("MapLabel");
            var selectMapValue = document.getElementById("MapValue");
            
            for(var i = 0; i < headers.length; i++) {
                if(isNaN(parseFloat(secondLine[i]))==false){
                    var opt = headers[i];
                    var el = document.createElement("option");
                    el.textContent = opt;
                    el.value = opt;
                    selectMapValue.appendChild(el);
                    ListeDesEnteteTypeNombre.push(headers[i]);
                }else{
                    
                }
            }
            
            for(var i = 0; i < headers.length; i++) {
                if(isNaN(parseFloat(secondLine[i]))==false){
                  
                    
                }else{
                    var opt = headers[i];
                
                    var el = document.createElement("option");
                    el.textContent = opt;
                    el.value = opt;
                    selectMapLabel.appendChild(el);
                }
            }
            
            $("#drop").fadeOut("slow",function(){
                $("#MapLabel").fadeIn("slow");
                $("#MapValue").fadeIn("slow");
            });
            

        };
        oFReader.readAsText(file);
    } else {
        console.log("This file does not seem to be a CSV.");
    }
}



//MAP LOADING AND FEEDING
function changeData(SelectedEnteteLabel,SelectedEnteteValue){
    var labels = [];
    var valeurs = [];
    
    if(SelectedEnteteLabel==null){
        SelectedEnteteLabel=$("#MapLabel").val();
    }
    if(SelectedEnteteValue==null){
        SelectedEnteteValue=$("#MapValue").val();
    }
    
    if(!animationRunning){
        $("#buttonPlay").fadeIn("slow");
        $("#inputTime").fadeIn("slow");
        $("#IconTimeLabel").fadeIn("slow");
    }else{
        $("#inputTime").hide();
        $("#IconTimeLabel").hide();
    }
    
   
    $("#buttonDownloadGif").fadeIn("slow");
    $("#buttonDownloadJpg").fadeIn("slow");
    if(($("#MapLabel").val()=="") || ($("#MapValue").val()=="")){
        $("#buttonPlay").hide();
        $("#buttonStop").hide();
        
        $("#buttonDownloadGif").hide();
        $("#buttonDownloadJpg").hide();
    }
    
    
    for(var i = 0; i < datas.length; i++) {
        entetes=Object.keys(datas[i]);
        var exist = false;
        var index = 0;
        for(var j = 0; j < entetes.length; j++) {
            if(entetes[j]==SelectedEnteteLabel){
                for(var k=0; k<labels.length; k++) {
                    
                    if (labels[k] == datas[i][entetes[j]]){
                        index=k;
                        exist= true;
                        console.log("exsit");
                    }
                    //console.log("exsitdd "+labels[k]+" "+datas[i][entetes[j]]+" = "+exist);
                }

                if(exist==false){
                    labels.push(datas[i][entetes[j]]);
                }
            }
        
            if(entetes[j]==SelectedEnteteValue){
                if(exist==false){
                    valeurs.push(parseFloat(datas[i][entetes[j]]));
                    
                }else{
                    valeurs[index]+=parseFloat(datas[i][entetes[j]]);
                    exist = false;
                }
            }
            
            //console.log("ente "+datas[i][entetes[j]]);
        }
    }
    
    //console.log("entete "+labels);
    //console.log("valeurs "+valeurs);
    
    LoadMapData(labels,valeurs);
     
}

function LoadMap(champ){
    var valeur = champ.options[champ.selectedIndex].value;
    $("#carte").load("svg/"+valeur, function() {
        $('polygon').css({'fill': 'rgba(255, 255, 255,1)'});
        $('text').css({'fill': 'rgba(0, 0, 0,1)'});
        
        $("#drop").fadeIn("slow");
    });
}

function LoadMapData(labelArray,valueArray){ 
    
    $( "polygon" ).animate({
        fill: 'rgba(255, 255, 255,1)'
        }, 5, function() {
        

            var highest_value=0;
            for(i=0; i < valueArray.length; i++) {
                if(isNaN(parseInt(valueArray[i]))==false){
                    if(parseInt(valueArray[i])>highest_value){
                        highest_value=parseInt(valueArray[i]);
                    }
                }
            }

            for(i=0; i < labelArray.length; i++) {
                if(highest_value==0){


                }else{
                    if(labelArray[i]!=""){
                        valeur = valueArray[i];
                        if(isNaN(parseInt(valeur))==true){
                            valeur = 0;
                            //$('#'+ labelArray[i]).css({'fill': 'rgba(255, 255, 255,1)'});
                        }else{
                            red = 255;
                            green = Math.round((valeur/highest_value)*255);
                            blue = Math.round((valeur/highest_value)*255);
                            //alert(i+"-"+labelArray[i]+":"+red+","+green+","+blue+",");
                            $('#'+ labelArray[i]).css({'fill': 'rgba('+red+', '+green+','+blue+',1)'});
                            //console.log(i+"-"+labelArray[i]+":"+red+","+green+","+blue+",");
                        }
                    }
                } 
            }
        
        
        
    });
    
    

}



//IMAGE CAPTURE, GIF GENERATION AND ANIMATION
function GenerateGIF(){
    
    
    
    
    
    gifshot.createGIF({
        images: [
            'http://i.imgur.com/2OO33vX.jpg',
            'http://i.imgur.com/qOwVaSN.png',
            'http://i.imgur.com/Vo5mFZJ.gif'
        ]
    }, function (obj) {
        if (!obj.error) {
            var image = obj.image, animatedImage = document.createElement('img');
            animatedImage.src = image;
            document.body.appendChild(animatedImage);
        }
    });
}

function CaptureImage(download, destinationId){
    $('#tempCaptureZone').html("");
    var captureZone = "CaptureZone";
    //RESIZE
    var mapWidth = $('#'+captureZone).css("width").replace("px","");
    var mapHeight = $('#'+captureZone).css("height").replace("px","");
    var zoom = 1;
    mapWidth = mapWidth*zoom;
    mapHeight = mapHeight*zoom;
    
    $('#'+captureZone+' div svg').css({"width":mapWidth+"px","height":mapHeight+"px"});
    $('<canvas id="canvas" width="0px" height="0px" style="position:absolute;z-index:0"></canvas>').appendTo('#tempCaptureZone');
    canvg(document.getElementById('canvas'),$('#'+captureZone+' div svg')[0].outerHTML);
    //$('#carte').hide();
    
    
    
    $('#canvas').css({"width":mapWidth+"px","height":mapHeight+"px"});
    $('#canvas').css({"left":$('#'+captureZone).css("left"),"top":$('#'+captureZone).css("top")});
    
    html2canvas(($("#"+captureZone)), {
        onrendered: function(canvas) {
            if(download){
                
            }else{
                $("#"+destinationId).append(canvas);
            }
        },
        background:"#2cb5e8",
        width: mapWidth,
        height: mapHeight
    });
    
}

function animateData(saveImage){
    console.log(ListeDesEnteteTypeNombre);
    if(0<ListeDesEnteteTypeNombre.length){
        
        
        animationRunning = true;
        i = 0;
        j = 0;
        var interval = setInterval(function()
        {
            newTimeout = setTimeout(function()
            {
                $("#infoBloc").html("Playing ("+(j+1)+"/"+ListeDesEnteteTypeNombre.length+") - Datas of "+ListeDesEnteteTypeNombre[j]);
                changeData($("#MapLabel").val(),ListeDesEnteteTypeNombre[j]);
                
                console.log("save save: "+saveImage);
                if(saveImage==true){
                    console.log("Before save");
                    CaptureImage(false,"GifOutput");
                    console.log("after save");
                }
                
                j++;
                console.log("j:"+j+"--"+ListeDesEnteteTypeNombre.length);
            }, 1);
            if (j == (ListeDesEnteteTypeNombre.length-1))
            {
                animationRunning = false;
                console.log("fin");
                
                
                clearInterval(interval);
                
            }
            console.log("after");
        }, ($("#inputTime").val()*1000));
        
        console.log("Animation End");
    }
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}




