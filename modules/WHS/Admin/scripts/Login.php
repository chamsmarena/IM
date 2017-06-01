<?php

if(isset($_POST['login']) && isset($_POST['password']))
{
    
    if($_POST['login']!='' && $_POST['password']!='')
    {
        include("connectDB.php");
        
        $login = $_POST['login'];
        $password = $_POST['password'];
		$username = null;

        $stmt = $db->prepare("SELECT IDUSER FROM UTILISATEUR WHERE USERNAME=? and PASSWORD=? ");
        if ($stmt->execute(array($login,$password))) {
            while ($row = $stmt->fetch()) {
                $username = $row['IDUSER'];
            }
            
			if($username!=null){
				session_start();
				$_SESSION['USER']=$username;
			}
            
        }else{
            
        }
        
    }
}


header('Location: ../index.php');

?>