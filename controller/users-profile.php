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

    if (isset($_POST['cambiar'])) {
        // Procesamiento de foto de perfil
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $targetDir = "assets/img/foto-perfil/";
            $targetFile = $targetDir . basename($_FILES["foto_perfil"]["name"]);
            $extension = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
            $nuevoNombre = $datos['cedula'] . '.' . $extension;
            $targetFile = $targetDir . $nuevoNombre;

            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $targetFile)) {
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
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo actualizar el perfil'
            ];
        }
    }

    if (isset($_POST['passw'])) {
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
    if (isset($_POST['cambiarTema'])) {
        $tema = $_POST['cambiTema'];
        $usuario->set_tema($tema);

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
            $_SESSION['user']['user']['tema'] = 5;

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
