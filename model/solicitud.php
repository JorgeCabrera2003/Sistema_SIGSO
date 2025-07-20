<?php
require_once('model/conexion.php');

class Solicitud extends Conexion
{
    private $nro_solicitud;
    private $cedula_solicitante;
    private $id_equipo;
    private $motivo;
    private $resultado;
    private $estado;
    private $fecha_inicio;
    private $fecha_final;
    private $id_dependencia;
    private $conexion;

    public function __construct()
    {
        $this->nro_solicitud = 0;
        $this->cedula_solicitante = 0;
        $this->id_equipo = 0;
        $this->motivo = "";
        $this->resultado = "";
        $this->estado = "";
        $this->fecha_inicio = 0;
        $this->fecha_final = 0;
        $this->id_dependencia = 0;
    }

    // Setters
    public function set_nro_solicitud($nro_solicitud)
    {
        $this->nro_solicitud = $nro_solicitud;
    }

    public function set_cedula_solicitante($cedula_solicitante)
    {
        $this->cedula_solicitante = $cedula_solicitante;
    }

    public function set_id_equipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function set_motivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function set_resultado($resultado)
    {
        $this->resultado = $resultado;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    public function set_fecha_inicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function set_fecha_final($fecha_final)
    {
        $this->fecha_final = $fecha_final;
    }

    public function set_id_dependencia($id_dependencia)
    {
        $this->id_dependencia = $id_dependencia;
    }

    // Getters
    public function get_id_equipo()
    {
        return $this->id_equipo;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    private function registrarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => -1];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            // Asegurarse de que el ID no se establece manualmente
            $sql = "INSERT INTO solicitud(cedula_solicitante, motivo, id_equipo, fecha_solicitud, estado_solicitud, estatus)
                VALUES (:solicitante, :motivo, :equipo, CURRENT_TIMESTAMP(), 'Pendiente', 1)";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':solicitante', $this->cedula_solicitante);
            $stmt->bindParam(':motivo', $this->motivo);

            // Manejar el caso cuando no se selecciona un equipo
            $idEquipo = !empty($this->id_equipo) ? $this->id_equipo : null;
            $stmt->bindParam(':equipo', $idEquipo, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $nro = $this->conexion->lastInsertId();

                $datos['resultado'] = 'registrar';
                $datos['datos'] = $nro;
                $datos['bool'] = 1;
                $this->conexion->commit();
            } else {
                $errorInfo = $stmt->errorInfo();
                $datos['mensaje'] = 'Error al ejecutar la consulta: ' . $errorInfo[2];
                $this->conexion->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = 'Error en la base de datos: ' . $e->getMessage();
            $this->conexion->rollBack();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    // Nuevo método para detectar área basado en el equipo
    private function detectarAreaPorEquipo($idEquipo)
    {
        if (empty($idEquipo)) {
            return 1; // Default a Soporte Técnico si no hay equipo
        }

        try {
            // Obtener categoría del equipo a través del bien
            $sql = "SELECT c.id_tipo_servicio 
                FROM equipo e
                JOIN bien b ON e.codigo_bien = b.codigo_bien
                JOIN categoria c ON b.id_categoria = c.id_categoria
                WHERE e.id_equipo = :id_equipo";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_equipo', $idEquipo, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultado['id_tipo_servicio'] ?? 1; // Default a Soporte Técnico
        } catch (PDOException $e) {
            error_log('Error al detectar área por equipo: ' . $e->getMessage());
            return 1; // Default a Soporte Técnico
        }
    }

    /**
     * Obtiene las solicitudes de un usuario específico
     */
    private function obtenerSolicitudesUsuario()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT
                    s.nro_solicitud AS ID,
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS Tecnico,
                    s.motivo AS Motivo,
                    eq.tipo_equipo AS Equipo,
                    s.fecha_solicitud AS Inicio,
                    s.estado_solicitud AS Estatus, 
                    s.resultado_solicitud AS Resultado
                FROM solicitud s
                LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
                LEFT JOIN equipo eq ON s.id_equipo = eq.id_equipo
                WHERE s.cedula_solicitante = :cedula AND s.estado_solicitud <> 'Eliminado'
                ORDER BY s.fecha_solicitud DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $this->cedula_solicitante);
            $stmt->execute();
            $this->conexion->commit();
            $datos['resultado'] = 'consultar';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    private function detectarAreaPorMotivo($motivo)
    {
        $palabrasClave = [
            'soporte' => ['computador', 'pc', 'equipo', 'laptop', 'monitor', 'teclado', 'mouse', 'impresora', 'software'],
            'electronica' => ['circuito', 'soldadura', 'multímetro', 'osciloscopio', 'fuente', 'alimentación'],
            'telefonia' => ['teléfono', 'celular', 'central', 'pbx', 'extensión', 'tono'],
            'redes' => ['red', 'wifi', 'ethernet', 'cable', 'conexión', 'ip', 'dns', 'dhcp']
        ];

        $texto = mb_strtolower($this->quitarAcentos($motivo));
        $conteo = array_fill_keys(array_keys($palabrasClave), 0);

        foreach ($palabrasClave as $area => $palabras) {
            foreach ($palabras as $palabra) {
                $palabra = $this->quitarAcentos($palabra);
                if (preg_match("/\b$palabra\b/i", $texto)) {
                    $conteo[$area]++;
                }
            }
        }

        $areaSeleccionada = array_search(max($conteo), $conteo);

        $mapAreaToId = [
            'soporte' => 1,
            'redes' => 2,
            'telefonia' => 3,
            'electronica' => 4
        ];

        return $mapAreaToId[$areaSeleccionada] ?? 1; // Default a soporte técnico
    }

    private function quitarAcentos($texto)
    {
        return str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $texto
        );
    }


