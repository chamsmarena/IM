
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

function changeData(){
    var labels = [];
    var valeurs = [];
    
    for(var i = 0; i < datas.length; i++) {
        entetes=Object.keys(datas[i]);
        var exist = false;
        var index = 0;
        for(var j = 0; j < entetes.length; j++) {
            if(entetes[j]==$("#MapLabel").val()){
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
        
            if(entetes[j]==$("#MapValue").val()){
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
    
    //console.log("enete "+labels);
    //console.log("vall "+valeurs);
    
    LoadMapData(labels,valeurs);
     
}
function animateData(){
    var labels = [];
    var valeurs = [];
    
    for(var i = 0; i < datas.length; i++) {
        entetes=Object.keys(datas[i]);
        var exist = false;
        var index = 0;
        
        //RECUPERATION DES ENTETES
        for(var j = 0; j < entetes.length; j++) {
            if(entetes[j]==$("#MapLabel").val()){
                for(var k=0; k<labels.length; k++) {
                    
                    if (labels[k] == datas[i][entetes[j]]){
                        index=k;
                        exist= true;
                        //console.log("exsit");
                    }
                    //console.log("exsitdd "+labels[k]+" "+datas[i][entetes[j]]+" = "+exist);
                }

                if(exist==false){
                    labels.push(datas[i][entetes[j]]);
                }
            }
        
            for(var j = 0; j < ListeDesEnteteTypeNombre.length; j++){
                
            }
            if(entetes[j]==$("#MapValue").val()){
                if(exist==false){
                    valeurs.push(parseFloat(datas[i][entetes[j]]));
                }else{
                    valeurs[index]+=parseFloat(datas[i][entetes[j]]);
                    exist = false;
                }
            }
        }
    }
    
    
    

    
    
    console.log("enete "+labels);
    console.log("vall "+valeurs);
    
    LoadMapData(labels,valeurs);
     
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

        };
        oFReader.readAsText(file);
    } else {
        console.log("This file does not seem to be a CSV.");
    }
}

function LoadMap(champ){
    var valeur = champ.options[champ.selectedIndex].value;
    $("#carte").load("svg/"+valeur, function() {
      $('polygon').css({'fill': 'rgba(255, 255, 255,1)'});
      $('text').css({'fill': 'rgba(0, 0, 0,1)'});
    });
}

function LoadMapData(labelArray,valueArray){ 
    $('polygon').css({'fill': 'rgba(255, 255, 255,1)'});
    
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
                    $('#'+ labelArray[i]).css({'fill': 'rgba(109, 207, 246,'+ valeur/highest_value + ')'});
                    
                }
            }
        } 
    }

}


