<?php
if
	(
        isset($_POST["idActualite"])&&
        isset($_POST["PostPays"])&&
        isset($_POST["PostLocation"])&&
        isset($_POST["dateActualite"])&&
        isset($_POST["PostMenace"])&&
        isset($_POST["PostTypeActu"])&&
        isset($_POST["PostTag"])&&
        isset($_POST["PostFilreTags"])&&
        isset($_POST["PostTitreFr"])&&
		isset($_POST["PostDetailFr"])&&
		isset($_POST["PostTitreEn"])&&
		isset($_POST["PostDetailEn"])
	)
{
	include("../scripts/connectDB.php");
    session_start();

	$idActualite=$_POST["idActualite"];
	$PostPays=$_POST["PostPays"];
	$PostLocation=$_POST["PostLocation"];
	$dateActualite=$_POST["dateActualite"];
	$PostMenace=$_POST["PostMenace"];
	$PostTypeActu=$_POST["PostTypeActu"];
	$PostTag=$_POST["PostTag"];
	$PostFilreTags=$_POST["PostFilreTags"];
	$PostTitreFr=$_POST["PostTitreFr"];
	$PostDetailFr=$_POST["PostDetailFr"];
	$PostTitreEn=$_POST["PostTitreEn"];
	$PostDetailEn=$_POST["PostDetailEn"];
	

	$IdActualiteFR = 'FR'.$idActualite;
	$IdActualiteEN = 'EN'.$idActualite;
	$arrayDateActualite = explode('/', $dateActualite);
	$dateActualite = $arrayDateActualite[2]."".$arrayDateActualite[1]."".$arrayDateActualite[0];
	
	
	//RECUPERATION DE L'ID DE LA MENACE
	$requete = "SELECT ID_MENACE FROM menaces WHERE CODE_MENACE=?";
	$stmt = $db->prepare($requete);
	$idMenace = "";
	
	if ($stmt->execute(array($PostMenace))){
		while ($row = $stmt->fetch()){
			$idMenace = $row['ID_MENACE'];
		}
	}
	

	//RECUPERATION DE L'ID DE LA CATEGORIE D'ACTU
	$requete = "SELECT ID_CATEG_ACTU FROM categorie_actualite WHERE TYPE_CATEG_ACTU=?";
	$stmt = $db->prepare($requete);
	$idCategActualite = "";
	
	if ($stmt->execute(array($PostTypeActu))){
		while ($row = $stmt->fetch()){
			$idCategActualite = $row['ID_CATEG_ACTU'];
		}
	}
	
	
    //GET L'ID DU LIEU DEJA EXISTANT OU CREER UN NOUVEAU LIEU
	$requete = "SELECT ID_LIEU,ID_A1 FROM localisation where ID_A0=? and LIBELLE_LIEU=?";
	$stmt = $db->prepare($requete);
	$lieuExiste = false;
	$idLieu = "";
	$idAdmin1 = 99999999999;
	
	if ($stmt->execute(array($PostPays,$PostLocation))){
		while ($row = $stmt->fetch()){
			//var_dump($row);
			$idLieu = $row['ID_LIEU'];
			$idAdmin1 = $row['ID_A1'];
			$lieuExiste = true;
		}
	}
	
	if($lieuExiste){
		//echo "le lieu existe:".$idLieu;
	}else{
		//LE LIEU N'EXISTE PAS ON RECUPERE LA CAPITALE ET ON CREE LE LIEU
		//echo "le lieu n'existe pas existe:".$PostLocation;
		$requete = "SELECT ID_A1 FROM capitales where ID_A0=?";
		$stmt = $db->prepare($requete);
		$idDefaultAdmin1 = 99999999999;
		$idLieu = date('YmdHis').rand (0, 9999);
		$codeLieu = "";
		$hasAdmin = 0;
		
		if ($stmt->execute(array($PostPays))){
			while ($row = $stmt->fetch()) {
				$idDefaultAdmin1 = intval( $row['ID_A1']);
			}
		}
		
		$idAdmin1 = $idDefaultAdmin1;
		
		$requete = "INSERT INTO lieu(ID_LIEU, ID_A1, CODE_LIEU, LIBELLE_LIEU, HAS_ADMIN1) VALUES (?,?,?,?,?)";
		$stmt = $db->prepare($requete);
		$stmt->bindParam(1, $idLieu);
		$stmt->bindParam(2, $idDefaultAdmin1);
		$stmt->bindParam(3, $codeLieu);
		$stmt->bindParam(4, $PostLocation);
		$stmt->bindParam(5, $hasAdmin);
		
		try {
			$result = $stmt->execute();
			//echo "ok";
		} catch (PDOException $e) {
			echo 'Erreur execution : ' . $e->getMessage();
		}
	}
	
	
	//UPDATE DES TAGS
	//SUPRESSION DES ANCIENNES
	$requete = "DELETE FROM r_actualite_avoir_tag WHERE ID_ACTUALITE = ? OR ID_ACTUALITE = ?";
	$stmt = $db->prepare($requete);
	$stmt->bindParam(1, $IdActualiteFR);
	$stmt->bindParam(2, $IdActualiteEN);
	
	try {
		$result = $stmt->execute();
	} catch (PDOException $e) {
		echo 'Suppressions des anciens tags erreur insert : ' . $e->getMessage();
	}
	
	//CREATION DES NOUVELLES
	$arrayTags = explode('_',$PostFilreTags);
	var_dump ($arrayTags);
	for ($i = 1; $i < count($arrayTags); $i++) {
		if(count($arrayTags[$i])!=0){
			$requete = "INSERT INTO r_actualite_avoir_tag (ID_ACTUALITE, ID_TAG) VALUES (?,?)";
			$stmt = $db->prepare($requete);
			
			$idTag = $arrayTags[$i];
			$idActualite = $IdActualiteEN;
			
			$stmt->bindParam(1, $idActualite);
			$stmt->bindParam(2, $idTag);
			
			try {
				//EXECUTION REQUETE 1 EN
				$result = $stmt->execute();
				
				//EXECUTION REQUETE 2 FR
				$idActualite = $IdActualiteFR;
				$result = $stmt->execute();
				
			} catch (PDOException $e) {
				echo 'Creation des tags erreur insert : ' . $e->getMessage();
			}
		}
	}
	
	//UPDATE LA NEWS FRANCAISE
	$ID_LANGUE =1;
	$ID_actualite = $IdActualiteFR;
	$TITRE_ACTUALITE = $PostTitreFr;
    $DETAIL_ACTUALITE = $PostDetailFr;
	
    $stmt = $db->prepare("UPDATE actualite SET ID_LANGUE=?, ID_LIEU=?, IDUSER=?, ID_A1=?, ID_MENACE=?, ID_CATEG_ACTU=?, TITRE_ACTUALITE=?, DETAIL_ACTUALITE=?, DATE_ACTUALITE=? WHERE ID_ACTUALITE=?");
	
    $stmt->bindParam(1, $ID_LANGUE);
    $stmt->bindParam(2, $idLieu);
    $stmt->bindParam(3, $_SESSION["USER"]);
    $stmt->bindParam(4, $idAdmin1);
    $stmt->bindParam(5, $idMenace);
    $stmt->bindParam(6, $idCategActualite);
    $stmt->bindParam(7, $TITRE_ACTUALITE);
    $stmt->bindParam(8, $DETAIL_ACTUALITE);
    $stmt->bindParam(9, $dateActualite);
	$stmt->bindParam(10, $ID_actualite);

	$result = false;

	try {
		// EXECUTION DE LA REQUETE AVEC LA LANQUE FRANCAISE
		 $result = $stmt->execute();
		 
		// EXECUTION DE LA REQUETE AVEC LA LANQUE ANGLAISE
		$ID_LANGUE = 0;
		$ID_actualite = $IdActualiteEN;
		$TITRE_ACTUALITE = $PostTitreEn;
		$DETAIL_ACTUALITE = $PostDetailEn;
		
		$result = $stmt->execute();
		
	} catch (PDOException $e) {
		echo 'UPDATE des actualites erreur insert : ' . $e->getMessage();
	}
}else{
        
	$result = "PostPays:".$_POST["PostPays"].", PostLocation:".$_POST["PostLocation"].", dateActualite:".$_POST["dateActualite"].", PostMenace:".$_POST["PostMenace"].", PostTag:".$_POST["PostTag"].", TitreFr:".$_POST["TitreFr"].", DetailFr:".$_POST["DetailFr"].", TitreEn:".$_POST["TitreEn"].", DetailEn:".$_POST["DetailEn"];
	echo "Parametres manquants : ".$result;
}

header('Location: ../editWeekly.php?id='.$IdActualiteEN);


 

?>