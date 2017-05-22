<?php

header('Content-type: text/plain; charset=iso-8859-15');
$tags = array();

if(isset($_POST['idActualite']))
{
        include("connectDB.php");
		
		$idActualite = $_POST['idActualite'];
        $requete = "SELECT ID_TAG, LIBELLE_TAG FROM actualiteTags WHERE ID_ACTUALITE = ?";
        $stmt = $db->prepare($requete);
        
        try{
            if ($stmt->execute(array($idActualite))) {
                while ($row = $stmt->fetch()) {
					$tag = $actualite = array($row['ID_TAG'],$row['LIBELLE_TAG']);
                    array_push($tags,$tag);
                }
                
				//var_dump($tags);
                $resultJSON = json_encode($tags);
                echo $resultJSON;

            }else{
                echo json_encode("noLines");
            }
        }
        catch(PDOException $Exception){
             echo json_encode("erreur".$Exception->getMessage( ));
        }
}else{
    echo json_encode("noParams");
}
?>