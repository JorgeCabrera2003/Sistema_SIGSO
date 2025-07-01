<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/marca.php";


	$titulo = "Gestionar Marcas";
	$cabecera = array('#', "Nombre", "Modificar/Eliminar");

	$marca = new Marca();

	if (!isset($permisos['marca']['ver']['estado']) || $permisos['marca']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Marca";
		Bitacora($msg, "Marca");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Marca";

		Bitacora($msg, "Marca");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['marca']['registrar']['estado']) && $permisos['marca']['registrar']['estado'] == '1') {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre de la Marca no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				$marca->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "registrar";
				$json = $marca->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva marca";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nueva marca";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar una Marca";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Marca");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $marca->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}


	if (isset($_POST["modificar"])) {
		if (isset($permisos['marca']['modificar']['estado']) && $permisos['marca']['modificar']['estado'] == '1') {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_marca"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Marca no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre de la Marca no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$marca->set_id($_POST["id_marca"]);
				$marca->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "actualizar";
				$json = $marca->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro de la marca con el id: ". $_POST["id_marca"];
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar marca";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar una Marca";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Marca");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['marca']['eliminar']['estado']) && $permisos['marca']['eliminar']['estado'] == '1') {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_marca"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Marca no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				$marca->set_id($_POST["id_marca"]);
				$peticion["peticion"] = "eliminar";
				$json = $marca->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó una marca con el id: ". $_POST["id_marca"];
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar una marca";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar una Marca";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Marca");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}