<?php

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo "<list>";
$entite = (isset($_POST["entite"])) ? trim(htmlentities($_POST["entite"])) : NULL;
$valeur = (isset($_POST["valeur"])) ? trim(htmlentities($_POST["valeur"])) : NULL;
$valeur2 =(isset($_POST["valeur2"])) ? trim(htmlentities($_POST["valeur2"])) : NULL;

include("connectDB.php");

if($entite=="regions"){
    $stmt = $db->prepare("SELECT ID_A1, CAPTION_A1 FROM admin_1 WHERE ID_A0= ? ");
    if ($stmt->execute(array($valeur))) {
        while ($row = $stmt->fetch()) {
            echo "<item id='".$row['ID_A1']."' name='".$row['CAPTION_A1']."' />";

        }

        $resultJSON = json_encode($actualites);
        echo $resultJSON;
    }
}



echo "</list>";
?>