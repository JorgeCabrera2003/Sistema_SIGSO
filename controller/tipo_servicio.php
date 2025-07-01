<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/tipo_servicio.php";


	$titulo = "Gestionar Tipo de Servicio";
	$cabecera = array('#', "Nombre", "Modificar/Eliminar");

	$tipo_servicio = new TipoServicio();


	if (!isset($permisos['tipo_servicio']['ver']['estado']) || $permisos['tipo_servicio']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Tipo de Servicio";
		Bitacora($msg, "Tipo de Servicio");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Tipo de Servicio";

		Bitacora($msg, "Tipo de Servicio");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['tipo_servicio']['registrar']['estado']) && $permisos['tipo_servicio']['registrar']['estado'] == 1) {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$tipo_servicio->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "registrar";
				$json = $tipo_servicio->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de servicio";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo tipo de servicio";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Tipo de Servicio";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Tipo de Servicio");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $tipo_servicio->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}


	if (isset($_POST["modificar"])) {
		if (isset($permisos['tipo_servicio']['modificar']['estado']) && $permisos['tipo_servicio']['modificar']['estado'] == 1) {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_servicio"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$tipo_servicio->set_codigo($_POST["id_servicio"]);
				$tipo_servicio->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "actualizar";
				$json = $tipo_servicio->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del servicio";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar servicio";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar un Tipo de Servicio";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Tipo de Servicio");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['tipo_servicio']['eliminar']['estado']) && $permisos['tipo_servicio']['eliminar']['estado'] == 1) {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_servicio"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$tipo_servicio->set_codigo($_POST["id_servicio"]);
				$peticion["peticion"] = "eliminar";
				$json = $tipo_servicio->Transaccion($peticion);
				
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un servicio";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un servicio";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar un Tipo de Servicio";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Tipo de Servicio");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
