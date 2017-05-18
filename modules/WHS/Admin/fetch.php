<?php

include("../scripts/connectDB.php");
session_start();
$keyword = $_GET['keyword'];
$idAdmin0 = $_GET['idAdmin0'];
$keyword="%$keyword%";
$requete = "SELECT * FROM localisation WHERE ID_A0=? and LIBELLE_LIEU like ? order by LIBELLE_LIEU asc";
$stmt = $db->prepare($requete);
try
{
	if ($stmt->execute(array($idAdmin0,$keyword))) {
		$n = 0;
		while ($row = $stmt->fetch()) {
            echo "<div class='searchItem' onClick='ClickOnLiveSearchResult(this)' >".$row['LIBELLE_LIEU']."</div>";
            $n++;
		}

	}else{
		echo "noLines";
	}
}
catch( PDOException $Exception ) {
	 echo "erreur".$Exception->getMessage( );
}

?>