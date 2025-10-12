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
	require_once "model/ente.php";


	$titulo = "Gestionar Empleados";
	$cabecera = array('Cédula', "Nombre", "Apellido", "Teléfono", "Correo", "Dependencia", "Unidad", "Cargo", "Modificar/Eliminar");

	$empleado = new Empleado();
	$cargo = new Cargo();
	$unidad = new Unidad();
	$dependencia = new Dependencia();
	$ente = new Ente();


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
			$cedula = "";
			if (isset($_POST["cedula"]) && isset($_POST["particle"])) {
				$cedula = $_POST["particle"] . "" . $_POST["cedula"];
			}
			if (preg_match(c_regex['Cedula'], $cedula) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["apellido"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["apellido"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Apellido no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["correo"]) || preg_match(c_regex['Correo'], $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["unidad"]) || preg_match(c_regex['ID_Generado'], $_POST["unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Unidad no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["cargo"]) || preg_match(c_regex['ID_Generado'], $_POST["cargo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id del Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$empleado->set_correo($_POST["correo"]);
				$usuario->set_correo($_POST["correo"]);

				$usuario->set_cedula($cedula);
				$validarUsuario = $usuario->Transaccion(['peticion' => 'validar']);


				$empleado->set_cedula($cedula);
				$validarEmpleado = $empleado->Transaccion(['peticion' => 'validar']);

				if ($validarUsuario['bool'] == 0 && $validarEmpleado['bool'] == 0) {

					$empleado->set_nombre($_POST["nombre"]);
					$empleado->set_apellido($_POST["apellido"]);
					$empleado->set_telefono($_POST["telefono"]);
					$empleado->set_id_unidad($_POST["unidad"]);
					$empleado->set_id_cargo($_POST["cargo"]);

					$clave = password_hash($cedula, PASSWORD_DEFAULT);
					$usuario->set_nombre_usuario($cedula);
					$usuario->set_rol('SOLIC00520251001');
					$usuario->set_nombres($_POST["nombre"]);
					$usuario->set_apellidos($_POST["apellido"]);
					$usuario->set_correo($_POST["correo"]);
					$usuario->set_telefono($_POST["telefono"]);
					$usuario->set_clave($clave);

					$peticion["peticion"] = "registrar";
					$json = $empleado->Transaccion($peticion);

					if ($json['estado'] == 1) {
						$usuario->Transaccion(['peticion' => 'registrar']);
						$json['resultado'] = "registrar";
						$json['mensaje'] = "Se registró el empleado exitosamente";
						$msgN = "Se registró un Nuevo Empleado";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo empleado";

						NotificarUsuarios($msgN, "Empleado", ['modulo' => 'EMPLE00520251001', 'accion' => 'ver']);
					} else {
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo empleado";
					}

				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = "Error, Empleado ya registrado";
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), empleado y/o empleado registrado";
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
			$cedula = "";
			if (isset($_POST["cedula"]) && isset($_POST["particle"])) {
				$cedula = $_POST["particle"] . "" . $_POST["cedula"];
			}
			if (preg_match(c_regex['Cedula'], $cedula) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["apellido"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["apellido"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Apellido no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["correo"]) || preg_match(c_regex['Correo'], $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["unidad"]) || preg_match(c_regex['ID_Generado'], $_POST["unidad"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id de la Unidad no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (!isset($_POST["cargo"]) || preg_match(c_regex['ID_Generado'], $_POST["cargo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id del Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {

				$usuario->set_cedula($_POST["cedula"]);
				$validarUsuario = $usuario->Transaccion(['peticion' => 'validar']);

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
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó del empleado con la CI: " . $_POST['cedula'];

					if ($validarUsuario['bool'] == 1) {
						$usuario->set_nombres($_POST["nombre"]);
						$usuario->set_apellidos($_POST["apellido"]);
						$usuario->set_correo($_POST["correo"]);
						$usuario->set_telefono($_POST["telefono"]);
						$estado = $usuario->Transaccion(['peticion' => 'modificar_empleado']);

						if ($estado) {
							$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del usuario empleado con la CI: " . $_POST['cedula'];
							$msgN = "Se modificó un Empleado con la Cédula: " . $_POST['cedula'];
							NotificarUsuarios($msgN, "Empleado", ['modulo' => 'EMPLE00520251001', 'accion' => 'ver']);
						} else {
							$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar al usuario empleado";
						}
					}
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
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un empleado con la CI: " . $_POST['cedula'];
					$msgN = "Se eliminó un Empleado con la Cédula: " . $_POST['cedula'];
					NotificarUsuarios($msgN, "Empleado", ['modulo' => 'EMPLE00520251001', 'accion' => 'ver']);
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

	if (isset($_POST['buscar_usuario'])) {
		if (!isset($_POST["cedula"]) || preg_match(c_regex['Cedula'], $_POST["cedula"]) == 0) {
			$json["resultado"] = "error";
			$json["mensaje"] = "Cédula no válida";
		} else {
			$peticion["peticion"] = "validar";
			$empleado->set_cedula($_POST["cedula"]);
			$json['empleado'] = $empleado->Transaccion($peticion);
			$json['unidad'] = NULL;
			$json['dependencia'] = NULL;
			$json['ente'] = NULL;

			if ($json['empleado']['bool'] == 1) {
				$unidad->set_id($json['empleado']['arreglo']['id_unidad']);
				$json['unidad'] = $unidad->Transaccion($peticion);
				if ($json['unidad']['bool'] == 1) {
					$dependencia->set_id($json['unidad']['arreglo']['id_dependencia']);
					$json['dependencia'] = $dependencia->Transaccion($peticion);
					if ($json['dependencia']['bool'] == 1) {
						$ente->set_id($json['dependencia']['arreglo']['id_ente']);
						$json['ente'] = $ente->Transaccion($peticion);
					}
				}
			}
			$json["resultado"] = "buscar_usuario";
		}

		echo json_encode($json);
		exit;
	}

	if (isset($_POST['cargar_ente'])) {
		$peticion["peticion"] = "filtrar";
		$json = $ente->Transaccion($peticion);
		$json["resultado"] = "cargar_ente";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST['cargar_dependencia'])) {
		$peticion["peticion"] = "filtrar";
		$dependencia->set_id_ente($_POST['id_ente']);
		$json = $dependencia->Transaccion($peticion);
		$json["resultado"] = "cargar_dependencia";
		echo json_encode($json);
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
