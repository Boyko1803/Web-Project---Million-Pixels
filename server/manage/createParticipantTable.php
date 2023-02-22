<?php
	function createParticipantTable()
	{
		require_once("../../server/database/db.php");
		
		try {
			$db = new Database();
			
			$selectParticipants = $db->selectParticipantsQuery();
			
			if ($selectParticipants["success"]) {
				echo "<table id='participants-table'>\n";
				echo "\t<tr>\n";
				echo "\t\t<th>Факултетен номер</th>\n";
				echo "\t\t<th>Точки</th>\n";
				echo "\t\t<th>Оценка</th>\n";
				echo "\t\t<th>Позволено участие</th>\n";
				echo "\t\t<th>Промяна на участието</th>\n";
				echo "\t\t<th>Изтриване</th>\n";
				echo "\t</tr>\n";
				
				$participantsData = $selectParticipants["data"]->fetch(PDO::FETCH_ASSOC);
				
				while($participantsData) {
					echo "\t<tr>\n";
					
					echo "\t\t<td>" . $participantsData["fn"] . "</td>\n";
					echo "\t\t<td>" . $participantsData["points"] . "</td>\n";
					echo "\t\t<td>" . $participantsData["grade"] . "</td>\n";
					if ($participantsData["forbidden_to_participate"]) echo "\t\t<td class='forbidden-participation'>" . "Не" . "</td>\n";
					else echo "\t\t<td class='allowed-participation'>" . "Да" . "</td>\n";
					if ($participantsData["forbidden_to_participate"]) echo "\t\t<td><button class='allow-to-participate'>" . "Позволи" . "</button></td>\n";
					else echo "\t\t<td><button class='forbid-to-participate'>" . "Забрани" . "</button></td>\n";
					echo "\t\t<td><button class='delete'>Изтрий</button></td>\n";
					
					echo "\t</tr>\n";
					$participantsData = $selectParticipants["data"]->fetch(PDO::FETCH_ASSOC);	
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