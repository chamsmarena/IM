<?php
if(isset($_SESSION["USER"])){
	echo "<a class='lien' href='../index.php'><span class='ion-easel' ></span></a>";
	echo "<a class='lien' href='manageWeeklies.php' ><span class='ion-grid' ></span></a>";
	echo "<a class='lien' href='addWeekly.php'><span class='ion-ios-plus' ></span></a>";
	echo "<a class='lien' id='menulogOut'><span class='ion-log-out'></span></a>";
}else{
	echo "<a class='lien' ><span class='ion-log-in'  data-toggle='modal' data-target='#ModalConnexion'></span></a>";
}