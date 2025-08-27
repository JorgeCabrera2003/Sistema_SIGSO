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

			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{10,100}$/", $_POST["direccion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dirección no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7,8}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[a-zA-ZáéíóúüñÑçÇ -.]{4,65}$/", $_POST["responsable"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Responsable no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{4,20}$/", $_POST["tipo_ente"]) == 0) {
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
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo ente con ID:".$ente->get_id();
					$msgN = "Se registró un Nuevo Ente";
					NotificarUsuarios($msgN, "Ente", ['modulo' => 9, 'accion' => 'ver']);
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


	if (isset($_POST["modificar"])) {
		if (isset($permisos['ente']['modificar']['estado']) && $permisos['ente']['modificar']['estado'] == '1') {

			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,90}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Ente no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ\/\-.,# ]{10,100}$/", $_POST["direccion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Dirección no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7,8}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,65}$/", $_POST["responsable"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Responsable no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{4,20}$/", $_POST["tipo_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de no válido";
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
					$msgN = "Unidad con ID: " . $_POST["id_ente"] . " fue modificado";
					NotificarUsuarios($msgN, "Ente", ['modulo' => 9, 'accion' => 'ver']);
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

			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_ente"]) == 0) {
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
					NotificarUsuarios($msgN, "Ente", ['modulo' => 9, 'accion' => 'ver']);
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

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
