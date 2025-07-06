<?php 
	
	if (!$_SESSION) {
		echo'<script>window.location="?page=login"</script>';
		$msg["danger"] = "Sesion Finalizada.";
	}
	ob_start();
	if (is_file("view/".$page.".php")) {
		require_once "controller/utileria.php";
		// Estilos de Pagina
		$titulo = "Ayuda";
		$css = ["ayuda"];

		$datos = $_SESSION['user'];
		ob_clean();
		require_once "view/$page.php";
	} else {
		require_once "view/404.php";
	}
 ?>