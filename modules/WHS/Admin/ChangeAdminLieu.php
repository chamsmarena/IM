<?php
// Start the session
include("../scripts/connectDb.php");
session_start();

if(isset($_SESSION["USER"])){
	
	//RECUPERATION DES INFORMATIONS DU LIEU
	$idLieu = trim($_GET['id']);
	$requete = "SELECT ID_LIEU, CODE_A0, LIBELLE_LIEU, ID_A1, CAPTION_A1,ID_A0 FROM lieuxsansadmin1 WHERE ID_LIEU = ?";

	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute(array($idLieu))){
			while ($row = $stmt->fetch()) {
				$InfoLieu = array($row['ID_LIEU'],$row['CODE_A0'],$row['LIBELLE_LIEU'],$row['ID_A1'],$row['CAPTION_A1'],$row['ID_A0']);
			}
		}else{
			echo "noLines";
		}
	}
	catch( PDOException $Exception ) {
		 echo json_encode("erreur".$Exception->getMessage( ));
	}
	
	//RECUPERATION DE LA LISTE DES ADMIN1 DU ADMIN0
	$requete = "SELECT ID_A1, CAPTION_A1 FROM admin_1 WHERE ID_A0 = ?";
	$idAdmin0 = $InfoLieu[5];
	$listAdmin1 = array();
	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute(array($idAdmin0))){
			while ($row = $stmt->fetch()) {
				$admin1 = array($row['ID_A1'],$row['CAPTION_A1']);
				array_push($listAdmin1,$admin1);
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
						<span class="texteMoyen blocDanger"><span class='ion-edit' ></span> Correcting places Admin1</span>
					</div>
					<form class="form-horizontal" action="scripts/UpdateAdmin1Lieu.php" method="post">
						<div class="col-xs-12" style='margin-top:15px;' data-toggle="tooltip" data-placement="top" title="Select a country, type the location and select date"><span class='ion-ios-location smallIconText' ></span><span class='smallTextTitre' > Location</span> <span class='ion-ios-information-outline infoBulle' ></span></div>
						<div class="col-xs-12">
							<table>
								<tr>
									<td>
										<span class='inputLabel' >Country</span>
									</td>
									<td>
										<span class='inputLabel' ><?php echo $InfoLieu[1];?></span>
									</td>
								</tr>
								<tr>
									<td>
										<span class='inputLabel' >Admin1</span>
									</td>
									<td>
										<select name='PostAdmin1' id='PostAdmin1' class='form-control' style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'>
											<?php
											if(isset($_SESSION["USER"])){
												for ($i = 0; $i < count($listAdmin1); $i++) {
													$selected = ($listAdmin1[$i][0] == $InfoLieu[3]) ? "selected" : "";
													echo "<option value='".$listAdmin1[$i][0]."' ".$selected.">".$listAdmin1[$i][1]."</option>";
												}
													
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<span class='inputLabel' >Place caption</span>
									</td>
									<td>
										<input  name='PostPays' id="PostPays" type="text"  hidden='hidden' value='<?php echo $InfoLieu[5];?>'/>
										<input  name='idLieu' id="idLieu" type="text"  hidden='hidden' value='<?php echo $InfoLieu[0];?>'/>
										<input  name='PostLocation' id="PostLocation" type="text"  class="form-control" aria-label="Date" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value='<?php echo $InfoLieu[2];?>'/>
									</td>
								</tr>
								<tr>
									<td>
										<span class='inputLabel' >Place code</span>
									</td>
									<td>
										<input  name='PostLocationCode' id="PostLocationCode" type="text"  class="form-control" aria-label="Date" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value=''/>

									</td>
								</tr>
								<tr>
									<td>
									</td>
									<td><button type="submit" class="btn btn-default">Submit</button>
									</td>
								</tr>
							</table>
						</div>
					</form>
				</div>
			</div>
			<div class='col-lg-2'>
			</div>     
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

    </script>
    <script src="js/ScriptEndAdminEditPage.js"></script>
    
</body>