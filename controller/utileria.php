<?php
require_once "config/rol.php";
require_once "config/modulo.php";
require_once "config/regex.php";
require_once "model/usuario.php";
require_once "model/rol.php";
require_once "model/permiso.php";
require_once "model/bitacora.php";
require_once "model/notificacion.php";

$peticion = [];
$msg = "";

$titulo = "";
$cabecera = [];
$permisos = [];

$usuario = new Usuario();
$bitacora = new Bitacora();
$notificacion = new Notificacion();

// Manejo del cambio de tema
if (isset($_POST['cambiarTema'])) {
    $tema = $_POST['cambiTema'];
    $usuario->set_cedula($_SESSION['user']['cedula']);
    $usuario->set_tema($tema);

    if ($usuario->Transaccion(['peticion' => 'actualizarTema'])) {
        // Guardar datos importantes de la sesión actual
        $old_session_data = $_SESSION;

        // Destruir completamente la sesión actual
        session_unset();
        session_destroy();

        // Iniciar una nueva sesión
        session_start();

        // Restaurar los datos importantes de la sesión
        $_SESSION = $old_session_data;

        // Actualizar el tema en la nueva sesión
        $_SESSION['user']['tema'] = $tema;

        // Recargar los datos del usuario
        $usuario->set_cedula($_SESSION['user']['cedula']);
        $perfil = $usuario->Transaccion(['peticion' => 'perfil']);
        $_SESSION['user'] = array_merge($_SESSION['user'], $perfil['datos']);

        // Redirigir manteniendo el anchor #tema
        header("Location: ?page=users-profile#tema");
        exit();
    }
}

// Cargar datos del usuario
$usuario->set_cedula($_SESSION['user']['cedula']);
$datos = $_SESSION['user'];
$perfil = $usuario->Transaccion(['peticion' => 'perfil']);

// Verificar que $perfil['datos'] existe y es un array antes de hacer merge
if (isset($perfil['datos']) && is_array($perfil['datos'])) {
    $datos = array_merge($datos, $perfil['datos']);
} else {
    // Si no hay datos adicionales, mantener los datos de sesión actuales
    error_log("Error: No se pudieron cargar los datos del perfil para el usuario: " . $_SESSION['user']['cedula']);
}

// Cargar el tema actual
$tema_actual = isset($_SESSION['user']['tema']) ? $_SESSION['user']['tema'] : 0;

switch ($tema_actual) {
    case 1:
        $tema = "<link rel='stylesheet' href='assets/css/temas/rosa.css' />";
        break;
    case 2:
        $tema = "<link rel='stylesheet' href='assets/css/temas/azul.css' />";
        break;
    case 3:
        $tema = "<link rel='stylesheet' href='assets/css/temas/verde.css' />";
        break;
    case 4:
        $tema = "<link rel='stylesheet' href='assets/css/temas/rojo.css' />";
        break;
    case 5:
        $tema = "<link rel='stylesheet' href='assets/css/temas/morado.css' />";
        break;
    default:
        $tema = "<link rel='stylesheet' href='assets/css/temas/default.css' />";
        break;
}

function convertirJSON($objeto)
{
    if (is_object($objeto)) {

        $objeto = (array) $objeto;

        foreach ($objeto as &$valor) {

            if (is_object($valor)) {
                $valor = convertirJSON($valor);
            } elseif (is_array($valor)) {
                $valor = array_map(function ($item) {
                    return is_object($item) ? convertirJSON($item) : $item;
                }, $valor);
            }
        }
        return $objeto;
    }

    if (is_array($objeto)) {
        return array_map(function ($valor) {
            return is_object($valor) ? convertirJSON($valor) : $valor;
        }, $objeto);
    }

    return $objeto;
}

