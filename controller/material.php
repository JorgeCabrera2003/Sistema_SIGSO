<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/material.php";
	require_once "model/oficina.php";

	$titulo = "Materiales";
	$cabecera = array('#', "Nombre", "Ubicación", "Stock", "Modificar/Eliminar");

	$material = new Material();
	$oficina = new Oficina();

	if (!isset($permisos['material']['ver']['estado']) || $permisos['material']['ver']['estado'] == "0") {
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Material";
		Bitacora($msg, "Material");
		header('Location: ?page=home');
		exit;
	}

	if (isset($_POST["entrada"])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Materiales";
		Bitacora($msg, "Material");
		exit;
	}

	if (isset($_POST["registrar"])) {
		if (isset($permisos['material']['registrar']['estado']) && $permisos['material']['registrar']['estado'] == '1') {
			if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Material no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["ubicacion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Ubicación no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["stock"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Stock no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else {
				$material->set_nombre($_POST["nombre"]);
				$material->set_ubicacion($_POST["ubicacion"]);
				$material->set_stock($_POST["stock"]);
				$peticion["peticion"] = "registrar";
				$json = $material->Transaccion($peticion);
				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo material";
					$msgN = "Nuevo material registrado por " . $_SESSION['user']['nombre_usuario'] . ": " . $_POST["nombre"];
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo material";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para registrar Material";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
		}

		echo json_encode($json);
		Bitacora($msg, "Material");
		Notificar($msgN, "Material");
		exit;
	}

	if (isset($_POST['consultar'])) {
		$peticion["peticion"] = "consultar";
		$json = $material->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["modificar"])) {
		if (isset($permisos['material']['modificar']['estado']) && $permisos['material']['modificar']['estado'] == '1') {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_material"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/", $_POST["nombre"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Nombre del Material no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["ubicacion"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Ubicación no válida";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else if (preg_match("/^[0-9]{1,11}$/", $_POST["stock"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Stock no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else {
				$material->set_id($_POST["id_material"]);
				$material->set_nombre($_POST["nombre"]);
				$material->set_ubicacion($_POST["ubicacion"]);
				$material->set_stock($_POST["stock"]);
				$peticion["peticion"] = "actualizar";
				$json = $material->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del Material";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar Material";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para modificar Material";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Material");
		exit;
	}

	if (isset($_POST["consultar_eliminadas"])) {
		$peticion["peticion"] = "consultar_eliminadas";
		$datos = $material->Transaccion($peticion);
		echo json_encode($datos);
		exit;
	}

	if (isset($_POST["restaurar"])) {
		$material->set_id($_POST["id_material"]);
		$peticion["peticion"] = "restaurar";
		$datos = $material->Transaccion($peticion);
		echo json_encode($datos);
		exit;
	}

	if (isset($_POST["eliminar"])) {
		if (isset($permisos['material']['eliminar']['estado']) && $permisos['material']['eliminar']['estado'] == '1') {
			if (preg_match("/^[0-9]{1,11}$/", $_POST["id_material"]) == 0) {
				$json['resultado'] = "error";
				$json['mensaje'] = "Error, Id no válido";
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
			} else {
				$material->set_id($_POST["id_material"]);
				$peticion["peticion"] = "eliminar";
				$json = $material->Transaccion($peticion);

				if ($json['estado'] == 1) {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un Material";
				} else {
					$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Material";
				}
			}
		} else {
			$json['resultado'] = "error";
			$json['mensaje'] = "Error, No tienes permiso para eliminar Material";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
		}
		echo json_encode($json);
		Bitacora($msg, "Material");
		exit;
	}

	if (isset($_POST['generar_reporte'])) {
		$material = new Material();

		// Validar fechas
		$peticion['fecha_inicio'] = $_POST['fecha_inicio'];
		$peticion['fecha_fin'] = $_POST['fecha_fin'];
		$peticion['peticion'] = 'reporte';

		// Consultar materiales
		$resultado = $material->Transaccion($peticion);

		if ($resultado['resultado'] == 'success') {
			// Crear PDF
			require_once('view/Dompdf/material.php');

			// Configurar domPDF
			ob_start();
			require_once('vendor/autoload.php');
			require_once('model/conexion.php');
			$dompdf = new Dompdf\Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			// Descargar el PDF
			$dompdf->stream("reporte_materiales_" . date('Ymd') . ".pdf", array("Attachment" => false));
		} else {
			// Manejar error
			echo "Error al generar el reporte: " . $resultado['mensaje'];
		}
	}

	// Obtener lista de oficinas para el select
	$peticion_oficina["peticion"] = "consultar";
	$dato_oficina = $oficina->Transaccion($peticion_oficina);
	if ($dato_oficina['resultado'] == "consultar") {
		$oficinas = $dato_oficina['datos'];
	} else {
		$oficinas = [];
	}

	// Pass the data to the view
	$page = "material";
	$titulo = "Materiales";
	$cabecera = array('#', "Nombre", "Ubicación", "Stock", "Modificar/Eliminar");

	// Uncomment the next line if you want to use the oficinas variable in the view
	//$oficinas = $dato_oficina;

	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}
