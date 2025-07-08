<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
}

ob_start();

require_once "controller/utileria.php";
require_once "model/usuario.php";
require_once "model/empleado.php";
require_once "model/rol.php";

$usuario = new Usuario();
$empleado = new Empleado();
$rol = new Rol();

$peticion = [];
$peticion['peticion'] = "permiso";
$peticion['user'] = $_SESSION['user']['rol'];
$peticion['rol'] = 'ADMINISTRADOR';
$permiso = $usuario->Transaccion($peticion);

if (isset($permisos['usuario']['ver']['estado']) && $permisos['usuario']['ver']['estado'] === "0") {
	$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Usuario";
	Bitacora($msg, "Usuario");
	header('Location: ?page=home');
	exit;
}

$cabecera = array("Nombre de Usuario", "Rol", "Cedula", "Nombre", "Apellido", "Teléfono", "Correo", "Modificar/Eliminar");

if (is_file("view/" . $page . ".php")) {
	$titulo = "Usuarios";
	$css = ["alert"];

	$usuario->set_cedula($_SESSION['user']['cedula']);

	$datos = $_SESSION['user'];
	$datos = $datos + $usuario->Transaccion(['peticion' => 'perfil']);

	if (isset($_POST['entrada'])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['usuario']['registrar']['estado']) && $permisos['usuario']['registrar']['estado'] == "1") {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ_]{4,45}$/", $_POST["nombre_usuario"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre de Usuario no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
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

			} else if (preg_match("/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["rol"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Rol no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,]{8,45}$/", $_POST['clave']) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Clave no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if ($_POST['clave'] != $_POST['rclave']) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Clave no coincide";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$empleado->set_cedula($_POST["cedula"]);
				$empleado->set_correo($_POST["correo"]);
				$validarEmpleado = $empleado->Transaccion(['peticion' => 'validar']);
				$usuario->set_cedula($_POST["cedula"]);
				$validarUsuario = $usuario->Transaccion(['peticion' => 'validar']);

				if ($validarUsuario['bool'] == 0 && $validarEmpleado['bool'] == 0) {
					$usuario->set_nombre_usuario($_POST["nombre_usuario"]);
					$usuario->set_nombres($_POST["nombre"]);
					$usuario->set_apellidos($_POST["apellido"]);
					$usuario->set_correo($_POST["correo"]);
					$usuario->set_telefono($_POST["telefono"]);
					$usuario->set_rol($_POST["rol"]);
					$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
					$usuario->set_clave($clave);
					$peticion["peticion"] = "registrar";
					$json = $usuario->Transaccion($peticion);

					if ($json['estado'] == 1) {
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo usuario";

						$empleado->set_nombre($_POST["nombre"]);
						$empleado->set_apellido($_POST["apellido"]);
						$empleado->set_telefono($_POST["telefono"]);
						$estado = $empleado->Transaccion($peticion);

						if ($estado['estado'] == 1) {
							$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo usuario";
						} else {
							$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo usuario, (error en la tabla de empleado)";
						}
						$msgN = "Nuevo usuario creado por " . $_SESSION['user']['nombres'] . " " . $_SESSION['user']['apellidos'] . "(" . $_SESSION['user']['nombre_usuario'] . ")";
						NotificarUsuarios($msgN, "Usuario", ['modulo' => 1, 'accion' => 'ver']);
					} else {
						$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo usuario";
					}
				} else {
					$json['resultado'] = "error";
					$json['mensaje'] = "Error, Empleado ya registrado";
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), empleado y/o empleado registrado";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar un Usuario";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}

		echo json_encode($json);
		Bitacora($msg, "Usuario");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $usuario->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['usuario']['modificar']['estado']) && $permisos['usuario']['modificar']['estado'] == "1") {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ_]{4,45}$/", $_POST["nombre_usuario"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre de Usuario no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
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

			} else if (preg_match("/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Correo no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9]{4}[-]{1}[0-9]{7}$/", $_POST["telefono"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Teléfono no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["rol"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Rol no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,]{8,45}$/", $_POST['clave']) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Clave no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if ($_POST['clave'] != $_POST['rclave']) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Clave no coincide";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {

				$usuario->set_nombre_usuario($_POST["nombre_usuario"]);
				$usuario->set_cedula($_POST["cedula"]);
				$usuario->set_nombres($_POST["nombre"]);
				$usuario->set_apellidos($_POST["apellido"]);
				$usuario->set_correo($_POST["correo"]);
				$usuario->set_telefono($_POST["telefono"]);
				$usuario->set_rol($_POST["rol"]);
				$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
				$usuario->set_clave($clave);
				$peticion["peticion"] = "modificar";
				$json = $usuario->Transaccion($peticion);

				if ($json['estado'] == 1) {
					
					if($_POST["cedula"] == $_SESSION['user']['cedula']){
						$_SESSION['user']['nombres'] == $_POST["nombre"];
						$_SESSION['user']['apellidos'] == $_POST["apellido"];
						$_SESSION['user']['telefono'] == $_POST["telefono"];
						$_SESSION['user']['correo'] == $_POST["correo"];
					}
					$empleado->set_nombre($_POST["nombre"]);
					$empleado->set_apellido($_POST["apellido"]);
					$empleado->set_telefono($_POST["telefono"]);
					$empleado->set_cedula($_POST["cedula"]);
					$empleado->set_correo($_POST["correo"]);
					$estado = $empleado->Transaccion(['peticion' => 'modificar_datos_personal']);
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del usuario";
					$msgN = "Usuario " . $_POST["cedula"] . " modificado por " . $_SESSION['user']['nombres'] . " " . $_SESSION['user']['apellidos'] . "(" . $_SESSION['user']['nombre_usuario'] . ")";
					NotificarUsuarios($msgN, "Usuario", ['modulo' => 1, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar usuario";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar un Usuario";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Usuario");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['usuario']['eliminar']['estado']) && $permisos['usuario']['eliminar']['estado'] == "1") {
			if (preg_match("/^[VE]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Cédula no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else if ($_POST["cedula"] == $_SESSION['user']['cedula']) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, No puede eliminarse a usted mismo";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió datos no válidos";

			} else {
				$usuario->set_cedula($_POST["cedula"]);
				$peticion["peticion"] = "eliminar";
				$json = $usuario->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un usuario con la CI: " . $_POST["cedula"];
					$msgN = "Usuario " . $_POST["cedula"] . " eliminado por " . $_SESSION['user']['nombres'] . " " . $_SESSION['user']['apellidos'] . "(" . $_SESSION['user']['nombre_usuario'] . ")";
					NotificarUsuarios($msgN, "Usuario", ['modulo' => 1, 'accion' => 'ver']);
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un usuario";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar un Usuario";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Usuario");
		exit;
	}

	if (isset($_POST['cargar_rol'])) {
		$peticion["peticion"] = "consultar";
		$json = $rol->Transaccion($peticion);
		$json["resultado"] = "cargar_rol";
		echo json_encode($json);
		exit;
	}


	if (isset($_POST["reporte"])) {

	}

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
