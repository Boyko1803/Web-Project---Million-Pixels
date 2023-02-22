<?php
	function createPictureTable()
	{
		require_once("../../server/database/db.php");
		
		try {
			$db = new Database();
			
			$selectPictures = $db->selectNondeletedPicturesQuery();
			
			if ($selectPictures["success"]) {
				echo "<table id='pictures-table'>\n";
				echo "\t<tr>\n";
				echo "\t\t<th>Идентификатор на картината</th>\n";
				echo "\t\t<th>Факултетен номер на създателя</th>\n";
				echo "\t\t<th>Използвани точки</th>\n";
				echo "\t\t<th>X Начало</th>\n";
				echo "\t\t<th>X Край</th>\n";
				echo "\t\t<th>Y Начало</th>\n";
				echo "\t\t<th>Y Край</th>\n";
				echo "\t\t<th>Линк</th>\n";
				echo "\t\t<th>Текст</th>\n";
				echo "\t\t<th>Създадена</th>\n";
				echo "\t\t<th>Преглед</th>\n";
				echo "\t\t<th>Изтрий</th>\n";
				echo "\t</tr>\n";
				
				$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);
				
				while($pictureData) {
					echo "\t<tr>\n";
					
					echo "\t\t<td>" . $pictureData["id"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["creator_fn"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["points_cost"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["x_start"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["x_end"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["y_start"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["y_end"] . "</td>\n";
					if (!$pictureData["link"]) echo "\t\t<td></td>\n";
					else echo "\t\t<td><a href='" . $pictureData["link"] . "' target='_blank'>" . $pictureData["link"] . "</a></td>\n";
					if (!$pictureData["text"]) echo "\t\t<td></td>\n";
					else echo "\t\t<td>". $pictureData["text"] . "</td>\n";
					echo "\t\t<td>" . $pictureData["created"] . "</td>\n";
					echo "\t\t<td><button class='preview'>Преглед</button></td>\n";
					echo "\t\t<td><button class='delete'>Изтрий</button></td>\n";
					
					echo "\t</tr>\n";
					$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);	
				}
				echo "</table>\n";
			} else {
				throw new Exception('Неуспешна заявка');
			}
			
		} catch (Exception $e) {
			echo "<span id ='loading_error' class='error'>Възникна проблем със зареждането на таблицата</span>";
		}			
	}
?>