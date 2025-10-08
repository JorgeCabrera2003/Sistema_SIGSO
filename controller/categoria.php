<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesion Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/categoria.php";
    require_once "model/tipo_servicio.php";

    $titulo = "Gestionar Categorías";
    $cabecera = array('#', "Nombre", "Servico Dirigido", "Modificar/Eliminar");

    $categoria = new Categoria();
    $tipo_servicio = new TipoServicio();

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

    if (isset($_POST['consultar_tipoServicio'])) {
        $peticion['peticion'] = "consultar";
        $json = $tipo_servicio->Transaccion($peticion);
        $json['resultado'] = "consultar_tipoServicio";
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['categoria']['registrar']['estado']) && $permisos['categoria']['registrar']['estado'] == '1') {
            if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de la Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $categoria->set_id(generarID($_POST["nombre"]));
                $categoria->set_nombre($_POST["nombre"]);
                $categoria->set_id_servicio($_POST['id_tipoServicio']);
                $tipo_servicio->set_codigo($_POST['id_tipoServicio']);
                $peticion["peticion"] = "registrar";
                $json = $categoria->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msgN = "Se registró una Nueva Categoria";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 'CATEG01520251001', 'accion' => 'ver']);

                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva Categoria con ID: " . $categoria->get_id();
                    $texto = $tipo_servicio->Transaccion(['peticion' => 'validar']);
                    if ($json['servicio_asignado'] == 1) {
                        $msgPropio = "Se le asignó está Categoria: " . $_POST["nombre"] . " a su Área de Servicio";
                        Notificar($msgPropio, "Categoria", $texto['arreglo']['cedula_encargado']);
                        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró una nueva Categoria y fue asignada al Servicio: " . $texto['arreglo']['nombre_tipo_servicio'];
                    }

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
        $json = $categoria->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $categoria->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['categoria']['reactivar']['estado']) && $permisos['categoria']['reactivar']['estado'] == '1') {
            if (!isset($_POST["id_categoria"]) || preg_match(c_regex['ID_Generado'], $_POST["id_categoria"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $categoria->set_id($_POST["id_categoria"]);
                $peticion["peticion"] = "reactivar";
                $json = $categoria->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un Categoria con el id: " . $_POST["id_categoria"];
                    $msgN = "Se registró una Nuevo Categoria";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 'CATEG01520251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar una Categoria";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para reactivar un Categoria";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Categoria");
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['categoria']['modificar']['estado']) && $permisos['categoria']['modificar']['estado'] == '1') {
            if (!isset($_POST["id_categoria"]) || preg_match(c_regex['ID_Generado'], $_POST["id_categoria"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else if (!isset($_POST["nombre"]) || preg_match(c_regex['Nombre_NaturalCorto'], $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre de la Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $categoria->set_id($_POST["id_categoria"]);
                $categoria->set_nombre($_POST["nombre"]);
                $categoria->set_id_servicio($_POST['id_tipoServicio']);
                $buscarServicio = $categoria->Transaccion(['peticion' => 'validar']);
                $tipo_servicio->set_codigo($_POST['id_tipoServicio']);
                $peticion["peticion"] = "actualizar";
                $json = $categoria->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del Categoria con el id: " . $_POST["id_categoria"];
                    $msgN = "Categoria con ID: " . $_POST["id_categoria"] . " fue modificado";

                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 'CATEG01520251001', 'accion' => 'ver']);
                    $texto = $tipo_servicio->Transaccion(['peticion' => 'validar']);

                    if ($json['servicio_asignado'] == 1 && ($buscarServicio['arreglo']['id_tipo_servicio'] != $texto['arreglo']['id_tipo_servicio'])) {
                        $msgPropio = "Se le asignó está Categoria: " . $_POST["nombre"] . " a su Área de Servicio";
                        Notificar($msgPropio, "Categoria", $texto['arreglo']['cedula_encargado']);
                        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó una Categoria y fue asignada al Servicio: " . $texto['arreglo']['nombre_tipo_servicio'];
                    }
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
            if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $_POST["id_categoria"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id del Categoria no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";

            } else {
                $categoria->set_id($_POST["id_categoria"]);
                $peticion["peticion"] = "eliminar";
                $json = $categoria->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó una Categoria con el id: " . $_POST["id_categoria"];
                    $msgN = "Categoria con ID: " . $_POST["id_categoria"] . " fue eliminado";
                    NotificarUsuarios($msgN, "Categoria", ['modulo' => 'CATEG01520251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Categoria";
                }
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