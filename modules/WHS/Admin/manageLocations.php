<?php
// Start the session
include("../scripts/connectDb.php");
session_start();

if(isset($_SESSION["USER"])){
    
	//RECUPERATION ACTUALITE ANGLAISE
	$requete = "SELECT ID_A0, CODE_A0, CAPTION_A0 FROM admin_0";
	$listPays = array();
	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute()){
			while ($row = $stmt->fetch()) {
				$pays = array($row['ID_A0'],$row['CODE_A0'],$row['CAPTION_A0']);
				array_push($listPays,$pays);
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
			<div class='col-lg-4'>
				<div class='blocData'>
					<div>
						<span class="texteMoyen blocDanger"><span class='ion-ios-location' ></span> Countries</span>
					</div>
					<div class='blocData' id='blocPays'>
						<table id="tablePays" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Code</th>
									<th>Caption</th>
								</tr>
							</thead>
							<tbody>
								<?php
									for ($i = 0; $i < count($listPays); $i++) {
										echo "<tr onclick=\"getCountryAdmin1('".$listPays[$i][0]."')\"><td><a href='EditAdmin0.php?id=".$listPays[$i][0]."'><span class='ion-edit smallIconText' ></span></a></td><td>".$listPays[$i][1]."</td><td>".$listPays[$i][2]."</td></tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class='col-lg-4'>
				<div class='blocData'>
					<div>
						<span class="texteMoyen blocDanger"><span class='ion-ios-location' ></span> Admin1</span>
					</div>
					<div class='blocData'  id='blocAdmin1'>
					</div>
				</div>
			</div>
			<div class='col-lg-4'>
				<div class='blocData'>
					<div>
						<span class="texteMoyen blocDanger"><span class='ion-ios-location' ></span> Places</span>
					</div>
					<div class='blocData'  id='blocPlaces'>
					</div>
				</div>
			</div>
        </div>
    </div>
    

    
    
    
    <script type="text/javascript">
    </script>
    <script src="js/ScriptEndAdmin.js"></script>
    
</body>