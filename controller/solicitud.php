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
    $titulo = "Solicitudes";
    $cabecera = array('#', "Solicitante", "Cedula", "Equipo", "Motivo", "Estado", "Fecha Reporte", "Resultado", "Modificar/Eliminar");

    // Instanciación de modelos
    $solicitud = new Solicitud();
    $empleado = new Empleado();
    $equipo = new Equipo();
    $hojaServicio = new HojaServicio();
    $dependencia = new Dependencia();


    // Manejo de acciones AJAX
    if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "load_equipos":
            $peticion = [
                "peticion" => "equipos_por_dependencia",
                "dependencia_id" => $_POST["dependencia_id"]
            ];
            $equipo_datos = $equipo->Transaccion($peticion);
            echo json_encode($equipo_datos);
            break;
            
        case "load_solicitantes":
            $peticion = [
                "peticion" => "empleados_por_dependencia",
                "dependencia_id" => $_POST["dependencia_id"]
            ];
            $solicitantes = $empleado->Transaccion($peticion);
            echo json_encode($solicitantes);
            break;
            
        case "load_dependencias":
            $peticion = ["peticion" => "consultar"];
            $dependencias = $dependencia->Transaccion($peticion);
            echo json_encode($dependencias);
            break;
            
        case "consultar_por_id":
            $peticion = [
                "peticion" => "consultar_por_id",
                "id" => $_POST["id"]
            ];
            $datosSolicitud = $solicitud->Transaccion($peticion);
            echo json_encode($datosSolicitud);
            break;
            
        default:
            echo json_encode(["resultado" => "error", "mensaje" => "Acción no reconocida"]);
    }
    exit;
}

    // Registro de entrada al módulo
    if (isset($_POST["entrada"])) {
        $json = ['resultado' => "entrada"];
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Solicitud";
        Bitacora($msg, "Solicitud");
        exit;
    }

    // Consulta de solicitudes
    if (isset($_POST['consultar'])) {
        $peticion = ['peticion' => "consultar_servicio"];
        $json = $solicitud->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    // Consulta de equipo
    if (isset($_POST['consultar_equipo'])) {
        $peticion = ["peticion" => "consultar"];
        $datos = $equipo->Transaccion($peticion);
        echo json_encode($datos);
        exit;
    }

    // Registro de nueva solicitud
    if (isset($_POST["registrar"])) {
        try {
            $solicitud->set_motivo($_POST["motivo"]);
            $solicitud->set_cedula_solicitante($_POST["cedula"]);
            
            // Manejo del equipo (puede ser null)
            $equipoSerial = ($_POST["serial"] == " " || empty($_POST["serial"])) ? null : $_POST["serial"];
            $solicitud->set_id_equipo($equipoSerial);
            
            $peticion = ['peticion' => "registrar"];
            $nroSolicitud = $solicitud->Transaccion($peticion);
            
            if ($nroSolicitud) {
                // Crear hoja de servicio asociada
                $hojaServicio->set_nro_solicitud($nroSolicitud);
                $hojaServicio->set_tipo_servicio($_POST["area"]);
                $hojaServicio->NuevaHojaServicio();
                
                $response = [
                    "resultado" => "success",
                    "mensaje" => "Solicitud registrada correctamente",
                    "nro_solicitud" => $nroSolicitud
                ];
            } else {
                $response = ["resultado" => "error", "mensaje" => "Error al registrar la solicitud"];
            }
        } catch (Exception $e) {
            $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
        
        echo json_encode($response);
        exit;
    }

    // Actualización de solicitud existente
    if (isset($_POST["modificar"])) {
        try {
            $solicitud->set_nro_solicitud($_POST["nrosol"]);
            $solicitud->set_motivo($_POST["motivo"]);
            $solicitud->set_cedula_solicitante($_POST["cedula"]);
            
            // Validar y asignar equipo
            $equipoSerial = ($_POST["serial"] == " " || empty($_POST["serial"])) ? null : $_POST["serial"];
            $solicitud->set_id_equipo($equipoSerial);
            
            $peticion = ['peticion' => "actualizar"];
            $resultado = $solicitud->Transaccion($peticion);
            
            if ($resultado) {
                // Actualizar hoja de servicio
                $hojaServicio->set_nro_solicitud($_POST["nrosol"]);
                $hojaServicio->set_tipo_servicio($_POST["area"]);
                $hojaServicio->NuevaHojaServicio();
                
                $response = ["resultado" => "success", "mensaje" => "Solicitud actualizada correctamente"];
            } else {
                $response = ["resultado" => "error", "mensaje" => "Error al actualizar la solicitud"];
            }
        } catch (Exception $e) {
            $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
        
        echo json_encode($response);
        exit;
    }

    // Eliminación de solicitud
    if (isset($_POST["eliminar"])) {
        try {
            $solicitud->set_nro_solicitud($_POST['nrosol']);
            $peticion = ['peticion' => "eliminar"];
            $resultado = $solicitud->Transaccion($peticion);
            
            if ($resultado) {
                $response = ["resultado" => "success", "mensaje" => "Solicitud eliminada correctamente"];
            } else {
                $response = ["resultado" => "error", "mensaje" => "Error al eliminar la solicitud"];
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
        $reporte = new reporte();
        $solicitud->set_fecha_inicio($_POST["inicio"]);
        $solicitud->set_fecha_final($_POST["final"]);
        $reporte->solicitudes($solicitud->consulta_reporte());
        exit;
    }

    // Carga de la vista principal
    require_once "view/$page.php";
} else {
    require_once "view/404.php";
}