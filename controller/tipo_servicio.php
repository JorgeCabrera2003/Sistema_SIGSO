<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/tipo_servicio.php";
	require_once "model/servicio_prestado.php";
	require_once "model/componente.php";
	require_once "model/empleado.php";


	$titulo = "Gestionar Tipo de Servicio";
	$cabecera = array('#', "Nombre del Servicio", "Cédula", "Nombre del Encargado", "Modificar/Eliminar");

	$tipo_servicio = new TipoServicio();
	$empleado = new Empleado();
	$servicio_prestado = new ServicioPrestado();
	$componente = new Componente();

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

		$arrayServicio = convertirJSON(json_decode($_POST['servicios']));
		$arrayComponente = convertirJSON(json_decode($_POST['componentes']));

		foreach ($arrayServicio as &$keyS) {
			usleep(100000);
			$id = generarID($_POST["nombre"], $keyS["nombre"]);
			$keyS["id"] = $id;
		}

		foreach ($arrayComponente as &$keyC) {
			usleep(100000);
			$id = generarID($_POST["nombre"], $keyC["nombre"]);
			$keyC["id"] = $id;
		}

		if (isset($permisos['tipo_servicio']['registrar']['estado']) && $permisos['tipo_servicio']['registrar']['estado'] == 1) {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["encargado"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Encargado no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$tipo_servicio->set_codigo(generarID($_POST["nombre"]));
				$tipo_servicio->set_nombre($_POST["nombre"]);
				$tipo_servicio->set_encargado($_POST["encargado"]);
				$peticion["peticion"] = "registrar";
				$json = $tipo_servicio->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$contadorS = [];
					$contadorC = [];
					$servicio_prestado->set_id_servicio($tipo_servicio->get_codigo());
					$componente->set_id_servicio($tipo_servicio->get_codigo());
					
					$contadorS = $servicio_prestado->Transaccion(['peticion' => 'cargar', 'servicios' => $arrayServicio]);
					$contadorC = $componente->Transaccion(['peticion' => 'cargar', 'componentes' => $arrayComponente]);

					if($contadorS['total_errores'] > 0 || $contadorS['total_errores']){
						$total = $contadorS['total_errores'] + $contadorS['total_errores'];
						$msg = $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de servicio pero: ".$total."";
					}

					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de servicio";
					$msgN = "Se registró una Nuevo Tipo de Servicio";
					NotificarUsuarios($msgN, "Tipo de Servicio", ['modulo' => 13, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo tipo de servicio";
				}
				$json['resultado'] = "registrar";
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Tipo de Servicio";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		$json['servicios'] = $arrayServicio;
		$json['componentes'] = $arrayComponente;
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

			} else if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["encargado"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Encargado no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$tipo_servicio->set_codigo($_POST["id_servicio"]);
				$tipo_servicio->set_nombre($_POST["nombre"]);
				$tipo_servicio->set_encargado($_POST["encargado"]);
				$peticion["peticion"] = "actualizar";
				$json = $tipo_servicio->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del servicio con el id:" . $_POST['id_servicio'];
					$msgN = "Cargo con ID: " . $_POST["id_servicio"] . " fue modificado";
					NotificarUsuarios($msgN, "Tipo de Servicio", ['modulo' => 13, 'accion' => 'ver']);
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
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un servicio con el id:" . $_POST['id_servicio'];
					$msgN = "Cargo con ID: " . $_POST["id_servicio"] . " fue eliminado";
					NotificarUsuarios($msgN, "Tipo de Servicio", ['modulo' => 13, 'accion' => 'ver']);
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

	if (isset($_POST['listar_tecnicos'])) {
		$peticion["peticion"] = "listar_tecnicos";
		$json = $empleado->Transaccion($peticion);
		$json['resultado'] = "listar_tecnicos";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["listar_componente"])) {

		$componente->set_id_servicio($_POST['id_servicio']);
		$json = $componente->Transaccion(['peticion' => 'consultar']);
		$json['resultado'] = "listar_componente";

				if (isset($_POST["componente"])) {
			if ($_POST['componente'] == "input" || $_POST["componente"] == "tabla") {
				$json['componente'] = $_POST["componente"];
			} else {
				$json['componente'] = "error";
			}
		}
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["listar_servicio"])) {

		$servicio_prestado->set_id_servicio($_POST['id_servicio']);
		$json = $servicio_prestado->Transaccion(['peticion' => 'consultar']);
		$json['resultado'] = "listar_servicio";

		if (isset($_POST["componente"])) {
			if ($_POST['componente'] == "input" || $_POST["componente"] == "tabla") {
				$json['componente'] = $_POST["componente"];
			} else {
				$json['componente'] = "error";
			}
		}
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
