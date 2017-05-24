<?php
if
	(
        isset($_POST["code"])&&
        isset($_POST["caption"])&&
        isset($_POST["coordy"])&&
        isset($_POST["idAdmin1"])&&
        isset($_POST["coordx"])
	)
{
	include("../scripts/connectDB.php");
    session_start();

	$code=$_POST["code"];
	$caption=$_POST["caption"];
	$coordy=$_POST["coordy"];
	$idAdmin1=$_POST["idAdmin1"];
	$coordx=$_POST["coordx"];
	
	$stmt = $db->prepare("UPDATE admin_1 SET CODE_A1=?,CAPTION_A1=?,COORD_X_A1=?,COORD_Y_A1=? WHERE ID_A1=?");
	$stmt->bindParam(1, $code);
	$stmt->bindParam(2, $caption);
	$stmt->bindParam(3, $coordx);
	$stmt->bindParam(4, $coordy);
	$stmt->bindParam(5, $idAdmin1);
	$result = false;
	try {
		 $result = $stmt->execute();
	} catch (PDOException $e) {
		echo 'UPDATE des admin1 erreur updates : ' . $e->getMessage();
	}


}else{
	echo "Parametres manquants : ".$result;
}

header('Location: ../manageLocations.php?id='.$IdActualiteEN);


 

?>