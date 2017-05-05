<?php
$user = "root";
$pass= "";
try {
    $db = new PDO('mysql:host=localhost;dbname=whs', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $db = null;
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>