function generarID($primaria, $secundaria = NULL)
{
    // Formato de ID: (XXXXX)(XXX)(XXXXXXXX)(XXXXXXXX)
    // (Clave Primaria)(Clave Secundaria)(Fecha)(Hora Minutos Segundos Milisegundos)
    $primaria = preg_replace('/[^A-Za-z0-9]/', '', $primaria);
    $primaria = strtoupper(substr(trim($primaria), 0, 5));
    $dia = date('Ymd');
    $hora = date('Hms');
    $milisegundo = number_format(microtime(true) * 1000, 0, '', '');

    if ($secundaria == NULL) {
        $secundaria = substr($milisegundo, -3);
    } else {
        $secundaria = preg_replace('/[^A-Za-z0-9]/', '', $secundaria);
        $secundaria = strtoupper(substr(trim($secundaria), 0, 3));
    }

    // Componer el ID
    $id = $primaria . "" . $secundaria . "" . $dia . "". $hora . "" . substr($milisegundo, -2);

    return $id;
}

// Cargar foto de perfil
if (is_file($foto = $datos['foto'])) {
    $foto = $datos['foto'];
} else {
    $foto = "assets/img/foto-perfil/default.jpg";
}

function ObtenerPermisos()
{
    $id_rol = $_SESSION['user']['id_rol'];
    $rol = new Rol();
    $permiso = new Permiso();
    $rol->set_id($id_rol);
    $rol->ObjPermiso($permiso);

    $permisos_rol = $rol->Transaccion(['peticion' => 'filtrar_permiso', 'parametro' => 'nombre_modulo']);

    return $permisos_rol['permiso'];
}

function Bitacora($msg, $modulo)
{
    global $bitacora;
    $peticion["peticion"] = "registrar";
    $hora = date('H:i:s');
    $fecha = date('Y-m-d');
    $id = generarID($modulo, $_SESSION['user']['nombre_usuario']);
    $bitacora->set_id($id);
    $bitacora->set_usuario($_SESSION['user']['nombre_usuario']);
    $bitacora->set_modulo($modulo);
    $bitacora->set_accion($msg);
    $bitacora->set_fecha($fecha);
    $bitacora->set_hora($hora);
    $bitacora->Transaccion($peticion);
}

function NotificarUsuarios($msg, $modulo, $parametro = [])
{
    $usuario = new Usuario();
    $peticion['peticion'] = 'usuario_permiso';
    $peticion['parametro'] = ['modulo' => $parametro['modulo'], 'accion' => $parametro['accion']];
    $arrayUsuario = $usuario->Transaccion($peticion);
    global $notificacion;

    $peticion["peticion"] = "registrar";
    $hora = date('H:i:s');
    $fecha = date('Y-m-d');

    if (empty($usuarios)) {
        $usuarios = [$_SESSION['user']['nombre_usuario']];
    }

    $resultados = [];
    if ($arrayUsuario['bool'] == 1) {
        foreach ($arrayUsuario['datos'] as $usuario) {
            $notificacion->set_usuario($usuario['nombre_usuario']);
            $notificacion->set_modulo($modulo);
            $notificacion->set_mensaje($msg);
            $notificacion->set_fecha($fecha);
            $notificacion->set_hora($hora);
            $resultados[] = $notificacion->Transaccion($peticion);
        }
    }

    return $resultados;
}

function Notificar($msg, $modulo, $busqueda)
{
    $usuario = new Usuario();
    $peticion['peticion'] = 'validar';
    $usuario->set_cedula($busqueda);
    $arrayUsuario = $usuario->Transaccion($peticion);

    global $notificacion;
    $peticion["peticion"] = "registrar";
    $hora = date('H:i:s');
    $fecha = date('Y-m-d');

    if ($arrayUsuario['bool'] == 1) {
        $notificacion->set_usuario($arrayUsuario['datos']['nombre_usuario']);
        $notificacion->set_modulo($modulo);
        $notificacion->set_mensaje($msg);
        $notificacion->set_fecha($fecha);
        $notificacion->set_hora($hora);
        $resultados = $notificacion->Transaccion($peticion);
    } else {
        $resultados = 0;
    }

    return $resultados;
}

if (isset($_POST['permisos'])) {
    $json['resultado'] = 'permisos_modulo';
    $json['permisos'] = ObtenerPermisos();
    echo json_encode($json);
    exit;
}

$permisos = ObtenerPermisos();