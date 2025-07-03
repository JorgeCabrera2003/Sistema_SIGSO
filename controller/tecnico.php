<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/tecnico.php";
	require_once "model/cargo.php";
	require_once "model/unidad.php";
	require_once "model/dependencia.php";
    require_once "model/tipo_servicio.php";

	$titulo = "Gestionar Técnicos";
	$cabecera = array('Cédula', "Nombre", "Apellido", "Teléfono", "Correo", "Dependencia", "Unidad", "Cargo", "Área", "Modificar/Eliminar");

	$tecnico = new tecnico();
	$cargo = new Cargo();
	$unidad = new Unidad();
	$dependencia = new Dependencia();
    $tipo_servicio = new TipoServicio();

	if (!isset($permisos['tecnico']['ver']['estado']) || $permisos['tecnico']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Técnico";
		Bitacora($msg, "Técnico");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Técnico";
		Bitacora($msg, "Técnico");
		exit;
	}

	// Registrar técnico
	if (isset($_POST["registrar"])) {
		if (isset($permisos['tecnico']['registrar']['estado']) && $permisos['tecnico']['registrar']['estado'] == '1') {
			if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/", $_POST["apellido"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Apellido no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[-0-9a-zç_]{4,15}[@]{1}[0-9a-z]{5,10}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Unidad no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["cargo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id del Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$tecnico->set_cedula($_POST["cedula"]);
				$tecnico->set_nombre($_POST["nombre"]);
				$tecnico->set_apellido($_POST["apellido"]);
				$tecnico->set_correo($_POST["correo"]);
				$tecnico->set_telefono($_POST["telefono"]);
				$tecnico->set_id_unidad($_POST["unidad"]);
				$tecnico->set_id_cargo($_POST["cargo"]);
				$tecnico->set_id_servicio($_POST["servicio"]); // <--- área
				$peticion["peticion"] = "registrar";
				$json = $tecnico->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo técnico";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo técnico";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar Técnico";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Técnico");
		exit;
	}

	// Consultar técnicos
	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$peticion["rol"] = "tecnico";
		$json = $tecnico->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	// Modificar técnico
	if (isset($_POST["modificar"])) {
		if (isset($permisos['tecnico']['modificar']['estado']) && $permisos['tecnico']['modificar']['estado'] == '1') {
			if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/", $_POST["apellido"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Apellido no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[-0-9a-zç_]{4,15}[@]{1}[0-9a-z]{5,10}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Unidad no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["cargo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id del Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$tecnico->set_cedula($_POST["cedula"]);
				$tecnico->set_nombre($_POST["nombre"]);
				$tecnico->set_apellido($_POST["apellido"]);
				$tecnico->set_correo($_POST["correo"]);
				$tecnico->set_telefono($_POST["telefono"]);
				$tecnico->set_id_unidad($_POST["unidad"]);
				$tecnico->set_id_cargo($_POST["cargo"]);
				$tecnico->set_id_servicio($_POST["servicio"]); // <--- área
				$peticion["peticion"] = "modificar";
				$json = $tecnico->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del técnico";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar técnico";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar Técnico";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		Bitacora($msg, "Técnico");
		echo json_encode($json);
		exit;
	}

	// Eliminar técnico
	if (isset($_POST["eliminar"])) {
		if (isset($permisos['tecnico']['eliminar']['estado']) && $permisos['tecnico']['eliminar']['estado'] == '1') {
			if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$tecnico->set_cedula($_POST["cedula"]);
				$tecnico->set_id_cargo(1); 
                $peticion["peticion"] = "eliminar";
				$json = $tecnico->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un técnico";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un técnico";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar Técnico";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Técnico");
		exit;
	}

	if (isset($_POST['cargar_unidad'])) {
		$peticion["peticion"] = "filtrar";
		$unidad->set_id_dependencia($_POST['id_dependencia']);
		$json = $unidad->Transaccion($peticion);
		$json["resultado"] = "cargar_unidad";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST['cargar_dependencia'])) {
		$peticion["peticion"] = "consultar";
		$json = $dependencia->Transaccion($peticion);
		$json["resultado"] = "cargar_dependencia";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST['cargar_cargo'])) {
		$peticion["peticion"] = "consultar";
		$json = $cargo->Transaccion($peticion);
		$json["resultado"] = "cargar_cargo";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST['cargar_servicio'])) {
		$peticion["peticion"] = "consultar";
		$json = $tipo_servicio->Transaccion($peticion);
		$json["resultado"] = "cargar_servicio";
		echo json_encode($json);
		exit;
	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
