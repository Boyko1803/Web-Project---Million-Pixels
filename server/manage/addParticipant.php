<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
        $points = isset($data['points']) ? testInput($data['points']) : '';
		$grade = isset($data['grade']) ? testInput($data['grade']) : '';

		if ($fn == '') {
            $errors["fn-add"] = 'Факултетният номер е задължително поле';
        }
		
		if (!preg_match("/^[0-9][0-9]*$/", $points)) {
			$errors["points-add"] = 'Точките трябва да са цяло неотрицателно число';
		}
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$selectParticipant = $db->selectParticipantQuery(['fn' => $fn]);
				
				if ($selectParticipant["success"]) {
					$participantData = $selectParticipant["data"]->fetch(PDO::FETCH_ASSOC);
					
					if ($participantData) {
						$errors["fn-add"] = "Вече има участник с този факултетен номер";
					} else {
						$participantToInsert = [];
						
						$participantToInsert["fn"] = $fn;
						$participantToInsert["points"] = intval($points);
						$participantToInsert["grade"] = $grade;
						
						$insertQuery = $db->insertParticipantQuery($participantToInsert);
						if (!$insertQuery["success"]) {
							$errors["general-add"] = "Неуспешно добавяне";
						}
					}
				} else {
					$errors["general-add"] = "Неуспешна заявка към базата данни";
				}
			} catch(Exception $e) {
				$errors["general-add"] = 'Неуспешно свързване с базата данни';
			}
		}
    } else {
        $errors["general-add"] = 'Невалидна заявка';
    }
	
    if ($errors) {
        http_response_code(401);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>