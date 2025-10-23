<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/bien.php";
    require_once "model/equipo.php";
    require_once "model/empleado.php"; // Necesario para notificaciones

    $titulo = "Gestionar Bienes";
    $cabecera = array('#', "Código Bien", "Categoria", "Marca", "Descripción", "Estado", "Oficina", "Empleado", "Modificar/Eliminar");

    $bien = new Bien();
    $equipo = new Equipo();
    $empleado = new Empleado(); // Para notificaciones

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

    if (isset($_POST['permisos_modulo'])) {
        $json['resultado'] = 'permisos_modulo';
        $json['permisos'] = $permisos;
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['bien']['registrar']['estado']) && $permisos['bien']['registrar']['estado'] == '1') {
            // Validar código de bien
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió código de bien no válido";

                // Validar tipo de bien (categoría) - CORREGIDO
            } else if (empty($_POST["id_categoria"]) || $_POST["id_categoria"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar un Tipo de Bien";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó tipo de bien";

                // Validar marca - CORREGIDO
            } else if (empty($_POST["id_marca"]) || $_POST["id_marca"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar una Marca";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó marca";

                // Validar oficina - CORREGIDO
            } else if (empty($_POST["id_oficina"]) || $_POST["id_oficina"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar una Oficina";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó oficina";

                // Validar estado - CORREGIDO
            } else if (empty($_POST["estado"]) || $_POST["estado"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar un Estado";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), no seleccionó estado";

                // Validar descripción
            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/", $_POST["descripcion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Descripción no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió descripción no válida";
            } else {
                $ci_empleado = $_POST["cedula_empleado"];
                // Validar cédula (puede ser opcional)
                if (!empty($_POST["cedula_empleado"]) && $_POST["cedula_empleado"] != "default") {
                    if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula_empleado"]) == 0) {
                        $json['resultado'] = "error";
                        $json['mensaje'] = "Error, Cédula del empleado no válida";
                        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió cédula no válida";
                        echo json_encode($json);
                        Bitacora($msg, "Bien");
                        exit;
                    }
                } else {
                    $ci_empleado = NULL;
                }

                // Todos los datos son válidos, proceder con el registro
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $bien->set_id_categoria($_POST["id_categoria"]);
                $bien->set_id_marca($_POST["id_marca"]);
                $bien->set_id_oficina($_POST["id_oficina"]);
                $bien->set_cedula_empleado($ci_empleado);
                $bien->set_descripcion($_POST["descripcion"]);
                $bien->set_estado($_POST["estado"]);
                $peticion["peticion"] = "registrar";
                $json = $bien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo bien";

                    // Notificación al usuario que registró el bien
                    $Nmsg = "Has registrado exitosamente el bien con código: " . $_POST["codigo_bien"];
                    Notificar($Nmsg, "Bien", $_SESSION['user']['cedula']);

                    // Si se asignó a un empleado, notificar al empleado
                    if ($ci_empleado !== NULL) {
                        $Nmsg = "Se te ha asignado el bien con código: " . $_POST["codigo_bien"];
                        Notificar($Nmsg, "Bien", $ci_empleado);
                    }

                    // Registrar equipo si corresponde
                    if (isset($_POST['registrar_equipo']) && $_POST['registrar_equipo'] == '1') {
                        require_once "model/equipo.php";
                        $equipo = new Equipo();

                        // Validar campos del equipo
                        if (preg_match("/^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/", $_POST["serial_equipo"]) == 0) {
                            $jsonEquipo['resultado'] = "error";
                            $jsonEquipo['mensaje'] = "Error, Serial no válido";
                        } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/", $_POST["tipo_equipo"]) == 0) {
                            $jsonEquipo['resultado'] = "error";
                            $jsonEquipo['mensaje'] = "Error, Tipo de Equipo no válido";
                        } else if (empty($_POST["id_unidad_equipo"]) || $_POST["id_unidad_equipo"] == "default") {
                            $jsonEquipo['resultado'] = "error";
                            $jsonEquipo['mensaje'] = "Debe seleccionar una unidad para el equipo";
                        } else {
                            $equipo->set_id_equipo(generarID($_POST["tipo_equipo"]));
                            $equipo->set_tipo_equipo($_POST["tipo_equipo"]);
                            $equipo->set_serial($_POST["serial_equipo"]);
                            $equipo->set_codigo_bien($_POST["codigo_bien"]);
                            $equipo->set_id_unidad($_POST["id_unidad_equipo"]);
                            $peticionEquipo["peticion"] = "registrar";
                            $jsonEquipo = $equipo->Transaccion($peticionEquipo);

                            // Si el equipo se registró correctamente
                            if ($jsonEquipo['estado'] == 1) {
                                $msgEquipo = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo equipo asociado al bien " . $_POST["codigo_bien"];
                                Bitacora($msgEquipo, "Bien");

                                // Notificación del equipo
                                $Nmsg = "Has registrado exitosamente el equipo con serial: " . $_POST["serial_equipo"] . " asociado al bien: " . $_POST["codigo_bien"];
                                Notificar($Nmsg, "Equipo", $_SESSION['user']['cedula']);
                            }
                        }
                        $json['equipo'] = $jsonEquipo;
                    }
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

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['bien']['reactivar']['estado']) && $permisos['bien']['reactivar']['estado'] == '1') {
            if (preg_match("/^[0-9a-zA-Z\-]{3,20}$/", $_POST["codigo_bien"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Código de Bien no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $peticion["peticion"] = "reactivar";
                $json = $bien->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un bien";

                    // Notificación al usuario que restauró el bien
                    $Nmsg = "Has restaurado el bien con código: " . $_POST["codigo_bien"];
                    Notificar($Nmsg, "Bien", $_SESSION['user']['cedula']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar un nuevo bien";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para reactivar un Bien";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
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
            } else if (empty($_POST["id_categoria"]) || $_POST["id_categoria"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar un Tipo de Bien";
            } else if (empty($_POST["id_marca"]) || $_POST["id_marca"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar una Marca";
            } else if (empty($_POST["id_oficina"]) || $_POST["id_oficina"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar una Oficina";
            } else if (empty($_POST["estado"]) || $_POST["estado"] == "default") {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, debe seleccionar un Estado";
            } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.,]{3,100}$/", $_POST["descripcion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Descripción no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $ci_empleado = $_POST["cedula_empleado"];
                $ci_empleado_anterior = $_POST["cedula_empleado_anterior"] ?? null;

                if (preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $_POST["cedula_empleado"]) == 0) {
                    $ci_empleado = NULL;
                }
                $bien->set_codigo_bien($_POST["codigo_bien"]);
                $bien->set_id_categoria($_POST["id_categoria"]);
                $bien->set_id_marca($_POST["id_marca"]);
                $bien->set_id_oficina($_POST["id_oficina"]);
                $bien->set_cedula_empleado($ci_empleado);
                $bien->set_descripcion($_POST["descripcion"]);
                $bien->set_estado($_POST["estado"]);
                $peticion["peticion"] = "actualizar";
                $json = $bien->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó un bien";

                    // Notificación al usuario que modificó el bien
                    $Nmsg = "Has modificado el bien con código: " . $_POST["codigo_bien"];
                    Notificar($Nmsg, "Bien", $_SESSION['user']['cedula']);

                    // Notificar al nuevo empleado asignado (si cambió)
                    if ($ci_empleado !== NULL && $ci_empleado !== $ci_empleado_anterior) {
                        $Nmsg = "Se te ha asignado el bien con código: " . $_POST["codigo_bien"];
                        Notificar($Nmsg, "Bien", $ci_empleado);
                    }

                    // Notificar al empleado anterior (si fue removido del bien)
                    if ($ci_empleado_anterior !== NULL && $ci_empleado !== $ci_empleado_anterior) {
                        $Nmsg = "Se te ha removido la asignación del bien con código: " . $_POST["codigo_bien"];
                        Notificar($Nmsg, "Bien", $ci_empleado_anterior);
                    }
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

                    // Notificación al usuario que eliminó el bien
                    $Nmsg = "Has eliminado el bien con código: " . $_POST["codigo_bien"];
                    Notificar($Nmsg, "Bien", $_SESSION['user']['cedula']);

                    // Notificar al empleado asignado (si tenía uno)
                    $ci_empleado = $_POST["cedula_empleado"] ?? null;
                    if ($ci_empleado !== NULL && preg_match("/^[V]{1}[-]{1}[0-9]{7,10}$/", $ci_empleado)) {
                        $Nmsg = "Se ha eliminado el bien con código: " . $_POST["codigo_bien"] . " que tenías asignado";
                        Notificar($Nmsg, "Bien", $ci_empleado);
                    }
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

    if (isset($_POST['consultar_bienes_empleado']) && isset($_POST['cedula_empleado'])) {
        $peticion = [
            'peticion' => 'consultar_bienes_empleado',
            'cedula_empleado' => $_POST['cedula_empleado']
        ];
        $json = $bien->Transaccion($peticion);
        $json['resultado'] = 'consultar_bienes_empleado';
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_unidades'])) {
        require_once "model/unidad.php";
        $unidad = new Unidad();
        $json = $unidad->Transaccion(['peticion' => 'consultar']);
        $json['resultado'] = "consultar_unidades";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['consultar_unidad_equipo'])) {
        require_once "model/unidad.php";
        $unidad = new Unidad();
        $json = $unidad->Transaccion(['peticion' => 'consultar']);
        $json['resultado'] = "consultar_unidad_equipo";
        echo json_encode($json);
        exit;
    }

    // Si no es una petición AJAX, cargar la vista
    if (empty($_POST)) {
        require_once "view/" . $page . ".php";
    }
} else {
    require_once "view/404.php";
}
?>