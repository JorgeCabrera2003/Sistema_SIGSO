<?php
// Verificar sesión
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo '<script>window.location="?page=login"</script>';
    $_SESSION['msg']['danger'] = "Sesión Finalizada.";
    exit;
}

// Verificar permisos - Solo superusuario (5) y técnicos (2)
$rolesPermitidos = [2, 5];
if (!in_array($_SESSION['user']['id_rol'], $rolesPermitidos)) {
    echo '<script>window.location="?page=inicio"</script>';
    $_SESSION['msg']['danger'] = "No tiene permisos para acceder a este módulo";
    exit;
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/hoja_servicio.php";
    require_once "model/empleado.php";
    require_once "model/tipo_servicio.php";

    $titulo = "Gestión de Hojas de Servicio";
    $cabecera = array('#', "N° Solicitud", "Tipo Servicio", "Solicitante", "Equipo", "Marca", "Serial", "Código Bien", "Motivo", "Fecha Solicitud", "Técnico", "Estado", "Acciones");

    $hojaServicio = new HojaServicio();
    $empleado = new Empleado();
    $tipoServicio = new TipoServicio();

    // Registrar entrada al módulo
    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Servicios Técnicos";
        Bitacora($msg, "Servicio");
        exit;
    }

    // Listar hojas de servicio según rol
    if (isset($_POST["listar"])) {
        $datos = $hojaServicio->Transaccion([
            'peticion' => 'listar',
            'usuario' => $_SESSION['user']
        ]);
        echo json_encode($datos);
        exit;
    }



    // Registrar nueva hoja de servicio (solo superusuario)
    if (isset($_POST["registrar"])) {
        if ($_SESSION['user']['id_rol'] != 5) {
            echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene permisos para esta acción']);
            exit;
        }

        try {
            $hojaServicio->set_nro_solicitud($_POST["nro_solicitud"]);
            $hojaServicio->set_id_tipo_servicio($_POST["id_tipo_servicio"]);

            $peticion = [
                'peticion' => 'crear',
                'detalles' => isset($_POST["detalles"]) ? $_POST["detalles"] : []
            ];

            $datos = $hojaServicio->Transaccion($peticion);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Registró la hoja de servicio #" . $datos['codigo'];
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Error al registrar servicio: " . $datos['mensaje'];
            }

            Bitacora($msg, "Servicio");
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    if (isset($_POST['tomar_hoja'])) {
        try {
            // Verificar que el usuario es un tecnico
            if ($_SESSION['user']['id_rol'] != 2 && $_SESSION['user']['id_rol'] != 5) { // Asumiendo que 2 es el rol de TÉCNICO
                echo json_encode(['resultado' => 'error', 'mensaje' => 'Solo los técnicos pueden tomar hojas de servicio']);
                exit;
            }

            // Obtener información del técnico
            $infoTecnico = $empleado->Transaccion([
                'peticion' => 'obtener_tecnico',
                'cedula' => $_SESSION['user']['cedula']
            ]);
            // if (!$infoTecnico || !$infoTecnico['id_servicio']) {
            //     echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene un área de servicio asignada']);
            //     exit;
            // }

            // Verificar que la hoja puede ser tomada por este técnico
            $verificacion = $hojaServicio->Transaccion([
                'peticion' => 'verificar_hoja_tomar',
                'codigo_hoja_servicio' => $_POST['codigo_hoja_servicio'],

            ]
            );

            if ($verificacion['resultado'] === 'error') {
                echo json_encode($verificacion);
                exit;
            }

            // Asignar la hoja al técnico
            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_cedula_tecnico($_SESSION['user']['cedula']);

            $datos = $hojaServicio->Transaccion(['peticion' => 'tomar_hoja']);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Tomó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    if (isset($_POST['get_user_data'])) {
        echo json_encode([
            'rol' => $datos["rol"],
            'cedula' => $datos["cedula"],
            'isSuperUsuario' => ($datos["rol"] == "SUPERUSUARIO" || $datos["rol"] == "ADMINISTRADOR")
        ]);
        exit;
    }

    // Consultar hoja de servicio
    if (isset($_POST['consultar'])) {
        try {
            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $datos = $hojaServicio->Transaccion(['peticion' => 'consultar']);

            if ($datos['resultado'] === 'success') {
                // Verificar permisos para ver esta hoja
                $puedeVer = false;

                // Superusuario puede ver todo
                if ($_SESSION['user']['id_rol'] == 5) {
                    $puedeVer = true;
                }
                // Técnico puede ver si es de su área o la ha tomado
                else {
                    $infoTecnico = $empleado->obtenerTecnico($_SESSION['user']['cedula']);
                    if (
                        $infoTecnico &&
                        ($datos['datos']['id_tipo_servicio'] == $infoTecnico['id_servicio'] ||
                            $datos['datos']['cedula_tecnico'] == $_SESSION['user']['cedula'])
                    ) {
                        $puedeVer = true;
                    }
                }

                if ($puedeVer) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Consultó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                    Bitacora($msg, "Servicio");
                    echo json_encode($datos);
                } else {
                    echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene permisos para ver esta hoja de servicio']);
                }
            } else {
                echo json_encode($datos);
            }
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Consultar tipos de servicio disponibles para una solicitud
    if (isset($_POST['tipos_disponibles'])) {
        try {
            $hojaServicio->set_nro_solicitud($_POST['nro_solicitud']);
            $datos = $hojaServicio->Transaccion(['peticion' => 'tipos_disponibles']);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Finalizar hoja de servicio
    if (isset($_POST['finalizar'])) {
        try {
            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_cedula_tecnico($_SESSION['user']['cedula']);
            $hojaServicio->set_resultado_hoja_servicio($_POST['resultado_hoja_servicio']);
            $hojaServicio->set_observacion($_POST['observacion']);

            // Verificar que el técnico es el asignado a esta hoja
            $sqlVerificar = "SELECT cedula_tecnico FROM hoja_servicio 
                            WHERE codigo_hoja_servicio = :codigo";
            $stmt = $hojaServicio->Conex()->prepare($sqlVerificar);
            $stmt->bindParam(':codigo', $_POST['codigo_hoja_servicio'], PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hoja || ($hoja['cedula_tecnico'] != $_SESSION['user']['cedula'] && $_SESSION['user']['id_rol'] != 5)) {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene permisos para finalizar esta hoja']);
                exit;
            }

            $datos = $hojaServicio->Transaccion(['peticion' => 'finalizar']);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Finalizó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Error al finalizar servicio: " . $datos['mensaje'];
            }

            Bitacora($msg, "Servicio");
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Tomar una hoja de servicio (para técnicos)
    if (isset($_POST['tomar_hoja'])) {
        if ($_SESSION['user']['id_rol'] != 2 || $_SESSION['user']['id_rol'] != 5) { // Assuming 1 is TECNICO role and 2 is another role
            echo json_encode(['resultado' => 'error', 'mensaje' => 'Solo los técnicos pueden tomar hojas de servicio']);
            exit;
        }

        try {
            // Verificar que la hoja es del área del técnico
            $infoTecnico = $empleado->obtenerTecnico($_SESSION['user']['cedula']);
            if (!$infoTecnico || !$infoTecnico['id_servicio']) {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene un área de servicio asignada']);
                exit;
            }

            $sqlVerificar = "SELECT hs.codigo_hoja_servicio, ts.id_tipo_servicio 
                            FROM hoja_servicio hs
                            JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                            WHERE hs.codigo_hoja_servicio = :codigo
                            AND (hs.cedula_tecnico IS NULL OR hs.cedula_tecnico = '')
                            AND ts.id_tipo_servicio = :id_servicio";

            $stmt = $hojaServicio->Conex()->prepare($sqlVerificar);
            $stmt->bindParam(':codigo', $_POST['codigo_hoja_servicio'], PDO::PARAM_INT);
            $stmt->bindParam(':id_servicio', $infoTecnico['id_servicio'], PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hoja) {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'No puede tomar esta hoja de servicio']);
                exit;
            }

            // Asignar la hoja al técnico
            $sql = "UPDATE hoja_servicio 
                    SET cedula_tecnico = :cedula 
                    WHERE codigo_hoja_servicio = :codigo";

            $stmt = $hojaServicio->Conex()->prepare($sql);
            $stmt->bindParam(':cedula', $_SESSION['user']['cedula']);
            $stmt->bindParam(':codigo', $_POST['codigo_hoja_servicio'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Tomó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
                echo json_encode(['resultado' => 'success', 'mensaje' => 'Hoja de servicio asignada']);
            } else {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'Error al asignar hoja']);
            }
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Registrar detalles de la hoja
    if (isset($_POST['registrar_detalles'])) {
        try {
            // Verificar permisos
            $sqlVerificar = "SELECT cedula_tecnico FROM hoja_servicio 
                            WHERE codigo_hoja_servicio = :codigo";
            $stmt = $hojaServicio->Conex()->prepare($sqlVerificar);
            $stmt->bindParam(':codigo', $_POST['codigo_hoja_servicio'], PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hoja || ($hoja['cedula_tecnico'] != $_SESSION['user']['cedula'] && $_SESSION['user']['id_rol'] != 5)) {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene permisos para modificar esta hoja']);
                exit;
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_detalles($_POST['detalles']);

            $datos = $hojaServicio->Transaccion(['peticion' => 'registrar_detalles']);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Listar técnicos por área
    if (isset($_POST['listar_tecnicos'])) {
        try {
            if ($_SESSION['user']['id_rol'] != 5) {
                echo json_encode(['resultado' => 'error', 'mensaje' => 'No tiene permisos para esta acción']);
                exit;
            }

            $datos = $empleado->listarTecnicosPorArea($_POST['id_servicio']);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Listar tipos de servicio
    if (isset($_POST['listar_tipos'])) {
        try {
            $datos = $tipoServicio->Transaccion(['peticion' => 'consultar']);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Reporte PDF de hojas de servicio
    if (isset($_POST['generar_reporte'])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();
        

        // Filtros de fechas y tipo de servicio
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $id_tipo_servicio = $_POST['id_tipo_servicio'] ?? null;

        // Obtener datos desde el modelo
        $datos = $hojaServicio->reporteHojasServicio($fecha_inicio, $fecha_fin, $id_tipo_servicio);

        // HTML para el PDF
        $html = "";
        require_once "view/Dompdf/servicios.php";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("reporte_hojas_servicio_" . date('Ymd') . ".pdf", array("Attachment" => false));
        exit;
    }

    // Modificar hoja de servicio (superusuario o técnico asignado)
    if (isset($_POST["actualizar"])) {
        try {
            $hojaServicio->set_codigo_hoja_servicio($_POST["codigo_hoja_servicio"]);
            // Solo superusuario puede cambiar tipo de servicio
            if ($_SESSION['user']['id_rol'] == 5 && isset($_POST["id_tipo_servicio"])) {
                $hojaServicio->set_id_tipo_servicio($_POST["id_tipo_servicio"]);
            }
            if (isset($_POST["resultado_hoja_servicio"])) {
                $hojaServicio->set_resultado_hoja_servicio($_POST["resultado_hoja_servicio"]);
            }
            if (isset($_POST["observacion"])) {
                $hojaServicio->set_observacion($_POST["observacion"]);
            }
            // Detalles técnicos (opcional)
            if (isset($_POST["detalles"])) {
                $detalles = json_decode($_POST["detalles"], true);
                $hojaServicio->set_detalles($detalles);
            }
            $datos = $hojaServicio->Transaccion([
                'peticion' => 'actualizar',
                'usuario' => $_SESSION['user']
            ]);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(['resultado' => 'error', 'mensaje' => $e->getMessage()]);
        }
        exit;
    }

    // Cargar vista principal
    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
        
