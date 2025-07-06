<?php
if (!$_SESSION) {
	echo '<script>window.location="?page=login"</script>';
	$msg["danger"] = "Sesion Finalizada.";
}

require_once "model/bien.php";
require_once "model/equipo.php";
$bien = new Bien();
$equipo = new Equipo();

// Endpoint para consultar equipos del empleado
if (isset($_POST['peticion']) && $_POST['peticion'] === 'consultar_equipos_empleado' && isset($_POST['cedula_empleado'])) {
    $cedula = $_POST['cedula_empleado'];
    // Usa el método Transaccion del modelo Equipo
    $json = $equipo->Transaccion([
        'peticion' => 'equipos_por_empleado',
        'cedula_empleado' => $cedula
    ]);
    echo json_encode(['resultado' => 'consultar_equipos_empleado', 'datos' => $json]);
    exit;
}

if (isset($_POST['consultar_bienes_empleado']) && isset($_POST['cedula_empleado'])) {
    $peticion = [
        'peticion' => 'consultar_bienes_empleado',
        'cedula_empleado' => $_POST['cedula_empleado']
    ];
    $json = $bien->Transaccion($peticion);
    $json['resultado'] = 'consultar_bienes_empleado';
    header('Content-Type: application/json');
    echo json_encode($json);
    exit;
}

if (is_file("view/" . $page . ".php")) {
	require_once "controller/utileria.php";
	require_once "model/solicitud.php";
	require_once "model/hoja_servicio.php";
	require_once "model/bien.php";

	$titulo = "Mis Solicitudes";
	$cabecera = array('#', "Motivo", "Fecha Reporte", "Estado", "Resultado");

	$btn_color = "warning";
	$btn_icon = "filetype-pdf";
	$btn_name = "reporte";
	$btn_value = "0";
	$origen = "";

	$solicitud = new Solicitud();
	$bien = new Bien();

	$usuario->set_cedula($_SESSION['user']['cedula']);
	$datos = $_SESSION['user'];
	$datos = $datos + $usuario->Transaccion(['peticion' => 'perfil']);


	if (isset($_POST['entrada'])) {
		$json['resultado'] = "entrada";
		echo json_encode($json);

		$peticion['peticion'] = "registrar";
		$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al módulo de Solicitudes, lugar: Mis servicios";
		$hora = date('H:i:s');
		$fecha = date('Y-m-d');

		$bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
		$bitacora->set_modulo("Solicitudes");
		$bitacora->set_accion($msg);
		$bitacora->set_fecha($fecha);
		$bitacora->set_hora($hora);
		$bitacora->Transaccion($peticion);
		exit;
	}

	if (isset($_POST['consultar'])) {
		$solicitud->set_cedula_solicitante($_SESSION['user']['cedula']);
		$peticion["peticion"] = "solicitud_usuario";
		$json = $solicitud->Transaccion($peticion);
		echo json_encode($json);
		exit;
	}

	if (isset($_POST["solicitud"]) && $_POST["motivo"] != NULL) {
		if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/", $_POST["motivo"]) == 0) {

			$json['resultado'] = "error";
			$json['mensaje'] = "Error, datos no válidos";
			$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

		} else {

			$solicitud->set_cedula_solicitante($datos["cedula"]);
			$solicitud->set_motivo($_POST["motivo"]);
			// Registrar el equipo si viene en el POST
			if (isset($_POST["id_equipo"]) && $_POST["id_equipo"] !== "") {
				$solicitud->set_id_equipo($_POST["id_equipo"]);
			} else {
				$solicitud->set_id_equipo(null);
			}
			$peticion["peticion"] = "registrar";
			$json = $solicitud->Transaccion($peticion);
			
			if ($json['bool'] == 1) {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Realizó una solicitud exitosamente";
                $msgN = "Nueva solicitud creada por " . $_SESSION['user']['nombre_usuario'] . ": " . $_POST["motivo"];
			} else {
				$msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al enviar la solicitud";
			}
			
		}
		
		echo json_encode($json);
		Bitacora($msg, "Solicitud");
		//Tengan cuidado con las notificaciones por que dan problemas con el envio
		Notificar(
                    $msgN,
                    "Solicitudes"
                );
		exit;
	}


    if (isset($_POST['consultar_bienes_empleado']) && isset($_POST['cedula_empleado'])) {
        $peticion = [
            'peticion' => 'consultar_bienes_empleado',
            'cedula_empleado' => $_POST['cedula_empleado']
        ];
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = 'consultar_bienes_empleado';
        echo json_encode($json);
        exit;
    }

	if (isset($_POST["reporte"])) {

		$hoja = new Hoja();
		$hoja->set_nro_solicitud($_POST["reporte"]);
		$hojas = $hoja->Transaccion('listar');
		$info = [];
		foreach ($hojas as $nro) {
			$hoja->set_cod_hoja($nro["cod_hoja"]);
			$datos_hoja = $hoja->Transaccion('Datos');
			$aux = $hoja->Transaccion('consultar_detalles');
			$valores = [];
			foreach ($aux as $detalle) {
				$valores[$detalle["componente"]] = $detalle["detalle"];
			}
			$info[] = [$datos_hoja, $valores];
		}
		require_once "model/reporte.php";
		$reporte = new reporte();
		ob_clean();
		$reporte->mis_servicios($info);
	}
	require_once "view/" . $page . ".php";
} else {
	require_once "view/404.php";
}

