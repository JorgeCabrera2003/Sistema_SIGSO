<?php
if (!$_SESSION) {
  echo '<script>window.location="?page=login"</script>';
  $msg["danger"] = "Sesion Finalizada.";
}

ob_start();

if (is_file("view/home.php")) {

  $titulo = "Home";
  $css = ["alert", "style"];
  require_once "controller/utileria.php";
  require_once "model/usuario.php";
  require_once "model/empleado.php";
  require_once "model/oficina.php";
  require_once "model/tecnico.php";
  require_once "model/hoja_servicio.php";
  $usuario = new Usuario();
  $empleado = new Empleado();
  $oficina = new Oficina();
  $tecnico = new tecnico(); 
  $servicio = new HojaServicio();
  $usuario->set_cedula($_SESSION['user']['cedula']);
  $cantidadEmpleados = $empleado->Transaccion(['peticion' => 'contar_empleados']);
  $totalHojas = 0;

  
  $cantidadHojas = $servicio->Transaccion(['peticion' => 'contar']);
  $cantidadTecnicos = $tecnico->Transaccion(['peticion' => 'contarTecnico']);
  
  if (isset($_POST["grafico"])) {
    
    $areas = [
      2 => 'Soporte Técnico',
      3 => 'Electrónica',
      4 => 'Redes',
      5 => 'Telefonía'
    ];
    $areaLabels = ['Soporte Técnico', 'Electrónica', 'Redes', 'Telefonía'];
    $areaCounts = [0, 0, 0, 0];
    
    $tecnicos = $tecnico->Transaccion(['peticion' => 'consultar']);
    
    
    $grafico = [
      'resultado' => 'grafico',
      'datos' => [
        'GraUsuario' => [
          'label' => 'Usuarios',
          'labels' => ['Usuarios','Empleados OFITIC', 'Empleados', 'Oficinas'],
          'data' => [$cantidadEmpleados['datos']['Total usuario'], 
                       $cantidadEmpleados['datos']['Total empleados OFITIC'], 
                       $cantidadEmpleados['datos']['Total empleados general'],
                       $cantidadEmpleados['datos']['Total oficina']],
        ],
        'Graftecnicos' => [
          'label' => 'Técnicos',
          'labels' => $areaLabels,
          'data' => [$cantidadTecnicos['datos'][0]['Total soporte'], 
                       $cantidadTecnicos['datos'][0]['Total electronica'], 
                       $cantidadTecnicos['datos'][0]['Total redes'],
                       $cantidadTecnicos['datos'][0]['Total telefono']],
        ],
        'miGrafico' => [
          'label' => 'Puntos de Red',
          'labels' => ['Chequeados', 'Funcionando', 'Fuera de servicio'],
          'data' => [28, 18, 10],
        ],
        
          'hojas' => [
            'label' => 'Hojas de Servicio',
            'labels' => ['Hojas eliminadas','Hojas en proceso','Hojas finalizadas'],
            'data' => [$cantidadHojas['datos'][0]['Hojas eliminadas'], 
                       $cantidadHojas['datos'][0]['Hojas activas'], 
                       $cantidadHojas['datos'][0]['Hojas finalizadas']]
          ],
        
        ]
    ];
    echo json_encode($grafico);
    exit;
  }
  


  
  $datos = $_SESSION['user'];
  $datos = $datos + $usuario->Transaccion(['peticion' => 'perfil']);
  
  if (isset($_POST["entrada"])) {
    $json['resultado'] = "entrada";
    echo json_encode($json);
    $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Ingresó al Módulo de Dashboard";
    Bitacora($msg, "Dashboard");
    exit;
  }
  

  
  
  require_once "view/home.php";
} else {
  require_once "view/404.php";
}
