<?php
    require_once "controller/utileria.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    Bitacora("Cerró sesión", "Usuario");

    session_unset();
    session_destroy();

    header("Location: ?page=login");
?>