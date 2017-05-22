<?php
// Start the session
include("../scripts/connectDb.php");
session_start();

if(isset($_SESSION["USER"])){
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Weekly humanitarian snapshot</title>
	<meta charset="iso-8859-15">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    

	
    <!-- CSS-->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../datatable/jquery.dataTables.css"/>
    
    <link rel="stylesheet" href="../jqueryUI/jquery-ui.min.css"/>
    <link rel="stylesheet" href="css/css.css"/>
    <link rel="stylesheet" href="../ionIcons/css/ionicons.css"/>

    
    
    <!-- JAVASCRIPT-->
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="../jqueryUI/jquery-ui.min.js"></script>
    <script src="../bootstrap/js/bootstrap-datepicker.js"></script>
    <script src="../datatable/jquery.dataTables.min.js"></script>
    <script src="js/FunctionsAdmin.js"></script>
    <script src="js/typeahead.min.js"></script>
    
    
    
	    
	
</head>
<body>

    <div class="container-fluid">
	
        <!-- SEARCH BAR -->
        <div class='row searchBar'>
            <div class='col-lg-12 noselect'>
				<div class="blocSurMemeLigne" style="margin-right:28px;">
					<img src="../images/ochalogoHorizontalx500" class='logoUn'/>
				</div>
				<div class="blocSurMemeLigne" style="margin-top:10px;padding-left:30px;border-left-style: solid;border-width: 1px;border-color: #969696;">
					<span  id='test'  class="titrepageGros noselect">Weekly Humanitarian Snapshot</span>
					<span class="titrepagePetit noselect">West and Central Africa</span>
					
				</div>
				<div class="blocConnect">
					<?php
						include("menu.php");
					?>
				</div>
            </div>
		
			
			<div class='col-lg-12 noselect' >
				<div class='col-lg-3 noselect'>
					<span class='blocSurMemeLigne textFiltre'>COUNTRY</span>
					<div class='blocSurMemeLigne'>
						<select name='Pays' id='Pays' class='form-control pointer' onchange="FiltrerPays()" style='font-size:14px;font-family: "Avenir Next","arial";'>
						  <option value='all'>All</option>
							<?php
								
								$stmt = $db->prepare("SELECT CODE_A0,CAPTION_A0 FROM admin_0 order by CAPTION_A0 asc");
								$id = 0;
								if ($stmt->execute()) {
									while ($row = $stmt->fetch()) {
										echo "<option value='".$row['CODE_A0']."'>".$row['CAPTION_A0']."</option>";
									}
								}
							?>
						</select>
					</div>
					
                </div>
				<div class='col-lg-3 noselect'>
					<span class='blocSurMemeLigne textFiltre'>THEME</span>
					<div class='blocSurMemeLigne'>
						<select name='TypeActu' id='typeActu' class='form-control pointer'  onchange="FiltrerTheme()" style='font-size:14px;font-family: "Avenir Next","arial";'>
							<option value='all'>All</option>
							<?php
								$stmt = $db->prepare("SELECT ID_TAG, LIBELLE_TAG FROM tag  order by LIBELLE_TAG asc");
								$id = 0;
								if ($stmt->execute()) {
									while ($row = $stmt->fetch()) {
										echo "<option value='".$row['ID_TAG']."'>".$row['LIBELLE_TAG']."</option>";
									}
								}
							?>
						</select>
					</div>
                </div>
				<div class='col-lg-3 noselect'>
					<span class='blocSurMemeLigne textFiltre'>FROM</span>
					<div class='blocSurMemeLigne'>
						<input type="text" class="form-control pointer" id="dateStart"  style="font-size:14px;font-family: 'arial';">					
					</div>
                </div>
				<div class='col-lg-3 noselect'>
					<span class='blocSurMemeLigne textFiltre'>TO</span>
					<div class='blocSurMemeLigne'>
						<input type="text"  class="form-control pointer" id="dateEnd" style="font-size:14px;font-family: 'arial';">						
					</div>
                </div>
				<div class='col-lg-12 noselect' style="border-bottom-style: dotted;border-width: 1px;border-color: #969696;margin-top:7px;">
				</div>
			</div>
			<div class='col-lg-12 noselect' style="margin-bottom:5px;margin-top:5px;">
				<div class='blocSurMemeLigne zoneDetailsMenu'>
					Search results
				</div>
				<div class='filtre blocSurMemeLigne'>
					<div class='filtrePays'></div><div class='filtreTheme'></div>
				</div>
			</div>
        </div>
        
		
		<!-- DATAS -->
        <div class="row">
			<div class='col-lg-12'>
				<div class='zoneDetails'>
					<div class='zoneDetailsTableau'>
					</div>
				</div>
				<div class='piedDetail'></div>
			</div>
			
        </div>
        
        
        <!-- MODALS -->
		<!-- MODAL CONNEXION -->
        <div class="modal fade" id="ModalConnexion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <form class="form-inline" method="post" action='scripts/Login.php'>
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Log in</h4>
              </div>
              <div class="modal-body">
                <input name ='login' type="text" class="form-control" id="dateStart"  placeholder="Username">
                <input name="password" type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></button>
              </div>
            </div>
          </div>
		</form>
        </div>
    </div>
    

    
    
    
    <script type="text/javascript">
        var filtresPays = [];
        var filtresThemes = [];
        var filtrestags = [];
        var filtrestagsText = "";
        var listePays = [];
        var listeThemes = [];
        var offsetDetailActualite = 0;
        var listeActualites;
		var zoom = 1.4
		
		
		
		;
		var data;
		var widthOrigin = 0;
		var heightOrigin = 0;
		
    </script>
    <script src="js/ScriptEndAdmin.js"></script>
    
</body>