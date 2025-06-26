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

    public function __construct()
    {
        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
    }

    // Setters
    public function set_nro_solicitud($nro_solicitud) {
        $this->nro_solicitud = $nro_solicitud;
    }

    public function set_cedula_solicitante($cedula_solicitante) {
        $this->cedula_solicitante = $cedula_solicitante;
    }

    public function set_id_equipo($id_equipo) {
        $this->id_equipo = $id_equipo;
    }

    public function set_motivo($motivo) {
        $this->motivo = $motivo;
    }

    public function set_resultado($resultado) {
        $this->resultado = $resultado;
    }

    public function set_estado($estado) {
        $this->estado = $estado;
    }

    public function set_fecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function set_fecha_final($fecha_final) {
        $this->fecha_final = $fecha_final;
    }

    public function set_id_dependencia($id_dependencia) {
        $this->id_dependencia = $id_dependencia;
    }

    // Getters
    public function get_id_equipo() {
        return $this->id_equipo;
    }

    public function get_estado() {
        return $this->estado;
    }

    /**
     * Registra una nueva solicitud
     */
    private function registrarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => -1];

        try {
            $this->conex->beginTransaction();

            $sql = "INSERT INTO solicitud(cedula_solicitante, motivo, id_equipo, fecha_solicitud, estado_solicitud, estatus)
                    VALUES (:solicitante, :motivo, :equipo, CURRENT_TIMESTAMP(), 'Pendiente', :estatus)";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':solicitante', $this->cedula_solicitante);
            $stmt->bindParam(':equipo', $this->id_equipo);
            $stmt->bindParam(':motivo', $this->motivo);
            $stmt->bindValue(':estatus', 1);

              if ($stmt->execute()) {
                $nro = $this->conex->lastInsertId();
                
                $datos['resultado'] = 'registrar';
                $datos['datos'] = $nro;
                $datos['bool'] = 1;
                $this->conex->commit();
            } else {
                $datos['mensaje'] = 'Error al ejecutar la consulta';
                $this->conex->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conex->rollBack();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Obtiene las solicitudes de un usuario específico
     */
    private function obtenerSolicitudesUsuario()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
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
                WHERE s.cedula_solicitante = :cedula
                ORDER BY s.fecha_solicitud DESC";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':cedula', $this->cedula_solicitante);
            $stmt->execute();

            $datos['resultado'] = 'consultar';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Valida si existe una solicitud
     */
    private function validarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $sql = "SELECT * FROM solicitud WHERE nro_solicitud = :nro_solicitud";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(":nro_solicitud", $this->nro_solicitud);
            $stmt->execute();

            $datos['resultado'] = "validar";
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Actualiza una solicitud existente
     */
    private function actualizarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conex->beginTransaction();

            $sql = "UPDATE solicitud 
                    SET motivo = :motivo, 
                        id_equipo = :equipo, 
                        estado_solicitud = 'Pendiente'
                    WHERE nro_solicitud = :nro";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);
            $stmt->bindParam(':equipo', $this->id_equipo);
            $stmt->bindParam(':motivo', $this->motivo);

            if ($stmt->execute()) {
                $datos['resultado'] = 'actualizar';
                $datos['bool'] = true;
                $this->conex->commit();
            } else {
                $this->conex->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conex->rollBack();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Elimina una solicitud
     */
    private function eliminarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conex->beginTransaction();

            $sql = "UPDATE solicitud SET estatus = 0 WHERE nro_solicitud = :nro";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);

            if ($stmt->execute()) {
                $datos['resultado'] = 'eliminar';
                $datos['mensaje'] = 'Solicitud eliminada exitosamente';
                $datos['bool'] = $stmt->rowCount() > 0;
                $this->conex->commit();
            } else {
                $this->conex->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conex->rollBack();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Finaliza una solicitud
     */
    private function finalizarSolicitud()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'bool' => false];

        try {
            $this->conex->beginTransaction();

            $sql = "UPDATE solicitud 
                    SET resultado_solicitud = :resultado, 
                        estado_solicitud = 'Finalizado' 
                    WHERE nro_solicitud = :nro";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud);
            $stmt->bindParam(':resultado', $this->resultado);

            if ($stmt->execute()) {
                $datos['resultado'] = 'finalizar';
                $datos['bool'] = true;
                $this->conex->commit();
            } else {
                $this->conex->rollBack();
            }
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
            $this->conex->rollBack();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Obtiene todas las solicitudes
     */
    private function obtenerTodasSolicitudes() {
    $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];
    
    try {
        $sql = "SELECT 
                s.nro_solicitud AS ID,
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS Solicitante,
                s.cedula_solicitante AS Cedula,
                d.nombre AS Dependencia,
                IFNULL(eq.tipo_equipo, 'N/A') AS Equipo,
                s.motivo AS Motivo,
                s.estado_solicitud AS Estado,
                s.fecha_solicitud AS Inicio,
                IFNULL(hs.resultado_hoja_servicio, IFNULL(s.resultado_solicitud, 'N/A')) AS Resultado
            FROM solicitud s
            LEFT JOIN empleado e ON s.cedula_solicitante = e.cedula_empleado
            LEFT JOIN equipo eq ON s.id_equipo = eq.id_equipo
            LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
            LEFT JOIN dependencia d ON u.id_dependencia = d.id
            LEFT JOIN hoja_servicio hs ON hs.nro_solicitud = s.nro_solicitud AND hs.estatus = 'I'
            WHERE s.estatus = 1
            ORDER BY s.fecha_solicitud DESC";

        $stmt = $this->conex->query($sql);
        $datos['resultado'] = 'consultar';
        $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
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

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':inicio', $this->fecha_inicio);
            $stmt->bindParam(':final', $this->fecha_final);
            $stmt->execute();

            $datos['resultado'] = 'reporte';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Obtiene empleados por dependencia
     */
    private function obtenerEmpleadosPorDependencia()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $sql = "SELECT 
                    e.cedula_empleado AS cedula, 
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre
                FROM empleado e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();

            $datos['resultado'] = 'consultar_solicitantes';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    /**
     * Obtiene equipos por dependencia
     */
    private function obtenerEquiposPorDependencia()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $sql = "SELECT 
                    e.id_equipo, 
                    e.tipo_equipo, 
                    e.serial
                FROM equipo e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia
                AND e.estatus = 1";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();

            $datos['resultado'] = 'consultar_equipos';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
        return $datos;
    }

    private function obtenerAreas()
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];

        try {
            $sql = "SELECT 
                    *
                FROM equipo e
                JOIN unidad u ON e.id_unidad = u.id_unidad
                WHERE u.id_dependencia = :dependencia
                AND e.estatus = 1";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':dependencia', $this->id_dependencia);
            $stmt->execute();

            $datos['resultado'] = 'consultar_equipos';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stmt);
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
                
            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}