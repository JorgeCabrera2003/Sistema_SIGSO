<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/ente.php";

	$titulo = "Entes";
	$cabecera = array('#', "Nombre", "Responsable", "Teléfono", "Ubicación", "Tipo de Ente", "Modificar/Eliminar");

	$ente = new Ente();

	if (!isset($permisos['ente']['ver']['estado']) || $permisos['ente']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Ente";
		Bitacora($msg, "Ente");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Ente";
		echo json_encode($json);

		Bitacora($msg, "Ente");
		exit;
	}

	if (isset($_POST["registrar"])) {

		if (isset($permisos['ente']['registrar']['estado']) && $permisos['ente']['registrar']['estado'] == '1') {

			if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalLargo'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["direccion"]) || preg_match(c_regex['Nombre_Descripcion'], $_POST["direccion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dirección no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["responsable"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["responsable"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Responsable no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["tipo_ente"]) || preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{4,20}$/", $_POST["tipo_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$ente->set_id(generarID($_POST["nombre"]));
				$ente->set_nombre($_POST["nombre"]);
				$ente->set_direccion($_POST["direccion"]);
				$ente->set_telefono($_POST["telefono"]);
				$ente->set_responsable($_POST["responsable"]);
				$ente->set_tipo_ente($_POST['tipo_ente']);
				$peticion["peticion"] = "registrar";
				$json = $ente->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo ente con ID:" . $ente->get_id();
					$msgN = "Se registró un Nuevo Ente";
					NotificarUsuarios($msgN, "Ente", ['modulo' => 'ENTE000920251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo ente";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Ente";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";

		}
		echo json_encode($json);
		Bitacora($msg, "Ente");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $ente->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["consultar_eliminadas"])) {
		$peticion["peticion"] = "consultar_eliminadas";
		$json = $ente->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['ente']['modificar']['estado']) && $permisos['ente']['modificar']['estado'] == '1') {

			if (!isset($_POST["id_ente"]) || preg_match(c_regex['ID_Generado'], $_POST["id_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalLargo'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["direccion"]) || preg_match(c_regex['Nombre_Descripcion'], $_POST["direccion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dirección no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["responsable"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["responsable"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Responsable no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (!isset($_POST["tipo_ente"]) || preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{4,20}$/", $_POST["tipo_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				$ente->set_id($_POST["id_ente"]);
				$ente->set_nombre($_POST["nombre"]);
				$ente->set_direccion($_POST["direccion"]);
				$ente->set_telefono($_POST["telefono"]);
				$ente->set_responsable($_POST["responsable"]);
				$ente->set_tipo_ente($_POST['tipo_ente']);
				$peticion["peticion"] = "actualizar";
				$json = $ente->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó del ente con el id:" . $_POST["id_ente"];
					$msgN = "Ente con ID: " . $_POST["id_ente"] . " fue modificado";
					NotificarUsuarios($msgN, "Ente", ['modulo' => 'ENTE000920251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar Ente";
				}

			}
		} else {

			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar un Ente";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";

		}
		echo json_encode($json);
		Bitacora($msg, "Ente");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['ente']['eliminar']['estado']) && $permisos['ente']['eliminar']['estado'] == '1') {

			if (!isset($_POST["id_ente"]) || preg_match(c_regex['ID_Generado'], $_POST["id_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				$ente->set_id($_POST["id_ente"]);
				$peticion["peticion"] = "eliminar";
				$json = $ente->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un ente con el id:" . $_POST["id_ente"];
					$msgN = "Unidad con Ente: " . $_POST["id_ente"] . " fue eliminado";
					NotificarUsuarios($msgN, "Ente", ['modulo' => 'ENTE000920251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Ente";
				}
			}
		} else {

			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar un Ente";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";

		}
		echo json_encode($json);
		Bitacora($msg, "Ente");
		exit;
	}

	if (isset($_POST["reactivar"])) {
		if (isset($permisos['ente']['reactivar']['estado']) && $permisos['ente']['reactivar']['estado'] == '1') {
			if (!isset($_POST["id_ente"]) || preg_match(c_regex['ID_Generado'], $_POST["id_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id del Categoria no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$ente->set_id($_POST["id_ente"]);
				$peticion["peticion"] = "reactivar";
				$json = $ente->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un Ente con el id: " . $_POST["id_ente"];
					$msgN = "Se restauró un Ente con el id" . $_POST["id_ente"];
					NotificarUsuarios($msgN, "Ente", ['modulo' => 'ENTE000920251001', 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar un Ente";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para reactivar un Ente";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Ente");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
