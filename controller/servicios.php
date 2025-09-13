<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo '<script>window.location="?page=login"</script>';
    $_SESSION['msg']['danger'] = "Sesión Finalizada.";
    exit;
}
$rolesPermitidos = [2, 1];


ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/hoja_servicio.php";
    require_once "model/empleado.php";
    require_once "model/tipo_servicio.php";
    require_once "model/material.php";
    require_once "model/componente.php";
    require_once "model/servicio_prestado.php";
    require_once "model/componente_atendido.php";
    require_once "model/servicio_realizado.php";

    $titulo = "Gestión de Hojas de Servicio";
    $cabecera = array('#', "N° Solicitud", "Tipo Servicio", "Solicitante", "Equipo", "Marca", "Serial", "Código Bien", "Motivo", "Fecha Solicitud", "Técnico", "Estado", "Acciones");

    $hojaServicio = new HojaServicio();
    $empleado = new Empleado();
    $tipoServicio = new TipoServicio();
    $material = new Material();

    // Forzar respuesta JSON para peticiones AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
    }


    if (isset($_POST['registrar_detalles'])) {
        try {
            if (empty($_POST['codigo_hoja_servicio'])) {
                throw new Exception('Código de hoja no especificado');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);

            // Procesar detalles
            $detalles = [];
            if (!empty($_POST['detalles'])) {
                $detalles = is_string($_POST['detalles']) ?
                    json_decode($_POST['detalles'], true) : $_POST['detalles'];
            }
            $hojaServicio->set_detalles($detalles);

            $resultado = $hojaServicio->registrarDetalles();

            echo json_encode([
                'resultado' => $resultado ? 'success' : 'error',
                'mensaje' => $resultado ? 'Detalles registrados' : 'Error al registrar detalles'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Servicios Técnicos";
        Bitacora($msg, "Servicio");
        exit;
    }

    if (isset($_POST["listar"])) {
        try {
            $datos = $hojaServicio->Transaccion([
                'peticion' => 'listar',
                'usuario' => $_SESSION['user']
            ]);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => 'Error al listar hojas: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Registrar nueva hoja de servicio (solo superusuario)
    if (isset($_POST["registrar"])) {
        try {
            if ($_SESSION['user']['id_rol'] != 1 || $_SESSION['user']['id_rol'] != 3) {
                throw new Exception('No tiene permisos para esta acción');
            }

            // Validar datos requeridos
            if (empty($_POST["nro_solicitud"]) || empty($_POST["id_tipo_servicio"])) {
                throw new Exception('Datos incompletos para registrar');
            }

            $hojaServicio->set_nro_solicitud($_POST["nro_solicitud"]);
            $hojaServicio->set_id_tipo_servicio($_POST["id_tipo_servicio"]);

            // Procesar detalles si existen
            $detalles = [];
            if (!empty($_POST["detalles"])) {
                $detalles = is_string($_POST["detalles"]) ?
                    json_decode($_POST["detalles"], true) : $_POST["detalles"];
            }

            $peticion = [
                'peticion' => 'crear',
                'detalles' => $detalles
            ];

            $datos = $hojaServicio->Transaccion($peticion);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Registró la hoja de servicio #" . $datos['codigo'];
                Bitacora($msg, "Servicio");
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Error al registrar servicio: " . $datos['mensaje'];
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    if (isset($_POST["eliminar"])) {
        try {
            

            // Validar datos requeridos
            if (empty($_POST["codigo_hoja_servicio"])) {
                throw new Exception('Datos incompletos para registrar');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST["codigo_hoja_servicio"]);


            $peticion = [
                'peticion' => 'eliminar'
            ];

            $datos = $hojaServicio->Transaccion($peticion);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Eliminó la hoja de servicio #" . $datos['codigo'];
                Bitacora($msg, "Servicio");
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Error al eliminar servicio: " . $datos['mensaje'];
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    if (isset($_POST['redireccionar'])) {
        try {
            

            // Validar datos requeridos
            if (empty($_POST['codigo_hoja_servicio']) || empty($_POST['area_destino'])) {
                throw new Exception('Datos incompletos para redireccionar');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);

            $datos = $hojaServicio->Transaccion([
                'peticion' => 'redireccionar',
                'area_destino' => $_POST['area_destino'],
                'tecnico_destino' => $_POST['tecnico_destino'] ?? null
            ]);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Redireccionó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Error al redireccionar servicio: " . $datos['mensaje'];
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    if (isset($_POST['obtener_tecnicos_por_area'])) {
        try {
            if (empty($_POST['area_id'])) {
                throw new Exception('ID de área no especificado');
            }

            $areaId = (int)$_POST['area_id'];
            $peticion = [
                'peticion' => 'obtener_tecnicos_por_area',
                'area_id' => $areaId
            ];

            $tecnicos = $hojaServicio->Transaccion($peticion);

            echo json_encode($tecnicos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Consultar hoja de servicio
    if (isset($_POST['consultar'])) {
        try {
            if (empty($_POST['codigo_hoja_servicio'])) {
                throw new Exception('Código de hoja no especificado');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $datos = $hojaServicio->Transaccion(['peticion' => 'consultar']);

            if ($datos['resultado'] === 'success') {
                // Verificar permisos para ver esta hoja
                $puedeVer = false;
                $infoUsuario = $_SESSION['user'];

                // Superusuario puede ver todo
                if ($infoUsuario['id_rol'] == 1) {
                    $puedeVer = true;
                }
                // Técnico puede ver si es de su area o la ha tomado
                else {
                    $infoTecnico = $empleado->obtenerTecnico($infoUsuario['cedula']);
                    if (
                        $infoTecnico &&
                        ($datos['datos']['id_tipo_servicio'] == $infoTecnico['id_servicio'] ||
                            $datos['datos']['cedula_tecnico'] == $infoUsuario['cedula'])
                    ) {
                        $puedeVer = true;
                    }
                }

                if ($puedeVer) {
                    $msg = "(" . $infoUsuario['nombre_usuario'] . "), Consultó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                    Bitacora($msg, "Servicio");
                    echo json_encode($datos);
                } else {
                    throw new Exception('No tiene permisos para ver esta hoja de servicio');
                }
            } else {
                echo json_encode($datos);
            }
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Finalizar hoja de servicio
    if (isset($_POST['finalizar'])) {
        try {
            // Validar datos requeridos
            if (empty($_POST['codigo_hoja_servicio']) || empty($_POST['resultado_hoja_servicio'])) {
                throw new Exception('Datos incompletos para finalizar');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_cedula_tecnico($_SESSION['user']['cedula']);
            $hojaServicio->set_resultado_hoja_servicio($_POST['resultado_hoja_servicio']);
            $hojaServicio->set_observacion($_POST['observacion'] ?? '');

            // Verificar que el técnico es el asignado a esta hoja
            $sqlVerificar = "SELECT cedula_tecnico FROM hoja_servicio 
            WHERE codigo_hoja_servicio = :codigo";

            $stmt = $hojaServicio->Conex()->prepare($sqlVerificar);
            $stmt->bindParam(':codigo', $_POST['codigo_hoja_servicio'], PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hoja || ($hoja['cedula_tecnico'] != $_SESSION['user']['cedula'] && $_SESSION['user']['id_rol'] != 1 || $_SESSION['user']['id_rol'] != 3)) {
                throw new Exception('No tiene permisos para finalizar esta hoja');
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
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Tomar una hoja de servicio (para tecnicos)
    if (isset($_POST['tomar_hoja'])) {
        try {
            // Validar datos requeridos
            if (empty($_POST['codigo_hoja_servicio'])) {
                throw new Exception('Código de hoja no especificado');
            }

            // Verificar que el usuario es técnico
            if ($_SESSION['user']['id_rol'] != 2 && $_SESSION['user']['id_rol'] != 1 && $_SESSION['user']['id_rol'] != 3) {
                throw new Exception('Solo los técnicos pueden tomar hojas de servicio');
            }

            // Verificar que la hoja es del área del tecnico
            $infoTecnico = $empleado->obtenerTecnico($_SESSION['user']['cedula']);
            if (!$infoTecnico || !$infoTecnico['id_servicio']) {
                throw new Exception('No tiene un área de servicio asignada');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_cedula_tecnico($_SESSION['user']['cedula']);

            $datos = $hojaServicio->Transaccion(['peticion' => 'tomar_hoja']);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Tomó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Actualizar hoja de servicio
    if (isset($_POST["peticion"]) && $_POST["peticion"] === "actualizar") {
        try {
            // Validar datos requeridos
            if (empty($_POST["codigo_hoja_servicio"])) {
                throw new Exception('Código de hoja no especificado');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST["codigo_hoja_servicio"]);

            // Solo superusuario puede cambiar tipo de servicio
            if ($_SESSION['user']['id_rol'] == 1 && isset($_POST["id_tipo_servicio"])) {
                $hojaServicio->set_id_tipo_servicio($_POST["id_tipo_servicio"]);
            }

            if (isset($_POST["resultado_hoja_servicio"])) {
                $hojaServicio->set_resultado_hoja_servicio($_POST["resultado_hoja_servicio"]);
            }

            if (isset($_POST["observacion"])) {
                $hojaServicio->set_observacion($_POST["observacion"]);
            }

            // Procesar detalles tecnicos
            if (isset($_POST["detalles"])) {
                $detalles = is_string($_POST["detalles"]) ?
                    json_decode($_POST["detalles"], true) : $_POST["detalles"];
                $hojaServicio->set_detalles($detalles);
            }

            $datos = $hojaServicio->Transaccion([
                'peticion' => 'actualizar',
                'usuario' => $_SESSION['user']
            ]);

            if ($datos['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Actualizó la hoja de servicio #" . $_POST["codigo_hoja_servicio"];
                Bitacora($msg, "Servicio");
            }

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => 'Error al procesar la actualización: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    // Consultar detalles técnicos de una hoja de servicio
    if (isset($_POST['consultar_detalles'])) {
        try {
            if (empty($_POST['codigo_hoja_servicio'])) {
                throw new Exception('Código de hoja no especificado');
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $detalles = $hojaServicio->Transaccion(['peticion' => 'consultar_detalles']);

            // Obtener informacion de materiales para los detalles
            if (is_array($detalles) && !empty($detalles)) {
                $materialesDisponibles = $material->listarDisponibles();
                if ($materialesDisponibles['resultado'] === 'success') {
                    $materiales = $materialesDisponibles['datos'];
                    foreach ($detalles as &$detalle) {
                        if ($detalle['id_material']) {
                            $materialEncontrado = array_filter($materiales, function ($m) use ($detalle) {
                                return $m['id_material'] == $detalle['id_material'];
                            });
                            if (!empty($materialEncontrado)) {
                                $materialEncontrado = reset($materialEncontrado);
                                $detalle['material_info'] = [
                                    'nombre' => $materialEncontrado['nombre_material'],
                                    'stock' => $materialEncontrado['stock']
                                ];
                            }
                        }
                    }
                }
            }

            echo json_encode($detalles);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Listar tipos de servicio disponibles para una solicitud
    if (isset($_POST['tipos_disponibles'])) {
        try {
            if (empty($_POST['nro_solicitud'])) {
                throw new Exception('Número de solicitud no especificado');
            }

            $hojaServicio->set_nro_solicitud($_POST['nro_solicitud']);
            $datos = $hojaServicio->Transaccion(['peticion' => 'tipos_disponibles']);
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Listar tipos de servicio

    if (isset($_POST['listar_tipos'])) {
        try {
            $datos = $tipoServicio->Transaccion(['peticion' => 'consultar']);
            // Asegurarse de que la respuesta tenga el formato correcto
            if ($datos['resultado'] === 'consultar') {
                echo json_encode([
                    'resultado' => 'success',
                    'datos' => $datos['datos']
                ]);
            } else {
                echo json_encode([
                    'resultado' => 'error',
                    'mensaje' => 'Formato de respuesta inesperado'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }


    if (isset($_POST['listar_materiales'])) {
        try {
            $datos = $material->listarDisponibles();
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }


    if (isset($_POST['generar_reporte'])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();

        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $id_tipo_servicio = $_POST['id_tipo_servicio'] ?? null;


        if ($fecha_inicio && $fecha_fin && $fecha_inicio > $fecha_fin) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => 'La fecha de inicio no puede ser mayor a la fecha final'
            ]);
            exit;
        }

        $datos = $hojaServicio->reporteHojasServicio($fecha_inicio, $fecha_fin, $id_tipo_servicio);

        ob_start();
        $html;
        require_once "view/Dompdf/servicios.php";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $nombreArchivo = "reporte_hojas_servicio_" . date('Ymd_His');
        if ($id_tipo_servicio) {
            $tipo = array_filter($tipos_servicio, function ($t) use ($id_tipo_servicio) {
                return $t['id_tipo_servicio'] == $id_tipo_servicio;
            });
            if (!empty($tipo)) {
                $tipo = reset($tipo);
                $nombreArchivo .= "_" . strtolower(str_replace(' ', '_', $tipo['nombre_tipo_servicio']));
            }
        }
        $nombreArchivo .= ".pdf";

        $dompdf->stream($nombreArchivo, array("Attachment" => false));
        exit;
    }

    // ******************Finalizar hoja de servicio **********
    if (isset($_POST['peticion']) && $_POST['peticion'] === 'finalizar') {
        try {
            if (!isset($_SESSION['user'])) {
                throw new Exception('Sesión no iniciada');
            }

            // Solo tecnicos y superusuarios pueden finalizar la hoja
            if (!in_array($_SESSION['user']['id_rol'], [1, 2, 3])) {
                throw new Exception('No tiene permisos para esta acción');
            }

            $camposRequeridos = ['codigo_hoja_servicio', 'resultado_hoja_servicio'];
            foreach ($camposRequeridos as $campo) {
                if (empty($_POST[$campo])) {
                    throw new Exception("El campo $campo es requerido");
                }
            }

            $hojaServicio->set_codigo_hoja_servicio($_POST['codigo_hoja_servicio']);
            $hojaServicio->set_cedula_tecnico($_SESSION['user']['cedula']);
            $hojaServicio->set_resultado_hoja_servicio($_POST['resultado_hoja_servicio']);
            $hojaServicio->set_observacion($_POST['observacion'] ?? '');

            $resultado = $hojaServicio->Transaccion([
                'peticion' => 'finalizar',
                'usuario' => $_SESSION['user']
            ]);

            if ($resultado['resultado'] === 'success') {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Finalizó la hoja de servicio #" . $_POST['codigo_hoja_servicio'];
                Bitacora($msg, "Servicio");
            }

            echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    if(isset($_POST['traer_item'])){

    }

    if(isset($_POST['traer_servicios'])){
    $response = $tipoServicio->Transaccion(['peticion' => 'consultar']);
    echo json_encode($response);
    exit;
    }
    if (isset($_POST['pdf_hoja_servicio'])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();

        $codigo_hoja = $_POST['pdf_hoja_servicio'];
        $hojaServicio->set_codigo_hoja_servicio($codigo_hoja);
        $datos = $hojaServicio->Transaccion(['peticion' => 'consultar']);

        // HTML para el PDF individual
        ob_start();
        $datos_hoja = $datos['datos'];
        require "view/Dompdf/hoja_servicio_pdf.php";
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = "hoja_servicio_" . $codigo_hoja . ".pdf";
        $dompdf->stream($nombreArchivo, array("Attachment" => false));
        exit;
    }

    // Agregar este nuevo if para el caso de imprimir sin finalizar
    if (isset($_POST['imprimir_hoja_servicio'])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();

        $codigo_hoja = $_POST['imprimir_hoja_servicio'];
        $hojaServicio->set_codigo_hoja_servicio($codigo_hoja);
        $datos = $hojaServicio->Transaccion(['peticion' => 'consultar']);

        // HTML para el PDF individual
        ob_start();
        $datos_hoja = $datos['datos'];
        require "view/Dompdf/hoja_servicio_pdf.php";
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = "hoja_servicio_" . $codigo_hoja . ".pdf";
        $dompdf->stream($nombreArchivo, array("Attachment" => false));
        exit;
    }

    // Reporte PDF de una hoja de servicio individual al finalizar
    if (isset($_POST['pdf_hoja_servicio'])) {
        require_once "vendor/autoload.php";
        $dompdf = new Dompdf\Dompdf();

        $codigo_hoja = $_POST['pdf_hoja_servicio'];
        $hojaServicio->set_codigo_hoja_servicio($codigo_hoja);
        $datos = $hojaServicio->Transaccion(['peticion' => 'consultar']);

        // HTML para el PDF individual
        ob_start();
        $datos_hoja = $datos['datos'];
        require "view/Dompdf/hoja_servicio_pdf.php";
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = "hoja_servicio_" . $codigo_hoja . ".pdf";
        $dompdf->stream($nombreArchivo, array("Attachment" => false));
        exit;
    }


    if (isset($_POST['get_user_data'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'rol' => ($_SESSION['user']['id_rol'] == 1 ? 'SUPERUSUARIO' : ($_SESSION['user']['id_rol'] == 2 ? 'TECNICO' : 'OTRO')),
            'cedula' => $_SESSION['user']['cedula'],
            'nombre_usuario' => $_SESSION['user']['nombre_usuario'],
            'id_rol' => $_SESSION['user']['id_rol']
        ]);
        exit;
    }


    if (isset($_POST['obtener_tecnicos'])) {
        try {
            $tecnicos = $empleado->Transaccion(['peticion' => 'listar_tecnicos']);
            echo json_encode($tecnicos);
        } catch (Exception $e) {
            echo json_encode([
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
        exit;
    }

    // Si es una peticion AJAX pero no coincide con ningun endpoints devolver error JSON y no HTML para no dañar el sistema
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode([
            'resultado' => 'error',
            'mensaje' => 'Petición no reconocida'
        ]);
        exit;
    }

    // Agrega este bloque de permisos
    if (isset($_POST['permisos'])) {
        $json['resultado'] = 'permisos_modulo';
        $json['permisos'] = $permisos;
        echo json_encode($json);
        exit;
    }

    // Cargar vista principal
    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
