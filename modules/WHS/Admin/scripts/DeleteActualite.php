<?php
if
	(
        isset($_POST["idActualite"])
	)
{
	include("../scripts/connectDB.php");
    session_start();

	$tmpActualite = trim($_POST['idActualite']);
	$idActualite = substr($tmpActualite,-(count($tmpActualite)-3));
	$IdActualiteFR = 'FR'.$idActualite;
	$IdActualiteEN = 'EN'.$idActualite;
	
	
	//SUPPRESSION DES TAGS
	$idActualite=$_POST["idActualite"];
	$requete = "DELETE FROM r_actualite_avoir_tag WHERE ID_ACTUALITE = ? OR ID_ACTUALITE = ?";
	$stmt = $db->prepare($requete);
	$stmt->bindParam(1, $IdActualiteFR);
	$stmt->bindParam(2, $IdActualiteEN);
	
	try {
		$result = $stmt->execute();
	} catch (PDOException $e) {
		echo 'Suppressions des tags erreur delete : ' . $e->getMessage();
	}
	
	//SUPPRESSION DES ACTUALITES
	$requete = "DELETE FROM actualite WHERE ID_ACTUALITE = ? OR ID_ACTUALITE = ?";
	$stmt = $db->prepare($requete);
	$stmt->bindParam(1, $IdActualiteFR);
	$stmt->bindParam(2, $IdActualiteEN);
	
	try {
		$result = $stmt->execute();
	} catch (PDOException $e) {
		echo 'Suppressions des actualites erreur delete : ' . $e->getMessage();
	}

	echo "Terminé";
}else{
        
	$result = "PostPays:".$_POST["PostPays"].", PostLocation:".$_POST["PostLocation"].", dateActualite:".$_POST["dateActualite"].", PostMenace:".$_POST["PostMenace"].", PostTag:".$_POST["PostTag"].", TitreFr:".$_POST["TitreFr"].", DetailFr:".$_POST["DetailFr"].", TitreEn:".$_POST["TitreEn"].", DetailEn:".$_POST["DetailEn"];
	echo "Parametres manquants : ".$result;
}

?>