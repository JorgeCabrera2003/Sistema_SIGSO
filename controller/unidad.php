<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/unidad.php";
	require_once "model/dependencia.php";


	$titulo = "Gestionar Unidades";
	$cabecera = array('#', "Dependencia", "Nombre", "Modificar/Eliminar");

	$unidad = new Unidad();
	$dependencia = new Dependencia();

	if (!isset($permisos['unidad']['ver']['estado']) || $permisos['unidad']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Unidad";
		Bitacora($msg, "Unidad");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Unidad";

		Bitacora($msg, "Unidad");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['unidad']['registrar']['estado']) && $permisos['unidad']['registrar']['estado'] == "1") {
			if (preg_match("/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_dependencia"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dependencia no valida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$unidad->set_id(generarID($_POST["nombre"], $_POST["id_dependencia"]));
				$unidad->set_nombre($_POST["nombre"]);
				$unidad->set_id_dependencia($_POST["id_dependencia"]);
				$peticion["peticion"] = "registrar";
				$json = $unidad->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva unidad con ID: " . $unidad->get_id();
					$msgN = "Se registró una Nueva Unidad";
					NotificarUsuarios($msgN, "Unidad", ['modulo' => 11, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nueva unidad";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar una Unidad";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Unidad");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $unidad->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["consultar_eliminadas"])) {
		$peticion["peticion"] = "consultar_eliminadas";
		$json = $unidad->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['unidad']['modificar']['estado']) && $permisos['unidad']['modificar']['estado'] == "1") {
			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no valido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_dependencia"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dependencia no valida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {

				$unidad->set_id($_POST["id_unidad"]);
				$unidad->set_nombre($_POST["nombre"]);
				$unidad->set_id_dependencia($_POST["id_dependencia"]);
				$peticion["peticion"] = "actualizar";
				$json = $unidad->Transaccion($peticion);

			}

			if ($json['estado'] == 1) {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro de la unidad, id: " . $_POST["id_unidad"];
				$msgN = "Unidad con ID: " . $_POST["id_unidad"] . " fue modificada";
				NotificarUsuarios($msgN, "Unidad", ['modulo' => 11, 'accion' => 'ver']);
			} else {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar unidad, id: " . $_POST["id_unidad"];
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar una Unidad";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Unidad");
		exit;
	}

	if (isset($_POST["restaurar"])) {
		if (isset($permisos['unidad']['restaurar']['estado']) && $permisos['unidad']['restaurar']['estado'] == '1') {
			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Depedencia no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$unidad->set_id($_POST["id_unidad"]);
				$peticion["peticion"] = "restaurar";
				$json = $unidad->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró una Unidad con el id: " . $_POST["id_unidad"];
					$msgN = "Se restauró una Unidad con el id" . $_POST["id_unidad"];
					NotificarUsuarios($msgN, "Unidad", ['modulo' => 11, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al restaurar una Unidad";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para restaurar una Unidad";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'restaurar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Unidad");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['unidad']['modificar']['estado']) && $permisos['unidad']['modificar']['estado'] == "1") {
			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no valido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$unidad->set_id($_POST["id_unidad"]);
				$peticion["peticion"] = "eliminar";
				$json = $unidad->Transaccion($peticion);
				echo json_encode($json);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó una unidad, id: " . $_POST["id_unidad"];
					$msgN = "Unidad con ID: " . $_POST["id_unidad"] . " fue eliminada";
					NotificarUsuarios($msgN, "Unidad", ['modulo' => 11, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar una unidad, id:" . $_POST["id_unidad"];
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar una Unidad";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}

		Bitacora($msg, "Unidad");
		exit;
	}

	if (isset($_POST['cargar_dependencia'])) {
		$peticion["peticion"] = "consultar";
		$json = $dependencia->Transaccion($peticion);
		$json['resultado'] = "consultar_dependencia";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
