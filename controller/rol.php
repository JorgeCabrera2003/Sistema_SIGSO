<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/permiso.php";
	require_once "model/rol.php";

	$titulo = "Gestionar Roles y Permisos";
	$cabecera = array('#', "Nombre", "Modificar/Eliminar");

	$rol = new Rol();
	$permiso = new Permiso();

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Roles y Permisos";

		Bitacora($msg, "Rol y Permiso");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['rol']['registrar']['estado']) && $permisos['rol']['registrar']['estado'] === "1") {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Rol no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$rol->set_nombre($_POST["nombre"]);
				$rol->ObjPermiso($permiso);
				$peticion["peticion"] = "registrar";
				$peticion["permisos"] = json_decode($_POST['datos']);
				$json_rol = $rol->Transaccion($peticion);


				if ($json_rol['estado'] == 1) {
					$peticion["permisos"] = json_decode($_POST['datos']);
					$peticion["peticion"] = "cargar_permiso";
					$id_fila = $rol->Transaccion(['peticion' => 'ultimo_id']);
					$permiso->set_id_rol($id_fila['id_rol']);
					$json = $permiso->Transaccion($peticion);
					if ($json['estado'] == 1) {
						$json['icon'] = "success";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo rol";
						$json['mensaje'] = "Se registró un nuevo rol";

					} else {
						$json['icon'] = "warning";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo rol, (error en los permisos)";
						$json['mensaje'] = "Se registró un nuevo rol, (error en los permisos)";
					}
					$json['resultado'] = "registrar";

				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registró rol";
				}
			}

		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Rol";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Rol y Permiso");
		exit;
	}


	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $rol->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}


	if (isset($_POST['filtrar_permiso'])) {
		$peticion["peticion"] = "filtrar_permiso";
		$peticion['parametro'] = $_POST['parametro'];
		$rol->set_id($_POST["id_rol"]);
		$rol->ObjPermiso($permiso);
		$json = $rol->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['rol']['modificar']['estado']) && $permisos['rol']['modificar']['estado'] === "1") {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_rol"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Rol no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$rol->set_id($_POST["id_rol"]);
				$rol->set_nombre($_POST["nombre"]);
				$peticion["peticion"] = "actualizar";
				$json_rol = $rol->Transaccion($peticion);

				if ($json_rol['estado'] == 1) {
					$peticion["permisos"] = json_decode($_POST['datos']);
					$peticion["peticion"] = "cargar_permiso";
					$permiso->set_id_rol($_POST["id_rol"]);
					$json = $permiso->Transaccion($peticion);
					if ($json['estado'] == 1) {
						$json['icon'] = "success";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó un rol";
						$json['mensaje'] = "Se modificó un rol exitosamente";


					} else {
						$json['icon'] = "warning";
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó un rol, (error en los permisos)";
						$json['mensaje'] = "Se modificó rol, (error en los permisos)";
					}
					$json['resultado'] = "modificar";

				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar rol";
				}
			}

		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar un Rol";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Rol y Permiso");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['rol']['modificar']['estado']) && $permisos['rol']['modificar']['estado'] === "1") {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_ente"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

			} else {
				$rol->set_id($_POST["id_rol"]);
				$peticion["peticion"] = "eliminar";
				$json = $rol->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un rol";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un rol";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar un Rol";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Rol y Permiso");
		exit;
	}

	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
?>