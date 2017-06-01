<?php
if
	(
        isset($_POST["PostAdmin1"])&&
        isset($_POST["PostPays"])&&
        isset($_POST["PostLocation"])&&
        isset($_POST["PostLocationCode"])&&
        isset($_POST["idLieu"])
	)
{
	include("connectDB.php");
    session_start();

	$idAdmin1=$_POST["PostAdmin1"];
	$PostPays=$_POST["PostPays"];
	$PostLocation=$_POST["PostLocation"];
	$codeLieu=$_POST["PostLocationCode"];
	$idLieu=$_POST["idLieu"];
	

    //GET L'ID DU LIEU DEJA EXISTANT OU CREER UN NOUVEAU LIEU
	$requete = "SELECT ID_LIEU,CODE_LIEU FROM localisation where ID_A0=? and LIBELLE_LIEU=?";
	$stmt = $db->prepare($requete);
	$lieuExiste = false;
	$idLieuNew = "";
	$hasAdmin = true;
	
	if ($stmt->execute(array($PostPays,$PostLocation))){
		while ($row = $stmt->fetch()){
			//var_dump($row);
			$idLieuNew = $row['ID_LIEU'];
			$existingCodeLieu = $row['CODE_LIEU'];
			$lieuExiste = true;
		}
	}
	
	if($lieuExiste){
		//METTRE A JOUR LE LIEU DE L'ACTUALITE
		$stmt = $db->prepare("UPDATE ACTUALITE set ID_LIEU=? WHERE ID_LIEU=?");
		$stmt->bindParam(1, $idLieuNew);
		$stmt->bindParam(2, $idLieu);
		$result = false;
		try {
			 $result = $stmt->execute();
			 
			 //SI L'ANCIEN LIEU EST DIFFERENT DU NOUVEAU LIEU ON SUPPRIME L'ANCIEN
			if($idLieu!=$idLieuNew){
				$stmt = $db->prepare("DELETE FROM LIEU WHERE ID_LIEU=?");
				$stmt->bindParam(1, $idLieu);
				$result = $stmt->execute();
			}
		
		} catch (PDOException $e) {
			echo 'UPDATE des actualites erreur insert : ' . $e->getMessage();
		}
		
		
		//METTRE A JOUR LE LIEU DE L'ACTUALITE
		$stmt = $db->prepare("UPDATE LIEU set CODE_LIEU=?, LIBELLE_LIEU=?, HAS_ADMIN1=? WHERE ID_LIEU=?");
		$stmt->bindParam(1, $codeLieu);
		$stmt->bindParam(2, $PostLocation);
		$stmt->bindParam(3, $hasAdmin);
		$stmt->bindParam(4, $idLieu);
		$result = false;
		try {
			 $result = $stmt->execute();
		}
		catch (PDOException $e) {
			echo 'UPDATE des actualites erreur insert : ' . $e->getMessage();
		}
		
		
		
		
	}else{
		//LE LIEU N'EXISTE PAS ON CREE LE LIEU
		$requete = "INSERT INTO LIEU (ID_LIEU, ID_A1, CODE_LIEU, LIBELLE_LIEU, HAS_ADMIN1) VALUES (?,?,?,?,?)";
		$idLieuNew = date('YmdHis').rand (0, 9999);
		
		$stmt = $db->prepare($requete);
		$stmt->bindParam(1, $idLieuNew);
		$stmt->bindParam(2, $idAdmin1);
		$stmt->bindParam(3, $codeLieu);
		$stmt->bindParam(4, $PostLocation);
		$stmt->bindParam(5, $hasAdmin);
		
		try {
			$result = $stmt->execute();
			
			//METTRE A JOUR LE LIEU DE L'ACTUALITE
			$stmt = $db->prepare("UPDATE ACTUALITE set ID_LIEU=? WHERE ID_LIEU=?");
			$stmt->bindParam(1, $idLieuNew);
			$stmt->bindParam(2, $idLieu);
			$result = false;
			try {
				 $result = $stmt->execute();
				 
				 //SI L'ANCIEN LIEU EST DIFFERENT DU NOUVEAU LIEU ON SUPPRIME L'ANCIEN
				if($idLieu!=$idLieuNew){
					$stmt = $db->prepare("DELETE FROM LIEU WHERE ID_LIEU=?");
					$stmt->bindParam(1, $idLieu);
					$result = $stmt->execute();
				}
			
			} catch (PDOException $e) {
				echo 'UPDATE des actualites erreur insert : ' . $e->getMessage();
			}
			
		} catch (PDOException $e) {
			echo 'Erreur execution : ' . $e->getMessage();
		}
	}
	
	
}else{
	echo "Parametres manquants : ".$result;
}

//header('Location: ../editWeekly.php?id='.$IdActualiteEN);


 

?>