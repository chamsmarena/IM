<?php
if(isset($_SESSION["USER"])){
	echo "<a class='lien' href='../index.php'>Visitor space</a>";
	echo "<a class='lien' href='manageWeeklies.php' >Weeklies</a>";
	
	echo "<a class='lien' href='manageLocations.php' >Locations</a>";
	echo "<a class='lien' href='Logout.php' >Log out</a>";
}else{
	echo "<a class='lien' ><span class='ion-log-in'  data-toggle='modal' data-target='#ModalConnexion'></span></a>";
}