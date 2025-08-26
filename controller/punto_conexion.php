<?php

if (!$_SESSION) {
    header("Location: ?page=login");
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();

if (is_file("view/" . $page . ".php")) {

    require_once "controller/utileria.php";
    require_once "model/punto_conexion.php";
    require_once "model/equipo.php";
    require_once "model/patch_panel.php";
    require_once "model/estadistica.php";



    $titulo = "Gestionar Punto de Conexión";
    $cabecera = array('ID', "Patch Panel", "Equipo", "Puerto", "Modificar / Eliminar");

    $punto_conexion = new punto_conexion();
    $equipo = new equipo();
    $patch_panel = new patch_panel();
    $reporteModel = new reporte();

    $peticion_equipos["peticion"] = "consultar";
    $equipos = $equipo->Transaccion($peticion_equipos);
    $equipos = isset($equipos['datos']) ? $equipos['datos'] : [];
    // Ahora cada equipo tiene el campo 'ocupado' (1 si está asignado, 0 si no)


    $peticion_patch["peticion"] = "consultar";
    $patch_panels = $patch_panel->Transaccion($peticion_patch);
    $patch_panels = isset($patch_panels['datos']) ? $patch_panels['datos'] : [];

    $peticion_pisos["peticion"] = "consultar_pisos";
    $pisos = $reporteModel->Transaccion($peticion_pisos);
    $pisos = isset($pisos['datos']) ? $pisos['datos'] : [];

    if (isset($_POST['peticion']) && $_POST['peticion'] === 'obtener_infraestructura_grafica') {
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

    if (isset($_POST['conectar_equipo'])) {
        $punto_conexion->set_id_equipo($_POST["id_equipo"]);
        $punto_conexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
        $punto_conexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);

        if ($_POST['accion'] == 'conectar') {
            $peticion["peticion"] = "registrar";
        } else {
            $peticion["peticion"] = "eliminar_por_datos";
        }

        $json = $punto_conexion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    // Procesar solicitud de equipos por oficina
    if (isset($_POST['peticion']) && $_POST['peticion'] === 'obtener_equipos_oficina') {
        $id_oficina = intval($_POST['id_oficina']);

        // Obtener equipos filtrados por oficina y solo los disponibles (no conectados)
        $equiposConectados = [];
        foreach ($puntos_conexion ?? [] as $punto) {
            if (!empty($punto['id_equipo'])) {
                $equiposConectados[] = $punto['id_equipo'];
            }
        }
        $equiposFiltrados = [];
        foreach ($equipos as $equipo) {
            if (
                isset($equipo['id_oficina']) && $equipo['id_oficina'] == $id_oficina
                && !in_array($equipo['id_equipo'], $equiposConectados)
            ) {
                $equiposFiltrados[] = $equipo;
            }
        }

        echo json_encode([
            'resultado' => 'success',
            'equipos' => $equiposFiltrados
        ]);
        exit;
    }

    if (isset($_POST['peticion']) && $_POST['peticion'] === 'consultar_equipos_estado') {
        $peticion_equipos["peticion"] = "consultar";
        $equipos = $equipo->Transaccion($peticion_equipos);
        echo json_encode($equipos);
        exit;
    }

    if (!isset($permisos['punto_conexion']['ver']['estado']) || $permisos['punto_conexion']['ver']['estado'] != "1") {

        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Punto de Conexión";

        Bitacora($msg, "punto_conexion");
        header("Location: ?page=home");

        exit;
    }

    if (isset($_POST["entrada"])) {

        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Punto de Conexión";
        Bitacora($msg, "punto conexion");

        exit;
    }

    if (isset($_POST["registrar"])) {

        if (isset($permisos['punto_conexion']['registrar']['estado']) && $permisos['punto_conexion']['registrar']['estado'] == '1') {

            $punto_conexion->set_id_equipo($_POST["id_equipo"]);
            $punto_conexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
            $punto_conexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);
            $peticion["peticion"] = "registrar";
            $json = $punto_conexion->Transaccion($peticion);

            if ($json['estado'] == 1) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo Punto de Conexión";
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo Punto de Conexión";
            }
        } else {

            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar un Punto de Conexión";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Permiso 'Registrar' Denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "punto_conexion");
        exit;
    }

    if (isset($_POST['consultar'])) {

        $peticion["peticion"] = "consultar";
        $json = $punto_conexion->Transaccion($peticion);
        echo json_encode($json);

        exit;
    }

    if (isset($_POST["modificar"])) {

        if (isset($permisos['punto_conexion']['modificar']['estado']) && $permisos['punto_conexion']['modificar']['estado'] == '1') {

            $punto_conexion->set_id_punto_conexion($_POST["id_punto_conexion"]);
            $punto_conexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
            $punto_conexion->set_id_equipo($_POST["id_equipo"]);
            $punto_conexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);
            $peticion["peticion"] = "actualizar";
            $json = $punto_conexion->Transaccion($peticion);

            if ($json['estado'] == 1) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el Punto de Conexión";
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar el Punto de Conexión";
            }
        } else {

            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar un Punto de Conexión";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Permiso 'Modificar' Denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "punto_conexion");

        exit;
    }

    if (isset($_POST["eliminar"])) {

        if (isset($permisos['punto_conexion']['eliminar']['estado']) && $permisos['punto_conexion']['eliminar']['estado'] == '1') {

            $punto_conexion->set_id_punto_conexion($_POST["id_punto_conexion"]);
            $peticion["peticion"] = "eliminar";
            $json = $punto_conexion->Transaccion($peticion);

            if ($json['estado'] == 1) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un Punto de Conexión";
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Punto de Conexión";
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar un Punto de Conexión";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Permiso 'Eliminar' Denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "punto_conexion");
        exit;
    }

    if (isset($_POST['get_puertos_patch_panel'])) {
        require_once "model/patch_panel.php";
        require_once "model/punto_conexion.php";
        $codigo_patch_panel = $_POST['codigo_patch_panel'];

        // 1. Obtener la cantidad de puertos del patch panel
        $patch_panel = new patch_panel();
        $peticion = ["peticion" => "consultar"];
        $patch_panels = $patch_panel->Transaccion($peticion);
        $cantidad_puertos = 0;
        if (isset($patch_panels['datos'])) {
            foreach ($patch_panels['datos'] as $pp) {
                if ($pp['codigo_bien'] == $codigo_patch_panel) {
                    $cantidad_puertos = $pp['cantidad_puertos'];
                    break;
                }
            }
        }

        // 2. Obtener los puertos ya ocupados en punto_conexion
        $punto_conexion = new punto_conexion();
        $punto_conexion->set_codigo_patch_panel($codigo_patch_panel);
        $peticion = ["peticion" => "consultar"];
        $puntos = $punto_conexion->Transaccion($peticion);
        $puertos_ocupados = [];
        if (isset($puntos['datos'])) {
            foreach ($puntos['datos'] as $row) {
                if ($row['codigo_patch_panel'] == $codigo_patch_panel) {
                    $puertos_ocupados[] = $row['puerto_patch_panel'];
                }
            }
        }

        // 3. Generar lista de puertos disponibles
        $puertos_disponibles = [];
        for ($i = 1; $i <= $cantidad_puertos; $i++) {
            if (!in_array($i, $puertos_ocupados)) {
                $puertos_disponibles[] = $i;
            }
        }

        // 4. Si estás modificando, permite el puerto actual (opcional)
        if (isset($_POST['puerto_actual']) && $_POST['puerto_actual'] != '') {
            $puertos_disponibles[] = $_POST['puerto_actual'];
            $puertos_disponibles = array_unique($puertos_disponibles);
            sort($puertos_disponibles, SORT_NUMERIC);
        }

        echo json_encode(array_values($puertos_disponibles));
        exit;
    }

    if (isset($_POST['get_equipos_disponibles'])) {
        require_once "model/equipo.php";
        require_once "model/punto_conexion.php";
        $equipo = new equipo();
        $punto_conexion = new punto_conexion();

        // Obtener todos los equipos
        $peticion = ["peticion" => "consultar"];
        $equipos = $equipo->Transaccion($peticion);
        $equipos = isset($equipos['datos']) ? $equipos['datos'] : [];

        // Obtener equipos ya conectados
        $peticion = ["peticion" => "consultar"];
        $puntos = $punto_conexion->Transaccion($peticion);
        $equipos_conectados = [];
        if (isset($puntos['datos'])) {
            foreach ($puntos['datos'] as $row) {
                $equipos_conectados[] = $row['id_equipo'];
            }
        }

        // Si estás modificando, permite el equipo actual
        if (isset($_POST['equipo_actual']) && $_POST['equipo_actual'] != '') {
            $equipos_conectados = array_diff($equipos_conectados, [$_POST['equipo_actual']]);
        }

        // Filtrar equipos disponibles (solo los que no están conectados)
        $equipos_disponibles = [];
        foreach ($equipos as $eq) {
            if (!in_array($eq['id_equipo'], $equipos_conectados)) {
                $equipos_disponibles[] = $eq;
            }
        }

        // Si estás modificando, agrega el equipo actual al principio
        if (isset($_POST['equipo_actual']) && $_POST['equipo_actual'] != '') {
            foreach ($equipos as $eq) {
                if ($eq['id_equipo'] == $_POST['equipo_actual']) {
                    array_unshift($equipos_disponibles, $eq);
                    break;
                }
            }
        }

        echo json_encode($equipos_disponibles);
        exit;
    }

    // Marcar puerto como dañado
    if (isset($_POST['marcar_danado'])) {
        $punto_conexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
        $punto_conexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);
        $peticion["peticion"] = "marcar_danado";
        $json = $punto_conexion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    require_once "view/" . $page . ".php";
} else {

    require_once "view/404.php";
}

?>
<?php

if (isset($_POST["reporte_puntos"])) {
    require_once "vendor/autoload.php";
    $dompdf = new Dompdf\Dompdf();

    require_once "model/punto_conexion.php";
    $punto_conexion = new punto_conexion();
    $datosReporte = $punto_conexion->Transaccion(["peticion" => "consultar"]);
    $resultado = isset($datosReporte['datos']) ? $datosReporte['datos'] : [];

    // Renderizar HTML para el PDF
    ob_start();
    require "view/Dompdf/puntos_conexion.php";
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $dompdf->stream("reporte_puntos_conexion_" . date('Ymd_His') . ".pdf", array("Attachment" => false));
    exit;
}
