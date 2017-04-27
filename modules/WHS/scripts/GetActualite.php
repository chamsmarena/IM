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
        $filterPays = "";
        $filterTheme = "";
        
        $Pays = explode("_", $_POST['filtrePays']);
        $Themes = explode("_", $_POST['filtreTheme']);
        
        
        
        if($_POST['filtrePays']!=""){
            $filterPays = " and CODE_A0 in (";
            for ($i = 0; $i < count($Pays); $i++){
                if($i!=(count($Pays)-1)){
                    $filterPays.="'".$Pays[$i]."', ";
                }else{
                    $filterPays.="'".$Pays[$i]."')";
                }
            }
        }                             
        
        
        if($_POST['filtreTheme']!=""){
            $filterTheme = " and ID_CATEG_ACTU in (";
            for ($i = 0; $i < count($Themes); $i++){
                if($i!=(count($Themes)-1)){
                    $filterTheme.=$Themes[$i].", ";
                }else{
                    $filterTheme.=$Themes[$i].")";
                }
            }
        }
        
        $requete = "SELECT * FROM listeactualite where DATE_ACTUALITE BETWEEN ? and ? and ID_LANGUE=1 ".$filterPays.$filterTheme;
        //echo $requete;
        $stmt = $db->prepare($requete);
        
        $id = 0;
        
        try{
            if ($stmt->execute(array($dateDebut,$dateFin))) {
                
                while ($row = $stmt->fetch()) {
                    
                    $actualite = array($row['TYPE_CATEG_ACTU'], $row['TITRE_CATEG_ACTU'], $row['CODE_A0'], $row['CAPTION_A0'], $row['COORD_X_A0'], $row['COORD_Y_A0'], $row['CODE_A1'], $row['CAPTION_A1'], $row['COORD_X_A1'], $row['COORD_Y_A1'], utf8_encode($row['TITRE_ACTUALITE']), utf8_encode($row['DETAIL_ACTUALITE']), $row['DATE_ACTUALITE'], $row['CODE_MENACE']);

                    array_push($actualites,$actualite);
                }
                
                $resultJSON = json_encode($actualites);
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


//echo $myJSON." -- ";
//$myJSON = json_encode(array(array("attack","Bombe","senegal","Senegal","","","dakar","Dakar","105.7456","201.0894","test","test","2017-01-01","1000000")));



?>