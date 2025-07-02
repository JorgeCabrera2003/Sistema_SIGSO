<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/equipo.php";
    require_once "model/unidad.php";
    require_once "model/bien.php";
    require_once "model/dependencia.php";

    $titulo = "Gestionar Equipos";
    $cabecera = array('#', "Tipo", "Serial", "Código Bien", "Dependencia", "Unidad", "Modificar/Eliminar");

    $equipo = new Equipo();
    $unidad = new Unidad();
    $dependencia = new Dependencia();
    $bien = new Bien();

    if (!isset($permisos['equipo']['ver']['estado']) || $permisos['equipo']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Equipo";
        Bitacora($msg, "Equipo");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Equipos";
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['equipo']['registrar']['estado']) && $permisos['equipo']['registrar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/", $_POST["serial"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_unidad"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/", $_POST["tipo_equipo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {

                $equipo->set_tipo_equipo($_POST["tipo_equipo"]);
                $equipo->set_serial($_POST["serial"]);
                $equipo->set_codigo_bien($_POST["codigo_bien"]);
                $equipo->set_id_unidad($_POST["id_unidad"]);
                $peticion["peticion"] = "registrar";
                $json = $equipo->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo equipo";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo equipo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $equipo->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['equipo']['modificar']['estado']) && $permisos['equipo']['modificar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_equipo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/", $_POST["serial"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Serial no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_unidad"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Unidad no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/", $_POST["tipo_equipo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Tipo de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $equipo->set_tipo_equipo($_POST["tipo_equipo"]);
                $equipo->set_serial($_POST["serial"]);
                $equipo->set_codigo_bien($_POST["codigo_bien"]);
                $equipo->set_id_unidad($_POST["id_unidad"]);
                $peticion["peticion"] = "actualizar";
                $json = $equipo->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del equipo";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar equipo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['equipo']['eliminar']['estado']) && $permisos['equipo']['eliminar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_equipo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $peticion["peticion"] = "eliminar";
                $json = $equipo->Transaccion($peticion);
                echo json_encode($json);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un equipo";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un equipo";
                }
            }

        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST['filtrar_bien'])) {
        $peticion["peticion"] = "filtrar";
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = "filtrar_bien";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['cargar_unidad'])) {
        $peticion["peticion"] = "filtrar";
        $unidad->set_id_dependencia($_POST['id_dependencia']);
        $json = $unidad->Transaccion($peticion);
        $json['resultado'] = "consultar_unidad";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['cargar_dependencia'])) {
        $peticion["peticion"] = "consultar";
        $json = $dependencia->Transaccion($peticion);
        $json['resultado'] = "consultar_dependencia";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $equipo->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["restaurar"])) {
        if (isset($permisos['equipo']['restaurar']['estado']) && $permisos['equipo']['restaurar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_equipo"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $peticion["peticion"] = "restaurar";
                $json = $equipo->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del equipo";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar equipo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para restaurar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'restaurar' denegado";
        }
        echo json_encode($json);
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>