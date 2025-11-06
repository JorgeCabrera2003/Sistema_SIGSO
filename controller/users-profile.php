<?php
if (!$_SESSION['user']) {
    echo '<script>window.location="?page=login"</script>';
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Sesión Finalizada',
        'message' => 'Por favor inicie sesión nuevamente'
    ];
}
ob_start();

if (is_file("view/" . $page . ".php")) {
    require_once "controller/utileria.php";
    $titulo = "Mi Perfil";
    // DEBUG: registrar POST/FILE/session para diagnosticar problemas del módulo Perfil
    try {
        $debugPath = __DIR__ . '/../logs/profile_debug.log';
        $dbg = fopen($debugPath, 'a');
        if ($dbg) {
            fwrite($dbg, "\n---- " . date('Y-m-d H:i:s') . " ----\n");
            fwrite($dbg, "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'cli') . "\n");
            fwrite($dbg, "SESSION_USER_PRESENT: " . (isset($_SESSION['user']) ? 'yes' : 'no') . "\n");
            fwrite($dbg, "POST_KEYS: " . json_encode(array_keys($_POST)) . "\n");
            fwrite($dbg, "FILES_KEYS: " . json_encode(array_keys($_FILES)) . "\n");
            fwrite($dbg, "RAW_POST: " . json_encode($_POST) . "\n");
            fwrite($dbg, str_repeat('-',40) . "\n");
            fclose($dbg);
        }
    } catch (Exception $e) {
        // no interrumpir la ejecución si falla el log
    }
    if (is_file($datos['foto'])) {
        $foto = $datos['foto'];
    }

    // Manejo de mensajes de sesión
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
    }

    if (isset($_POST['eliminarF'])) {
        $ruta_archivo = $datos['foto'];

        if (file_exists($ruta_archivo)) {
            if (unlink($ruta_archivo)) {
                // asegurar que el objeto usuario tenga la cédula establecida
                if (isset($datos['cedula'])) {
                    $usuario->set_cedula($datos['cedula']);
                }
                $usuario->set_foto('assets/img/default-profile.jpg');
                if ($usuario->Transaccion(['peticion' => 'actualizarFoto'])) {
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'title' => 'Foto eliminada',
                        'message' => 'La foto de perfil se ha eliminado correctamente'
                    ];
                    header("Location: ?page=users-profile");
                    exit();
                }
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Error al intentar eliminar el archivo'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'El archivo no existe'
            ];
        }
    }

    // Procesamiento de actualización de perfil (nombre, apellido, correo, teléfono, foto)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['Nombre']) || isset($_FILES['foto_perfil']) || isset($_POST['eliminarF']))) {
        // Procesamiento de foto de perfil
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $targetDir = "assets/img/foto-perfil/";
            $targetFile = $targetDir . basename($_FILES["foto_perfil"]["name"]);
            $extension = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
            $nuevoNombre = $datos['cedula'] . '.' . $extension;
            $targetFile = $targetDir . $nuevoNombre;

            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $targetFile)) {
                // asegurar que el objeto usuario tenga la cédula establecida
                if (isset($datos['cedula'])) {
                    $usuario->set_cedula($datos['cedula']);
                }
                $usuario->set_foto($targetFile);
                $usuario->Transaccion(['peticion' => 'actualizarFoto']);
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'title' => 'Foto actualizada',
                    'message' => 'La foto de perfil se ha actualizado correctamente'
                ];
                header("Location: ?page=users-profile");
                exit();
            }

            // Log resultado de subida de archivo para debugging
            try {
                $dbg = fopen(__DIR__ . '/../logs/profile_debug.log', 'a');
                if ($dbg) {
                    fwrite($dbg, "move_uploaded_file_result: " . (file_exists($targetFile) ? 'exists' : 'missing') . "\n");
                    fwrite($dbg, "targetFile: " . $targetFile . "\n");
                    fclose($dbg);
                }
            } catch (Exception $e) {}
        }

        // Actualización de datos del perfil
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];
        $correo = $_POST['Correo'];
        $tlf = $_POST['Telefono'];

        $usuario->set_nombres($nombre);
        $usuario->set_apellidos($apellido);
        $usuario->set_correo($correo);
        $usuario->set_telefono($tlf);
        $peticion['peticion'] = 'modificar';

        // asegurar que el objeto usuario tenga la cédula establecida
        if (isset($datos['cedula'])) {
            $usuario->set_cedula($datos['cedula']);
        }

        if ($usuario->Transaccion($peticion)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Perfil actualizado',
                'message' => 'Los datos del perfil se han actualizado correctamente'
            ];
            $datos = $usuario->Transaccion(['peticion' => 'perfil']);
            $_SESSION['user']['user'] = $datos;
            header("Location: ?page=users-profile");
            exit();
        } else {
            // Log fallo de actualización
            try {
                $dbg = fopen(__DIR__ . '/../logs/profile_debug.log', 'a');
                if ($dbg) {
                    fwrite($dbg, "ModificarUsuario failed for cedula: " . ($datos['cedula'] ?? 'unknown') . "\n");
                    fclose($dbg);
                }
            } catch (Exception $e) {}
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo actualizar el perfil'
            ];
        }
    }

    // Procesamiento de cambio de contraseña
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newpassword']) && isset($_POST['renewpassword'])) {
        if ($_POST['newpassword'] == $_POST['renewpassword']) {
            $clave = password_hash($_POST['renewpassword'], PASSWORD_BCRYPT);
            $usuario->set_clave($clave);
            if ($usuario->Transaccion(['peticion' => 'ActualizarClave'])) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'title' => 'Contraseña actualizada',
                    'message' => 'La contraseña se ha cambiado correctamente'
                ];
                header("Location: ?page=users-profile");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'No se pudo actualizar la contraseña'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Las contraseñas no coinciden'
            ];
        }
    }

    // Manejo de cambio de tema
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiTema'])) {
        $tema = $_POST['cambiTema'];
        $usuario->set_tema($tema);

        // asegurar que el objeto usuario tenga la cédula establecida
        if (isset($datos['cedula'])) {
            $usuario->set_cedula($datos['cedula']);
        }

        if ($usuario->Transaccion(['peticion' => 'actualizarTema'])) {
            // Guardar datos importantes de la sesión actual
            $old_session_data = $_SESSION;

            // Destruir completamente la sesión actual
            session_unset();
            session_destroy();

            // Iniciar una nueva sesión
            session_start();

            // reactivar los datos importantes de la sesión
            $_SESSION = $old_session_data;

            // Actualizar el tema en la nueva sesión
            $_SESSION['user']['user']['tema'] = $tema;

            // Configurar la alerta
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Tema actualizado',
                'message' => 'El tema se ha cambiado correctamente'
            ];

            // Redirigir manteniendo el anchor #tema
            header("Location: ?page=users-profile");
            exit();
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo cambiar el tema'
            ];
            header("Location: ?page=users-profile");
            exit();
        }
    }

    // En users-profile.php (línea ~59)
    if (isset($datos['clave']) && $datos['clave'] == $datos['cedula']) {
        $active3 = "active";
        $active4 = "show active";
    } else {
        $active1 = "active";
        $active2 = "show active";
    }

    require_once "view/users-profile.php";
} else {
    require_once "view/404.php";
}
