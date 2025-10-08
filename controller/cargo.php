<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesión Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "config/cargo.php";
    require_once "controller/utileria.php";
    require_once "model/cargo.php";

    $titulo = "Gestionar Cargos";
    $cabecera = array('#', "Nombre", "Modificar/Eliminar");

    $cargo = new Cargo();

    if (!isset($permisos['cargo']['ver']['estado']) || $permisos['cargo']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Cargo";
        Bitacora($msg, "Cargo");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Cargo";
        Bitacora($msg, "Cargo");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['cargo']['registrar']['estado']) && $permisos['cargo']['registrar']['estado'] == '1') {
            if (!isset($_POST["nombre_cargo"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre_cargo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Cargo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $cargo->set_id(generarID($_POST['nombre_cargo']));
                $cargo->set_nombre($_POST["nombre_cargo"]);
                $peticion["peticion"] = "registrar";
                $json = $cargo->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo cargo";
                    $msgN = "Se registró una Nuevo Cargo";
                    NotificarUsuarios($msgN, "Cargo", ['modulo' => 'CARGO01220251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo cargo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar un Cargo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Cargo");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $cargo->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminados"])) {
        $peticion["peticion"] = "consultar_eliminados";
        $json = $cargo->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['cargo']['reactivar']['estado']) && $permisos['cargo']['reactivar']['estado'] == '1') {
            if (!isset($_POST["id_cargo"]) || preg_match(c_regex['ID_Generado'], $_POST["id_cargo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Cargo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $cargo->set_id($_POST["id_cargo"]);
                $peticion["peticion"] = "reactivar";
                $json = $cargo->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró una Cargo con el id" . $_POST["id_cargo"];
                    $msgN = "Se restauró una Cargo con el id: " . $_POST["id_cargo"];
                    NotificarUsuarios($msgN, "Cargo", ['modulo' => 10, 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar una Cargo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para reactivar una Cargo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Cargo");
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['cargo']['modificar']['estado']) && $permisos['cargo']['modificar']['estado'] == '1') {
            if (!isset($_POST["id_cargo"]) || preg_match(c_regex['ID_Generado'], $_POST["id_cargo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de la Marca no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else if (!isset($_POST["nombre_cargo"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre_cargo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Cargo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else if ($_POST["id_cargo"] == Ccargo[0]['id']) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Este Cargo por defecto no puede modificarse";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $cargo->set_id($_POST["id_cargo"]);
                $cargo->set_nombre($_POST["nombre_cargo"]);
                $peticion["peticion"] = "actualizar";
                $json = $cargo->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del cargo con el id: " . $_POST["id_cargo"];
                    $msgN = "Cargo con ID: " . $_POST["id_cargo"] . " fue modificado";
                    NotificarUsuarios($msgN, "Cargo", ['modulo' => 'CARGO01220251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar cargo";
                }
            }

        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar un Cargo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "Cargo");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['cargo']['eliminar']['estado']) && $permisos['cargo']['eliminar']['estado'] == '1') {
            if (preg_match(c_regex['ID_Generado'], $_POST["id_cargo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de la Marca no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else if ($_POST["id_cargo"] == Ccargo[0]['id']) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Este Cargo por defecto no puede eliminarse";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $cargo->set_id($_POST["id_cargo"]);
                $peticion["peticion"] = "eliminar";
                $json = $cargo->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un cargocon el id: " . $_POST["id_cargo"];
                    $msgN = "Cargo con ID: " . $_POST["id_cargo"] . " fue eliminado";
                    NotificarUsuarios($msgN, "Cargo", ['modulo' => 'CARGO01220251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un cargo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar un Cargo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Cargo");
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>