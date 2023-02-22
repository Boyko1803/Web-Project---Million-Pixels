<?php
    require_once '../utilities/testInput.php';
	require_once '../utilities/getNumberOfFreePoints.php';
    require_once '../database/db.php';
	
	header('Content-type: application/json');
	
	session_start();
	
    $errors = [];
	$savedPicture = false;
	$directory = "";

    if ($_POST) {
		$data = json_decode($_POST['data'], true);
		
		$x_start = isset($data['x_start']) ? testInput($data['x_start']) : '';
		$x_end = isset($data['x_end']) ? testInput($data['x_end']) : '';
		$y_start = isset($data['y_start']) ? testInput($data['y_start']) : '';
		$y_end = isset($data['y_end']) ? testInput($data['y_end']) : '';
		$image = isset($data['image']) ? testInput($data['image']) : '';
		$link = isset($data['link']) ? testInput($data['link']) : '';
		$text = isset($data['text']) ? $data['text'] : '';
		
		if (!preg_match("/^[0-9][0-9]*$/", $x_start) ||
			!preg_match("/^[0-9][0-9]*$/", $x_end) ||
			!preg_match("/^[0-9][0-9]*$/", $y_start) ||
			!preg_match("/^[0-9][0-9]*$/", $y_end)) {
				$errors["general"] = "Невалидни координати";
		}
			
		if (!$errors) {
			$x_start_val = intval($x_start);
			$x_end_val = intval($x_end);
			$y_start_val = intval($y_start);
			$y_end_val = intval($y_end);
			
			if ($x_start_val < 0 || $x_end_val < 0 || $y_start_val < 0 || $y_end_val < 0 ||
				$x_start_val > 1000 || $x_end_val > 1000 || $y_start_val > 1000 || $y_end_val > 1000 ||
				$x_start_val >= $x_end_val || $y_start_val >= $y_end_val) {
				$errors["general"] = "Невалидни координати";
			}
		}
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$selectParticipant = $db->selectParticipantQuery(['fn' => $_SESSION['fn']]);
					
				if ($selectParticipant["success"]) {
					$participantData = $selectParticipant["data"]->fetch(PDO::FETCH_ASSOC);
					
					if ($participantData) {
						if ($participantData["forbidden_to_participate"] == "1") $errors["general"] = "Имате забрана да добавяте картини";
					} else {
						$errors["general"] = "Нямате права на участник";
					}
				} else {
					$errors["general"] = "Неуспешна заявка към базата данни";
				}
			} catch (Exception $e) {
				$errors["general"] = "Неуспешна връзка с базата данни";
			}
		}
		
		if (!$errors) {
			$freePointsQuery = getNumberOfFreePoints();
			if ($freePointsQuery["success"]) {
				$freePoints = $freePointsQuery["points"];
				$neededPoints = ($x_end_val - $x_start_val) * ($y_end_val - $y_start_val);
				if ($freePoints < $neededPoints) $errors["general"] = "Нямате достатъчно свободни точки";
			} else {
				$errors["general"] = "Неуспешна заявка към базата данни";
			}
		}
		
		if (!$errors && $image == "") $errors["general"] = "Липсва картина";
		
		if (!$errors) {
			$image = str_replace('data:image/png;base64,', '', $image);
			$image = str_replace(' ', '+', $image);
			$fileData = base64_decode($image);
			if (!$fileData) $errors["general"] = "Неуспешно зареждане на добавената картината";
		}
		
		if (!$errors) {
			$imageName = "i" . strval(rand()) . ".png";
			$directory = "../../pictures/parts/" . $imageName;
			
			$save = file_put_contents($directory, $fileData);
			if (!$save) $errors["general"] = "Неуспешно запаметяване на добавената картината";
			else $savedPicture = true;
		}
		
		if (!$errors) {
			try {
				$db = new Database();
				
				$db->beginTransaction();
				$selectPictures = $db->selectNondeletedPicturesWithBlockingQuery();
				
				if (!$selectPictures["success"]) {
					$errors["general"] = "Неуспешна заявка към базата данни";
				}
				
				if (!$errors) {
					$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);
					
					while($pictureData) {
						$picture_x_start = intval($pictureData["x_start"]);
						$picture_x_end = intval($pictureData["x_end"]);
						$picture_y_start = intval($pictureData["y_start"]);
						$picture_y_end = intval($pictureData["y_end"]);
						
						if (!($x_start_val >= $picture_x_end ||
							  $x_end_val <= $picture_x_start ||
							  $y_start_val >= $picture_y_end ||
							  $y_end_val <= $picture_y_start)) {
							$errors["general"] = "Картината се застъпва с останалите картини";
							break;
						}
						
						$pictureData = $selectPictures["data"]->fetch(PDO::FETCH_ASSOC);
					}
				}
					
				if (!$errors) {
					$add = imagecreatefrompng($directory);
					$used = imagecreatefrompng("../../pictures/main/used.png");
					$main = imagecreatefrompng("../../pictures/main/main.png");
					$blocked = imagecreatetruecolor($x_end_val - $x_start_val, $y_end_val - $y_start_val);
					
					if (!$main || !$used || !$add || !$blocked) {
						$errors["general"] = "Неуспешно зареждане на картините";
					} else {
						imageAlphaBlending($used, true);
						imageSaveAlpha($used, true);
						$red = imagecolorallocate($blocked, 255, 0, 0);
						imagefill($blocked, 0, 0, $red);
					}
				}
				
				if (!$errors) {
					if (!imagecopy($main, $add, $x_start_val, $y_start_val, 0, 0, $x_end_val - $x_start_val, $y_end_val - $y_start_val)) {
						$errors["general"] = "Неуспешно съчетаване на картините";
					} else if(!imagecopy($used, $blocked, $x_start_val, $y_start_val, 0, 0, $x_end_val - $x_start_val, $y_end_val - $y_start_val)) {
						$errors["general"] = "Неуспешно обновяване на използваните полета";
					}
				}
				
				if (!$errors) {
					$query = [];
					$query["creator_fn"] = $_SESSION['fn'];
					$query["points_cost"] = strval(($x_end_val - $x_start_val) * ($y_end_val - $y_start_val));
					$query["x_start"] = $x_start;
					$query["x_end"] = $x_end;
					$query["y_start"] = $y_start;
					$query["y_end"] = $y_end;
					$query["picture_name"] = $imageName;
					$query["link"] = $link;
					$query["text"] = $text;
					
					$insertPicture = $db->insertPictureQuery($query);
					if (!$insertPicture["success"]) {
						$errors["general"] = "Неуспешно добавяне на снимката към базата данни";
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
			} catch (Exception $e) {
				$errors["general"] = "Неуспешна връзка с базата данни";
			}
		}
    } else {
        $errors["general"] = 'Невалидна заявка';
    }
	
    if ($errors) {
        http_response_code(401);
		if ($savedPicture) unlink($directory);

        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        echo json_encode(['success' => true]);
    }
?>