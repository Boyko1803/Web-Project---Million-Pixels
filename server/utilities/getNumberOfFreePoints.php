<?php
	function getNumberOfFreePoints() {
		require_once("../../server/database/db.php");
		
		try {
			$db = new Database();
			
			$getPoints = $db->calculateUsedPointsCostQuery(["fn" => $_SESSION["fn"]]);
			$selectParticipant = $db->selectParticipantQuery(["fn" => $_SESSION["fn"]]);
			
			if ($getPoints["success"] && $selectParticipant["success"]) {
				$participantData = $selectParticipant["data"]->fetch(PDO::FETCH_ASSOC);
							
				if ($participantData) {
					$usedPoints = intval($getPoints["sum"]);
					$totalPoints = $participantData["points"];
					$freePoints = $totalPoints - $usedPoints;
					return ["success" => true, "points" => $freePoints];
				} else {
					return ["success" => false];
				}
			} else {
				return ["success" => false];
			}
		} catch (Exception $e) {
			return ["success" => false];
		}
	}
?>