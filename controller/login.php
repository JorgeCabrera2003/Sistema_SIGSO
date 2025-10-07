<?php

ob_start();

if (is_file("view/$page.php")) {

	if($_SESSION != NULL){
		header("Location: ?page=home");
	}
	
	$peticion = [];
	$titulo = "Login";
	$css = [];
	$error_login = false;

	require_once "model/usuario.php";
	require_once "model/bitacora.php";
	require_once "model/login.php";

	$user = new Usuario();
	$bitacora = new Bitacora();
	$Login = new login();

	$recaptcha_sitekey = $Login->get_recaptcha_sitekey();

	if (!empty($_POST)) {

		$recaptcha = $_POST['g-recaptcha-response'];
		$secret = $Login->get_recaptcha_secret();
		$response = file_get_contents(
			"https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha"
		);
		$arr = json_decode($response, true);


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
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Inició sesión, ingresa al Home para cambiar contraseña";
				$hora = date('H:i:s');
				$fecha = date('Y-m-d');

				$bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
				$bitacora->set_modulo("Login/Usuario");
				$bitacora->set_accion($msg);
				$bitacora->set_fecha($fecha);
				$bitacora->set_hora($hora);
				$bitacora->Transaccion($peticion);

				// Almacenar en sesión que debe mostrar el alert
				$_SESSION['mostrar_alerta'] = true;

				ob_clean();
				header("Location: ?page=home");
				exit();
			}

			$peticion['peticion'] = "registrar";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Inició sesión, ingresa al home";
			$hora = date('H:i:s');
			$fecha = date('Y-m-d');

			$bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
			$bitacora->set_modulo("Login/Usuario");
			$bitacora->set_accion($msg);
			$bitacora->set_fecha($fecha);
			$bitacora->set_hora($hora);
			$bitacora->Transaccion($peticion);
			ob_clean();

			header("Location: ?page=home");
			exit();
		} else {

			$error_login = true;

			$peticion['peticion'] = "registrar";
			$msg = "(" . $cedula . "), Usuario y/o contraseña incorrectos";
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
