<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/bien.php";

    $titulo = "Gestionar Bienes";
    $cabecera = array('#', "Código", "Tipo", "Marca", "Descripción", "Estado", "Oficina", "Empleado", "Modificar/Eliminar");

    $bien = new Bien();

    if (!isset($permisos['bien']['ver']['estado']) || $permisos['bien']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Bien";
        Bitacora($msg, "Bien");
        header('Location: ?page=home');
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Bienes";
        Bitacora($msg, "Bien");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['bien']['registrar']['estado']) && $permisos['bien']['registrar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_marca"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_oficina"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{3,45}$/", $_POST["estado"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/", $_POST["descripcion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $ci_empleado = $_POST["cedula_empleado"];
                if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula_empleado"]) == 0) {
                    $ci_empleado = NULL;
                }
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $bien->set_id_tipo_bien($_POST["id_tipo_bien"]);
                $bien->set_id_marca($_POST["id_marca"]);
                $bien->set_id_oficina($_POST["id_oficina"]);
                $bien->set_cedula_empleado($ci_empleado);
                $bien->set_descripcion($_POST["descripcion"]);
                $bien->set_estado($_POST["estado"]);
                $peticion["peticion"] = "registrar";
                $json = $bien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo bien";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo bien";
                }
            }

        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Bien");
        exit;
    }



    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $bien->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $bien->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["restaurar"])) {
        if (isset($permisos['bien']['restaurar']['estado']) && $permisos['bien']['restaurar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $peticion["peticion"] = "restaurar";
                $json = $bien->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un bien";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al restaurar un nuevo bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para restaurar un Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'restaurar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Bien");
        exit;
    }



    if (isset($_POST["modificar"])) {
        if (isset($permisos['bien']['modificar']['estado']) && $permisos['bien']['modificar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_tipo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Tipo de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_marca"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9]{1,11}$/", $_POST["id_oficina"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[a-zA-ZáéíóúüñÑçÇ]{3,45}$/", $_POST["estado"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/", $_POST["descripcion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Marca no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $ci_empleado = $_POST["cedula_empleado"];
                if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula_empleado"]) == 0) {
                    $ci_empleado = NULL;
                }
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $bien->set_id_tipo_bien($_POST["id_tipo_bien"]);
                $bien->set_id_marca($_POST["id_marca"]);
                $bien->set_id_oficina($_POST["id_oficina"]);
                $bien->set_cedula_empleado($ci_empleado);
                $bien->set_descripcion($_POST["descripcion"]);
                $bien->set_estado($_POST["estado"]);
                $peticion["peticion"] = "actualizar";
                $json = $bien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó un bien";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar un bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar un Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Bien");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['bien']['eliminar']['estado']) && $permisos['bien']['eliminar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $peticion["peticion"] = "eliminar";
                $json = $bien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un bien";
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar un Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Bien");
        exit;
    }

    if (isset($_POST['consultar_tipos_bien'])) {
        $peticion["peticion"] = "consultar_tipos_bien";
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = "consultar_tipos_bien";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_marcas'])) {
        $peticion["peticion"] = "consultar_marcas";
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = "consultar_marcas";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_oficinas'])) {
        $peticion["peticion"] = "consultar_oficinas";
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = "consultar_oficinas";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_empleados'])) {
        $peticion["peticion"] = "consultar_empleados";
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = "consultar_empleados";
        echo json_encode($json);
        exit;
    }

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}
?>