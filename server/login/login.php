<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';

	require '../utilities/destroySession.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
        $password = isset($data['password']) ? testInput($data['password']) : '';

		if ($fn == '') {
            $errors["fn"] = 'Факултетният номер е задължително поле';
        }

        if ($password == '') {
            $errors["password"] = 'Паролата е задължително поле';
        }
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$selectUser = $db->selectUserQuery(['fn' => $fn]);
				
				if ($selectUser["success"]) {
					$userData = $selectUser["data"]->fetch(PDO::FETCH_ASSOC);
					
					if ($userData) {
						$passwordHash = $userData["password"];
						if (password_verify($password, $passwordHash)) {
							session_start();
							$_SESSION["fn"] = $fn;
							$_SESSION["participant"] = $userData["is_participant"];
							$_SESSION["administrator"] = $userData["is_administrator"];
						} else {
							$errors["password"] = "Невалидна парола";
						}
					} else {
						$errors["fn"] = "Невалиден факултетен номер";
					}
				} else {
					$errors["general"] = "Неуспешна заявка към базата данни";
				}
			} catch(Exception $e) {
				$errors["general"] = 'Неуспешно свързване с базата данни';
			}
		}
    } else {
        $errors["general"] = 'Невалидна заявка';
    }
	
    if ($errors) {
        http_response_code(401);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>