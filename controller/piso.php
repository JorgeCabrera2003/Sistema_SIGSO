<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/piso.php";

	$titulo = "Gestionar Pisos";
	$cabecera = array("ID Piso", "Piso", "Nro de Piso", "Modificar/Eliminar");

	$piso = new Piso();

	if (!isset($permisos['piso']['ver']['estado']) || $permisos['piso']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Piso";
		Bitacora($msg, "Piso");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$peticion['peticion'] = "registrar";
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Piso";
		Bitacora($msg, "Piso");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['piso']['registrar']['estado']) && $permisos['piso']['registrar']['estado'] == "1") {
			if (preg_match("/^[a-z A-Záéíóúü]{4,45}$/", $_POST["tipo_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de Piso no válido no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["tipo_piso"] != 'Planta Baja' && $_POST["tipo_piso"] != 'Piso' && $_POST["tipo_piso"] != 'Sótano' && $_POST["tipo_piso"] != 'Terraza') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9]{1,2}$/", $_POST["nro_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Número de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["nro_piso"] == 0 && $_POST["tipo_piso"] != 'Planta Baja') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Solo Planta Baja empieza en 0";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["nro_piso"] != 0 && $_POST["tipo_piso"] == 'Planta Baja') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Solo Planta Baja empieza en 0";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$piso->set_tipo($_POST["tipo_piso"]);
				$piso->set_nro_piso($_POST["nro_piso"]);
				$peticion["peticion"] = "registrar";
				$json = $piso->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo piso";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo piso";
				}
			}

		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Piso";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Piso");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		echo json_encode($piso->Transaccion($peticion));
		exit;
	}


	if (isset($_POST["modificar"])) {
		if (isset($permisos['piso']['modificar']['estado']) && $permisos['piso']['modificar']['estado'] == "1") {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["nro_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[a-z A-Záéíóúü]{4,45}$/", $_POST["tipo_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["tipo_piso"] != 'Planta Baja' && $_POST["tipo_piso"] != 'Piso' && $_POST["tipo_piso"] != 'Sótano' && $_POST["tipo_piso"] != 'Terraza') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Tipo de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9]{1,2}$/", $_POST["nro_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Número de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["nro_piso"] == 0 && $_POST["tipo_piso"] != 'Planta Baja') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Solo Planta Baja empieza en 0";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if ($_POST["nro_piso"] != 0 && $_POST["tipo_piso"] == 'Planta Baja') {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Solo Planta Baja empieza en 0";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$piso->set_id($_POST["id_piso"]);
				$piso->set_tipo($_POST["tipo_piso"]);
				$piso->set_nro_piso($_POST["nro_piso"]);
				$peticion["peticion"] = "actualizar";
				$json = $piso->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del piso con el id:" . $_POST["id_piso"];
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar piso";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar un Piso";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Piso");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['piso']['eliminar']['estado']) && $permisos['piso']['eliminar']['estado'] == "1") {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["nro_piso"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de Piso no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				$piso->set_id($_POST["id_piso"]);
				$peticion["peticion"] = "eliminar";
				$json = $piso->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un piso con el id: " . $_POST["id_piso"];
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un piso";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar un Piso";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";

		}
		echo json_encode($json);
		Bitacora($msg, "Piso");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}
	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
