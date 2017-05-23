<?php
// Start the session
include("../scripts/connectDb.php");
session_start();

if(isset($_SESSION["USER"])){
	
	//RECUPERATION DES INFOS DE L'ACTUALITE
	$tmpActualite = trim($_GET['id']);
	$idActualite = substr($tmpActualite,-(count($tmpActualite)-3));
	$idActualiteEN = "EN".$idActualite;
	$idActualiteFR = "FR".$idActualite;

	
	//RECUPERATION ACTUALITE ANGLAISE
	$requete = "SELECT ID_ACTUALITE, ID_A0, LIBELLE_LIEU, DATE_ACTUALITE, TYPE_CATEG_ACTU, CODE_MENACE, TITRE_ACTUALITE, DETAIL_ACTUALITE FROM InfoActualite WHERE ID_ACTUALITE = ? and ID_LANGUE = 0";
	$InfoActualiteEN = null;

	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute(array($idActualiteEN))){
			while ($row = $stmt->fetch()) {
				$InfoActualiteEN = array($row['ID_ACTUALITE'],$row['ID_A0'],$row['LIBELLE_LIEU'],$row['DATE_ACTUALITE'],$row['TYPE_CATEG_ACTU'],$row['CODE_MENACE'],$row['TITRE_ACTUALITE'],$row['DETAIL_ACTUALITE']);
			}
		}else{
			echo "noLines";
		}
	}
	catch( PDOException $Exception ) {
		 echo json_encode("erreur".$Exception->getMessage( ));
	}
	
	//RECUPERATION ACTUALITE FRANCAISE
	$requete = "SELECT ID_ACTUALITE, ID_A0, LIBELLE_LIEU, DATE_ACTUALITE, TYPE_CATEG_ACTU, CODE_MENACE, TITRE_ACTUALITE, DETAIL_ACTUALITE FROM InfoActualite WHERE ID_ACTUALITE = ? and ID_LANGUE = 1";
	$InfoActualiteFR = null;

	$stmt = $db->prepare($requete);
	try{
		if ($stmt->execute(array($idActualiteFR))){
			while ($row = $stmt->fetch()) {
				$InfoActualiteFR = array($row['ID_ACTUALITE'],$row['ID_A0'],$row['LIBELLE_LIEU'],$row['DATE_ACTUALITE'],$row['TYPE_CATEG_ACTU'],$row['CODE_MENACE'],$row['TITRE_ACTUALITE'],$row['DETAIL_ACTUALITE']);
			}
		}else{
			echo "noLines";
		}
	}
	catch( PDOException $Exception ) {
		 echo json_encode("erreur".$Exception->getMessage( ));
	}
    
	$tabDateActualite = explode('-', $InfoActualiteEN[3]);
	$dateActualite = $tabDateActualite[2]."/".$tabDateActualite[1]."/".$tabDateActualite[0];
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
						<span class="texteMoyen blocDanger"><span class='ion-edit' ></span> Editing news</span>
					</div>
					<form class="form-horizontal" action="scripts/UpdateActualite.php" method="post">
						<div class="col-lg-12">
							<div class="col-xs-12" style='margin-top:15px;' data-toggle="tooltip" data-placement="top" title="Select a country, type the location and select date"><span class='ion-ios-location smallIconText' ></span><span class='smallTextTitre' > Location</span> <span class='ion-ios-information-outline infoBulle' ></span></div>
							<div class="col-xs-4">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Country</span>
										</td>
									</tr>
									<tr>
										<td>
											<select name='PostPays' id='PostPays' class='form-control' style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'>
												<option value=''>---</option>
												<?php
												if(isset($_SESSION["USER"])){
													$stmt = $db->prepare("SELECT ID_A0,CAPTION_A0 FROM admin_0");
													$id = 0;
													if ($stmt->execute()) {
														while ($row = $stmt->fetch()) {
															$selected = ($InfoActualiteEN[1] == $row['ID_A0']) ? "selected" : "";
															echo "<option value='".$row['ID_A0']."' ".$selected.">".$row['CAPTION_A0']."</option>";
														}
													}
												}
												?>
											</select>
										</td>
									</tr>
								</table>
								
							</div>
							<div class="col-xs-4">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Location</span>
										</td>
									</tr>
									<tr>
										<td>
											<input name='PostLocation' autocomplete="off" spellcheck="false" id="PostLocation" type="text"  class="form-control" placeholder="---" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value='<?php echo $InfoActualiteEN[2];?>'/>
											<div id="results"></div>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-4">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Date</span>
										</td>
									</tr>
									<tr>
										<td>
											<input  name='dateActualite' id="dateActualite" type="text"  class="form-control" aria-label="Date" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";' value='<?php echo $dateActualite;?>'/>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-xs-4">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Icon</span>
										</td>
									</tr>
									<tr>
										
										<td>
											<select name='PostTypeActu'  id='PostTypeActu' class='form-control'  style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'>
												<option value=''>---</option>
												<?php
												if(isset($_SESSION["USER"])){
													$stmt = $db->prepare("SELECT TYPE_CATEG_ACTU, TITRE_CATEG_ACTU FROM categorie_actualite");
													$id = 0;
													if ($stmt->execute()) {
														while ($row = $stmt->fetch()) {
															$selected = ($InfoActualiteEN[4] == $row['TYPE_CATEG_ACTU']) ? "selected" : "";
															echo "<option value='".$row['TYPE_CATEG_ACTU']."' ".$selected.">".$row['TITRE_CATEG_ACTU']."</option>";
														}
													}
												}
												?>
											</select>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-xs-8">
								<table>
									<tr>
										<td>
											<span class='inputLabel' >Icon color</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td>
											<select name='PostMenace' id='PostMenace' class='form-control'  style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'>
												<option value=''>---</option>
												<?php
												if(isset($_SESSION["USER"])){
													$stmt = $db->prepare("SELECT CODE_MENACE, TITRE_MENACE FROM menaces");
													$id = 0;
													if ($stmt->execute()) {
														while ($row = $stmt->fetch()) {
															$selected = ($InfoActualiteEN[5] == $row['CODE_MENACE']) ? "selected" : "";
															echo "<option value='".$row['CODE_MENACE']."' ".$selected.">".$row['TITRE_MENACE']."</option>";
														}
													}
												}
												?>
											</select>
										</td>
										<td>
											<img class='VerySmallImageX1' id='iconPreview' src='../images/events/<?php echo $InfoActualiteEN[4].$InfoActualiteEN[5]?>Group.svg'/>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-xs-12"  data-toggle="tooltip" data-placement="top" title="Select one or more tags" style='margin-top:15px;'><span class='ion-pricetags smallIconText' ></span><span class='smallTextTitre' > Tags </span><span class='ion-ios-information-outline infoBulle' ></span></div>
							<div class="col-xs-3">
								<select name='PostTag' id='PostTag' class='form-control' onchange="FiltrerTag()" data-toggle="tooltip" data-placement="top" title="Add one or more tags" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'>
									<option value=''>---</option>
									<?php
									if(isset($_SESSION["USER"])){
										$stmt = $db->prepare("SELECT ID_TAG,LIBELLE_TAG FROM tag");
										$id = 0;
										if ($stmt->execute()) {
											while ($row = $stmt->fetch()) {
												
												echo "<option value='".$row['ID_TAG']."'>".$row['LIBELLE_TAG']."</option>";
											}
										}
									}
									?>
								</select>
							</div>
							<div class="col-xs-8">
								<div class='filtreTag'></div>
								<input type="text" hidden='hidden' name='PostFilreTags'   id="PostFilreTags"/>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-xs-12" style='margin-top:15px;' data-toggle="tooltip" data-placement="top" title="Enter english and french content"><span class='ion-document-text smallIconText' ></span> <span class='smallTextTitre' > Content</span> <span class='ion-ios-information-outline infoBulle' ></span></div>
							<div class="col-md-6">
								<span class='inputLabel' >English</span>
								<input type="text"  name='PostTitreEn'  class="form-control" placeholder="Title" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'   id="PostTitreEn" value ='<?php echo $InfoActualiteEN[6];?>'/>
								<textarea style='margin-top:5px;' name='PostDetailEn' class="form-control" placeholder="News details"  style='font-size:10px;font-family: "Avenir Next","arial";'  id="PostDetailEn" ><?php echo $InfoActualiteEN[7];?></textarea>
							</div>
							<div class="col-md-6">
								<span class='inputLabel' >Français</span>
								<input name='PostTitreFr' type="text" class="form-control" placeholder="Titre" style='font-size:10px;height:27px;font-family: "Avenir Next","arial";'  id="PostTitreFr" value ='<?php echo $InfoActualiteFR[6];?>'/>
								<textarea name='PostDetailFr' style='margin-top:5px;' class="form-control" placeholder="Détail de l'actualité"  style='font-size:10px;font-family: "Avenir Next","arial";'   id="PostDetailFr" ><?php echo $InfoActualiteFR[7];?></textarea>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-xs-12"  data-toggle="tooltip" data-placement="top" title="Add images" style='margin-top:15px;'><span class='ion-images smallIconText' ></span><span class='smallTextTitre' > Images </span><span class='ion-ios-information-outline infoBulle' ></span></div>
						</div>
						<div class="col-lg-12">
							<div class="col-xs-12"  data-toggle="tooltip" data-placement="top" title="Insert external links" style='margin-top:15px;'><span class='ion-link smallIconText' ></span><span class='smallTextTitre' > Links </span><span class='ion-ios-information-outline infoBulle' ></span></div>
						</div>
						<div class="col-lg-12">
							<button type="submit" class="btn btn-default">Submit</button>
							<input name='idActualite' hidden='hidden' type="text"  value='<?php echo $idActualite;?>'/>
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