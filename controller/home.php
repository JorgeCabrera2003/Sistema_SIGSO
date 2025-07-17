<?php
if (!$_SESSION) {
  echo '<script>window.location="?page=login"</script>';
  $msg["danger"] = "Sesion Finalizada.";
}

ob_start();

if (is_file("view/home.php")) {

 
  $css = ["alert", "style"];
  require_once "controller/utileria.php";
  require_once "model/usuario.php";
  require_once "model/empleado.php";
  require_once "model/oficina.php";
  require_once "model/tecnico.php";
  require_once "model/hoja_servicio.php";
  require_once "model/piso.php";
  require_once "model/patch_panel.php";
  require_once "model/switch_.php";
  $usuario = new Usuario();
  $empleado = new Empleado();
  $oficina = new Oficina();
  $tecnico = new tecnico(); 
  $servicio = new HojaServicio();
  $pisoModel = new Piso();
  $patchPanelModel = new patch_panel();
  $switchModel = new Switch_();
  $usuario->set_cedula($_SESSION['user']['cedula']);
  $cantidadEmpleados = $empleado->Transaccion(['peticion' => 'contar_empleados']);
  $totalHojas = 0;

  
  $cantidadHojas = $servicio->Transaccion(['peticion' => 'contar']);
  $cantidadTecnicos = $tecnico->Transaccion(['peticion' => 'contarTecnico']);
  
  // Obtener pisos activos para el select
  $piso = $pisoModel->Transaccion(['peticion' => 'consultar'])['datos'];

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

// NUEVO: Procesar petición AJAX para patch_panel por piso
if (isset($_POST['pisoFiltrado'])) {
    $id_piso = intval($_POST['pisoFiltrado']);
    $reporte = $patchPanelModel->Transaccion([
        'peticion' => 'reporte_patch_panel',
        'id_piso' => $id_piso
    ]);
    echo json_encode($reporte);
    exit;
  }

// NUEVO: Procesar petición AJAX para switches por piso
if (isset($_POST['pisoFiltradoSwitch'])) {
    $id_piso = intval($_POST['pisoFiltradoSwitch']);
    $reporte = $switchModel->Transaccion([
        'peticion' => 'reporte_switch_panel',
        'id_piso' => $id_piso
    ]);
    echo json_encode($reporte);
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
   $titulo = "Home";
  
  require_once "view/home.php";
} else {
  require_once "view/404.php";
}
