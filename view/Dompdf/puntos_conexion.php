
// filepath: c:\xampp\htdocs\Sistema OFITIC\Sistema_OFITIC\view\Dompdf\puntos_conexion.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Puntos de Conexión</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Reporte de Puntos de Conexión</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Equipo</th>
                <th>Patch Panel</th>
                <th>Puerto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultado as $row): ?>
            <tr>
                <td><?= $row['id_punto_conexion'] ?></td>
                <td><?= $row['id_equipo'] ?></td>
                <td><?= $row['codigo_patch_panel'] ?></td>
                <td><?= $row['puerto_patch_panel'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>