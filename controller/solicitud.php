<?php
if (!isset($_SESSION)) {
    echo '<script>window.location="?page=login"</script>';
    exit;
}

if (is_file("view/" . $page . ".php")) {
    // Inclusión de archivos necesarios
    require_once "controller/utileria.php";
    require_once "model/solicitud.php";
    require_once "model/empleado.php";
    require_once "model/hoja_servicio.php";
    require_once "model/equipo.php";
    require_once "model/dependencia.php";

    // Configuración inicial
    $titulo = "Gestión de Solicitudes";
    $cabecera = array(
        '#', 
        "Solicitante", 
        "Cédula", 
        "Dependencia",
        "Equipo", 
        "Motivo", 
        "Estado", 
        "Fecha Reporte", 
        "Resultado", 
        "Acciones"
    );

    // Instanciación de modelos
    $solicitud = new Solicitud();
    $empleado = new Empleado();
    $equipo = new Equipo();
    $hojaServicio = new HojaServicio();
    $dependencia = new Dependencia();

    // Manejo de acciones AJAX
    if (isset($_POST["action"])) {
        header('Content-Type: application/json');
        
        try {
            switch ($_POST["action"]) {
                case "load_equipos":
                    $solicitud->set_id_dependencia($_POST["dependencia_id"]);
                    $response = $solicitud->Transaccion(["peticion" => "consultar_equipos"]);
                    break;
                    
                case "load_solicitantes":
                    $solicitud->set_id_dependencia($_POST["dependencia_id"]);
                    $response = $solicitud->Transaccion(["peticion" => "consultar_solicitantes"]);
                    break;
                    
                case "load_dependencias":
                    $response = $dependencia->Transaccion(["peticion" => "consultar"]);
                    break;

                case "load_areas":
                    $response = $dependencia->Transaccion(["peticion" => "consultar_areas"]);
                    break;
                    
                case "consultar_por_id":
                    $solicitud->set_nro_solicitud($_POST["id"]);
                    $response = $solicitud->Transaccion(["peticion" => "consultar"]);
                    break;
                    
                default:
                    $response = ["resultado" => "error", "mensaje" => "Acción no reconocida"];
            }
            
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(["resultado" => "error", "mensaje" => $e->getMessage()]);
        }
        exit;
    }

    // Registro de entrada al módulo
    if (isset($_POST["entrada"])) {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Solicitud";
        Bitacora($msg, "Solicitud");
        echo json_encode(['resultado' => "entrada"]);
        exit;
    }

    // Consulta de solicitudes
    if (isset($_POST['consultar'])) {
        header('Content-Type: application/json');
        echo json_encode($solicitud->Transaccion(['peticion' => "consultar"]));
        exit;
    }

    // Registro de nueva solicitud
    if (isset($_POST["registrar"])) {
        header('Content-Type: application/json');
        
        try {
            $solicitud->set_motivo($_POST["motivo"]);
            $solicitud->set_cedula_solicitante($_POST["cedula"]);
            $solicitud->set_id_equipo($_POST["serial"] ?: null);
            
            $resultado = $solicitud->Transaccion(['peticion' => "registrar"]);
            
            if ($resultado['bool']) {
                // Crear hoja de servicio asociada
                $hojaServicio->set_nro_solicitud($resultado['datos']);
                $hojaServicio->set_id_tipo_servicio($_POST["area"]);
                $hojaServicio->NuevaHojaServicio();
                
                $response = [
                    "resultado" => "success",
                    "mensaje" => "Solicitud registrada correctamente",
                    "nro_solicitud" => $resultado['datos']
                ];
            } else {
                $response = ["resultado" => "error", "mensaje" => $resultado['mensaje']];
            }
        } catch (Exception $e) {
            $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
        
        echo json_encode($response);
        exit;
    }

    // Actualización de solicitud existente
    if (isset($_POST["modificar"])) {
        header('Content-Type: application/json');
        
        try {
            $solicitud->set_nro_solicitud($_POST["nrosol"]);
            $solicitud->set_motivo($_POST["motivo"]);
            $solicitud->set_cedula_solicitante($_POST["cedula"]);
            $solicitud->set_id_equipo($_POST["serial"] ?: null);
            
            $resultado = $solicitud->Transaccion(['peticion' => "actualizar"]);
            
            if ($resultado['bool']) {
                // Actualizar hoja de servicio
                $hojaServicio->set_nro_solicitud($_POST["nrosol"]);
                $hojaServicio->set_id_tipo_servicio($_POST["area"]);
                $hojaServicio->NuevaHojaServicio();
                
                $response = ["resultado" => "success", "mensaje" => "Solicitud actualizada correctamente"];
            } else {
                $response = ["resultado" => "error", "mensaje" => $resultado['mensaje']];
            }
        } catch (Exception $e) {
            $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
        
        echo json_encode($response);
        exit;
    }

    // Eliminación de solicitud
    if (isset($_POST["eliminar"])) {
        header('Content-Type: application/json');
        
        try {
            $solicitud->set_nro_solicitud($_POST['nrosol']);
            $resultado = $solicitud->Transaccion(['peticion' => "eliminar"]);
            
            if ($resultado['bool']) {
                $response = ["resultado" => "success", "mensaje" => $resultado['mensaje']];
            } else {
                $response = ["resultado" => "error", "mensaje" => $resultado['mensaje']];
            }
        } catch (Exception $e) {
            $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
        
        echo json_encode($response);
        exit;
    }

    // Generación de reportes
    if (isset($_POST["reporte"])) {
        require_once "model/reporte.php";
        $reporte = new Reporte();
        
        $solicitud->set_fecha_inicio($_POST["inicio"]);
        $solicitud->set_fecha_final($_POST["final"]);
        
        $datosReporte = $solicitud->Transaccion(["peticion" => "reporte"]);
        $reporte->solicitudes($datosReporte['datos']);
        exit;
    }

    // Carga de la vista principal
    require_once "view/$page.php";
} else {
    require_once "view/404.php";
}