<?php
if
	(
        isset($_POST["idAdmin0"])&&
        isset($_POST["code"])&&
        isset($_POST["caption"])
	)
{
	include("connectDB.php");
    session_start();

	$idAdmin0=$_POST["idAdmin0"];
	$code=$_POST["code"];
	$caption=$_POST["caption"];
	
	$stmt = $db->prepare("UPDATE ADMIN_0 SET CODE_A0=?,CAPTION_A0=?  WHERE ID_A0 = ?");
	$stmt->bindParam(1, $code);
	$stmt->bindParam(2, $caption);
	$stmt->bindParam(3, $idAdmin0);
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