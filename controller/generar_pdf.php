<?php
require_once "../vendor/autoload.php";
require_once "../model/estadistica.php";

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['user'])) {
    echo "Acceso denegado";
    exit;
}

$reporte = $_GET['reporte'] ?? '';
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

$model = new reporte();

function fechaBonita($fecha) {
    if (!$fecha) return '';
    return date('d/m/Y', strtotime($fecha));
}

// Mapear el nombre del reporte a la función y título
$mapa = [
    'eficiencia_tecnicos' => [
        'func' => 'reporteEficienciaTecnicos',
        'titulo' => 'Reporte de Eficiencia por Técnico'
    ],
    'tiempos_respuesta' => [
        'func' => 'reporteTiemposRespuesta',
        'titulo' => 'Reporte de Tiempos de Respuesta'
    ],
    'utilizacion_materiales' => [
        'func' => 'reporteUtilizacionMateriales',
        'titulo' => 'Reporte de Utilización de Materiales'
    ],
    'estado_equipos' => [
        'func' => 'reporteEstadoEquipos',
        'titulo' => 'Reporte de Estado de Equipos'
    ],
    'estado_infraestructura' => [
        'func' => 'reporteEstadoInfraestructura',
        'titulo' => 'Reporte de Estado de Infraestructura'
    ],
    'tendencias_solicitudes' => [
        'func' => 'reporteTendenciasSolicitudes',
        'titulo' => 'Reporte de Tendencias de Solicitudes'
    ],
    'reincidencia_problemas' => [
        'func' => 'reporteReincidenciaProblemas',
        'titulo' => 'Reporte de Reincidencia de Problemas'
    ],
    'kpis' => [
        'func' => 'reporteKPIs',
        'titulo' => 'Reporte Ejecutivo de KPIs'
    ],
    'carga_trabajo' => [
        'func' => 'reporteCargaTrabajoTecnicos',
        'titulo' => 'Reporte de Carga de Trabajo por Técnico'
    ]
];

if (!isset($mapa[$reporte])) {
    echo "Reporte no válido";
    exit;
}

$func = $mapa[$reporte]['func'];
$titulo = $mapa[$reporte]['titulo'];

// Preparar filtros
$filtros = [];
if ($fecha_inicio) $filtros['fecha_inicio'] = $fecha_inicio;
if ($fecha_fin) $filtros['fecha_fin'] = $fecha_fin;

// Obtener datos
$datos = $model->$func($filtros);
$filas = $datos['datos'] ?? [];

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Construir HTML del reporte
$html = '<html><head>
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h2 { text-align: center; }
table { width: 100%; border-collapse: collapse; margin-top: 20px;}
th, td { border: 1px solid #444; padding: 5px; text-align: left; }
th { background: #f0f0f0; }
</style>
</head><body>';

$html .= "<h2>$titulo</h2>";
if ($fecha_inicio || $fecha_fin) {
    $html .= "<p><strong>Desde:</strong> " . fechaBonita($fecha_inicio) . " &nbsp; <strong>Hasta:</strong> " . fechaBonita($fecha_fin) . "</p>";
}

if (!$filas || count($filas) == 0) {
    $html .= "<p>No hay datos para mostrar.</p>";
} else {
    // Encabezados
    $primer = $filas;
    if (isset($primer[0])) $primer = $primer[0];
    $html .= "<table><tr>";
    foreach (array_keys($primer) as $col) {
        $html .= "<th>" . htmlspecialchars($col) . "</th>";
    }
    $html .= "</tr>";

    // Filas
    foreach ($filas as $fila) {
        $html .= "<tr>";
        foreach ($fila as $valor) {
            $html .= "<td>" . htmlspecialchars($valor) . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
}

$html .= "<br><br><small>Generado por el sistema OFITIC - " . date('d/m/Y H:i') . "</small>";
$html .= '</body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream($reporte . ".pdf", ["Attachment" => false]);
exit;
