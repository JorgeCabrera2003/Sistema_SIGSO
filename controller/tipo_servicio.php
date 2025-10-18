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
	$boolArray = [];

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
			if (!isset($_POST["nombre"]) || preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				if (!isset($_POST["encargado"]) || preg_match(c_regex['Cedula'], $_POST["encargado"]) == 0) {
					$tipo_servicio->set_encargado(NULL);
				} else {
					$tipo_servicio->set_encargado($_POST["encargado"]);
				}

				$tipo_servicio->set_codigo(generarID($_POST["nombre"]));
				$tipo_servicio->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "registrar";
				$json = $tipo_servicio->Transaccion($peticion);

				$boolArray['boolServicio'] = 0;
				$boolArray['boolComponente'] = 0;
				$arrayServicio = convertirJSON(json_decode($_POST['servicios']));
				$arrayComponente = convertirJSON(json_decode($_POST['componentes']));

				foreach ($arrayServicio as &$keyS) {
					$boolArray['boolServicio'] = 1;
					usleep(100000);
					$id = generarID($_POST["nombre"], $keyS["nombre"]);
					$keyS["id"] = $id;
				}

				foreach ($arrayComponente as &$keyC) {
					$boolArray['boolComponente'] = 1;
					usleep(100000);
					$id = generarID($_POST["nombre"], $keyC["nombre"]);
					$keyC["id"] = $id;
				}

				if ($json['estado'] == 1) {
					$contadorS = ['total_errores' => 0];
					$contadorC = ['total_errores' => 0];

					if ($boolArray['boolServicio'] == 1) {
						$servicio_prestado->set_id_servicio($tipo_servicio->get_codigo());
						$contadorS = $servicio_prestado->Transaccion(['peticion' => 'cargar', 'servicios' => $arrayServicio]);
					}

					if ($boolArray['boolComponente'] == 1) {
						$componente->set_id_servicio($tipo_servicio->get_codigo());
						$contadorC = $componente->Transaccion(['peticion' => 'cargar', 'componentes' => $arrayComponente]);
					}

					if ($contadorS['total_errores'] > 0 || $contadorS['total_errores'] > 0) {
						$total = $contadorS['total_errores'] + $contadorS['total_errores'];
						$msg = $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de servicio pero: " . $total . " errores en los items";
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
			if (preg_match(c_regex['ID_Generado'], $_POST["id_servicio"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, ID del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Tipo de Servicio no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {

				if (!isset($_POST["encargado"]) || preg_match(c_regex['Cedula'], $_POST["encargado"]) == 0) {
					$tipo_servicio->set_encargado(NULL);
				} else {
					$tipo_servicio->set_encargado($_POST["encargado"]);
				}

				$tipo_servicio->set_codigo($_POST["id_servicio"]);
				$tipo_servicio->set_nombre($_POST["nombre"]);
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
			if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_servicio"]) == 0) {
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

	if (isset($_POST["cargar_item"])) {
		$boolArray['boolServicio'] = 0;
		$boolArray['boolComponente'] = 0;
		$arrayServicio = convertirJSON(json_decode($_POST['servicios']));
		$arrayComponente = convertirJSON(json_decode($_POST['componentes']));

		foreach ($arrayServicio as &$keyS) {
			$boolArray['boolServicio'] = 1;
			usleep(100000);
			if ($keyS["id"] == NULL) {
				$id = generarID($_POST["nombre"], $keyS["nombre"]);
				$keyS["id"] = $id;
			}
		}

		foreach ($arrayComponente as &$keyC) {
			$boolArray['boolComponente'] = 1;
			usleep(100000);
			if ($keyC["id"] == NULL) {
				$id = generarID($_POST["nombre"], $keyC["nombre"]);
				$keyC["id"] = $id;
			}
		}

		if (isset($permisos['tipo_servicio']['modificar']['estado']) && $permisos['tipo_servicio']['modificar']['estado'] == 1) {
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
					$contadorS = ['total_errores' => 0];
					$contadorC = ['total_errores' => 0];

					if ($boolArray['boolServicio'] == 1) {
						$servicio_prestado->set_id_servicio($tipo_servicio->get_codigo());
						$contadorS = $servicio_prestado->Transaccion(['peticion' => 'cargar', 'servicios' => $arrayServicio]);
					}

					if ($boolArray['boolComponente'] == 1) {
						$componente->set_id_servicio($tipo_servicio->get_codigo());
						$contadorC = $componente->Transaccion(['peticion' => 'cargar', 'componentes' => $arrayComponente]);
					}

					if ($contadorS['total_errores'] > 0 || $contadorS['total_errores'] > 0) {
						$total = $contadorS['total_errores'] + $contadorS['total_errores'];
						$msg = $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de servicio pero: " . $total . "";
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

	if (isset($_POST["configurar_servicio"])) {

		$json['resultado'] = "configurar_servicio";
		if (!isset($_POST["id_servicio"]) || preg_match(c_regex['ID_Generado'], $_POST["id_servicio"]) == 0) {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, ID del Tipo de Servicio no válido";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

		} else if (!isset($_POST["valores"])) {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, Datos no Válidos";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

		} else {
			$arrayDatos = convertirJSON(json_decode($_POST['valores']));
			$i = 0;
			foreach ($arrayDatos as &$key) {
				if ($key["id"] == NULL) {
					$i++;
					$id = generarID($_POST["id_servicio"], $key["nombre"], $i);
					$key["id"] = $id;
				}
			}

			if (isset($_POST["item"])) {

				if ($_POST["item"] == "servicios") {
					$servicio_prestado->set_id_servicio($_POST['id_servicio']);
					$json = $servicio_prestado->Transaccion(['peticion' => 'cargar', 'servicios' => $arrayDatos]);
					$json['resultado'] = "modificar_lista_servicio";
					$json['item'] = "servicio";

					if ($json['total_errores'] > 0) {
						$json['mensaje'] = "Advertencia, algunos registros no se guardaron correctamente: " . $json['total_errores'] . "";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Advertencia, algunos registros no se guardaron correctamente: " . $json['total_errores'] . "";
					} else {
						$json['mensaje'] = "Configuración guardada exitosamente";
					}

				} else if ($_POST["item"] == "componentes") {
					$componente->set_id_servicio($_POST['id_servicio']);
					$json = $componente->Transaccion(['peticion' => 'cargar', 'componentes' => $arrayDatos]);
					$json['resultado'] = "modificar_lista_servicio";
					$json['item'] = "componente";

					if ($json['total_errores'] > 0) {
						$json['mensaje'] = "Advertencia, algunos registros no se guardaron correctamente: " . $json['total_errores'] . "";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Advertencia, algunos registros no se guardaron correctamente: " . $json['total_errores'] . "";
					} else {
						$json['mensaje'] = "Configuración guardada exitosamente";
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = "Error, Parámetro no válido";
				}
				$json['id_servicio'] = $_POST['id_servicio'];
			} else {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Parámetro no válido";
			}
		}
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["eliminar_item"])) {

		if (!isset($_POST["id"]) || preg_match(c_regex['ID_Generado'], $_POST["od"]) == 0) {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, ID no válido";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

		} else {

			if (isset($_POST["tabla"])) {

				if ($_POST["tabla"] == "servicios") {
					$servicio_prestado->set_id($_POST['id']);
					$json = $servicio_prestado->Transaccion(['peticion' => 'eliminar']);
					$json['resultado'] = "eliminar_item";
					$json['item'] = "servicio";

					if ($json['estado'] == 1) {
						$json['mensaje'] = "Error al eliminar el registroc con ID: " . $_POST["id"];
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Advertencia, algunos registros no se guardaron correctamente: " . $_POST["id"] . "";
					} else {
						$json['mensaje'] = "Configuración guardada exitosamente";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), No pudo eliminar el registro: " ;
					}

				} else if ($_POST["tabla"] == "componentes") {
					$componente->set_id($_POST['id']);
					$json = $componente->Transaccion(['peticion' => 'eliminar']);
					$json['resultado'] = "eliminar_item";
					$json['item'] = "componente";

					if ($json['estado'] == 1) {
						$json['mensaje'] = "Error al eliminar el registroc con ID: " . $_POST["id"];
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Advertencia, algunos registros no se guardaron correctamente: " . $_POST["id"] . "";
					} else {
						$json['mensaje'] = "Configuración guardada exitosamente";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), No pudo eliminar el registro: " ;
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = "Error, Parámetro no válido";
				}
			} else {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Parámetro no válido";
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
