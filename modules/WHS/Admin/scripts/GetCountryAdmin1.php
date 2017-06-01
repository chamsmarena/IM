<?php
header('Content-type: text/plain; charset=iso-8859-15');
$admin1s = array();

if(isset($_POST['idAdmin0']))
{
    if($_POST['idAdmin0']!='')
    {
        include("connectDB.php");
        
        $idAdmin0 = $_POST['idAdmin0'];
        
        $requete = "SELECT ID_A1, ID_A0, CODE_A1, CAPTION_A1, COORD_X_A1, COORD_Y_A1, EST_CAPITALE FROM ADMIN_1 WHERE ID_A0 = ?";
        
		
		
        $stmt = $db->prepare($requete);
        
        $id = 0;
		$n=0;
        
        try{
            if ($stmt->execute(array($idAdmin0))) {
                
                while ($row = $stmt->fetch()) {
                    
                    $admin1 = array($row['ID_A1'], $row['ID_A0'], $row['CODE_A1'], $row['CAPTION_A1'], $row['COORD_X_A1'], $row['COORD_Y_A1'], $row['EST_CAPITALE']);
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