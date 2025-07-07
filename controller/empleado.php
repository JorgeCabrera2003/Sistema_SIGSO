<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/empleado.php";
	require_once "model/cargo.php";
	require_once "model/unidad.php";
	require_once "model/dependencia.php";

	$titulo = "Gestionar Empleados";
	$cabecera = array('Cédula', "Nombre", "Apellido", "Teléfono", "Correo", "Dependencia", "Unidad", "Cargo", "Modificar/Eliminar");

	$empleado = new Empleado();
	$cargo = new Cargo();
	$unidad = new Unidad();
	$dependencia = new Dependencia();


	if (!isset($permisos['empleado']['ver']['estado']) || $permisos['empleado']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Empleado";
		Bitacora($msg, "Empleado");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Empleado";

		Bitacora($msg, "Empleado");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['empleado']['registrar']['estado']) && $permisos['empleado']['registrar']['estado'] == '1') {
			if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
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
				$validarUsuario = NULL;

				$empleado->set_cedula($_POST["cedula"]);
				$empleado->set_nombre($_POST["nombre"]);
				$empleado->set_apellido($_POST["apellido"]);
				$empleado->set_correo($_POST["correo"]);
				$empleado->set_telefono($_POST["telefono"]);
				$empleado->set_id_unidad($_POST["unidad"]);
				$empleado->set_id_cargo($_POST["cargo"]);
				$peticion["peticion"] = "registrar";
				$usuario->set_cedula($_POST["cedula"]);
				$validarEmpleado = $empleado->Transaccion(['peticion' => 'validar']);
				$validarUsuario = $usuario->Transaccion(['peticion' => 'validar']);

				if ($json['estado'] == 1) {
					$clave = password_hash($_POST['cedula'], PASSWORD_DEFAULT);

					$usuario->set_cedula($_POST["cedula"]);
					$usuario->set_nombres($_POST["nombre"]);
					$usuario->set_apellidos($_POST["apellido"]);
					$usuario->set_clave($clave);
					$usuario->set_correo($_POST["correo"]);
					$usuario->set_telefono($_POST["telefono"]);
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo empleado";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo empleado";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar Empleado";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Empleado");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		// Si la petición viene del módulo técnico, agrega el filtro
		if (isset($_POST['rol']) && $_POST['rol'] == 'tecnico') {
			$peticion['rol'] = 'tecnico';
		}
		$json = $empleado->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['empleado']['modificar']['estado']) && $permisos['empleado']['modificar']['estado'] == '1') {
			if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
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

				$empleado->set_cedula($_POST["cedula"]);
				$empleado->set_nombre($_POST["nombre"]);
				$empleado->set_apellido($_POST["apellido"]);
				$empleado->set_correo($_POST["correo"]);
				$empleado->set_telefono($_POST["telefono"]);
				$empleado->set_id_unidad($_POST["unidad"]);
				$empleado->set_id_cargo($_POST["cargo"]);
				$peticion["peticion"] = "modificar";
				$json = $empleado->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del empleado";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar empleado";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar Empleado";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		Bitacora($msg, "Empleado");
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['empleado']['eliminar']['estado']) && $permisos['empleado']['eliminar']['estado'] == '1') {
			if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {

				$empleado->set_cedula($_POST["cedula"]);
				$peticion["peticion"] = "eliminar";
				$json = $empleado->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un empleado";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un empleado";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar Empleado";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}


		echo json_encode($json);
		Bitacora($msg, "Empleado");
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

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
