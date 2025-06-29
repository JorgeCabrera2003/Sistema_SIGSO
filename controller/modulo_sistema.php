<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/modulo_sistema.php";


	$titulo = "Módulos del Sistema";
	$cabecera = array('#', "Nombre");

	$modulo_sistema = new Modulo_Sistema();

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Módulos del Sistema";

		Bitacora($msg, "Módulo Sistema");
		exit;
	}

	if (isset($_POST["cargar"])) {
		$peticion["peticion"] = "cargar";

		$json = $modulo_sistema->Transaccion($peticion);

		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), cargó los módulos del sistema";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al realizar la carga";
		}

		echo json_encode($json);
		Bitacora($msg, "Módulo Sistema");
		exit;
	}


	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$datos = $modulo_sistema->Transaccion($peticion);
		echo json_encode($datos);
		exit;
	}

	if (isset($_POST["comprobar"])) {
		$peticion["peticion"] = "comprobar";

		$datos = $modulo_sistema->Transaccion($peticion);

		if ($datos['bool']) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), comprobó los módulos del sistema";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al realizar la comprobación";
		}
		echo json_encode($datos);
		Bitacora($msg, "Módulo Sistema");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
?>