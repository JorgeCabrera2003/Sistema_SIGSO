<?php 


	
	if (!$_SESSION) {

		header("Location: ?page=login");
		$msg["danger"] = "Sesion Finalizada.";

	}

	ob_start();

	if (is_file("view/".$page.".php")) {

		require_once "controller/utileria.php";
		
		$titulo = "Ayuda";
		$css = ["ayuda"];

		$datos = $_SESSION['user'];

		ob_clean();

		require_once "view/$page.php";

	} else {

		require_once "view/404.php";

	}

 ?>