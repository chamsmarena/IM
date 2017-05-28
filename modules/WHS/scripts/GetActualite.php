<?php
/*
$cars = array("a', 'b', 'c', 'd', 'e');
array_push($cars,'f');


$myJSON = json_encode($cars);*/

//

header('Content-type: text/plain; charset=iso-8859-15');
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
        $filterKeyWord = "";
        
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
            $filterTheme = " and ID_TAG in (";
            for ($i = 0; $i < count($Themes); $i++){
                if($i!=(count($Themes)-1)){
                    $filterTheme.=$Themes[$i].", ";
                }else{
                    $filterTheme.=$Themes[$i].")";
                }
            }
        }
		
        if($_POST['keyWord']!=""){
            $filterKeyWord = " and (DETAIL_ACTUALITE like '%".$_POST['keyWord']."%' OR TITRE_ACTUALITE like '%".$_POST['keyWord']."%') ";
        }
        
		//$cols = array('CODE_A0','CODE_A1','COORD_X_A1',' CODE_A1Lieu','COORD_X_A1Lieu','COORD_Y_A1Lieu');
        //$requete = "SELECT distinct(ID_ACTUALITE), TYPE_CATEG_ACTU, TITRE_CATEG_ACTU, CODE_A0,CAPTION_A0, COORD_X_A0, COORD_Y_A0, CODE_A1, CAPTION_A1, COORD_X_A1, COORD_Y_A1, TITRE_ACTUALITE, DETAIL_ACTUALITE, DATE_ACTUALITE, ID_LANGUE, VALEUR, ID_A0, ID_CATEG_ACTU, CODE_MENACE, TITRE_MENACE,LIBELLE_LIEU,CAPTION_A0Lieu,COORD_X_A0Lieu,COORD_Y_A0Lieu,CODE_A1Lieu,CAPTION_A1Lieu,COORD_X_A1Lieu,COORD_Y_A1Lieu FROM listeactualite where DATE_ACTUALITE BETWEEN ? and ? and ID_LANGUE=1 ".$filterPays.$filterTheme.$filterKeyWord." order by CODE_A0, CODE_A1Lieu,COORD_X_A1Lieu,COORD_Y_A1Lieu,CODE_A1,COORD_X_A1 asc";
        $requete = "SELECT distinct(ID_ACTUALITE), TYPE_CATEG_ACTU, TITRE_CATEG_ACTU, CODE_A0,CAPTION_A0, COORD_X_A0, COORD_Y_A0, CODE_A1, CAPTION_A1, COORD_X_A1, COORD_Y_A1, TITRE_ACTUALITE, DETAIL_ACTUALITE, DATE_ACTUALITE, ID_LANGUE, VALEUR, ID_A0, ID_CATEG_ACTU, CODE_MENACE, TITRE_MENACE,LIBELLE_LIEU,CAPTION_A0Lieu,COORD_X_A0Lieu,COORD_Y_A0Lieu,CODE_A1Lieu,CAPTION_A1Lieu,COORD_X_A1Lieu,COORD_Y_A1Lieu FROM listeactualite where DATE_ACTUALITE BETWEEN ? and ? and ID_LANGUE=1 ".$filterPays.$filterTheme.$filterKeyWord." order by CODE_A0, CODE_A1Lieu,CODE_A1 asc";
        
		
		
        $stmt = $db->prepare($requete);
        
        $id = 0;
		$n=0;
        
        try{
            if ($stmt->execute(array($dateDebut,$dateFin))) {
                
                while ($row = $stmt->fetch()) {
                    
                    $actualite = array($row['TYPE_CATEG_ACTU'], $row['TITRE_CATEG_ACTU'], $row['CODE_A0'], $row['CAPTION_A0'], $row['COORD_X_A0'], $row['COORD_Y_A0'], $row['CODE_A1'], $row['CAPTION_A1'], $row['COORD_X_A1'], $row['COORD_Y_A1'], utf8_encode($row['TITRE_ACTUALITE']), utf8_encode($row['DETAIL_ACTUALITE']), $row['DATE_ACTUALITE'], $row['CODE_MENACE'],$row['LIBELLE_LIEU'], $row['CAPTION_A0Lieu'], $row['COORD_X_A0Lieu'], $row['COORD_Y_A0Lieu'], $row['CODE_A1Lieu'], $row['CAPTION_A1Lieu'], $row['COORD_X_A1Lieu'], $row['COORD_Y_A1Lieu'],$n);

                    array_push($actualites,$actualite);
					$n++;
                }
                
				//var_dump($actualites);
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