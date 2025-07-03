<?php

    ob_start();

    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    

    if (is_file("view/$page.php")) {

        
        require_once "model/recuperar.php";
        require_once "model/usuario.php";
        require_once "model/bitacora.php";
        

        
        $titulo = "Recuperación de Contraseña";
        $peticion = [];
        
        $recuperar = new Recuperar();
        $bitacora = new Bitacora();

        if (isset($_POST['consultar'])) {

            $cedula = $_POST['particle'] . $_POST['CI'];

            $recuperar->set_cedula($cedula);
            $peticion["peticion"] = "consultar";
            $datos = $recuperar->Transaccion($peticion);
            echo json_encode($datos);

            $msg = "(" .$cedula. "), Ingresó a la Recuperación de Contraseña y Consulto una Cédula.";
            
            
            $bitacora->set_usuario(null);
            $bitacora->set_modulo("Recuperación");
            $bitacora->set_accion($msg);
            $bitacora->set_fecha(date('Y-m-d'));
            $bitacora->set_hora(date('H:i:s'));
            $bitacora->Transaccion(['peticion' => 'consultar']);

            exit;
        }

        if (isset($_POST["modificar"])) {

            $clave = $_POST['clave'];
            if (!preg_match('/^[a-zA-Z0-9.\-_*!@#$%=]+$/', $clave)) {
                echo json_encode(['estado' => 0, 'mensaje' => 'La clave contiene caracteres no permitidos.']);
                exit;
            }

            $cedula = $_POST['cedula'];
            $clave = password_hash($_POST['clave'], PASSWORD_BCRYPT);

            $recuperar->set_cedula($cedula);
            $recuperar->set_clave($clave);

            $peticion["peticion"] = "actualizar";
            $json = $recuperar->Transaccion($peticion);

            if ($json['estado'] == 1) {
                $msg = "(" . $cedula . "), Se cambió la contraseña";
            } else {
                $msg = "(" . $cedula . "), Error al cambiar la contraseña";
            }

            $bitacora->set_usuario(null);
            $bitacora->set_modulo("Recuperación");
            $bitacora->set_accion($msg);
            $bitacora->set_fecha(date('Y-m-d'));
            $bitacora->set_hora(date('H:i:s'));
            $bitacora->Transaccion(['peticion' => 'actualizar']);

            echo json_encode($json); 
            exit;
        }

        if (isset($_POST['enviar_codigo'])) {

            $ahora = time();
            $espera = 1 * 60;

            // Verifica si ya se envió un código recientemente
            if (isset($_SESSION['ultimo_envio_codigo']) && ($ahora - $_SESSION['ultimo_envio_codigo']) < $espera) {
                $faltan = $espera - ($ahora - $_SESSION['ultimo_envio_codigo']);
                $min = floor($faltan / 60);
                $seg = $faltan % 60;
                echo json_encode([
                    'estado' => 0,
                    'mensaje' => "Debes esperar $min minutos y $seg segundos para solicitar un nuevo código."
                ]);
                exit;
            }

            $correo = $_POST['correo'];
            $nombre = $_POST['nombre'];
            $codigo = rand(100000, 999999);

            $_SESSION['codigo_recuperacion'] = $codigo;
            $_SESSION['correo_recuperacion'] = $correo;
            $_SESSION['ultimo_envio_codigo'] = $ahora; // Guarda el tiempo del último envío

            $peticion = [
                'peticion' => 'enviar_correo',
                'correo' => $correo,
                'nombre' => $nombre,
                'codigo' => $codigo
            ];

            $json = $recuperar->Transaccion($peticion);
            echo json_encode($json);
            exit;
        }

        if (isset($_POST['validar_codigo'])) {
            $codigoIngresado = $_POST['codigo'];
            $codigoCorrecto = $_SESSION['codigo_recuperacion'] ?? null;

            if ($codigoIngresado == $codigoCorrecto) {
                echo json_encode(['estado' => 1, 'mensaje' => 'Código correcto']);
            } else {
                echo json_encode(['estado' => 0, 'mensaje' => 'El código ingresado es incorrecto.']);
            }
            exit;
        }

        require_once "view/" . $page . ".php";

        

    } else {

		require_once "view/404.php";

	}

?>