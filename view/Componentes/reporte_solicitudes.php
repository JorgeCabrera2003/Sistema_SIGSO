<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 13px; }
    h1 { font-size: 22px; font-weight: bold; margin-bottom: 10px; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .header-info { margin-bottom: 10px; }
    .header-info td { border: none; }
  </style>
</head>
<body>
  <center>
    <h1>Reporte de Solicitudes</h1>
  </center>
  <table class="header-info">
    <tr>
      <td><strong>Fecha de emisión:</strong> <?php echo date('d/m/Y H:i'); ?></td>
      <td><strong>Total de solicitudes:</strong> <?php echo isset($_SESSION['servicio']) ? count($_SESSION['servicio']) : 0; ?></td>
    </tr>
  </table>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Solicitante</th>
        <th>Cédula</th>
        <th>Motivo</th>
        <th>Equipo</th>
        <th>Estado</th>
        <th>Fecha Reporte</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($_SESSION['servicio'] as $informacion) { ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo isset($informacion['Solicitante']) ? $informacion['Solicitante'] : ''; ?></td>
          <td><?php echo isset($informacion['Cedula']) ? $informacion['Cedula'] : ''; ?></td>
          <td><?php echo isset($informacion['Motivo']) ? $informacion['Motivo'] : ''; ?></td>
          <td><?php echo isset($informacion['Equipo']) ? $informacion['Equipo'] : ''; ?></td>
          <td><?php echo isset($informacion['Estado']) ? $informacion['Estado'] : ''; ?></td>
          <td><?php echo isset($informacion['Inicio']) ? $informacion['Inicio'] : ''; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</body>
</html>