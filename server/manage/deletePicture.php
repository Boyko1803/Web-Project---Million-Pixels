<?php
    require_once '../utilities/testInput.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
    $errors = [];

    if ($_POST) {
        $data = json_decode($_POST['data'], true);

		$id = isset($data['id']) ? testInput($data['id']) : '';
		
		try {
			$db = new Database();
			
			$selectPicture = $db->selectPictureQuery(['id' => $id]);
			
			if (!$selectPicture["success"]) {
				$errors["general"] = "Неуспешна заявка към базата данни";
			} else {
				$pictureData = $selectPicture["data"]->fetch(PDO::FETCH_ASSOC);
				
				if (!$pictureData) $errors["general"] = "Няма картина с този номер";
			}
			
			if (!$errors) {
				$x_start_val = intval($pictureData["x_start"]);
				$x_end_val = intval($pictureData["x_end"]);
				$y_start_val = intval($pictureData["y_start"]);
				$y_end_val = intval($pictureData["y_end"]);
				
				$db->beginTransaction();
				$deletePicture = $db->updatePictureAsDeletedQuery(['id' => $id]);
				
				if (!$deletePicture["success"]) {
					$errors["general"] = "Неуспешна заявка към базата данни";
				}
				
				if (!$errors) {
					$used = imagecreatefrompng("../../pictures/main/used.png");
					$main = imagecreatefrompng("../../pictures/main/main.png");
					
					if (!$main || !$used) {
						$errors["general"] = "Неуспешно зареждане на картините";
					} else {
						imageAlphaBlending($used, false);
						imageSaveAlpha($used, true);
						$white = imagecolorallocate($main, 255, 255, 255);
						$trans = imagecolorallocatealpha($used, 255, 255, 255, 127);
					}
				}
				
				if (!$errors) {
					if (!imagefilledrectangle($main, $x_start_val, $y_start_val, $x_end_val, $y_end_val, $white)) {
						$errors["general"] = "Неуспешно обновяване на основната картина";
					} else if (!imagefilledrectangle($used, $x_start_val, $y_start_val, $x_end_val, $y_end_val, $trans)) {
						$errors["general"] = "Неуспешно обновяване на използваните полета";
					}
				}
				
				if (!$errors) {
					$directoryMain = "../../pictures/main/main.png";
					$directoryUsed = "../../pictures/main/used.png";
					
					imagepng($main, $directoryMain);
					imagepng($used, $directoryUsed);
				}
				
				if ($errors) $db->rollback();
				else $db->commit();
			}
		} catch(Exception $e) {
			$errors["general"] = 'Неуспешно изтриване на картината';
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