    /**
     * Valida si existe una solicitud
     */
    private function validarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $sql = "SELECT * FROM solicitud WHERE nro_solicitud = :nro_solicitud";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":nro_solicitud", $this->nro_solicitud);
            $stmt->execute();

            $datos['resultado'] = "validar";
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Actualiza una solicitud existente
     */
    // En el modelo Solicitud, agregar/actualizar el método actualizarSolicitud

    private function actualizarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            // Verificar si existe hoja de servicio asociada
            $sqlHoja = "SELECT COUNT(*) FROM hoja_servicio WHERE nro_solicitud = :nro";
            $stmtHoja = $this->conexion->prepare($sqlHoja);
            $stmtHoja->bindParam(':nro', $this->nro_solicitud);
            $stmtHoja->execute();
            $tieneHoja = $stmtHoja->fetchColumn() > 0;

            // Si tiene hoja asociada, el estado debe ser "En proceso", si no, "Pendiente"
            $nuevoEstado = $tieneHoja ? 'En proceso' : 'Pendiente';

            $sql = "UPDATE solicitud 
            SET motivo = :motivo, 
                id_equipo = :equipo, 
                estado_solicitud = :estado
            WHERE nro_solicitud = :nro";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);
            $stmt->bindParam(':equipo', $this->id_equipo);
            $stmt->bindParam(':motivo', $this->motivo);
            $stmt->bindParam(':estado', $nuevoEstado);

            if ($stmt->execute()) {
                $datos['resultado'] = 'success';
                $datos['mensaje'] = 'Solicitud actualizada correctamente';
                $datos['bool'] = true;
                $this->conexion->commit();
            } else {
                $datos['mensaje'] = 'Error al ejecutar la actualización';
                $this->conexion->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conexion->rollBack();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Elimina una solicitud
     */
    private function eliminarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $sql = "UPDATE solicitud SET estado_solicitud = 'Eliminado', estatus = 0 WHERE nro_solicitud = :nro";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);

            if ($stmt->execute()) {
                $datos['resultado'] = 'eliminar';
                $datos['mensaje'] = 'Solicitud eliminada exitosamente';
                $datos['bool'] = $stmt->rowCount() > 0;
                $this->conexion->commit();
            } else {
                $this->conexion->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conexion->rollBack();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Finaliza una solicitud
     */
    private function finalizarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $sql = "UPDATE solicitud 
                    SET resultado_solicitud = :resultado, 
                        estado_solicitud = 'Finalizado' 
                    WHERE nro_solicitud = :nro";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);
            $stmt->bindParam(':resultado', $this->resultado);

            if ($stmt->execute()) {
                $datos['resultado'] = 'finalizar';
                $datos['bool'] = true;
                $this->conexion->commit();
            } else {
                $this->conexion->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conexion->rollBack();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Obtiene todas las solicitudes
     */
    private function obtenerTodasSolicitudes()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                s.nro_solicitud AS ID,
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS Solicitante,
                s.cedula_solicitante AS Cedula,
                d.nombre AS Dependencia,
                IFNULL(eq.tipo_equipo, 'N/A') AS Equipo,
                s.motivo AS Motivo,
                s.estado_solicitud AS Estado,
                s.fecha_solicitud AS Inicio,
                IFNULL(hs.resultado_hoja_servicio, IFNULL(s.resultado_solicitud, 'N/A')) AS Resultado,
                ts.id_tipo_servicio AS tipo_servicio
                    FROM solicitud s
                    LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
                    LEFT JOIN equipo eq ON s.id_equipo = eq.id_equipo
                    LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                    LEFT JOIN dependencia d ON u.id_dependencia = d.id
                    LEFT JOIN hoja_servicio hs ON hs.nro_solicitud = s.nro_solicitud AND hs.estatus = 'I'
                    LEFT JOIN tipo_servicio ts ON ts.id_tipo_servicio = hs.id_tipo_servicio
                    WHERE s.estatus = 1
                    ORDER BY s.fecha_solicitud DESC";

            $stmt = $this->conexion->query($sql);
            $datos['resultado'] = 'consultar';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        return $datos;
    }

    /**
     * Obtiene solicitudes para reporte por rango de fechas
     */
    private function obtenerSolicitudesReporte()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                    s.nro_solicitud AS ID,
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS Solicitante,
                    s.cedula_solicitante AS Cedula,
                    s.motivo AS Motivo,
                    eq.tipo_equipo AS Equipo,
                    s.fecha_solicitud AS Inicio,
                    s.estado_solicitud AS Estado,
                    s.resultado_solicitud AS Resultado,
                    d.nombre AS Dependencia
                FROM solicitud s
                LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
                LEFT JOIN equipo eq ON s.id_equipo = eq.id_equipo
                LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                LEFT JOIN dependencia d ON u.id_dependencia = d.id
                WHERE s.fecha_solicitud BETWEEN :inicio AND :final
                ORDER BY s.fecha_solicitud DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':inicio', $this->fecha_inicio);
            $stmt->bindParam(':final', $this->fecha_final);
            $stmt->execute();
            $this->conexion->commit();

            $datos['resultado'] = 'reporte';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Obtiene empleados por dependencia
     */
    private function obtenerEmpleadosPorDependencia()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                    e.cedula_empleado AS cedula, 
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre
                FROM empleado e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();

            $datos['resultado'] = 'consultar_solicitantes';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Obtiene equipos por dependencia
     */
    private function obtenerEquiposPorDependencia()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                    e.id_equipo, 
                    e.tipo_equipo, 
                    e.serial
                FROM equipo e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia
                AND e.estatus = 1";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();

            $datos['resultado'] = 'consultar_equipos';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    private function obtenerAreas()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                    *
                FROM equipo e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia
                AND e.estatus = 1";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();
            $this->conexion->commit();

            $datos['resultado'] = 'consultar_equipos';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Obtiene una solicitud por ID junto al tipo de servicio de la hoja asociada
     */
    private function obtenerSolicitudPorId()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => null];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                        s.nro_solicitud,
                        s.cedula_solicitante,
                        s.motivo,
                        s.id_equipo,
                        s.estado_solicitud,
                        s.resultado_solicitud,
                        s.fecha_solicitud,
                        e.id_unidad,
                        u.id_dependencia,
                        hs.id_tipo_servicio
                    FROM solicitud s
                    LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
                    LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                    LEFT JOIN hoja_servicio hs ON hs.nro_solicitud = s.nro_solicitud
                    WHERE s.nro_solicitud = :nro_solicitud
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nro_solicitud', $this->nro_solicitud);
            $stmt->execute();

            $datos['resultado'] = 'consultar_por_id';
            $datos['datos'] = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Consultar solicitudes eliminadas
     */
    private function consultarEliminadas()
    {
        $datos = ['resultado' => 'consultar_eliminadas', 'datos' => []];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                        s.nro_solicitud,
                        CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS solicitante,
                        s.cedula_solicitante AS cedula,
                        d.nombre AS dependencia,
                        s.motivo
                    FROM solicitud s
                    LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
                    LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                    LEFT JOIN dependencia d ON u.id_dependencia = d.id
                    WHERE s.estado_solicitud = 'Eliminado'";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Restaurar una solicitud eliminada
     */
    private function restaurarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "UPDATE solicitud SET estado_solicitud = 'Pendiente', estatus = 1 WHERE nro_solicitud = :nro";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);
            if ($stmt->execute()) {
                $datos['resultado'] = 'restaurar';
                $datos['mensaje'] = 'Solicitud restaurada exitosamente';
                $datos['bool'] = $stmt->rowCount() > 0;
                $this->conexion->commit();
            } else {
                $this->conexion->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conexion->rollBack();
        }
        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    /**
     * Maneja las transacciones del modelo
     */
    public function Transaccion($peticion)
    {
        switch ($peticion["peticion"]) {
            case "registrar":
                return $this->registrarSolicitud();

            case "consultar":
                return $this->obtenerTodasSolicitudes();

            case "solicitud_usuario":
                return $this->obtenerSolicitudesUsuario();

            case "actualizar":
                return $this->actualizarSolicitud();

            case "eliminar":
                return $this->eliminarSolicitud();

            case "finalizar":
                return $this->finalizarSolicitud();

            case "reporte":
                return $this->obtenerSolicitudesReporte();

            case "consultar_solicitantes":
                return $this->obtenerEmpleadosPorDependencia();

            case "consultar_equipos":
                return $this->obtenerEquiposPorDependencia();

            case "consultar_por_id":
                return $this->obtenerSolicitudPorId();

            case "consultar_eliminadas":
                return $this->consultarEliminadas();

            case "restaurar":
                return $this->restaurarSolicitud();

            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}
