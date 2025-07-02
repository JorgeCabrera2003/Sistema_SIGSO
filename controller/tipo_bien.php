<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/tipo_bien.php";

    $titulo = "Gestionar Tipos de Bien";
    $cabecera = array('#', "Nombre", "Modificar/Eliminar");

    $tipoBien = new TipoBien();

    if (!isset($permisos['tipo_bien']['ver']['estado']) || $permisos['tipo_bien']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Tipo de Bien";
        Bitacora($msg, "TipoBien");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Tipos de Bien";
        Bitacora($msg, "TipoBien");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['tipo_bien']['registrar']['estado']) && $permisos['tipo_bien']['registrar']['estado'] == '1') {
            if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "registrar";
                $json = $tipoBien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo tipo de bien";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo tipo de bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar un Tipo de Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "TipoBien");
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
        if (isset($permisos['tipo_bien']['restaurar']['estado']) && $permisos['tipo_bien']['restaurar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_id($_POST["id_tipo_bien"]);
                $peticion["peticion"] = "restaurar";
                $json = $tipoBien->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un tipo de bien con el id". $_POST["id_tipo_bien"];
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al restaurar un nuevo tipo de bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para restaurar un Tipo de Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'restaurar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "TipoBien");
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['tipo_bien']['modificar']['estado']) && $permisos['tipo_bien']['modificar']['estado'] == '1') {
            if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if(preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,45}$/", $_POST["nombre"]) == 0){
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $tipoBien->set_id($_POST["id_tipo_bien"]);
                $tipoBien->set_nombre($_POST["nombre"]);
                $peticion["peticion"] = "actualizar";
                $json = $tipoBien->Transaccion($peticion);
                
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del tipo de bien con el id: ".$_POST["id_tipo_bien"];
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar tipo de bien";
                } 
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar un Tipo de Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "TipoBien");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['tipo_bien']['eliminar']['estado']) && $permisos['tipo_bien']['eliminar']['estado'] == '1') {
        $tipoBien->set_id($_POST["id_tipo_bien"]);
        $peticion["peticion"] = "eliminar";
        $json = $tipoBien->Transaccion($peticion);
        
        if ($json['estado'] == 1) {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un tipo de bien con el id". $_POST["id_tipo_bien"];
        } else {
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un tipo de bien";
        }
    } else {
        $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar un Tipo de Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
    }
        echo json_encode($json);
        Bitacora($msg, "TipoBien");
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>