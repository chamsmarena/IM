<?php
    include("connectDB.php");
    session_start();

    $ID_ACTUALITE = date('YmdHis').rand (0, 9999);
    $ID_LANGUE =1;
    $ID_CATEG_ACTU = $_POST["TypeActu"];
    $ID_ADMIN1 = $_POST["Region"];
    $PAYS = $_POST["Pays"];
    $TITRE_ACTUALITE = $_POST['TitreFr'];
    $DETAIL_ACTUALITE = $_POST['DetailFr'];
    $DATE_ACTUALITE =  date('Y/m/d', strtotime($_POST['Date']));
    $VALEUR = $_POST['Valeur'];    
    
    // insertion d'une actualité francaise
    $stmt = $db->prepare("INSERT INTO actualite(ID_LANGUE, IDUSER, ID_CATEG_ACTU, ID_A1, ID_ACTUALITE, TITRE_ACTUALITE, DETAIL_ACTUALITE, DATE_ACTUALITE, VALEUR) VALUES ( ?,?,?,?,?,?,?,?,?)");
    $stmt->bindParam(1, $ID_LANGUE);
    $stmt->bindParam(2, $_SESSION["USER"]);
    $stmt->bindParam(3, $ID_CATEG_ACTU);
    $stmt->bindParam(4, $ID_ADMIN1);
    $stmt->bindParam(5, $ID_ACTUALITE);
    $stmt->bindParam(6, $TITRE_ACTUALITE);
    $stmt->bindParam(7, $DETAIL_ACTUALITE);
    $stmt->bindParam(8, $DATE_ACTUALITE);
    $stmt->bindParam(9, $VALEUR);


    $result = false;

    try {
         $result = $stmt->execute();
    } catch (PDOException $e) {
        echo 'Échec lors de la connexion : ' . $e->getMessage();
    }

    // insertion d'une actualité anglais
    $ID_LANGUE = 0;
    $TITRE_ACTUALITE = $_POST['TitreEn'];
    $DETAIL_ACTUALITE = $_POST['DetailEn'];


    try {
         $result = $stmt->execute();
    } catch (PDOException $e) {
        echo 'Échec lors de la connexion : ' . $e->getMessage();
    }

?>