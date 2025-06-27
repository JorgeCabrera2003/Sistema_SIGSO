<?php 
$imagePath = __DIR__ . '/assets/img/logo.png';
$html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Reporte de Hojas de Servicio</title>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #333; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { padding: 6px; border: 1px solid #ccc; font-size: 12px; }
                th { background-color: #f8f9fa; }
                .header { margin-bottom: 20px; }
                .logo { width: 80px; }
                .fecha { text-align: right; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="assets/img/logo.jpg" class="logo">
                <div class="fecha">Fecha: '.date('d/m/Y').'</div>
            </div>
            <h1>Reporte de Hojas de Servicio</h1>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>N° Solicitud</th>
                        <th>Tipo Servicio</th>
                        <th>Solicitante</th>
                        <th>Equipo</th>
                        <th>Marca</th>
                        <th>Serial</th>
                        <th>Código Bien</th>
                        <th>Motivo</th>
                        <th>Fecha Solicitud</th>
                        <th>Resultado</th>
                        <th>Observación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($datos as $i => $fila) {
            $html .= '<tr>
                <td>'.($i+1).'</td>
                <td>'.$fila['nro_solicitud'].'</td>
                <td>'.$fila['nombre_tipo_servicio'].'</td>
                <td>'.$fila['solicitante'].'</td>
                <td>'.$fila['tipo_equipo'].'</td>
                <td>'.$fila['nombre_marca'].'</td>
                <td>'.$fila['serial'].'</td>
                <td>'.$fila['codigo_bien'].'</td>
                <td>'.$fila['motivo'].'</td>
                <td>'.$fila['fecha_solicitud'].'</td>
                <td>'.$fila['resultado_hoja_servicio'].'</td>
                <td>'.$fila['observacion'].'</td>
                <td>'.($fila['estatus']=='A'?'Activo':($fila['estatus']=='I'?'Finalizado':'Eliminado')).'</td>
            </tr>';
        }
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
?>