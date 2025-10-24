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

    // CONSTANTES DE VALIDACIÓN CORREGIDAS - MÁS FLEXIBLES
    define('REGEX_ID_EQUIPO', '/^[a-zA-Z0-9]{1,30}$/'); // CORREGIDO: 1-30 caracteres
    define('REGEX_CODIGO_BIEN', '/^[0-9a-zA-Z\-]{1,20}$/'); // CORREGIDO: 1-20 caracteres
    define('REGEX_SERIAL', '/^[0-9a-zA-ZáéíóúüñÑçÇ.\-\s]{1,45}$/'); // CORREGIDO: 1-45 caracteres
    define('REGEX_TIPO_EQUIPO', '/^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.]{1,45}$/'); // CORREGIDO: 1-45 caracteres
    define('REGEX_ID_UNIDAD', '/^[A-Z0-9]{1,30}$/'); // CORREGIDO: 1-30 caracteres

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Equipos";
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['equipo']['registrar']['estado']) && $permisos['equipo']['registrar']['estado'] == '1') {
            // Validaciones más flexibles para el frontend
            if (empty($_POST["codigo_bien"]) || $_POST["codigo_bien"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Debe seleccionar un código de bien válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó código de bien";

            } else if (empty($_POST["serial"]) || !preg_match(REGEX_SERIAL, $_POST["serial"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Serial no válido. Debe contener caracteres alfanuméricos (máximo 45 caracteres)";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió serial no válido";

            } else if (empty($_POST["id_unidad"]) || $_POST["id_unidad"] == "default" || !preg_match(REGEX_ID_UNIDAD, $_POST["id_unidad"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Unidad no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió unidad no válida";

            } else if (empty($_POST["tipo_equipo"]) || !preg_match(REGEX_TIPO_EQUIPO, $_POST["tipo_equipo"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Tipo de Equipo no válido. Debe contener caracteres alfanuméricos (máximo 45 caracteres)";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió tipo de equipo no válido";

            } else {
                $equipo->set_id_equipo(generarID($_POST["tipo_equipo"]));
                $equipo->set_tipo_equipo($_POST["tipo_equipo"]);
                $equipo->set_serial($_POST["serial"]);
                $equipo->set_codigo_bien($_POST["codigo_bien"]);
                $equipo->set_id_unidad($_POST["id_unidad"]);
                $peticion["peticion"] = "registrar";
                $json = $equipo->Transaccion($peticion);

                // Normaliza la respuesta para el frontend
                $json['resultado'] = "registrar";
                if (!isset($json['estado'])) {
                    $json['estado'] = isset($json['bool']) ? $json['bool'] : 0;
                }
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo equipo";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo equipo";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['estado'] = 0;
            $json['mensaje'] = "Error: No tienes permiso para registrar Equipo";
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
            // Validaciones corregidas - más flexibles
            if (empty($_POST["id_equipo"]) || !preg_match(REGEX_ID_EQUIPO, $_POST["id_equipo"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: ID de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ID de equipo no válido: " . $_POST["id_equipo"];

            } else if (empty($_POST["codigo_bien"]) || $_POST["codigo_bien"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Debe seleccionar un código de bien válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó código de bien";

            } else if (empty($_POST["serial"]) || !preg_match(REGEX_SERIAL, $_POST["serial"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Serial no válido. Debe contener caracteres alfanuméricos (máximo 45 caracteres)";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió serial no válido";

            } else if (empty($_POST["id_unidad"]) || $_POST["id_unidad"] == "default" || !preg_match(REGEX_ID_UNIDAD, $_POST["id_unidad"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Unidad no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió unidad no válida";

            } else if (empty($_POST["tipo_equipo"]) || !preg_match(REGEX_TIPO_EQUIPO, $_POST["tipo_equipo"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: Tipo de Equipo no válido. Debe contener caracteres alfanuméricos (máximo 45 caracteres)";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió tipo de equipo no válido";

            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $equipo->set_tipo_equipo($_POST["tipo_equipo"]);
                $equipo->set_serial($_POST["serial"]);
                $equipo->set_codigo_bien($_POST["codigo_bien"]);
                $equipo->set_id_unidad($_POST["id_unidad"]);
                $peticion["peticion"] = "actualizar";
                $json = $equipo->Transaccion($peticion);

                // Normaliza la respuesta
                if (!isset($json['estado'])) {
                    $json['estado'] = 1;
                }
                if (!isset($json['resultado'])) {
                    $json['resultado'] = "modificar";
                }
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del equipo " . $_POST["id_equipo"];
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar equipo " . $_POST["id_equipo"];
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['estado'] = 0;
            $json['mensaje'] = "Error: No tienes permiso para modificar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "Equipo");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['equipo']['eliminar']['estado']) && $permisos['equipo']['eliminar']['estado'] == '1') {
            if (empty($_POST["id_equipo"]) || !preg_match(REGEX_ID_EQUIPO, $_POST["id_equipo"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: ID de Equipo no válido";
                $json['estado'] = 0;
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ID de equipo no válido: " . $_POST["id_equipo"];
            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $peticion["peticion"] = "eliminar";
                $json = $equipo->Transaccion($peticion);

                $json['resultado'] = "eliminar";
                if (!isset($json['estado'])) {
                    $json['estado'] = isset($json['bool']) ? $json['bool'] : 0;
                }
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó el equipo " . $_POST["id_equipo"];
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar equipo " . $_POST["id_equipo"];
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['estado'] = 0;
            $json['mensaje'] = "Error: No tienes permiso para eliminar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        Bitacora($msg, "Equipo");
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['detalle'])) {
        if (empty($_POST["id_equipo"]) || !preg_match(REGEX_ID_EQUIPO, $_POST["id_equipo"])) {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error: ID de Equipo no válido";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ID de equipo no válido: " . $_POST["id_equipo"];
        } else {
            $peticion["peticion"] = "detalle";
            $equipo->set_id_equipo($_POST['id_equipo']);
            $json = $equipo->Transaccion($peticion);
            $json['resultado'] = "detalle";
        }
        
        echo json_encode($json);
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
        $datos = $equipo->Transaccion($peticion);
        // Extrae el array de equipos eliminados correctamente
        $json = [
            'resultado' => 'consultar_eliminadas',
            'datos' => isset($datos['datos']) && is_array($datos['datos']) ? $datos['datos'] : [],
        ];
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['equipo']['reactivar']['estado']) && $permisos['equipo']['reactivar']['estado'] == '1') {
            if (empty($_POST["id_equipo"]) || !preg_match(REGEX_ID_EQUIPO, $_POST["id_equipo"])) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error: ID de Equipo no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ID de equipo no válido: " . $_POST["id_equipo"];

            } else {
                $equipo->set_id_equipo($_POST["id_equipo"]);
                $peticion["peticion"] = "restaurar";
                $json = $equipo->Transaccion($peticion);
                // Normaliza la respuesta
                $json['resultado'] = "reactivar";
                if (!isset($json['estado'])) {
                    $json['estado'] = isset($json['bool']) ? $json['bool'] : 0;
                }
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró el equipo " . $_POST["id_equipo"];
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar equipo " . $_POST["id_equipo"];
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['estado'] = 0;
            $json['mensaje'] = "Error: No tienes permiso para reactivar Equipo";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Equipo");
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>