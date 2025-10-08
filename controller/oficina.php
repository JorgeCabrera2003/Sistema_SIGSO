<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/oficina.php";
    require_once "model/piso.php";

    $titulo = "Gestionar Oficinas";
    $cabecera = array('#', "Nombre", "Piso", "Modificar/Eliminar");

    $oficina = new Oficina();
    $piso = new Piso();

    if (!isset($permisos['oficina']['ver']['estado']) || $permisos['oficina']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Tipo de Bien";
        Bitacora($msg, "Oficina");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Oficina";
        Bitacora($msg, "Oficina");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['oficina']['registrar']['estado']) && $permisos['oficina']['registrar']['estado'] == '1') {
            if (!isset($_POST["nombre"]) || preg_match(C_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de la Oficina no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (!isset($_POST["id_piso"]) || preg_match(C_regex['ID_Generado'], $_POST["id_piso"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Piso no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $oficina->set_id(generarID($_POST["nombre"], $_POST["id_piso"]));
                $oficina->set_id_piso($_POST["id_piso"]);
                $oficina->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "registrar";
                $json = $oficina->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva oficina";
                    $msgN = "Se registró una Nueva Oficina";
                    NotificarUsuarios($msgN, "Oficina", ['modulo' => 'OFICIN02320251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar una nueva oficina";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar una Oficina";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Oficina");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $oficina->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $oficina->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['oficina']['reactivar']['estado']) && $permisos['oficina']['reactivar']['estado'] == '1') {
            if (!isset($_POST["id_oficina"]) || preg_match(c_regex['ID_Generado'], $_POST["id_oficina"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de la OFicina no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $oficina->set_id($_POST["id_oficina"]);
                $peticion["peticion"] = "reactivar";
                $json = $oficina->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró el registro de la oficina con el id: " . $_POST["id_oficina"];
                    $msgN = "Oficina con ID: " . $_POST["id_oficina"] . " fue restaurada";
                    NotificarUsuarios($msgN, "Oficina", ['modulo' => 'OFICIN02320251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar la oficina";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para reactivar una Oficina";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
        }
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_pisos'])) {
        $peticion["peticion"] = "consultar";
        $json = $piso->Transaccion($peticion);
        $json['resultado'] = "consultar_pisos";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['oficina']['modificar']['estado']) && $permisos['oficina']['modificar']['estado'] == '1') {
            if (!isset($_POST["id_oficina"]) || preg_match(c_regex['ID_Generado'], $_POST["id_oficina"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Oficina no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (!isset($_POST["id_piso"]) || preg_match(c_regex['ID_Generado'], $_POST["id_piso"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Piso no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de Oficina no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {

                $oficina->set_id($_POST["id_oficina"]);
                $oficina->set_id_piso($_POST["id_piso"]);
                $oficina->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "actualizar";
                $json = $oficina->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro de la oficina con el id: " . $_POST["id_oficina"];
                    $msgN = "Oficina con ID: " . $_POST["id_oficina"] . " fue modificada";
                    NotificarUsuarios($msgN, "Oficina", ['modulo' => 'OFICIN02320251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar oficina";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar una Oficina";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Oficina");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['oficina']['eliminar']['estado']) && $permisos['oficina']['eliminar']['estado'] == '1') {
            if (!isset($_POST["id_oficina"]) || preg_match(c_regex['ID_Generado'], $_POST["id_oficina"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Oficina no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $oficina->set_id($_POST["id_oficina"]);
                $peticion["peticion"] = "eliminar";
                $json = $oficina->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó una oficina con el id: " . $_POST["id_oficina"];
                    $msgN = "Oficina con ID: " . $_POST["id_oficina"] . " fue eliminada";
                    NotificarUsuarios($msgN, "Oficina", ['modulo' => 'OFICIN02320251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar una oficina";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar una Oficina";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Oficina");
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>