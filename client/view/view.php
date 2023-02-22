<!DOCTYPE html>

<html lang="bg">
    <head>
        <meta charset="UTF-8"/>
        <title>Начало</title>
		<script defer src="../utilities/sendRequest.js?version=10"></script>
		<script defer src="../utilities/logout.js?version=10"></script>
		<link rel="stylesheet" href="../../styles/navigation.css">
		<link rel="stylesheet" href="../../styles/pictures.css">
    </head>
	
    <body>
		<?php
			session_start();
		
			if (!isset($_SESSION["fn"])) {
				header("Location: ../../client/redirect/noPermission.html");
				die();
			}
			
			require_once("../../server/navigation/createNavigation.php");
			createNavigation("view");
		?>
		<main class="pictureContainer">
			<figure class="mainframe">
				<img src="<?php echo "../../pictures/main/main.png" . "?v=" . filemtime("../../pictures/main/main.png"); ?>" id="main-picture" usemap="#link-map"/>
				<?php
					require_once("../../server/view/createLinks.php");
					createLinks();
				?>
			</figure>
		</main>
    </body>
</html>