<?php
ob_start();
if (is_file("view/$page.php")) {
	$peticion = [];
	$titulo = "Login";
	$css = [];

	require_once "model/usuario.php";
	require_once "model/bitacora.php";

	$user = new Usuario();
	$bitacora = new Bitacora();

	if (!empty($_POST)) {
		$peticion['peticion'] = "sesion";
		$cedula = $_POST['particle'] . $_POST['CI'];
		$pass = $_POST['password'];
		$user->set_cedula($cedula);
		$user->set_clave($pass);
		if ($user->Transaccion($peticion)) {
			require_once "model/empleado.php";
			$peticion['peticion'] = "perfil";
			$emp = new Empleado();


			$emp->set_cedula($cedula);
			$datos = $user->Transaccion($peticion);
			$_SESSION['user'] = $datos['datos'];
			$_GET['page'] = "";
			if ($cedula == $pass) {

				$peticion['peticion'] = "registrar";
				$msg = "(".$_SESSION['user']['nombre_usuario']."), Inició sesión, ingresa al perfil para cambiar contraseña";
				$hora = date('H:i:s');
				$fecha = date('Y-m-d');
	
				$bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
				$bitacora->set_modulo("Login/Usuario");
				$bitacora->set_accion($msg);
				$bitacora->set_fecha($fecha);
				$bitacora->set_hora($hora);
				$bitacora->Transaccion($peticion);

				ob_clean();
				echo '<script>window.location="?page=users-profile"</script>';
				exit();

			}

			$peticion['peticion'] = "registrar";
			$msg = "(".$_SESSION['user']['nombre_usuario']."), Inició sesión, ingresa al home";
			$hora = date('H:i:s');
			$fecha = date('Y-m-d');

			$bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
			$bitacora->set_modulo("Login/Usuario");
			$bitacora->set_accion($msg);
			$bitacora->set_fecha($fecha);
			$bitacora->set_hora($hora);
			$bitacora->Transaccion($peticion);
			ob_clean();
			echo '<script>window.location="?page=home"</script>';
			exit();
		} else {
			$peticion['peticion'] = "registrar";
			$msg = "(".$cedula."), Usuario y/o contraseña incorrectos";
			$hora = date('H:i:s');
			$fecha = date('Y-m-d');

			$bitacora->set_usuario(NULL);
			$bitacora->set_modulo("Login/Usuario");
			$bitacora->set_accion($msg);
			$bitacora->set_fecha($fecha);
			$bitacora->set_hora($hora);
			$bitacora->Transaccion($peticion);
		}
	}

	if ($page == "login") {
		require_once "view/$page.php";
	}
} else {
	require_once "view/404.php";
}
