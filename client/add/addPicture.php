<!DOCTYPE html>

<html lang="bg">
    <head>
        <meta charset="UTF-8"/>
        <title>Добави картина</title>
		<script defer src="../../configuration/clientConfig.js?version=10"></script>
		<script defer src="../utilities/sendRequest.js?version=10"></script>
		<script defer src="../utilities/logout.js?version=10"></script>
		<script defer src="./addPicture.js?version=11"></script>
		<link rel="stylesheet" href="../../styles/navigation.css">
		<link rel="stylesheet" href="../../styles/pictures.css">
		<link rel="stylesheet" href="../../styles/requestForm.css">
    </head>
    <body>
        <?php
			session_start();
		
			if (!isset($_SESSION["fn"]) || !$_SESSION["participant"]) {
				header("Location: ../../client/redirect/noPermission.html");
				die();
			}
		
			require_once("../../server/navigation/createNavigation.php");
			createNavigation("add");
		?>
		
		<main class="pictureContainer">
			<figure class="mainframe">
				<img src="../../pictures/main/grid.png" id="grid-picture">
				<img src="<?php echo "../../pictures/main/main.png" . "?v=" . filemtime("../../pictures/main/main.png"); ?>" id="main-picture"/>
				<img src="<?php echo "../../pictures/main/used.png" . "?v=" . filemtime("../../pictures/main/used.png"); ?>" id="overlay-picture"/>
				<a href="" id="placeholder-link" target="_blank"><img id="placeholder"></a>
			</figure>
		</main>
			
		<main class="forms">
			<fieldset>
				<form id="add-picture" class="request-form">
					<section class="wrapper">
						<button id="grid-control" class="inline">Мрежа</button>
						<button id="overlay-control" class="inline">Заети полета</button>
					</section>
					
					<?php
						require_once("../../server/utilities/getNumberOfFreePoints.php");
					
						$getPoints = getNumberOfFreePoints();
						if ($getPoints["success"]) {
							echo "<h3 class='message'>Разполагате с " . strval($getPoints["points"]) . " свободни точки</h3>";
						} else {
							echo "<h3 class='message'>Възникна грешка в изчисляването на свободните ви точки</h3>";
						}
					?>
					
					<label for="x-start">X начало<span class="required">*</span></label>
					<input type="number" id="x-start" name="x-start"/>

					<label for="x-end">X край<span class="required">*</span></label>
					<input type="number" id="x-end" name="x-end"/>

					<label for="y-start">Y начало<span class="required">*</span></label>
					<input type="number" id="y-start" name="y-start"/>
					
					<label for="y-end">Y край<span class="required">*</span></label>
					<input type="number" id="y-end" name="y-end"/>
					
					<label for="new-picture">Снимка<span class="required">*</span></label>
					<input type="file" accept="image/png, image/gif, image/jpeg" id="new-picture" name="new-picture">
					
					<label for="link">Линк</label>
					<input type="text" id="link" name="link">
					
					<label for="text">Текст</label>
					<input type="text" id="text" name="text">
					
					<span id="general_error" class="error"></span>
					
					<section class="wrapper">
						<button id="preview" class="inline">Преглед</button>
						<button id="submit" class="inline">Добавяне</button>
					</section>
					
					<h3 class="message" id="add-cost"></h3>
				</form>
			</fieldset>
		</main>
	</body>
</html>