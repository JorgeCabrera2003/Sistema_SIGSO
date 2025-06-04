<?php
if (!$_SESSION) {
    echo '<script>window.location="?page=login"</script>';
    $msg["danger"] = "Sesión Finalizada.";
}

if (is_file("view/".$page.".php")) {
    require_once "controller/utileria.php";
    require_once "model/notificacion.php";

    $titulo = "Notificaciones";
    $css = ["alert", "style"];
    $cabecera = array('#', "Módulo", "Mensaje", "Fecha", "Hora", "Estado");

    $notificacion = new Notificacion();

    if(isset($_POST['entrada'])){
        $json['resultado'] = "entrada";
        echo json_encode($json);

        $peticion['peticion'] = "registrar";
        $msg = "(".$_SESSION['user']['nombre_usuario']."), Ingresó al módulo de Notificaciones";
        $hora = date('H:i:s');
        $fecha = date('Y-m-d');
    
        $bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
        $bitacora->set_modulo("Notificaciones");
        $bitacora->set_accion($msg);
        $bitacora->set_fecha($fecha);
        $bitacora->set_hora($hora);
        $bitacora->Transaccion($peticion);
        exit;
    }

    if (isset($_POST['consultar'])) {
        $notificacion->set_usuario($_SESSION['user']['nombre_usuario']);
        $peticion["peticion"] = "consultar";
        $json = $notificacion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['marcar_leido'])) {
        $notificacion->set_usuario($_SESSION['user']['nombre_usuario']);
        $notificacion->set_id($_POST['id']);
        $peticion["peticion"] = "marcar_leido";
        $json = $notificacion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    if (isset($_POST['contar_nuevas'])) {
        $notificacion->set_usuario($_SESSION['user']['nombre_usuario']);
        $peticion["peticion"] = "contar_nuevas";
        $json = $notificacion->Transaccion($peticion);
        echo json_encode($json);
        exit;
    }

    require_once "view/".$page.".php";
} else {
    require_once "view/404.php";
}
?>