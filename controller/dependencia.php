<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/ente.php";
	require_once "model/dependencia.php";


	$titulo = "Dependencia";
	$cabecera = array('#', "Nombre", "Ente", "Modificar/Eliminar");

	$dependencia = new Dependencia();
	$ente = new Ente();

	if (!isset($permisos['dependencia']['ver']['estado']) || $permisos['dependencia']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Dependencia";
		Bitacora($msg, "Dependencia");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST['cargar_ente'])) {
		$peticion["peticion"] = "consultar";
		$json = $ente->Transaccion($peticion);
		$json['resultado'] = "cargar_ente";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Depedencia";

		Bitacora($msg, "Depedencia");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['dependencia']['registrar']['estado']) && $permisos['dependencia']['registrar']['estado'] == "1") {

			if (!isset($_POST["ente"]) || preg_match(c_regex['ID_Generado'], $_POST["ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else {

				$dependencia->set_id(generarID($_POST["nombre"], $_POST["ente"]));
				$dependencia->set_nombre($_POST["nombre"]);
				$dependencia->set_id_ente($_POST["ente"]);
				$peticion["peticion"] = "registrar";
				$json = $dependencia->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva Depedencia";
					$msgN = "Se registró una Nueva Dependencia";
					NotificarUsuarios($msgN, "Dependencia", ['modulo' => 'DEPEN01020251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo Depedencia";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar una Dependencia";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Depedencia");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $dependencia->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["consultar_eliminadas"])) {
		$peticion["peticion"] = "consultar_eliminadas";
		$json = $dependencia->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['dependencia']['modificar']['estado']) && $permisos['dependencia']['modificar']['estado'] == "1") {
			if (!isset($_POST["ente"]) || preg_match(c_regex['ID_Generado'], $_POST["ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["id_dependencia"]) || preg_match(c_regex['ID_Generado'], $_POST["id_dependencia"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else {

				$dependencia->set_id($_POST["id_dependencia"]);
				$dependencia->set_nombre($_POST["nombre"]);
				$dependencia->set_id_ente($_POST["ente"]);
				$peticion["peticion"] = "actualizar";
				$json = $dependencia->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó la dependencia con el id: " . $_POST["id_dependencia"];
					$msgN = "Dependencia con ID: " . $_POST["id_dependencia"] . " fue modificado";
					NotificarUsuarios($msgN, "Dependencia", ['modulo' => 'DEPEN01020251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar Depedencia";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar una Dependencia";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}

		echo json_encode($json);

		Bitacora($msg, "Depedencia");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['dependencia']['eliminar']['estado']) && $permisos['dependencia']['eliminar']['estado'] == "1") {
			if (!isset($_POST["id_dependencia"]) || preg_match(c_regex['ID_Generado'], $_POST["id_dependencia"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$dependencia->set_id($_POST["id_dependencia"]);
				$peticion["peticion"] = "eliminar";
				$json = $dependencia->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó la dependencia con el id:" . $_POST["id_dependencia"];
					$msgN = "Dependencia con ID: " . $_POST["id_dependencia"] . " fue eliminada";
					NotificarUsuarios($msgN, "Dependencia", ['modulo' => 'DEPEN01020251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar una Depedencia";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar una Dependencia";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}

		echo json_encode($json);
		Bitacora($msg, "Depedencia");
		exit;
	}

	if (isset($_POST["reactivar"])) {
		if (isset($permisos['dependencia']['reactivar']['estado']) && $permisos['dependencia']['reactivar']['estado'] == '1') {
			if (!isset($_POST["id_dependencia"]) || preg_match(c_regex['ID_Generado'], $_POST["id_dependencia"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Depedencia no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$dependencia->set_id($_POST["id_dependencia"]);
				$peticion["peticion"] = "reactivar";
				$json = $dependencia->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se reactivó una Depedencia con el id: " . $_POST["id_dependencia"];
					$msgN = "Se reactivó una Depedencia con el id" . $_POST["id_dependencia"];
					NotificarUsuarios($msgN, "Dependencia", ['modulo' => 'DEPEN01020251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar una Depedencia";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para reactivar una Depedencia";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Depedencia");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}