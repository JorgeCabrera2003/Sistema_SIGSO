<?php
require_once('model/conexion.php');

class HojaServicio extends Conexion
{
    // Propiedades privadas
    private $codigo_hoja_servicio;
    private $nro_solicitud;
    private $id_tipo_servicio;
    private $redireccion;
    private $cedula_tecnico;
    private $fecha_resultado;
    private $resultado_hoja_servicio;
    private $observacion;
    private $estatus;
    private $detalles = [];

    public function __construct()
    {
        parent::__construct("sistema");
        $this->conex = $this->Conex();
    }

    // Setters con validación básica
    public function set_codigo_hoja_servicio($codigo) { 
        $this->codigo_hoja_servicio = filter_var($codigo, FILTER_VALIDATE_INT); 
    }
    
    public function set_nro_solicitud($nro) { 
        $this->nro_solicitud = filter_var($nro, FILTER_VALIDATE_INT); 
    }
    
    public function set_id_tipo_servicio($id) { 
        $this->id_tipo_servicio = filter_var($id, FILTER_VALIDATE_INT); 
    }
    
    public function set_redireccion($redireccion) { 
        $this->redireccion = filter_var($redireccion, FILTER_VALIDATE_INT); 
    }
    
    public function set_cedula_tecnico($cedula) { 
        $this->cedula_tecnico = preg_replace('/[^V0-9\-]/', '', $cedula); 
    }
    
    public function set_fecha_resultado($fecha) { 
        $this->fecha_resultado = DateTime::createFromFormat('Y-m-d H:i:s', $fecha) ? $fecha : null; 
    }
    
    public function set_resultado_hoja_servicio($resultado) { 
        $this->resultado_hoja_servicio = substr(htmlspecialchars($resultado), 0, 45); 
    }
    
    public function set_observacion($observacion) { 
        $this->observacion = substr(htmlspecialchars($observacion), 0, 200); 
    }
    
    public function set_estatus($estatus) { 
        $this->estatus = in_array($estatus, ['A', 'I', 'E']) ? $estatus : 'A'; 
    }
    
    public function set_detalles($detalles) { 
        if (is_array($detalles)) {
            $this->detalles = array_map(function($item) {
                return [
                    'componente' => substr(htmlspecialchars($item['componente'] ?? ''), 0, 100),
                    'detalle' => substr(htmlspecialchars($item['detalle'] ?? ''), 0, 200),
                    'id_movimiento_material' => filter_var($item['id_movimiento_material'] ?? null, FILTER_VALIDATE_INT)
                ];
            }, $detalles);
        }
    }

