<?php
if
	(
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
	include("connectDB.php");
    session_start();

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
	
	$idAcutaliteTmp = date('YmdHis').rand (0, 9999);
	$IdActualiteFR = 'FR'.$idAcutaliteTmp;
	$IdActualiteEN = 'EN'.$idAcutaliteTmp;
	$arrayDateActualite = explode('/', $dateActualite);
	$dateActualite = $arrayDateActualite[2]."".$arrayDateActualite[1]."".$arrayDateActualite[0];
	
	$message = "";
	
	//RECUPERATION DE L'ID DE LA MENACE
	$requete = "SELECT ID_MENACE FROM MENACES WHERE CODE_MENACE=?";
	$stmt = $db->prepare($requete);
	$idMenace = "";
	
	try {
		if ($stmt->execute(array($PostMenace))){
			while ($row = $stmt->fetch()){
				$idMenace = $row['ID_MENACE'];
			}
		}
	}catch (PDOException $e){
		$message .= " #SelectMENACES:".$e->getMessage();
	}
	
	
	

	//RECUPERATION DE L'ID DE LA CATEGORIE D'ACTU
	$requete = "SELECT ID_CATEG_ACTU FROM CATEGORIE_ACTUALITE WHERE TYPE_CATEG_ACTU=?";
	$stmt = $db->prepare($requete);
	$idCategActualite = "";
	
	try {
		if ($stmt->execute(array($PostTypeActu))){
			while ($row = $stmt->fetch()){
				$idCategActualite = $row['ID_CATEG_ACTU'];
			}
		}
	} catch (PDOException $e) {
		$message .= " #SelectCategorieActu:".$e->getMessage();
	}

	
	
    //GET L'ID DU LIEU DEJA EXISTANT OU CREER UN NOUVEAU LIEU
	$requete = "SELECT ID_LIEU,ID_A1 FROM localisation where ID_A0=? and LIBELLE_LIEU=?";
	$stmt = $db->prepare($requete);
	$lieuExiste = false;
	$idLieu = "";
	$idAdmin1 = 99999999999;
	
	try{
		if($stmt->execute(array($PostPays,$PostLocation))){
			while ($row = $stmt->fetch()){
				$idLieu = $row['ID_LIEU'];
				$idAdmin1 = $row['ID_A1'];
				$lieuExiste = true;
			}
		}
	}catch (PDOException $e){
		$message .= " #SelectLocalisation:".$e->getMessage();
	}
	
	
	
	
	if($lieuExiste){
		//echo "le lieu existe:".$idLieu;
	}else{
		//LE LIEU N'EXISTE PAS ON RECUPERE LA CAPITALE ET ON CREE LE LIEU
		$requete = "SELECT ID_A1 FROM capitales where ID_A0=?";
		$stmt = $db->prepare($requete);
		$idDefaultAdmin1 = 99999999999;
		$idLieu = date('YmdHis').rand (0, 9999);
		$codeLieu = "";
		$hasAdmin = 0;
		
		try {
			if ($stmt->execute(array($PostPays))){
				while ($row = $stmt->fetch()) {
					$idDefaultAdmin1 = intval( $row['ID_A1']);
				}
			}
		} catch (PDOException $e) {
			$message .= " #SelectCapitales:".$e->getMessage();
		}
		
		
		
		$idAdmin1 = $idDefaultAdmin1;
		
		$requete = "INSERT INTO LIEU(ID_LIEU, ID_A1, CODE_LIEU, LIBELLE_LIEU, HAS_ADMIN1) VALUES (?,?,?,?,?)";
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
	
	
	//INSERTION DES TAGS
	$arrayTags = explode('_',$PostFilreTags);
	var_dump ($arrayTags);
	for ($i = 1; $i < count($arrayTags); $i++) {
		if(count($arrayTags[$i])!=0){
			$requete = "INSERT INTO R_ACTUALITE_AVOIR_TAG (ID_ACTUALITE, ID_TAG) VALUES (?,?)";
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
	
	//INSERER LA NEWS FRANCAISE
	$ID_LANGUE =0;
	$ID_actualite = $IdActualiteFR;
	$TITRE_ACTUALITE = $PostTitreFr;
    $DETAIL_ACTUALITE = $PostDetailFr;
	
    $stmt = $db->prepare("INSERT INTO actualite(ID_ACTUALITE, ID_LANGUE, ID_LIEU, IDUSER, ID_A1, ID_MENACE, ID_CATEG_ACTU, TITRE_ACTUALITE, DETAIL_ACTUALITE, DATE_ACTUALITE) VALUES ( ?,?,?,?,?,?,?,?,?,?)");
	
    $stmt->bindParam(1, $ID_actualite);
    $stmt->bindParam(2, $ID_LANGUE);
    $stmt->bindParam(3, $idLieu);
    $stmt->bindParam(4, $_SESSION["USER"]);
    $stmt->bindParam(5, $idAdmin1);
    $stmt->bindParam(6, $idMenace);
    $stmt->bindParam(7, $idCategActualite);
    $stmt->bindParam(8, $TITRE_ACTUALITE);
    $stmt->bindParam(9, $DETAIL_ACTUALITE);
    $stmt->bindParam(10, $dateActualite);

	$result = false;

	try {
		// EXECUTION DE LA REQUETE AVEC LA LANQUE FRANCAISE
		 $result = $stmt->execute();
		 
		// EXECUTION DE LA REQUETE AVEC LA LANQUE ANGLAISE
		$ID_LANGUE = 1;
		$ID_actualite = $IdActualiteEN;
		$TITRE_ACTUALITE = $PostTitreEn;
		$DETAIL_ACTUALITE = $PostDetailEn;
		
		$result = $stmt->execute();
		
	} catch (PDOException $e) {
		echo 'Creation des actualites erreur insert : ' . $e->getMessage();
	}
}else{
        
	$result = "PostPays:".$_POST["PostPays"].", PostLocation:".$_POST["PostLocation"].", dateActualite:".$_POST["dateActualite"].", PostMenace:".$_POST["PostMenace"].", PostTag:".$_POST["PostTag"].", TitreFr:".$_POST["TitreFr"].", DetailFr:".$_POST["DetailFr"].", TitreEn:".$_POST["TitreEn"].", DetailEn:".$_POST["DetailEn"];
	echo "Parametres manquants : ".$result;
}

header('Location: ../manageWeeklies.php?message='.$message);


 

?>