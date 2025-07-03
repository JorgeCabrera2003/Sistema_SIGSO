<?php
$imagePath = __DIR__ . '/assets/img/logo.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Servicio - <?= $datos_hoja['codigo_hoja_servicio'] ?></title>
    <style>
        @page {
            margin: 0.8cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }
        .header img {
            width: 60px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .header h1 {
            display: inline-block;
            font-size: 20px;
            color: #0056b3;
            margin: 0;
            vertical-align: middle;
        }
        .info-grid {
            display: table; /* Use table layout for better alignment in PDF */
            width: 100%;
            margin-bottom: 10px;
        }
        .info-grid-row {
            display: table-row;
        }
        .info-grid-item {
            display: table-cell;
            padding: 3px 0;
            vertical-align: top;
            width: 33.33%; /* Adjust as needed */
        }
        .info-grid-item strong {
            color: #555;
            display: inline-block;
            width: 90px; /* Align labels */
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0056b3;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        .data-box {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .data-box p {
            margin: 0 0 5px 0;
        }
        .data-box p:last-child {
            margin-bottom: 0;
        }
        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .tabla th, .tabla td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .tabla th {
            background-color: #e9ecef;
            color: #495057;
            font-weight: bold;
            font-size: 11px;
        }
        .tabla td {
            font-size: 10px;
        }
        .resultado-reparado {
            color: #28a745; /* Green for repaired */
            font-weight: bold;
        }
        .resultado-noreparado {
            color: #dc3545; /* Red for not repaired */
            font-weight: bold;
        }
        .resultado-pendiente {
            color: #ffc107; /* Yellow for pending */
            font-weight: bold;
        }
        .resultado-diagnostico {
            color: #007bff; /* Blue for diagnostic */
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="assets/img/logo.png" alt="Logo Lara">
            <h1>Hoja de Servicio <?= htmlspecialchars($datos_hoja['nombre_tipo_servicio']) ?></h1>
            <p style="font-size:12px; color:#666; margin-top:5px;">N° Solicitud: **<?= htmlspecialchars($datos_hoja['codigo_hoja_servicio']) ?>**</p>
        </div>

        <div class="info-grid">
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Solicitante:</strong> <?= htmlspecialchars($datos_hoja['nombre_solicitante']) ?></div>
                <div class="info-grid-item"><strong>Dependencia:</strong> <?= htmlspecialchars($datos_hoja['nombre_dependencia']) ?></div>
                <div class="info-grid-item"><strong>Unidad:</strong> <?= htmlspecialchars($datos_hoja['nombre_unidad']) ?></div>
            </div>
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Contacto:</strong> <?= htmlspecialchars($datos_hoja['telefono_empleado']) ?></div>
                <div class="info-grid-item"><strong>Email:</strong> <?= htmlspecialchars($datos_hoja['correo_empleado']) ?></div>
                <div class="info-grid-item"><strong>Fecha Solicitud:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($datos_hoja['fecha_solicitud']))) ?></div>
            </div>
        </div>

        <div class="section-title">Información del Servicio</div>
        <div class="info-grid">
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Técnico Asignado:</strong> <?= htmlspecialchars($datos_hoja['nombre_tecnico']) ?></div>
                <div class="info-grid-item"><strong>Tipo de Servicio:</strong> <?= htmlspecialchars($datos_hoja['nombre_tipo_servicio']) ?></div>
                <div class="info-grid-item"><strong>Estado:</strong>
                    <?php
                    $estatus_text = '';
                    switch ($datos_hoja['estatus']) {
                        case 'A': $estatus_text = 'Activa'; break;
                        case 'I': $estatus_text = 'Finalizada'; break;
                        case 'E': $estatus_text = 'Eliminada'; break;
                        default: $estatus_text = 'Desconocido'; break;
                    }
                    echo htmlspecialchars($estatus_text);
                    ?>
                </div>
            </div>
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Fecha Resultado:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($datos_hoja['fecha_resultado']))) ?></div>
                <div class="info-grid-item"><strong>Resultado:</strong>
                    <?php
                    $resultado_class = '';
                    switch (strtolower($datos_hoja['resultado_hoja_servicio'])) {
                        case 'reparado': $resultado_class = 'resultado-reparado'; break;
                        case 'no reparado': $resultado_class = 'resultado-noreparado'; break;
                        case 'pendiente': $resultado_class = 'resultado-pendiente'; break;
                        case 'diagnóstico': $resultado_class = 'resultado-diagnostico'; break;
                        default: $resultado_class = ''; break;
                    }
                    echo '<span class="' . $resultado_class . '">' . htmlspecialchars($datos_hoja['resultado_hoja_servicio']) . '</span>';
                    ?>
                </div>
                <div class="info-grid-item"></div> </div>
        </div>

        <div class="section-title">Información del Equipo</div>
        <div class="info-grid">
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Tipo de Equipo:</strong> <?= htmlspecialchars($datos_hoja['tipo_equipo']) ?></div>
                <div class="info-grid-item"><strong>Marca:</strong> <?= htmlspecialchars($datos_hoja['nombre_marca']) ?></div>
                <div class="info-grid-item"><strong>Serial:</strong> <?= htmlspecialchars($datos_hoja['serial']) ?></div>
            </div>
            <div class="info-grid-row">
                <div class="info-grid-item"><strong>Código Bien:</strong> <?= htmlspecialchars($datos_hoja['codigo_bien']) ?></div>
                <div class="info-grid-item"></div>
                <div class="info-grid-item"></div>
            </div>
        </div>

        <div class="section-title">Detalles de la Falla y Solución</div>
        <div class="data-box">
            <p><strong>Motivo / Falla Reportada:</strong> <?= nl2br(htmlspecialchars($datos_hoja['motivo'])) ?></p>
            <p><strong>Observación / Diagnóstico:</strong> <?= nl2br(htmlspecialchars($datos_hoja['observacion'])) ?></p>
        </div>

        <div class="section-title">Componentes y Materiales Utilizados</div>
        <table class="tabla">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Componente</th>
                    <th style="width: 40%;">Detalle / Descripción</th>
                    <th style="width: 30%;">Material Utilizado (ID / Cantidad)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($datos_hoja['detalles'])): ?>
                    <?php foreach ($datos_hoja['detalles'] as $i => $det): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($det['componente']) ?></td>
                            <td><?= htmlspecialchars($det['detalle']) ?></td>
                            <td>
                                <?php
                                if (!empty($det['id_material'])) {
                                    echo htmlspecialchars($det['id_material']) . " (" . htmlspecialchars($det['cantidad']) . ")";
                                } else {
                                    echo "No aplica";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; color:#777;">Sin detalles técnicos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            Generado el <?= date('d/m/Y H:i:s') ?> por el Sistema de Gestión de Servicios
            <br>¡Trabajando para la eficiencia y el progreso de Lara!
        </div>
    </div>
</body>
</html>