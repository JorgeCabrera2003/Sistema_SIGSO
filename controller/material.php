<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesión Finalizada.";
}

ob_start();
if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    require_once "model/material.php";
    require_once "model/oficina.php";

    $titulo = "Materiales";
    $cabecera = array('#', "Nombre", "Ubicación", "Stock", "Modificar/Historial/Eliminar");

    $material = new Material();
    $oficina = new Oficina();

    // CONSTANTES DE VALIDACIÓN UNIFICADAS CON EL MODELO
    define('REGEX_ID_MATERIAL', '/^[A-Z0-9\-_]{1,50}$/');
    define('REGEX_NOMBRE_MATERIAL', '/^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.,()]{1,100}$/');
    define('REGEX_ID_OFICINA', '/^[A-Z0-9]{1,30}$/');
    define('REGEX_STOCK', '/^[0-9]{1,6}$/'); // 0-999999

    // Verificar permisos primero
    if (!isset($permisos['material']['ver']['estado']) || $permisos['material']['ver']['estado'] == "0") {
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), intentó entrar al Módulo de Material";
        Bitacora($msg, "Material");
        header('Location: ?page=home');
        exit;
    }

    // Manejar petición de permisos del módulo
    if (isset($_POST["permisos_modulo"])) {
        $json['resultado'] = "permisos_modulo";
        $json['permisos'] = $permisos;
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["entrada"])) {
        $json['resultado'] = "entrada";
        echo json_encode($json);
        $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Materiales";
        Bitacora($msg, "Material");
        exit;
    }

    if (isset($_POST["registrar"])) {
        if (isset($permisos['material']['registrar']['estado']) && $permisos['material']['registrar']['estado'] == '1') {
            if (!isset($_POST["nombre"]) || preg_match(REGEX_NOMBRE_MATERIAL, $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Material no válido. Debe tener 1-100 caracteres alfanuméricos";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió nombre de material no válido";
            } else if (!isset($_POST["ubicacion"]) || preg_match(REGEX_ID_OFICINA, $_POST["ubicacion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Ubicación no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ubicación no válida";
            } else if (!isset($_POST["stock"]) || preg_match(REGEX_STOCK, $_POST["stock"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Stock no válido. Debe ser un número entre 0 y 999999";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió stock no válido";
            } else {
                $material->set_id(generarID($_POST["nombre"]));
                $material->set_nombre($_POST["nombre"]);
                $material->set_ubicacion($_POST["ubicacion"]);
                $material->set_stock($_POST["stock"]);
                $peticion["peticion"] = "registrar";
                $json = $material->Transaccion($peticion);
                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se registró un nuevo material";
                    $msgN = "Se registró un Nuevo Material";
                    NotificarUsuarios($msgN, "Material", ['modulo' => 'MATER02420251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al registrar un nuevo material";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para registrar Material";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'registrar' denegado";
        }

        echo json_encode($json);
        Bitacora($msg, "Material");
        exit;
    }

    if (isset($_POST['consultar'])) {
        $peticion["peticion"] = "consultar";
        $json = $material->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['detalle'])) {
        if (preg_match(REGEX_ID_MATERIAL, $_POST["id_material"]) == 0) {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, Id no válido";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
        } else {
            $peticion["peticion"] = "detalle";
            $material->set_id($_POST['id_material']);
            $json = $material->Transaccion($peticion);
            $json['resultado'] = "detalle";
        }

        echo json_encode($json);
        exit;
    }

    if (isset($_POST["modificar"])) {
        if (isset($permisos['material']['modificar']['estado']) && $permisos['material']['modificar']['estado'] == '1') {
            if (!isset($_POST["id_material"]) || preg_match(REGEX_ID_MATERIAL, $_POST["id_material"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else if (!isset($_POST["nombre"]) || preg_match(REGEX_NOMBRE_MATERIAL, $_POST["nombre"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Nombre del Material no válido. Debe tener 1-100 caracteres alfanuméricos";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió nombre de material no válido";
            } else if (!isset($_POST["ubicacion"]) || preg_match(REGEX_ID_OFICINA, $_POST["ubicacion"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Ubicación no válida";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió ubicación no válida";
            } else if (!isset($_POST["stock"]) || preg_match(REGEX_STOCK, $_POST["stock"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Stock no válido. Debe ser un número entre 0 y 999999";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió stock no válido";
            } else {
                $material->set_id($_POST["id_material"]);
                $material->set_nombre($_POST["nombre"]);
                $material->set_ubicacion($_POST["ubicacion"]);
                $material->set_stock($_POST["stock"]);
                $peticion["peticion"] = "actualizar";
                $json = $material->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se modificó el registro del Material con el id: " . $_POST["id_material"];
                    $msgN = "Material con ID: " . $_POST["id_material"] . " fue modificado";
                    NotificarUsuarios($msgN, "Material", ['modulo' => 'MATER02420251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al modificar Material";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para modificar Material";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'modificar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Material");
        exit;
    }

    if (isset($_POST["consultar_eliminadas"])) {
        $peticion["peticion"] = "consultar_eliminadas";
        $json = $material->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST["reactivar"])) {
        if (isset($permisos['material']['reactivar']['estado']) && $permisos['material']['reactivar']['estado'] == '1') {
            if (!isset($_POST["id_material"]) || preg_match(REGEX_ID_MATERIAL, $_POST["id_material"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $material->set_id($_POST["id_material"]);
                $peticion["peticion"] = "reactivar";
                $json = $material->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró un Material";
                    $msgN = "Material con ID: " . $_POST["id_material"] . " fue restaurado";
                    NotificarUsuarios($msgN, "Material", ['modulo' => 'MATER02420251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al reactivar un Material";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para reactivar Material";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'reactivar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Material");
        exit;
    }

    if (isset($_POST["eliminar"])) {
        if (isset($permisos['material']['eliminar']['estado']) && $permisos['material']['eliminar']['estado'] == '1') {
            if (!isset($_POST["id_material"]) || preg_match(REGEX_ID_MATERIAL, $_POST["id_material"]) == 0) {
                $json['resultado'] = "error";
                $json['mensaje'] = "Error, Id no válido";
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), envió solicitud no válida";
            } else {
                $material->set_id($_POST["id_material"]);
                $peticion["peticion"] = "eliminar";
                $json = $material->Transaccion($peticion);

                if ($json['estado'] == 1) {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se eliminó un Material";
                    $msgN = "Material con ID: " . $_POST["id_material"] . " fue eliminado";
                    NotificarUsuarios($msgN, "Material", ['modulo' => 'MATER02420251001', 'accion' => 'ver']);
                } else {
                    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), error al eliminar un Material";
                }
            }
        } else {
            $json['resultado'] = "error";
            $json['mensaje'] = "Error, No tienes permiso para eliminar Material";
            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), permiso 'eliminar' denegado";
        }
        echo json_encode($json);
        Bitacora($msg, "Material");
        exit;
    }

    if (isset($_POST['generar_reporte'])) {
        $material = new Material();

        // Validar fechas
        $peticion['fecha_inicio'] = $_POST['fecha_inicio'];
        $peticion['fecha_fin'] = $_POST['fecha_fin'];
        $peticion['peticion'] = 'reporte';

        // Consultar materiales
        $resultado = $material->Transaccion($peticion);

        if ($resultado['resultado'] == 'success') {
            // Crear PDF
            require_once('view/Dompdf/material.php');

            // Configurar domPDF
            ob_start();
            require_once('vendor/autoload.php');
            require_once('model/conexion.php');
            $dompdf = new Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Descargar el PDF
            $dompdf->stream("reporte_materiales_" . date('Ymd') . ".pdf", array("Attachment" => false));
        } else {
            // Manejar error
            echo "Error al generar el reporte: " . $resultado['mensaje'];
        }
    }

    // Obtener lista de oficinas para el select
    $peticion_oficina["peticion"] = "consultar";
    $dato_oficina = $oficina->Transaccion($peticion_oficina);
    if ($dato_oficina['resultado'] == "consultar") {
        $oficinas = $dato_oficina['datos'];
    } else {
        $oficinas = [];
    }

    // Pass the data to the view
    $page = "material";
    $titulo = "Materiales";

    require_once "view/" . $page . ".php";
} else {
    require_once "view/404.php";
}

ob_end_flush();
?>