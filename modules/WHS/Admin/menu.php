<?php
if(isset($_SESSION["USER"])){
	echo "<a class='lien' href='../index.php'>Snapshot</a>";
	echo "<a class='lien' href='manageWeeklies.php' >Admin page</a>";
	echo "<a class='lien' href='manageLocations.php' >Locations</a>";
	echo "<a class='lien' href='addWeekly.php'>Add news</a>";
	echo "<a class='lien' id='menulogOut'>Log out</a>";
}else{
	echo "<a class='lien' ><span class='ion-log-in'  data-toggle='modal' data-target='#ModalConnexion'></span></a>";
}