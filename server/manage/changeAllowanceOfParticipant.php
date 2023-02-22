<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
		$forbidden_to_participate = isset($data['forbidden_to_participate']) ? testInput($data['forbidden_to_participate']) : '';
		
		try {
			$db = new Database();
			
			$updateParticipant = $db->updateParticipantAllowanceQuery(['fn' => $fn, 'forbidden_to_participate' => $forbidden_to_participate]);
			
			if (!$updateParticipant["success"]) {
				$errors["general"] = 'Неуспешна промяна на статуса на участника';
			}
		} catch(Exception $e) {
			$errors["general"] = 'Неуспешна промяна на статуса на участника';
		}
	}
	
    if ($errors) {
        http_response_code(401);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>