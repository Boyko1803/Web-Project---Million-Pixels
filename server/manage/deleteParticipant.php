<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
		
		try {
			$db = new Database();
			
			$deleteParticipant = $db->deleteParticipantQuery(['fn' => $fn]);
			
			if (!$deleteParticipant["success"]) {
				$errors["general"] = 'Неуспешно изтриване на участника';
			}
		} catch(Exception $e) {
			$errors["general"] = 'Неуспешно изтриване на участника';
		}
	} else {
		$errors["general"] = "Невалидна заявка";
	}
	
    if ($errors) {
        http_response_code(401);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>