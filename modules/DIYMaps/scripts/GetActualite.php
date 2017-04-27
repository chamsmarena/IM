<?php
/*
$cars = array("a', 'b', 'c', 'd', 'e');
array_push($cars,'f');


$myJSON = json_encode($cars);*/

//



$actualites = array();

if(isset($_POST['dateDebut']) && isset($_POST['dateFin']))
{
    
    if($_POST['dateDebut']!='' && $_POST['dateFin']!='')
    {
        include("connectDB.php");
        
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];

        $stmt = $db->prepare("SELECT * FROM listeactualite where DATE_ACTUALITE BETWEEN ? and ? ");
        $id = 0;
        if ($stmt->execute(array($dateDebut,$dateFin))) {
            while ($row = $stmt->fetch()) {
                $actualite = array($row['TYPE_CATEG_ACTU'], $row['TITRE_CATEG_ACTU'], $row['CODE_A0'], $row['CAPTION_A0'], $row['COORD_X_A0'], $row['COORD_Y_A0'], $row['CODE_A1'], $row['CAPTION_A1'], $row['COORD_X_A1'], $row['COORD_Y_A1'], $row['TITRE_ACTUALITE'], $row['DETAIL_ACTUALITE'], $row['DATE_ACTUALITE'], $row['VALEUR']);

                //$actualite = array("attack","Bombe","senegal","Senegal","","","dakar","Dakar","105.7456","201.0894","test","test","2017-01-01","1000000");
                
                array_push($actualites,$actualite);
                
                //array_push($actualites,$actualite);
              
            }
            
            $resultJSON = json_encode($actualites);
            echo $resultJSON;
        }else{
            echo json_encode("noLines");
        }
    }
    else
    {
       
        echo json_encode("paramsEmpty");
    }
}else{
    echo json_encode("noParams");
}


//echo $myJSON." -- ";
//$myJSON = json_encode(array(array("attack","Bombe","senegal","Senegal","","","dakar","Dakar","105.7456","201.0894","test","test","2017-01-01","1000000")));



?>