<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/categoria.php";

    $titulo = "Gestionar Tipos de Bien";
    $cabecera = array('#', "Nombre", "Modificar/Eliminar");

    $tipoBien = new Categoria();

    if (!isset($permisos['categoria']['ver']['estado']) || $permisos['categoria']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Categoria";
        Bitacora($msg, "Categoria");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Tipos de Bien";
        Bitacora($msg, "Categoria");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['categoria']['registrar']['estado']) && $permisos['categoria']['registrar']['estado'] == '1') {
            if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de la Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "registrar";
                $json = $tipoBien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo Categoria";
                    $msgN = "Se registró una Nueva Categoria";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 13, 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nueva Categoria";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar un Categoria";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Categoria");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $tipoBien->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $tipoBien->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["restaurar"])) {
        if (isset($permisos['categoria']['restaurar']['estado']) && $permisos['categoria']['restaurar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_id($_POST["id_tipo_bien"]);
                $peticion["peticion"] = "restaurar";
                $json = $tipoBien->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un Categoria con el id" . $_POST["id_tipo_bien"];
                    $msgN = "Se registró una Nuevo Categoria";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 15, 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al restaurar un nuevo Categoria";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para restaurar un Categoria";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'restaurar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Categoria");
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['categoria']['modificar']['estado']) && $permisos['categoria']['modificar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de la Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_id($_POST["id_tipo_bien"]);
                $tipoBien->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "actualizar";
                $json = $tipoBien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del Categoria con el id: " . $_POST["id_tipo_bien"];
                    $msgN = "Categoria con ID: " . $_POST["id_tipo_bien"] . " fue modificado";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 15, 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar Categoria";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar un Categoria";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Categoria");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['categoria']['eliminar']['estado']) && $permisos['categoria']['eliminar']['estado'] == '1') {
            $tipoBien->set_id($_POST["id_tipo_bien"]);
            $peticion["peticion"] = "eliminar";
            $json = $tipoBien->Transaccion($peticion);

            if ($json['estado'] == 1) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un Categoria con el id" . $_POST["id_tipo_bien"];
                $msgN = "Categoria con ID: " . $_POST["id_tipo_bien"] . " fue eliminado";
                NotificarUsuarios($msgN, "Categoria", ['modulo' => 15, 'accion' => 'ver']);
            } else {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Categoria";
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar un Categoria";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Categoria");
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>