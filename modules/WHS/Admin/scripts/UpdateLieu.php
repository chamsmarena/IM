<?php
if
	(
        isset($_POST["code"])&&
        isset($_POST["caption"])&&
        isset($_POST["idLieu"])
	)
{
	include("connectDB.php");
    session_start();

	$code=$_POST["code"];
	$caption=$_POST["caption"];
	$idLieu=$_POST["idLieu"];
	
	$stmt = $db->prepare("UPDATE LIEU SET CODE_LIEU=?,LIBELLE_LIEU=? WHERE ID_LIEU=?");
	$stmt->bindParam(1, $code);
	$stmt->bindParam(2, $caption);
	$stmt->bindParam(3, $idLieu);
	$result = false;
	try {
		 $result = $stmt->execute();
	} catch (PDOException $e) {
		echo 'UPDATE des LIEU erreur updates : ' . $e->getMessage();
	}


}else{
	echo "Parametres manquants : ".$result;
}

header('Location: ../manageLocations.php?id='.$IdActualiteEN);


 

?>