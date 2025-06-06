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

if ($permiso == 0) {
	echo '<script>window.location="?page=404"</script>';
}

$cabecera = array("Nombre de Usuario", "Cedula", "Nombre", "Apellido", "Rol", "Modificar/Eliminar");

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
		$usuario->set_cedula($_POST["cedula"]);
		$usuario->set_nombres($_POST["nombre"]);
		$usuario->set_apellidos($_POST["apellido"]);
		$usuario->set_telefono($_POST["telefono"]);
		$usuario->set_correo($_POST["correo"]);
		$usuario->set_rol($_POST["rol"]);
		$peticion["peticion"] = "registrar";
		$json = $usuario->Transaccion($peticion);
		echo json_encode($json);

		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo usuario";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo usuario";
		}
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
		$usuario->set_cedula($_POST["cedula"]);
		$usuario->set_nombres($_POST["nombre"]);
		$usuario->set_apellidos($_POST["apellido"]);
		$usuario->set_telefono($_POST["telefono"]);
		$usuario->set_correo($_POST["correo"]);
		$usuario->set_rol($_POST["rol"]);
		$peticion["peticion"] = "actualizar";
		$json = $usuario->Transaccion($peticion);
		echo json_encode($json);

		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del usuario";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar usuario";
		}
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
