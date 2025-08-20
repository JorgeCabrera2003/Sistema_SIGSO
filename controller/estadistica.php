<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesión Finalizada.";
}

ob_start();

if (is_file("view/estadistica.php")) {
    $titulo = "Gestión de Infraestructura de Red";
    $css = ["alert", "style"];
    require_once "controller/utileria.php";
    require_once "model/estadistica.php";
    
    // Instancia única del modelo reporte
    $reporteModel = new reporte();
    
    // Obtener datos generales para el dashboard
    $cantidadEmpleados = $reporteModel->Transaccion(['peticion' => 'contar_empleados']);
    $cantidadHojas = $reporteModel->Transaccion(['peticion' => 'contar']);
    $cantidadTecnicos = $reporteModel->Transaccion(['peticion' => 'contar_tecnicos']);
    
    // Obtener pisos activos para los selects
    $piso = $reporteModel->Transaccion(['peticion' => 'consultar_pisos'])['datos'];
    
    // Procesar solicitud de gráficos
    if (isset($_POST["grafico"])) {
        $grafico = [
            'resultado' => 'grafico',
            'datos' => [
                'GraUsuario' => [
                    'label' => 'Usuarios',
                    'labels' => ['Usuarios', 'Empleados OFITIC', 'Empleados', 'Oficinas'],
                    'data' => [
                        $cantidadEmpleados['datos']['Total usuario'],
                        $cantidadEmpleados['datos']['Total empleados OFITIC'],
                        $cantidadEmpleados['datos']['Total empleados general'],
                        $cantidadEmpleados['datos']['Total oficina']
                    ],
                ],
                'Graftecnicos' => [
                    'label' => 'Técnicos',
                    'labels' => ['Soporte Técnico', 'Electrónica', 'Redes', 'Telefonía'],
                    'data' => [
                        $cantidadTecnicos['datos'][0]['Total soporte'],
                        $cantidadTecnicos['datos'][0]['Total electronica'],
                        $cantidadTecnicos['datos'][0]['Total redes'],
                        $cantidadTecnicos['datos'][0]['Total telefono']
                    ],
                ],
                'miGrafico' => [
                    'label' => 'Puntos de Red',
                    'labels' => ['Chequeados', 'Funcionando', 'Fuera de servicio'],
                    'data' => [28, 18, 10],
                ],
                'hojas' => [
                    'label' => 'Hojas de Servicio',
                    'labels' => ['Hojas eliminadas', 'Hojas en proceso', 'Hojas finalizadas'],
                    'data' => [
                        $cantidadHojas['datos'][0]['Hojas eliminadas'],
                        $cantidadHojas['datos'][0]['Hojas activas'],
                        $cantidadHojas['datos'][0]['Hojas finalizadas']
                    ]
                ],
            ]
        ];
        echo json_encode($grafico);
        exit;
    }
    
    // Procesar solicitud de infraestructura
    if (isset($_POST['peticion']) && $_POST['peticion'] === 'obtener_infraestructura') {
        $tipo = $_POST['tipo'];
        $id_piso = intval($_POST['id_piso']);
        
        if ($tipo === 'patch') {
            $reporte = $reporteModel->Transaccion([
                'peticion' => 'obtener_patch_panels',
                'id_piso' => $id_piso
            ]);
        } else {
            $reporte = $reporteModel->Transaccion([
                'peticion' => 'obtener_switches',
                'id_piso' => $id_piso
            ]);
        }
        
        echo json_encode($reporte);
        exit;
    }

    // Procesar solicitud de detalles de puerto
    if (isset($_POST['peticion']) && $_POST['peticion'] === 'detalles_puerto') {
        $codigo_dispositivo = $_POST['codigo_dispositivo'];
        $numero_puerto = intval($_POST['numero_puerto']);
        $tipo = $_POST['tipo'];
        
        $detalles = $reporteModel->Transaccion([
            'peticion' => 'detalles_puerto',
            'codigo_dispositivo' => $codigo_dispositivo,
            'numero_puerto' => $numero_puerto,
            'tipo' => $tipo
        ]);
        
        echo json_encode($detalles);
        exit;
    }
    
    // Procesar filtros para Patch Panel
    if (isset($_POST['pisoFiltrado'])) {
        $id_piso = intval($_POST['pisoFiltrado']);
        $reporte = $reporteModel->Transaccion([
            'peticion' => 'reporte_patch_panel',
            'id_piso' => $id_piso
        ]);
        echo json_encode($reporte);
        exit;
    }
    
    // Procesar filtros para Switches
    if (isset($_POST['pisoFiltradoSwitch'])) {
        $id_piso = intval($_POST['pisoFiltradoSwitch']);
        $reporte = $reporteModel->Transaccion([
            'peticion' => 'reporte_switch_panel',
            'id_piso' => $id_piso
        ]);
        echo json_encode($reporte);
        exit;
    }
    
    // Procesar filtros para reporte de bienes
    if (isset($_POST['filtro_bienes'])) {
        $filtros = [
            'peticion' => 'reporte_bienes',
            'id_tipo_bien' => $_POST['id_tipo_bien'] ?? null,
            'estado' => $_POST['estado'] ?? null,
            'id_oficina' => $_POST['id_oficina'] ?? null,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null
        ];
        $reporte = $reporteModel->Transaccion($filtros);
        echo json_encode($reporte);
        exit;
    }
    
    // Procesar filtros para reporte de solicitudes
    if (isset($_POST['filtro_solicitudes'])) {
        $filtros = [
            'peticion' => 'reporte_solicitudes',
            'cedula_tecnico' => $_POST['cedula_tecnico'] ?? null,
            'id_tipo_servicio' => $_POST['id_tipo_servicio'] ?? null,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null,
            'estatus' => $_POST['estatus'] ?? null
        ];
        $reporte = $reporteModel->Transaccion($filtros);
        echo json_encode($reporte);
        exit;
    }
    
    // Procesar filtros para reporte de materiales
    if (isset($_POST['filtro_materiales'])) {
        $filtros = [
            'peticion' => 'reporte_materiales',
            'id_material' => $_POST['id_material'] ?? null,
            'id_oficina' => $_POST['id_oficina'] ?? null,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null
        ];
        $reporte = $reporteModel->Transaccion($filtros);
        echo json_encode($reporte);
        exit;
    }
    
    // Procesar entrada al módulo
    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Dashboard";
        Bitacora($msg, "Dashboard");
        exit;
    }
    
    // Obtener datos para los selects de filtros
    $tiposBien = $reporteModel->Transaccion(['peticion' => 'listar_tipos_bien'])['datos'];
    $oficinas = $reporteModel->Transaccion(['peticion' => 'listar_oficinas'])['datos'];
    $tecnicos = $reporteModel->Transaccion(['peticion' => 'listar_tecnicos'])['datos'];
    $tiposServicio = $reporteModel->Transaccion(['peticion' => 'listar_tipos_servicio'])['datos'];
    $materiales = $reporteModel->Transaccion(['peticion' => 'listar_materiales'])['datos'];
    
    require_once "view/estadistica.php";
} else {
    require_once "view/404.php";
}
?>