<?php

include("connectDB.php");
session_start();

$requete = "SELECT * FROM localisation WHERE ID_A0='".$filterPays."' order by LIBELLE_LIEU asc";
$stmt = $db->prepare($requete);
try{
	if ($stmt->execute() {
		
		while ($row = $stmt->fetch()) {
			$array = $row['LIBELLE_LIEU'];
		}
		
		echo json_encode($array);

	}else{
		echo json_encode("noLines");
	}
}
catch( PDOException $Exception ) {
	 echo json_encode("erreur".$Exception->getMessage( ));
}

?>