<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
        $points = isset($data['points']) ? testInput($data['points']) : '';

		if ($fn == '') {
            $errors["fn-update"] = 'Факултетният номер е задължително поле';
        }
		
		if (!preg_match("/^[0-9][0-9]*$/", $points)) {
			$errors["points-update"] = 'Точките трябва да са цяло неотрицателно число';
		}
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$selectParticipant = $db->selectParticipantQuery(['fn' => $fn]);
				
				if ($selectParticipant["success"]) {
					$participantData = $selectParticipant["data"]->fetch(PDO::FETCH_ASSOC);
					
					if (!$participantData) {
						$errors["fn-update"] = "Няма участник с този факултетен номер";
					} else {
						$usedPoints = $db->calculateUsedPointsCostQuery(['fn' => $fn]);
						if ($usedPoints["success"])
						{
							$usedPoints = intval($usedPoints["sum"]);
							if ($usedPoints > intval($points)) {
								$errors["points-update"] = "Участикът вече е изразходил " . strval($usedPoints) . " точки и това е минималният брой точки, които може да има. Можете да намалите този брой като изтриете картините му.";
							} else {
								$participantToUpdate = [];
						
								$participantToUpdate["fn"] = $fn;
								$participantToUpdate["points"] = intval($points);
								
								$updateQuery = $db->updateParticipantPoints($participantToUpdate);
								if (!$updateQuery["success"]) {
									$errors["general-update"] = "Неуспешно обновяване";
								}
							}
						} else {
							$errors["general-update"] = "Неуспешна заявка към базата данни";
						}
					}
				} else {
					$errors["general-update"] = "Неуспешна заявка към базата данни";
				}
			} catch(Exception $e) {
				$errors["general-update"] = 'Неуспешно свързване с базата данни';
			}
		}
    } else {
        $errors["general-update"] = 'Невалидна заявка';
    }
	
    if ($errors) {
        http_response_code(401);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>