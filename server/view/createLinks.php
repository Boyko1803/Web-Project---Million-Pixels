<?php
	function createLinks()
	{
		require_once("../../server/database/db.php");
		
		try {
			$db = new Database();
			
			$selectPictures = $db->selectNondeletedPicturesQuery();
			
			if ($selectPictures["success"]) {
				echo "<map id='link-map'>\n";
				
				$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);
				
				while($pictureData) {
					$coordinates = $pictureData["x_start"] . ',' . $pictureData["y_start"] . ',' . $pictureData["x_end"] . ',' . $pictureData["y_end"];
					
					if ($pictureData['link'] != "") echo "\t<area shape='rect' coords='" . $coordinates ."' href='" . $pictureData['link'] . "' title='" . $pictureData['text'] . "' target='_blank'>\n";
					else echo "\t<area shape='rect' coords='" . $coordinates ."' title='" . $pictureData['text'] . "' target='_blank'>\n";
					
					$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);
				}
				echo "</map>\n";
			} else {
				throw new Exception('Неуспешна заявка');
			}
		} catch (Exception $e) {
			echo "<span id ='loading_error' class='error'>Възникна проблем със зареждането на линковете</span>";
		}			
	}
?>