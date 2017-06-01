<?php
// Start the session
include("../scripts/connectDb.php");
session_start();

if(isset($_SESSION["USER"])){
	
	//RECUPERATION DES INFOS DE L'ACTUALITE
	$idAmin0 = trim($_GET['id']);

	
	//RECUPERATION ACTUALITE ANGLAISE
	$requete = "SELECT ID_A0, CODE_A0, CAPTION_A0 FROM ADMIN_0 WHERE ID_A0 = ?";
	$infoAdmin0 = null;

	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute(array($idAmin0))){
			while ($row = $stmt->fetch()) {
				$infoAdmin0 = array($row['ID_A0'],$row['CODE_A0'],$row['CAPTION_A0']);
			}
		}else{
			echo "noLines";
		}
	}
	catch( PDOException $Exception ) {
		 echo json_encode("erreur".$Exception->getMessage( ));
	}
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
	<link rel="stylesheet" href="../css/css2.css"/>
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
        </div>
        
		
		<!-- DATAS -->
        <div class="row dataBar">
			<div class='col-lg-2'>
			</div>
			<div class='col-lg-8'>
				<div class="zoneDetails">
					<div>	
						<span class="texteMoyen blocDanger"><span class='ion-edit' ></span> Editing country</span>
					</div>
					<form class="form-horizontal" action="scripts/UpdateAdmin0.php" method="post">
						<div class="col-lg-12">
							<div class="col-xs-12" style='margin-top:15px;' data-toggle="tooltip" data-placement="top" title="Select a country, type the location and select date"><span class='ion-ios-location smallIconText' ></span><span class='smallTextTitre' > Location</span> <span class='ion-ios-information-outline infoBulle' ></span></div>
							<div class="col-xs-12">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Code</span>
										</td>
									</tr>
									<tr>
										<td>
											<input name='code' autocomplete="off" spellcheck="false" class="form-control" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value='<?php echo $infoAdmin0[1];?>'/>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-12">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Caption</span>
										</td>
									</tr>
									<tr>
										<td>
											<input name='caption' autocomplete="off" spellcheck="false" class="form-control" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value='<?php echo $infoAdmin0[2];?>'/>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-12">
							<button type="submit" class="btn btn-default">Submit</button>
							<input name='idAdmin0' hidden='hidden' type="text"  value='<?php echo $idAmin0;?>'/>
						</div>
					</form>
				</div>
                
                
                
                

                
			</div>
			<div class='col-lg-2'>
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
    <script src="js/ScriptEndAdminEditPage.js"></script>
    
</body>