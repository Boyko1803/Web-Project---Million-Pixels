<?php
	function createNavigation($current)
	{
		echo "<nav id='navigation'>\n";
		
		if ($current == "view") echo "\t\t\t<a href='../view/view.php' id='view' class='active'>Начало</a>\n";
		else echo "\t\t\t<a href='../view/view.php' id='view'>Начало</a>\n";
		
		if ($_SESSION["participant"]) {
			if ($current == "add") echo "\t\t\t<a href='../add/addPicture.php' id='add' class='active'>Добави картина</a>\n";
			else echo "\t\t\t<a href='../add/addPicture.php' id='add'>Добави картина</a>\n";
		}
		
		if ($_SESSION["administrator"]) {
			if ($current == "pictures") echo "\t\t\t<a href='../manage/pictures.php' id='pictures' class='active'>Картини</a>\n";
			else echo "\t\t\t<a href='../manage/pictures.php' id='pictures'>Картини</a>\n";
			
			if ($current == "participants") echo "\t\t\t<a href='../manage/participants.php' id='participants' class='active'>Участници</a>\n";
			else echo "\t\t\t<a href = '../manage/participants.php' id='participants'>Участници</a>\n";
		}
		
		echo "\t\t\t<a href='../index.html' id='logout'>Изход</a>\n";
		
		echo "\t\t</nav>\n";
	}
?>