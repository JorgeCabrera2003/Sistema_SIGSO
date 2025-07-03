<?php

if (!$_SESSION) {
    header("Location: ?page=login");
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();

if (is_file("view/" . $page . ".php")) {

    require_once "controller/utileria.php";
    require_once "model/interconexion.php";
    require_once "model/switch_.php"; // Asegúrate de tener este modelo
    require_once "model/patch_panel.php";

    $titulo = "Gestionar Interconexión";
    $cabecera = array('ID', "Switch", "Puerto Switch", "Patch Panel", "Puerto Patch Panel", "Modificar / Eliminar");

    $interconexion = new interconexion();
    $switch_ = new switch_(); // Asegúrate de tener este modelo
    $patch_panel = new patch_panel();

    // Consultar switches
    $peticion_switch["peticion"] = "consultar";
    $switches = $switch_->Transaccion($peticion_switch);
    $switches = isset($switches['datos']) ? $switches['datos'] : [];

    // Consultar patch panels
    $peticion_patch["peticion"] = "consultar";
    $patch_panels = $patch_panel->Transaccion($peticion_patch);
    $patch_panels = isset($patch_panels['datos']) ? $patch_panels['datos'] : [];

    
     if (!isset($permisos['interconexion']['ver']['estado']) || $permisos['interconexion']['ver']['estado'] != "1") {
         $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Interconexión";
         Bitacora($msg, "interconexion");
         header("Location: ?page=home");
         exit;
     }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Interconexión";
        Bitacora($msg, "interconexion");
        exit;
    }

    if (isset($_POST["registrar"])) {

        $interconexion->set_codigo_switch($_POST["codigo_switch"]);
        $interconexion->set_puerto_switch($_POST["puerto_switch"]);
        $interconexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
        $interconexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);
        $peticion["peticion"] = "registrar";
        $json = $interconexion->Transaccion($peticion);

        if ($json['estado'] == 1) {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva Interconexión";
        } else {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar una nueva Interconexión";
        }

        echo json_encode($json);
        Bitacora($msg, "interconexion");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $interconexion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["modificar"])) {

        $interconexion->set_id_interconexion($_POST["id_interconexion"]);
        $interconexion->set_codigo_switch($_POST["codigo_switch"]);
        $interconexion->set_puerto_switch($_POST["puerto_switch"]);
        $interconexion->set_codigo_patch_panel($_POST["codigo_patch_panel"]);
        $interconexion->set_puerto_patch_panel($_POST["puerto_patch_panel"]);
        $peticion["peticion"] = "actualizar";
        $json = $interconexion->Transaccion($peticion);

        if ($json['estado'] == 1) {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó la Interconexión";
        } else {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar la Interconexión";
        }

        echo json_encode($json);
        Bitacora($msg, "interconexion");
        exit;
    }

    if (isset($_POST["eliminar"])) {

        $interconexion->set_id_interconexion($_POST["id_interconexion"]);
        $peticion["peticion"] = "eliminar";
        $json = $interconexion->Transaccion($peticion);

        if ($json['estado'] == 1) {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó una Interconexión";
        } else {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar una Interconexión";
        }

        echo json_encode($json);
        Bitacora($msg, "interconexion");
        exit;
    }

    if (isset($_POST['get_puertos_patch_panel'])) {
        require_once "model/punto_conexion.php";
        require_once "model/interconexion.php";
        $codigo_patch_panel = $_POST['codigo_patch_panel'];

        // 1. Obtener todos los puertos de puntos de conexión para ese patch panel
        $punto_conexion = new punto_conexion();
        $punto_conexion->set_codigo_patch_panel($codigo_patch_panel);
        $peticion = ["peticion" => "consultar"];
        $puertos = $punto_conexion->Transaccion($peticion);
        $puertos_disponibles = [];
        if (isset($puertos['datos'])) {
            foreach ($puertos['datos'] as $row) {
                if ($row['codigo_patch_panel'] == $codigo_patch_panel) {
                    $puertos_disponibles[] = $row['puerto_patch_panel'];
                }
            }
        }

        // 2. Obtener los puertos ya ocupados en interconexion
        $interconexion = new interconexion();
        $peticion = ["peticion" => "consultar"];
        $interconexiones = $interconexion->Transaccion($peticion);
        $puertos_ocupados = [];
        if (isset($interconexiones['datos'])) {
            foreach ($interconexiones['datos'] as $row) {
                if ($row['codigo_patch_panel'] == $codigo_patch_panel) {
                    $puertos_ocupados[] = $row['puerto_patch_panel'];
                }
            }
        }

        // 3. Filtrar: solo los puertos de puntos de conexión que NO están ocupados
        $puertos_finales = array_diff($puertos_disponibles, $puertos_ocupados);

        // 4. Si estás modificando, permite el puerto actual (opcional, si envías el id_interconexion y puerto actual)
        if (isset($_POST['puerto_actual']) && $_POST['puerto_actual'] != '') {
            $puertos_finales[] = $_POST['puerto_actual'];
            $puertos_finales = array_unique($puertos_finales);
            sort($puertos_finales, SORT_NUMERIC);
        }

        echo json_encode(array_values($puertos_finales));
        exit;
    }

    if (isset($_POST['get_puertos_switch'])) {
        require_once "model/switch_.php";
        $switch_ = new switch_();
        $codigo_switch = $_POST['codigo_switch'];

        // Obtener cantidad de puertos del switch
        $peticion = ["peticion" => "consultar"];
        $switches = $switch_->Transaccion($peticion);
        $cantidad_puertos = 0;
        if (isset($switches['datos'])) {
            foreach ($switches['datos'] as $sw) {
                if ($sw['codigo_bien'] == $codigo_switch) {
                    $cantidad_puertos = $sw['cantidad_puertos'];
                    break;
                }
            }
        }

        // Obtener puertos ocupados en interconexion
        require_once "model/interconexion.php";
        $interconexion = new interconexion();
        $peticion = ["peticion" => "consultar"];
        $interconexiones = $interconexion->Transaccion($peticion);
        $puertos_ocupados = [];
        if (isset($interconexiones['datos'])) {
            foreach ($interconexiones['datos'] as $row) {
                if ($row['codigo_switch'] == $codigo_switch) {
                    $puertos_ocupados[] = $row['puerto_switch'];
                }
            }
        }

        // Generar lista de puertos disponibles
        $puertos_disponibles = [];
        for ($i = 1; $i <= $cantidad_puertos; $i++) {
            if (!in_array($i, $puertos_ocupados)) {
                $puertos_disponibles[] = $i;
            }
        }

        echo json_encode($puertos_disponibles);
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}