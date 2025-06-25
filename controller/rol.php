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
	$cabecera = array('#', "Nombre", "Permisos");

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
		$rol->set_nombre($_POST["nombre"]);
		$rol->ObjPermiso($permiso);
		$peticion["peticion"] = "registrar";
		$peticion["permisos"] = json_decode($_POST['datos']);
		$json = $rol->Transaccion($peticion);
		echo json_encode($json);


		if ($json['estado'] == 1) {

			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo rol";
			$peticion["permisos"] = json_decode($_POST['datos']);
			$peticion["peticion"] = "cargar_permiso";
			$permiso->set_id_rol($_POST["id_rol"]);
			$permiso->Transaccion($peticion);

		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registró rol";
		}
		Bitacora($msg, "Rol y Permiso");
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
		$rol->set_id($_POST["id_rol"]);
		$rol->set_nombre($_POST["nombre"]);
		$peticion["peticion"] = "actualizar";
		$json = $rol->Transaccion($peticion);
		echo json_encode($json);


		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del rol";
			$peticion["permisos"] = json_decode($_POST['datos']);
			$peticion["peticion"] = "cargar_permiso";
			$permiso->set_id_rol($_POST["id_rol"]);
			$permiso->Transaccion($peticion);
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar rol";
		}
		Bitacora($msg, "Rol y Permiso");
		exit;
	}

	if (isset($_POST["eliminar"])) {
		$rol->set_id($_POST["id_rol"]);
		$peticion["peticion"] = "eliminar";
		$json = $rol->Transaccion($peticion);
		echo json_encode($json);

		if ($json['estado'] == 1) {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un rol";
		} else {
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un rol";
		}
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