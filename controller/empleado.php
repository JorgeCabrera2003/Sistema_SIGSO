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
	require_once "model/usuario.php";

	$titulo = "Gestionar Empleados";
	$cabecera = array('Cédula', "Nombre", "Apellido", "Teléfono", "Correo", "Dependencia", "Unidad", "Cargo", "Modificar/Eliminar");

	$empleado = new Empleado();
	$cargo = new Cargo();
	$unidad = new Unidad();
	$dependencia = new Dependencia();
	$ente = new Ente();
	$usuario = new Usuario();

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

	if (isset($_POST['permisos_modulo'])) {
		$json['resultado'] = 'permisos_modulo';
		$json['permisos'] = $permisos;
		echo json_encode($json);
		exit;
	}

	// === NUEVAS PETICIONES PARA CARGAR DATOS INICIALES ===
	if (isset($_POST["cargar_ente"])) {
		$resultado = $ente->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["cargar_cargo"])) {
		$resultado = $cargo->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["cargar_dependencia"])) {
		if (isset($_POST["id_ente"]) && $_POST["id_ente"] !== '' && $_POST["id_ente"] !== 'default' && $_POST["id_ente"] !== 'null') {
			// Evitar pasar valores literales como 'default' o 'null' al modelo
			$dependencia->set_id_ente($_POST["id_ente"]);
			$resultado = $dependencia->Transaccion(['peticion' => 'consultar_por_ente']);
		} else {
			$resultado = ['resultado' => 'error', 'mensaje' => 'ID de ente no proporcionado o no válido'];
		}
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["cargar_unidad"])) {
		if (isset($_POST["id_dependencia"])) {
			$unidad->set_id_dependencia($_POST["id_dependencia"]);
			$resultado = $unidad->Transaccion(['peticion' => 'consultar_por_dependencia']);
		} else {
			$resultado = ['resultado' => 'error', 'mensaje' => 'ID de dependencia no proporcionado'];
		}
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['empleado']['registrar']['estado']) && $permisos['empleado']['registrar']['estado'] == '1') {
			$cedula = "";
			if (isset($_POST["cedula"]) && isset($_POST["particle"])) {
				$cedula = $_POST["particle"] . "" . $_POST["cedula"];
			}

			// Validaciones mejoradas adaptadas del módulo de bien
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
			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["correo"]) || preg_match(c_regex['Correo'], $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["cargo"]) || $_POST["cargo"] == "default") {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["unidad"]) || $_POST["unidad"] == "default") {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Unidad no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$empleado->set_cedula($cedula);
				$empleado->set_nombre($_POST["nombre"]);
				$empleado->set_apellido($_POST["apellido"]);
				$empleado->set_telefono($_POST["telefono"]);
				$empleado->set_correo($_POST["correo"]);
				$empleado->set_id_cargo($_POST["cargo"]);
				$empleado->set_id_unidad($_POST["unidad"]);

				$resultado = $empleado->Transaccion(['peticion' => 'registrar']);

				if ($resultado['estado'] == 1) {
					// CORRECCIÓN COMPLETA: Crear usuario automáticamente para el empleado
					$usuario->set_cedula($cedula);
					$usuario->set_nombre_usuario($cedula);
					$usuario->set_nombres($_POST["nombre"]); // AÑADIDO: Establecer nombres
					$usuario->set_apellidos($_POST["apellido"]); // AÑADIDO: Establecer apellidos
					$usuario->set_telefono($_POST["telefono"]); // AÑADIDO: Establecer teléfono
					$usuario->set_correo($_POST["correo"]); // AÑADIDO: Establecer correo
					$usuario->set_clave(password_hash($cedula, PASSWORD_DEFAULT)); // Contraseña inicial = cédula
					$usuario->set_rol('SOLIC00520251001'); // Rol por defecto
					$usuario->set_tema(0);

					$crearUsuario = $usuario->Transaccion(['peticion' => 'registrar']);

					// DEBUG: Verificar qué devuelve la creación del usuario
					error_log("Resultado creación usuario: " . print_r($crearUsuario, true));

					if (isset($crearUsuario['estado']) && $crearUsuario['estado'] == 1) {
						$json['resultado'] = "registrar";
						$json['mensaje'] = $resultado['mensaje'] . " y se creó su usuario automáticamente";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), registró al empleado " . $cedula . " y creó su usuario";
					} else {
						// Si no se pudo crear el usuario, igualmente se registró el empleado
						$json['resultado'] = "registrar";
						$json['mensaje'] = $resultado['mensaje'];
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), registró al empleado " . $cedula . " pero falló la creación del usuario: " . ($crearUsuario['mensaje'] ?? 'Error desconocido');
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = $resultado['mensaje'];
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó registrar al empleado " . $cedula;
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "No tiene permisos para registrar empleados";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó registrar un empleado sin permisos";
		}

		Bitacora($msg, "Empleado");
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['empleado']['modificar']['estado']) && $permisos['empleado']['modificar']['estado'] == '1') {
			$cedula = "";
			if (isset($_POST["cedula"]) && isset($_POST["particle"])) {
				$cedula = $_POST["particle"] . "" . $_POST["cedula"];
			}

			// Validaciones mejoradas
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
			} else if (!isset($_POST["telefono"]) || preg_match(c_regex['Telefono'], $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["correo"]) || preg_match(c_regex['Correo'], $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["cargo"]) || $_POST["cargo"] == "default") {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cargo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else if (!isset($_POST["unidad"]) || $_POST["unidad"] == "default") {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Unidad no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$empleado->set_cedula($cedula);
				$empleado->set_nombre($_POST["nombre"]);
				$empleado->set_apellido($_POST["apellido"]);
				$empleado->set_telefono($_POST["telefono"]);
				$empleado->set_correo($_POST["correo"]);
				$empleado->set_id_cargo($_POST["cargo"]);
				$empleado->set_id_unidad($_POST["unidad"]);

				$resultado = $empleado->Transaccion(['peticion' => 'modificar']);

				if ($resultado['estado'] == 1) {
					$json['resultado'] = "modificar";
					$json['mensaje'] = $resultado['mensaje'];
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), modificó al empleado " . $cedula;
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = $resultado['mensaje'];
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó modificar al empleado " . $cedula;
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "No tiene permisos para modificar empleados";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó modificar un empleado sin permisos";
		}

		Bitacora($msg, "Empleado");
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['empleado']['eliminar']['estado']) && $permisos['empleado']['eliminar']['estado'] == '1') {
			$cedula = $_POST["cedula"];

			if (preg_match(c_regex['Cedula'], $cedula) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$empleado->set_cedula($cedula);
				$resultado = $empleado->Transaccion(['peticion' => 'eliminar']);

				if ($resultado['estado'] == 1) {
					// CORRECCIÓN: Eliminar usuario asociado (eliminación lógica)
					$usuario->set_cedula($cedula);
					$eliminarUsuario = $usuario->Transaccion(['peticion' => 'eliminar']);

					if (isset($eliminarUsuario['estado']) && $eliminarUsuario['estado'] == 1) {
						$json['resultado'] = "eliminar";
						$json['mensaje'] = $resultado['mensaje'] . " y se eliminó su usuario automáticamente";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), eliminó al empleado " . $cedula . " y su usuario";
					} else {
						$json['resultado'] = "eliminar";
						$json['mensaje'] = $resultado['mensaje'] . " (pero no se pudo eliminar el usuario: " . ($eliminarUsuario['mensaje'] ?? 'Error desconocido') . ")";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), eliminó al empleado " . $cedula . " pero falló la eliminación del usuario: " . ($eliminarUsuario['mensaje'] ?? 'Error desconocido');
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = $resultado['mensaje'];
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó eliminar al empleado " . $cedula;
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "No tiene permisos para eliminar empleados";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó eliminar un empleado sin permisos";
		}

		Bitacora($msg, "Empleado");
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["reactivar"])) {
		if (isset($permisos['empleado']['reactivar']['estado']) && $permisos['empleado']['reactivar']['estado'] == '1') {
			$cedula = $_POST["cedula"];

			if (preg_match(c_regex['Cedula'], $cedula) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";
			} else {
				$empleado->set_cedula($cedula);
				$resultado = $empleado->Transaccion(['peticion' => 'reactivar']);

				if ($resultado['estado'] == 1) {
					// CORRECCIÓN: Reactivar usuario asociado
					$usuario->set_cedula($cedula);
					$reactivarUsuario = $usuario->Transaccion(['peticion' => 'reactivar']);

					if (isset($reactivarUsuario['estado']) && $reactivarUsuario['estado'] == 1) {
						$json['resultado'] = "reactivar";
						$json['mensaje'] = $resultado['mensaje'] . " y se reactivó su usuario automáticamente";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), reactivó al empleado " . $cedula . " y su usuario";
					} else {
						$json['resultado'] = "reactivar";
						$json['mensaje'] = $resultado['mensaje'] ;
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), reactivó al empleado " . $cedula . ($reactivarUsuario['mensaje'] ?? 'Error desconocido');
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = $resultado['mensaje'];
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó reactivar al empleado " . $cedula;
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "No tiene permisos para reactivar empleados";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó reactivar un empleado sin permisos";
		}

		Bitacora($msg, "Empleado");
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["consultar"])) {
		$resultado = $empleado->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_eliminadas"])) {
		$resultado = $empleado->Transaccion(['peticion' => 'consultar_eliminadas']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_cargos"])) {
		$resultado = $cargo->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_unidades"])) {
		$resultado = $unidad->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_dependencias"])) {
		$resultado = $dependencia->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_entes"])) {
		$resultado = $ente->Transaccion(['peticion' => 'consultar']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["consultar_empleado"])) {
		$cedula = $_POST["cedula"];
		$empleado->set_cedula($cedula);
		$resultado = $empleado->Transaccion(['peticion' => 'validar']);

		// CORRECCIÓN: Incluir datos de ente, dependencia y unidad
		if ($resultado['bool'] == 1) {
			$datosCompletos = $empleado->Transaccion(['peticion' => 'consultar_por_cedula', 'cedula' => $cedula]);
			if (isset($datosCompletos['datos'][0])) {
				$resultado['arreglo'] = array_merge($resultado['arreglo'], $datosCompletos['datos'][0]);
			}
		}

		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["listar_tecnicos"])) {
		$resultado = $empleado->Transaccion(['peticion' => 'listar_tecnicos']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["contar_empleados"])) {
		$resultado = $empleado->Transaccion(['peticion' => 'contar_empleados']);
		echo json_encode($resultado);
		exit;
	}

	if (isset($_POST["tecnicos_por_area_rendimiento"])) {
		$area_id = $_POST["area_id"];
		$resultado = $empleado->Transaccion(['peticion' => 'tecnicos_por_area_rendimiento', 'area_id' => $area_id]);
		echo json_encode($resultado);
		exit;
	}

	require_once "view/" . $page . ".php";
} else {
	echo "Página no encontrada";
}
