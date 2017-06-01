<?php
header('Content-type: text/plain; charset=iso-8859-15');
$admin1s = array();

if(isset($_POST['idAdmin1']))
{
    if($_POST['idAdmin1']!='')
    {
        include("connectDB.php");
        
        $idAdmin1 = $_POST['idAdmin1'];
        
        $requete = "SELECT ID_LIEU, ID_A1, CODE_LIEU, LIBELLE_LIEU, HAS_ADMIN1 FROM LIEU WHERE ID_A1 = ?";
        
		
		
        $stmt = $db->prepare($requete);
        
        $id = 0;
		$n=0;
        
        try{
            if ($stmt->execute(array($idAdmin1))) {
                
                while ($row = $stmt->fetch()) {
                    
                    $admin1 = array($row['ID_LIEU'], $row['ID_A1'], $row['CODE_LIEU'], $row['LIBELLE_LIEU'], $row['HAS_ADMIN1']);
                    array_push($admin1s,$admin1);
					$n++;
                }
                
				//var_dump($admin1s);
                $resultJSON = json_encode($admin1s);
                echo $resultJSON;

            }else{
                echo json_encode("noLines");
            }
        }
        catch( PDOException $Exception ) {
             echo json_encode("erreur".$Exception->getMessage( ));
        }
    }
    else
    {
       
        echo json_encode("paramsEmpty");
    }
}else{
    echo json_encode("noParams");
}
?>