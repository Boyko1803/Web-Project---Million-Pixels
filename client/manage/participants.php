<!DOCTYPE html>

<html lang="bg">
    <head>
        <meta charset="UTF-8"/>
        <title>Участници</title>
		<script defer src="../utilities/sendRequest.js?version=10"></script>
		<script defer src="../utilities/logout.js?version=10"></script>
		<script defer src="./participants.js?version=10"></script>
		<link rel="stylesheet" href="../../styles/navigation.css">
		<link rel="stylesheet" href="../../styles/table.css">
		<link rel="stylesheet" href="../../styles/requestForm.css">
    </head>
    <body>
        <?php
			session_start();
		
			if (!isset($_SESSION["fn"]) || !$_SESSION["administrator"]) {
				header("Location: ../../client/redirect/noPermission.html");
				die();
			}
		
			require_once("../../server/navigation/createNavigation.php");
			createNavigation("participants");
			
			require_once("../../server/manage/createParticipantTable.php");
			createParticipantTable();
		?>
		
		<main class="forms">
			<span id="general_error" class="error"></span>
			<fieldset>
				<h2 class="form-header">Добави участник</h2>
				<form id="add-participant" class="request-form">
					<label for="fn-add">Факултетен номер<span class="required">*</span></label>
					<input type="text" id="fn-add" name="fn-add"/> <span id="fn-add_error" class="error"></span>
					
					<label for="points-add">Точки<span class="required">*</span></label>
					<input type="number" id="points-add" name="points-add"/> <span id="points-add_error" class="error"></span>
					
					<label for="grade-add">Оценка</label>
					<input type="text" id="grade-add" name="grade-add"/> <span id="grade-add_error" class="error"></span>
					
					<button id="add-button"> Добави </button>
					<span id="general-add_error" class="error"></span>
				</form>
			</fieldset>
			
			<fieldset>
				<h2 class="form-header">Промени точките на участник</h2>
				<form id="update-participant" class="request-form">
					<label for="fn-update">Факултетен номер<span class="required">*</span></label>
					<input type="text" id="fn-update" name="fn-update"/> <span id="fn-update_error" class="error"></span>
					
					<label for="points-update">Точки<span class="required">*</span></label>
					<input type="number" id="points-update" name="points-update"/> <span id="points-update_error" class="error"></span>
					
					<button id="update-button"> Промени </button>
					<span id="general-update_error" class="error"></span>
				</form>
			</fieldset>
		</main>
    </body>
</html>