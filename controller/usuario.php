<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
}

ob_start();

require_once "controller/utileria.php";
require_once "model/usuario.php";
require_once "model/rol.php";

$usuario = new Usuario();
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

		} else if (preg_match("/^[-0-9a-zç_]{4,15}[@]{1}[0-9a-z]{5,10}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
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
			$peticion["peticion"] = "registrar";
			$json = $usuario->Transaccion($peticion);

			if ($json['estado'] == 1) {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo usuario";
			} else {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo usuario";
			}
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

		} else if (preg_match("/^[-0-9a-zç_]{4,15}[@]{1}[0-9a-z]{5,10}[.]{1}[com]{3}$/", $_POST["correo"]) == 0) {
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
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del usuario";
			} else {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar usuario";
			}
		}
		echo json_encode($json);
		Bitacora($msg, "Usuario");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		$usuario->set_cedula($_POST["cedula"]);
		$peticion["peticion"] = "eliminar";
		$json = $usuario->Transaccion($peticion);
		echo json_encode($json);

		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un usuario";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un usuario";
		}
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
