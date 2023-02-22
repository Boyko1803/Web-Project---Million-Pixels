<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');

    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$fn = isset($data['fn']) ? testInput($data['fn']) : '';
        $name = isset($data['name']) ? testInput($data['name']) : '';
        $password = isset($data['password']) ? testInput($data['password']) : '';
        $confirmPassword = isset($data['confirm_password']) ? testInput($data['confirm_password']) : '';
        $email = isset($data['email']) ? testInput($data['email']) : '';

		if ($fn == '') {
            $errors["fn"] = 'Факултетният номер е задължително поле';
        }
		else if (strlen($fn) > 32) {
			$errors["fn"] = 'Факултетният номер е най-много 32 символа';
		}

        if ($name == '') {
            $errors["name"] = 'Името е задължително поле';
        }
		else if (strlen($name) > 200) {
			$errors["name"] = 'Името е най-много 200 символа';
		}

        if ($password == '') {
            $errors["password"] = 'Паролата е задължително поле';
        }

        if ($confirmPassword == '') {
            $errors["confirm_password"] = 'Паролата е задължително поле';
        }
		
		if (!($password === $confirmPassword)) {
			$errors["confirm_password"] = "Паролите трябва да съвпадат";
		}
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$selectUser = $db->selectUserQuery(['fn' => $fn]);
				$selectParticipant = $db->selectParticipantQuery(['fn' => $fn]);
				
				if ($selectUser["success"] && $selectParticipant["success"]) {
					$userData = $selectUser["data"]->fetch(PDO::FETCH_ASSOC);
					
					if ($userData) {
						$errors["fn"] = "Вече съществува потребител с този факултетен номер";
					} else {
						$userToInsert = [];
						$isParticipant = 0;
						
						$participantData = $selectParticipant["data"]->fetch(PDO::FETCH_ASSOC);
						if ($participantData) {
							$isParticipant = 1;
						}
						
						$userToInsert["fn"] = $fn;
						$userToInsert["name"] = $name;
						$userToInsert["email"] = $email;
						$userToInsert["password"] = password_hash($password, PASSWORD_DEFAULT);
						$userToInsert["is_participant"] = $isParticipant;
						
						$insertQuery = $db->insertUserQuery($userToInsert);
						if (!$insertQuery["success"]) {
							$errors["general"] = "Неуспешна регистрация";
						}
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