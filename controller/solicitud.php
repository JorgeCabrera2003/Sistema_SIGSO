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
                    // Retorna datos de la solicitud y el tipo de servicio asociado
                    $solicitud->set_nro_solicitud($_POST["id"]);
                    $datosSolicitud = $solicitud->Transaccion(["peticion" => "consultar_por_id"]);
                    echo json_encode($datosSolicitud);
                    exit;

                case "load_tecnicos_por_area":
                    // Nuevo endpoint: técnicos por área, ordenados por menor cantidad de hojas finalizadas en el mes
                    if (!isset($_POST["area_id"])) {
                        $response = ["resultado" => "error", "mensaje" => "Área no especificada"];
                        break;
                    }
                    // Nuevo método privado en Empleado
                    $empleado = new Empleado();
                    $response = $empleado->Transaccion([
                        "peticion" => "tecnicos_por_area_rendimiento",
                        "area_id" => $_POST["area_id"]
                    ]);
                    break;

                case "redireccionar_hoja":
                    // Redireccionar hoja: crea una copia y la asigna a otro técnico/área
                    $hojaServicio = new HojaServicio();
                    $hojaServicio->set_codigo_hoja_servicio($_POST["id_hoja"]);
                    $areaDestino = $_POST["area_destino"];
                    $tecnicoDestino = $_POST["tecnico_destino"];
                    $result = $hojaServicio->Transaccion([
                        "peticion" => "redireccionar",
                        "area_destino" => $areaDestino,
                        "tecnico_destino" => $tecnicoDestino
                    ]);
                    $response = $result;
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

    if (isset($_POST["registrar"])) {
        header('Content-Type: application/json');

        if (isset($permisos['solicitud']['registrar']['estado']) && $permisos['solicitud']['registrar']['estado'] == '1') {

            if (!isset($_POST["cedula"]) || preg_match(c_regex['Cedula'], $_POST["cedula"])) {
                $response['resultado'] = "error";
                $response['mensaje'] = "Error, Cédula no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (!isset($_POST["motivo"]) || preg_match(c_regex['Nombre_Descripcion'], $_POST["motivo"])) {
                $response['resultado'] = "error";
                $response['mensaje'] = "Error, Descripción no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {

                try {
                    // Generar ID único para la solicitud
                    $nro_solicitud = generarID($_POST["cedula"], $_POST["serial"]);
                    $solicitud->set_nro_solicitud($nro_solicitud);
                    $solicitud->set_motivo($_POST["motivo"]);
                    $solicitud->set_cedula_solicitante($_POST["cedula"]);
                    $solicitud->set_id_equipo($_POST["serial"] ?: null);
                    $resultado = $solicitud->Transaccion(['peticion' => "registrar"]);

                    if ($resultado['bool']) {
                        // Crear hoja de servicio asociada usando el nuevo método directo
                        $codigoHoja = generarID("HS", $nro_solicitud);
                        $id_tipo_servicio = $_POST["area"] ?? '1'; // Default a Soporte Técnico

                        $peticionHoja = [
                            'peticion' => 'crear',
                            'codigo_hoja_servicio' => $codigoHoja,
                            'nro_solicitud' => $nro_solicitud,
                            'id_tipo_servicio' => $id_tipo_servicio
                        ];

                        // Asignar técnico si viene
                        if (!empty($_POST["tecnico"]) && isset($_POST["tecnico"])) {
                            $peticionHoja['cedula_tecnico'] = $_POST["tecnico"];
                        }

                        $resultHoja = $hojaServicio->Transaccion($peticionHoja);

                        if ($resultHoja['resultado'] !== 'success') {
                            throw new Exception("Error al crear hoja de servicio: " . $resultHoja['mensaje']);
                        }

                        $response = [
                            "resultado" => "success",
                            "mensaje" => "Solicitud y hoja de servicio registradas correctamente",
                            "nro_solicitud" => $nro_solicitud
                        ];

                        // Notificar al técnico si fue asignado
                        if (!empty($_POST["tecnico"]) && isset($_POST["tecnico"])) {
                            $msgTecnico = "Se le ha asignado una nueva solicitud (#{$nro_solicitud})";
                            Notificar($msgTecnico, "Solicitud", $_POST["tecnico"]);
                        }

                    } else {
                        $response = ["resultado" => "error", "mensaje" => $resultado['mensaje']];
                    }
                } catch (Exception $e) {
                    $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
                }
            }

        } else {
            $response['resultado'] = "error";
            $response['mensaje'] = "Error, No tienes permiso para registrar Solicitud";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        $response["equipo"] = $solicitud->get_id_equipo();
        Bitacora($msg, "Solicitud");
        echo json_encode($response);
        exit;
    }

    // Consultar solicitudes eliminadas
    if (isset($_POST["consultar_eliminadas"])) {
        $resultado = $solicitud->Transaccion(['peticion' => "consultar_eliminadas"]);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // reactivar solicitud eliminada
    if (isset($_POST["reactivar"])) {
        $solicitud->set_nro_solicitud($_POST['nrosol']);
        $resultado = $solicitud->Transaccion(['peticion' => "reactivar"]);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

    // Eliminación de solicitud
    if (isset($_POST["eliminar"])) {
        header('Content-Type: application/json');
        if (isset($permisos['solicitud']['eliminar']['estado']) && $permisos['solicitud']['eliminar']['estado'] == '1') {
            if (!isset($_POST["nrosol"]) || preg_match(c_regex['ID_Generado'], $_POST["nrosol"])) {
                $response['resultado'] = "error";
                $response['mensaje'] = "Error, Número de Solitud no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
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
            }

        } else {
            $response['resultado'] = "error";
            $response['mensaje'] = "Error, No tienes permiso para eliminar Solicitud";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }

        echo json_encode($response);
        exit;
    }

    if (isset($_POST["modificar"])) {
        header('Content-Type: application/json');
        if (isset($permisos['solicitud']['modificar']['estado']) && $permisos['solicitud']['modificar']['estado'] == '1') {
            try {
                // Verificar que todos los campos requeridos estén presentes
                if (!isset($_POST["nroSolicitud"]) || empty($_POST["nroSolicitud"])) {
                    throw new Exception("Número de solicitud no especificado");
                }

                $solicitud->set_nro_solicitud($_POST["nroSolicitud"]);
                $solicitud->set_motivo($_POST["motivo"]);
                $solicitud->set_cedula_solicitante($_POST["cedula"]);
                $solicitud->set_id_equipo(isset($_POST["serial"]) ? $_POST["serial"] : null);

                $resultado = $solicitud->Transaccion(['peticion' => "actualizar"]);

                if ($resultado['bool']) {
                    // Solo actualizar hoja de servicio si se proporciona un área
                    if (isset($_POST["area"]) && !empty($_POST["area"])) {
                        // Primero consultar si ya existe una hoja para esta solicitud
                        $consultaHoja = $hojaServicio->Transaccion([
                            'peticion' => 'consultar_por_solicitud',
                            'nro_solicitud' => $_POST["nroSolicitud"]
                        ]);

                        if ($consultaHoja['resultado'] === 'success' && !empty($consultaHoja['datos'])) {
                            // Si existe, actualizarla usando el método directo
                            $peticionActualizar = [
                                'peticion' => 'actualizar',
                                'codigo_hoja_servicio' => $consultaHoja['datos']['codigo_hoja_servicio'],
                                'id_tipo_servicio' => $_POST["area"]
                            ];

                            // Asignar técnico si viene
                            if (!empty($_POST["tecnico"])) {
                                $peticionActualizar['cedula_tecnico'] = $_POST["tecnico"];
                            }

                            $resultHoja = $hojaServicio->Transaccion($peticionActualizar);
                        } else {
                            // Si no existe, crear una nueva usando el método directo
                            $codigoHoja = generarID("HS", substr($_POST["nroSolicitud"], -2));
                            $peticionCrear = [
                                'peticion' => 'crear',
                                'codigo_hoja_servicio' => $codigoHoja,
                                'nro_solicitud' => $_POST["nroSolicitud"],
                                'id_tipo_servicio' => $_POST["area"]
                            ];

                            // Asignar técnico si viene
                            if (!empty($_POST["tecnico"])) {
                                $peticionCrear['cedula_tecnico'] = $_POST["tecnico"];
                            }

                            $resultHoja = $hojaServicio->Transaccion($peticionCrear);
                        }

                        if ($resultHoja['resultado'] !== 'success') {
                            throw new Exception("Error al actualizar hoja de servicio: " . $resultHoja['mensaje']);
                        }
                    }

                    $response = [
                        "resultado" => "success",
                        "mensaje" => "Solicitud actualizada correctamente",
                        "nro_solicitud" => $_POST["nroSolicitud"]
                    ];

                    if (!empty($_POST["tecnico"])) {
                        $Nmsg = "Tienes un nuevo servicio con la solicitud: " . $_POST["nroSolicitud"];
                        Notificar($Nmsg, "Solicitud", $_POST["tecnico"]);
                    }

                    $Nmsg = "Se esta atendiendo su solicitud nro: " . $_POST["nroSolicitud"] . " y se direccionó al Servicio de: " . $_POST["area"];
                    Notificar($Nmsg, "Solicitud", $_POST["cedula"]);

                } else {
                    $response = ["resultado" => "error", "mensaje" => $resultado['mensaje']];
                }
            } catch (Exception $e) {
                $response = ["resultado" => "error", "mensaje" => $e->getMessage()];
            }
        } else {
            $response['resultado'] = "error";
            $response['mensaje'] = "Error, No tienes permiso para modificar Solicitud";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }

        echo json_encode($response);
        exit;
    }

    // Generación de reportes
    if (isset($_POST["reporte"])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();

        $solicitud->set_fecha_inicio($_POST["inicio"]);
        $solicitud->set_fecha_final($_POST["final"]);

        $datosReporte = $solicitud->Transaccion(["peticion" => "reporte"]);
        $resultado = $datosReporte['datos'];

        // Guardar datos en sesión para la vista si lo necesitas
        $_SESSION['servicio'] = [];
        foreach ($resultado as $fila) {
            $_SESSION['servicio'][] = $fila;
        }

        // Renderizar HTML para el PDF
        ob_start();
        require "view/Dompdf/solicitudes.php";
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("reporte_solicitudes_" . date('Ymd_His') . ".pdf", array("Attachment" => false));
        exit;
    }

    // Carga de la vista principal
    require_once "view/$page.php";
} else {
    require_once "view/404.php";
}