    /**
     * Crea una nueva hoja de servicio
     */
    private function crearHojaServicio()
    {
        $response = ['resultado' => 'error', 'mensaje' => '', 'codigo' => null];
        
        try {
            $this->conex->beginTransaction();
            
            $sql = "INSERT INTO hoja_servicio 
                    (nro_solicitud, id_tipo_servicio, estatus) 
                    VALUES (:nro_solicitud, :tipo_servicio, 'A')";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro_solicitud', $this->nro_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_servicio', $this->id_tipo_servicio, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $codigo = $this->conex->lastInsertId();
                
                // Registrar detalles si existen
                if (!empty($this->detalles)) {
                    $this->codigo_hoja_servicio = $codigo;
                    if (!$this->registrarDetalles()) {
                        throw new Exception("Error al registrar detalles");
                    }
                }
                
                $this->conex->commit();
                $response = [
                    'resultado' => 'success',
                    'mensaje' => 'Hoja de servicio creada exitosamente',
                    'codigo' => $codigo
                ];
            } else {
                throw new Exception("Error al crear hoja de servicio");
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $response['mensaje'] = $e->getMessage();
        } catch (Exception $e) {
            $this->conex->rollBack();
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Obtiene los datos completos de una hoja de servicio
     */
    private function obtenerHojaServicio()
    {
        $response = ['resultado' => 'error', 'mensaje' => '', 'datos' => null];
        
        try {
            $sql = "SELECT
                hs.codigo_hoja_servicio,
                hs.nro_solicitud,
                hs.id_tipo_servicio,
                ts.nombre_tipo_servicio,
                hs.redireccion,
                hs.cedula_tecnico,
                CONCAT(tec.nombre_empleado, ' ', tec.apellido_empleado) AS nombre_tecnico,
                hs.fecha_resultado,
                hs.resultado_hoja_servicio,
                hs.observacion,
                hs.estatus,
                s.motivo,
                s.fecha_solicitud,
                s.estado_solicitud,
                CONCAT(sol.nombre_empleado, ' ', sol.apellido_empleado) AS nombre_solicitante,
                sol.telefono_empleado,
                u.nombre_unidad,
                d.nombre AS nombre_dependencia,
                e.tipo_equipo,
                m.nombre_marca,
                e.serial,
                b.codigo_bien
            FROM hoja_servicio hs
            LEFT JOIN solicitud s ON hs.nro_solicitud = s.nro_solicitud
            LEFT JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
            LEFT JOIN empleado sol ON s.cedula_solicitante = sol.cedula_empleado
            LEFT JOIN empleado tec ON hs.cedula_tecnico = tec.cedula_empleado
            LEFT JOIN unidad u ON sol.id_unidad = u.id_unidad
            LEFT JOIN dependencia d ON u.id_dependencia = d.id
            LEFT JOIN equipo e ON s.id_equipo = e.id_equipo
            LEFT JOIN bien b ON e.codigo_bien = b.codigo_bien
            LEFT JOIN marca m ON b.id_marca = m.id_marca
            WHERE hs.codigo_hoja_servicio = :codigo";
            
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmt->execute();
            
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($datos) {
                // Obtener detalles
                $detalles = $this->obtenerDetallesHoja();
                
                $response = [
                    'resultado' => 'success',
                    'datos' => array_merge($datos, ['detalles' => $detalles])
                ];
            } else {
                $response['mensaje'] = 'No se encontró la hoja de servicio';
            }
        } catch (PDOException $e) {
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Obtiene los detalles de una hoja de servicio
     */
    private function obtenerDetallesHoja()
    {
        try {
            $sql = "SELECT componente, detalle, id_movimiento_material 
                    FROM detalle_hoja 
                    WHERE codigo_hoja_servicio = :codigo";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Registra los detalles de una hoja de servicio
     */
    private function registrarDetalles()
    {
        try {
            $this->conex->beginTransaction();
            
            $sql = "INSERT INTO detalle_hoja 
                    (codigo_hoja_servicio, componente, detalle) 
                    VALUES (:codigo, :componente, :detalle)";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            
            foreach ($this->detalles as $detalle) {
                $stmt->bindParam(':componente', $detalle['componente']);
                $stmt->bindParam(':detalle', $detalle['detalle']);
                $stmt->execute();
            }
            
            $this->conex->commit();
            return true;
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return false;
        }
    }

    /**
     * Finaliza una hoja de servicio
     */
    private function finalizarHojaServicio()
    {
        $response = ['resultado' => 'error', 'mensaje' => ''];
        
        try {
            $this->conex->beginTransaction();
            
            $sql = "UPDATE hoja_servicio 
                    SET cedula_tecnico = :tecnico, 
                        fecha_resultado = NOW(),
                        resultado_hoja_servicio = :resultado,
                        observacion = :observacion,
                        estatus = 'I' 
                    WHERE codigo_hoja_servicio = :codigo";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':tecnico', $this->cedula_tecnico);
            $stmt->bindParam(':resultado', $this->resultado_hoja_servicio);
            $stmt->bindParam(':observacion', $this->observacion);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Actualizar estado de la solicitud si todas las hojas están finalizadas
                $this->actualizarEstadoSolicitud();
                
                $this->conex->commit();
                $response = [
                    'resultado' => 'success',
                    'mensaje' => 'Hoja de servicio finalizada exitosamente'
                ];
            } else {
                throw new Exception("Error al finalizar hoja de servicio");
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $response['mensaje'] = $e->getMessage();
        } catch (Exception $e) {
            $this->conex->rollBack();
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Actualiza el estado de la solicitud relacionada
     */
    private function actualizarEstadoSolicitud()
    {
        try {
            // Verificar si todas las hojas están finalizadas
            $sql = "SELECT COUNT(*) as pendientes 
                    FROM hoja_servicio 
                    WHERE nro_solicitud = :nro AND estatus = 'A'";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['pendientes'] == 0) {
                // Actualizar solicitud a "Finalizado"
                $sql = "UPDATE solicitud 
                        SET estado_solicitud = 'Finalizado' 
                        WHERE nro_solicitud = :nro";
                        
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            // No hacemos nada, es opcional
        }
    }

    /**
     * Obtiene las hojas de servicio de una solicitud
     */
    private function listarHojasPorSolicitud()
    {
        $response = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];
        
        try {
            $sql = "SELECT 
                    hs.codigo_hoja_servicio,
                    ts.nombre_tipo_servicio,
                    hs.estatus,
                    hs.fecha_resultado,
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS tecnico
                FROM hoja_servicio hs
                JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                LEFT JOIN empleado e ON hs.cedula_tecnico = e.cedula_empleado
                WHERE hs.nro_solicitud = :nro
                ORDER BY hs.codigo_hoja_servicio";
                
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            $stmt->execute();
            
            $response = [
                'resultado' => 'success',
                'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Obtiene los tipos de servicio disponibles para una solicitud
     */
    private function obtenerTiposDisponibles()
    {
        $response = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];
        
        try {
            $sql = "SELECT ts.id_tipo_servicio, ts.nombre_tipo_servicio 
                    FROM tipo_servicio ts 
                    WHERE ts.id_tipo_servicio NOT IN (
                        SELECT hs.id_tipo_servicio 
                        FROM hoja_servicio hs 
                        WHERE hs.nro_solicitud = :nro
                    ) AND ts.estatus = 1";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            $stmt->execute();
            
            $response = [
                'resultado' => 'success',
                'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Obtiene todos los tipos de servicio activos
     */
    private function listarTiposServicio()
    {
        $response = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];
        
        try {
            $sql = "SELECT id_tipo_servicio, nombre_tipo_servicio 
                    FROM tipo_servicio 
                    WHERE estatus = 1";
                    
            $stmt = $this->conex->prepare($sql);
            $stmt->execute();
            
            $response = [
                'resultado' => 'success',
                'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            $response['mensaje'] = $e->getMessage();
        }
        
        return $response;
    }

    /**
     * Maneja las transacciones del modelo
     */
    public function Transaccion($peticion)
    {
        // Validar petición
        if (!isset($peticion['peticion'])) {
            return ['resultado' => 'error', 'mensaje' => 'Petición no especificada'];
        }

        // Asignar propiedades según la petición
        if (isset($peticion['codigo_hoja_servicio'])) {
            $this->set_codigo_hoja_servicio($peticion['codigo_hoja_servicio']);
        }
        
        if (isset($peticion['nro_solicitud'])) {
            $this->set_nro_solicitud($peticion['nro_solicitud']);
        }
        
        if (isset($peticion['id_tipo_servicio'])) {
            $this->set_tipo_servicio($peticion['id_tipo_servicio']);
        }
        
        if (isset($peticion['cedula_tecnico'])) {
            $this->set_cedula_tecnico($peticion['cedula_tecnico']);
        }
        
        if (isset($peticion['resultado_hoja_servicio'])) {
            $this->set_resultado_hoja_servicio($peticion['resultado_hoja_servicio']);
        }
        
        if (isset($peticion['observacion'])) {
            $this->set_observacion($peticion['observacion']);
        }
        
        if (isset($peticion['detalles'])) {
            $this->set_detalles($peticion['detalles']);
        }

        // Procesar petición
        switch ($peticion['peticion']) {
            case 'crear':
                return $this->crearHojaServicio();
                
            case 'consultar':
                return $this->obtenerHojaServicio();
                
            case 'finalizar':
                return $this->finalizarHojaServicio();
                
            case 'listar_por_solicitud':
                return $this->listarHojasPorSolicitud();
                
            case 'tipos_disponibles':
                return $this->obtenerTiposDisponibles();
                
            case 'listar_tipos':
                return $this->listarTiposServicio();
                
            case 'registrar_detalles':
                return [
                    'resultado' => $this->registrarDetalles() ? 'success' : 'error',
                    'mensaje' => $this->registrarDetalles() ? 'Detalles registrados' : 'Error al registrar detalles'
                ];
                
            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}