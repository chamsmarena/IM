<?php
if(isset($_SESSION["USER"])){
	echo "<a class='lien' href='../index.php'><span class='glyphicon glyphicon-globe' ></span></a>";
	
	echo "<a class='lien' href='manageWeeklies.php' ><span class='ion-grid' ></span></a>";
	echo "<a class='lien' href='manageWeeklies.php' ><span class='glyphicon glyphicon-map-marker' ></span></a>";
	echo "<a class='lien' href='addWeekly.php'><span class='ion-ios-plus' ></span></a>";
	echo "<a class='lien' id='menulogOut'><span class='ion-log-out'></span></a>";
}else{
	echo "<a class='lien' ><span class='ion-log-in'  data-toggle='modal' data-target='#ModalConnexion'></span></a>";
}