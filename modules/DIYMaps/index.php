<!DOCTYPE html>
<html lang="en">
<head>
    <title>HRT</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    

	
    <!-- CSS-->
    <link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="bootstrap/css/bootstrap-datepicker.css"/>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    
    <link rel="stylesheet" href="jqueryUI/jquery-ui.min.css"/>
    
    <link rel="stylesheet" href="css/css.css"/>

    
    
    <!-- JAVASCRIPT-->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="js/Functions.js"></script>
    <script src="js/gifshot.js"></script>
    <script src="js/canvg.js"></script>
    <script src="js/FileSaver.min.js"></script>
    <script src="js/html2canvas.js"></script>
    
    
    
    

    
	    
	
</head>
<body>

        <div class='row searchBar'>
            <div class='col-lg-12 noselect'>                
                <div class='col-lg-12'>
                    <form class="form-inline memeLigne">
                        <span class="titrepage noselect">DIY Interactive Maps</span>
                        <?php
                            $ListFiles = scandir("svg/");
                            echo "<select id='listeCarte' class='form-control' onchange='LoadMap(this)' style='font-size:12px;'>>";
                            if(count($ListFiles)>1){
                                echo "<option value=''>Sélectionnez une carte</option>";
                                for ($i = 2; $i < count($ListFiles); $i++) {
                                    echo "<option value='".$ListFiles[$i]."'>".$ListFiles[$i]."</option>";
                                }
                            }else{
                                echo "<option value=''>Aucune carte détectée</option>";
                            }
                            echo "</select>";
                        ?>
                        
                        <span id="drop">  Now drop your file data in csv format</span>
                        
                        <select id='MapLabel' class='form-control' onchange='changeData()' style='font-size:12px;'>
                            <option value="">Afficher des données de</option>
                        </select>
                        
                        <select id='MapValue' class='form-control' onchange='changeData()' style='font-size:12px;'>
                            <option value=''>Avec les valeurs de</option>
                        </select>
                        <img id='buttonPlay' src='images/play.svg'/>
                        <img id='buttonStop' src='images/stop.svg'/>
                        <span class="glyphicon glyphicon-time" id='IconTimeLabel' aria-hidden="true"></span>
                        <input class='form-control' value='10'  id='inputTime'  style="width:50px;" style='font-size:12px;'/>
                        <img id='buttonDownloadGif' class="smallImage" src='images/gif.svg'/>
                        <img id='buttonDownloadJpg' class="smallImage" src='images/jpg.svg'/>
                        
                        
                    </form>
                </div>
            </div>
        </div>
        

        <div class='row' id='CaptureZone'>
            <div class='row' id='infoBloc'>
                xxxxxxx
            </div>
            <div id='carte' class='row'>

            </div>
        </div>
        
        
        <div id="tempCaptureZone" class='row'>
        </div>
        <div id="output" class='row'>
        </div>
        
        <div id="GifOutput" class='row'>
        </div>
    
        <div id="JpgOutput" class='row'>
        </div>
    
        
    
        
  
    
    
    
    

    
    




<script type="text/javascript">
    
    

     
    
var output = document.getElementById("output");
    var datas = null;
    var ListeDesEnteteTypeNombre = [];
    var counter=0;
    var animationRunning =false;
    
    window.ondragover = function(e) {e.preventDefault()}
    window.ondrop = function(e) {
    e.preventDefault();
    //console.log("Reading...");
    var length = e.dataTransfer.items.length;
    if(length > 1){
        console.log("Please only drop 1 file.");
    } else {
        upload(e.dataTransfer.files[0]);
    }
}



</script>
<script src="js/ScriptEnd.js"></script>
</body>