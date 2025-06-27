<?php
require_once('model/conexion.php');

class HojaServicio extends Conexion
{
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

    // Setters con validación
    public function set_codigo_hoja_servicio($codigo)
    {
        $this->codigo_hoja_servicio = filter_var($codigo, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    public function set_nro_solicitud($nro)
    {
        $this->nro_solicitud = filter_var($nro, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    public function set_id_tipo_servicio($id)
    {
        $this->id_tipo_servicio = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    public function set_redireccion($redireccion)
    {
        $this->redireccion = filter_var($redireccion, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    }

    public function set_cedula_tecnico($cedula)
    {
        $this->cedula_tecnico = preg_match('/^[VE]-\d{5,8}$/', $cedula) ? $cedula : null;
    }

    public function set_fecha_resultado($fecha)
    {
        $this->fecha_resultado = DateTime::createFromFormat('Y-m-d H:i:s', $fecha) ? $fecha : null;
    }

    public function set_resultado_hoja_servicio($resultado)
    {
        $this->resultado_hoja_servicio = substr(htmlspecialchars(trim($resultado)), 0, 45);
    }

    public function set_observacion($observacion)
    {
        $this->observacion = substr(htmlspecialchars(trim($observacion)), 0, 200);
    }

    public function set_estatus($estatus)
    {
        $this->estatus = in_array($estatus, ['A', 'I', 'E']) ? $estatus : 'A';
    }

    public function set_detalles($detalles)
    {
        if (is_array($detalles)) {
            $this->detalles = array_filter(array_map(function ($item) {
                return [
                    'componente' => substr(htmlspecialchars(trim($item['componente'] ?? '')), 0, 100),
                    'detalle' => substr(htmlspecialchars(trim($item['detalle'] ?? '')), 0, 200),
                    'id_movimiento_material' => filter_var($item['id_movimiento_material'] ?? null, FILTER_VALIDATE_INT)
                ];
            }, $detalles));
        }
    }

    /**
     * Crea una nueva hoja de servicio
     */
    private function crearHojaServicio()
    {
        if (!$this->nro_solicitud || !$this->id_tipo_servicio) {
            return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para crear hoja de servicio'];
        }

        try {
            $this->conex->beginTransaction();

            // Verificar si la solicitud existe y está activa
            $sqlVerificar = "SELECT COUNT(*) FROM solicitud WHERE nro_solicitud = :nro AND estatus = 1";
            $stmtVerificar = $this->conex->prepare($sqlVerificar);
            $stmtVerificar->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            $stmtVerificar->execute();
            
            if ($stmtVerificar->fetchColumn() == 0) {
                throw new Exception("La solicitud no existe o está inactiva");
            }

            // Verificar si ya existe una hoja para este tipo de servicio
            $sqlExistente = "SELECT COUNT(*) FROM hoja_servicio 
                            WHERE nro_solicitud = :nro AND id_tipo_servicio = :tipo";
            $stmtExistente = $this->conex->prepare($sqlExistente);
            $stmtExistente->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            $stmtExistente->bindParam(':tipo', $this->id_tipo_servicio, PDO::PARAM_INT);
            $stmtExistente->execute();
            
            if ($stmtExistente->fetchColumn() > 0) {
                throw new Exception("Ya existe una hoja para este tipo de servicio en la solicitud");
            }

            // Insertar la hoja de servicio
            $sql = "INSERT INTO hoja_servicio 
                    (nro_solicitud, id_tipo_servicio, estatus) 
                    VALUES (:nro_solicitud, :tipo_servicio, 'A')";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':nro_solicitud', $this->nro_solicitud, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_servicio', $this->id_tipo_servicio, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $codigo = $this->conex->lastInsertId();
                $this->codigo_hoja_servicio = $codigo;

                // Registrar detalles si existen
                if (!empty($this->detalles)) {
                    if (!$this->registrarDetalles()) {
                        throw new Exception("Error al registrar detalles");
                    }
                }

                // Actualizar estado de la solicitud a "En proceso"
                $this->actualizarEstadoSolicitud('En proceso');

                $this->conex->commit();
                return [
                    'resultado' => 'success',
                    'mensaje' => 'Hoja de servicio creada exitosamente',
                    'codigo' => $codigo
                ];
            } else {
                throw new Exception("Error al crear hoja de servicio");
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()];
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    /**
     * Obtiene los datos completos de una hoja de servicio
     */
    private function obtenerHojaServicio()
    {
        if (!$this->codigo_hoja_servicio) {
            return ['resultado' => 'error', 'mensaje' => 'Código de hoja no especificado'];
        }

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
                    sol.correo_empleado,
                    u.nombre_unidad,
                    d.nombre AS nombre_dependencia,
                    e.tipo_equipo,
                    e.serial,
                    b.codigo_bien,
                    m.nombre_marca,
                    b.descripcion
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

                return [
                    'resultado' => 'success',
                    'datos' => array_merge($datos, ['detalles' => $detalles])
                ];
            } else {
                return ['resultado' => 'error', 'mensaje' => 'No se encontró la hoja de servicio'];
            }
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al consultar hoja de servicio: ' . $e->getMessage()];
        }
    }

    /**
     * Finaliza una hoja de servicio
     */
    private function finalizarHojaServicio()
    {
        if (!$this->codigo_hoja_servicio || !$this->cedula_tecnico || !$this->resultado_hoja_servicio) {
            return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para finalizar hoja'];
        }

        try {
            $this->conex->beginTransaction();

            // Verificar que la hoja existe y está activa
            $sqlVerificar = "SELECT estatus FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
            $stmtVerificar = $this->conex->prepare($sqlVerificar);
            $stmtVerificar->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtVerificar->execute();
            
            $estatusActual = $stmtVerificar->fetchColumn();
            
            if ($estatusActual === false) {
                throw new Exception("La hoja de servicio no existe");
            }
            
            if ($estatusActual !== 'A') {
                throw new Exception("La hoja de servicio ya está " . ($estatusActual == 'I' ? 'finalizada' : 'eliminada'));
            }

            // Actualizar la hoja
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
                // Obtener el número de solicitud para actualizar estado
                $sqlSolicitud = "SELECT nro_solicitud FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
                $stmtSolicitud = $this->conex->prepare($sqlSolicitud);
                $stmtSolicitud->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
                $stmtSolicitud->execute();
                $this->nro_solicitud = $stmtSolicitud->fetchColumn();

                // Actualizar estado de la solicitud si todas las hojas están finalizadas
                $this->actualizarEstadoSolicitud('Finalizado');

                $this->conex->commit();
                return [
                    'resultado' => 'success',
                    'mensaje' => 'Hoja de servicio finalizada exitosamente'
                ];
            } else {
                throw new Exception("Error al ejecutar la actualización");
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()];
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    /**
     * Lista las hojas de servicio según el rol del usuario
     */
    private function listarHojasServicio($usuario)
    {
        try {
            $sql = "SELECT 
                    hs.codigo_hoja_servicio,
                    hs.nro_solicitud,
                    ts.nombre_tipo_servicio,
                    CONCAT(sol.nombre_empleado, ' ', sol.apellido_empleado) AS solicitante,
                    e.tipo_equipo,
                    m.nombre_marca,
                    e.serial,
                    b.codigo_bien,
                    s.motivo,
                    s.fecha_solicitud,
                    hs.estatus,
                    hs.cedula_tecnico,
                    CONCAT(tec.nombre_empleado, ' ', tec.apellido_empleado) AS tecnico,
                    u.nombre_unidad,
                    d.nombre AS dependencia
                FROM hoja_servicio hs
                JOIN solicitud s ON hs.nro_solicitud = s.nro_solicitud
                JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                JOIN empleado sol ON s.cedula_solicitante = sol.cedula_empleado
                JOIN unidad u ON sol.id_unidad = u.id_unidad
                JOIN dependencia d ON u.id_dependencia = d.id
                LEFT JOIN equipo e ON s.id_equipo = e.id_equipo
                LEFT JOIN bien b ON e.codigo_bien = b.codigo_bien
                LEFT JOIN marca m ON b.id_marca = m.id_marca
                LEFT JOIN empleado tec ON hs.cedula_tecnico = tec.cedula_empleado
                WHERE s.estatus = 1";

            
            if ($usuario['id_rol'] != 5) { // No es superusuario
                // Obtener informacion del técnico
                $sqlTecnico = "SELECT e.cedula_empleado, e.id_cargo, e.id_servicio 
                              FROM empleado e
                              JOIN usuario u ON e.cedula_empleado = u.cedula
                              WHERE u.nombre_usuario = :usuario";
                
                $stmtTecnico = $this->conex->prepare($sqlTecnico);
                $stmtTecnico->bindParam(':usuario', $usuario['nombre_usuario']);
                $stmtTecnico->execute();
                $tecnico = $stmtTecnico->fetch(PDO::FETCH_ASSOC);

                if ($tecnico) {
                    // Tecnicos ven solo hojas de su area o que han tomado
                    $sql .= " AND (ts.id_tipo_servicio = :id_servicio OR hs.cedula_tecnico = :cedula)";
                    $stmt = $this->conex->prepare($sql);
                    $stmt->bindParam(':id_servicio', $tecnico['id_servicio'], PDO::PARAM_INT);
                    $stmt->bindParam(':cedula', $tecnico['cedula_empleado']);
                } else {
                    return ['resultado' => 'error', 'mensaje' => 'Usuario no tiene perfil de técnico'];
                }
            } else {
                $stmt = $this->conex->prepare($sql);
            }

            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'resultado' => 'success',
                'datos' => $resultados
            ];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al listar hojas: ' . $e->getMessage()];
        }
    }
    

    private function tomarHojaServicio() {
    if (!$this->codigo_hoja_servicio || !$this->cedula_tecnico) {
        return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para tomar hoja'];
    }

    try {
        $this->conex->beginTransaction();

        // Verify the service sheet exists and isn't assigned
        $sqlVerificar = "SELECT cedula_tecnico FROM hoja_servicio 
                        WHERE codigo_hoja_servicio = :codigo 
                        AND (cedula_tecnico IS NULL OR cedula_tecnico = '')";
        
        $stmtVerificar = $this->conex->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
        $stmtVerificar->execute();
        
        if ($stmtVerificar->rowCount() == 0) {
            throw new Exception("La hoja ya está asignada a otro técnico");
        }

        // Assign the sheet to the technician
        $sql = "UPDATE hoja_servicio 
                SET cedula_tecnico = :tecnico
                WHERE codigo_hoja_servicio = :codigo";

        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':tecnico', $this->cedula_tecnico);
        $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Get the request number to update status
            $sqlSolicitud = "SELECT nro_solicitud FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
            $stmtSolicitud = $this->conex->prepare($sqlSolicitud);
            $stmtSolicitud->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtSolicitud->execute();
            $this->nro_solicitud = $stmtSolicitud->fetchColumn();

            // Update request status to "In process"
            $this->actualizarEstadoSolicitud('En proceso');

            $this->conex->commit();
            return [
                'resultado' => 'success',
                'mensaje' => 'Hoja de servicio asignada exitosamente'
            ];
        } else {
            throw new Exception("Error al asignar hoja de servicio");
        }
    } catch (PDOException $e) {
        $this->conex->rollBack();
        return ['resultado' => 'error', 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()];
    } catch (Exception $e) {
        $this->conex->rollBack();
        return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
    }
}

    /**
     * Asigna una hoja de servicio a un técnico
     */
    

    /**
     * Obtiene los tipos de servicio disponibles para una solicitud
     */
    private function obtenerTiposDisponibles()
    {
        if (!$this->nro_solicitud) {
            return ['resultado' => 'error', 'mensaje' => 'Número de solicitud no especificado'];
        }

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

            return [
                'resultado' => 'success',
                'datos' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al obtener tipos: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene los detalles de una hoja de servicio
     */
    private function obtenerDetallesHoja()
    {
        if (!$this->codigo_hoja_servicio) return [];

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
        if (!$this->codigo_hoja_servicio || empty($this->detalles)) {
            return false;
        }

        try {
            $this->conex->beginTransaction();

            // Primero eliminar detalles existentes
            $sqlDelete = "DELETE FROM detalle_hoja WHERE codigo_hoja_servicio = :codigo";
            $stmtDelete = $this->conex->prepare($sqlDelete);
            $stmtDelete->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insertar nuevos detalles
            $sqlInsert = "INSERT INTO detalle_hoja 
                         (codigo_hoja_servicio, componente, detalle, id_movimiento_material) 
                         VALUES (:codigo, :componente, :detalle, :id_movimiento)";

            $stmtInsert = $this->conex->prepare($sqlInsert);
            $stmtInsert->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);

            foreach ($this->detalles as $detalle) {
                $stmtInsert->bindValue(':componente', $detalle['componente']);
                $stmtInsert->bindValue(':detalle', $detalle['detalle']);
                $stmtInsert->bindValue(':id_movimiento', $detalle['id_movimiento_material'], PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            $this->conex->commit();
            return true;
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return false;
        }
    }

    /**
     * Actualiza el estado de la solicitud relacionada
     */
    private function actualizarEstadoSolicitud($nuevoEstado)
    {
        if (!$this->nro_solicitud) return false;

        try {
            // Verificar si todas las hojas están finalizadas (solo si el nuevo estado es Finalizado)
            if ($nuevoEstado === 'Finalizado') {
                $sql = "SELECT COUNT(*) as pendientes 
                        FROM hoja_servicio 
                        WHERE nro_solicitud = :nro AND estatus = 'A'";

                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['pendientes'] > 0) {
                    return false; // Aún hay hojas pendientes
                }
            }

            // Actualizar estado de la solicitud
            $sql = "UPDATE solicitud 
                    SET estado_solicitud = :estado 
                    WHERE nro_solicitud = :nro";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':estado', $nuevoEstado);
            $stmt->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Maneja las transacciones del modelo
     */
    public function Transaccion($peticion)
    {
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
            $this->set_id_tipo_servicio($peticion['id_tipo_servicio']);
        }

        if (isset($peticion['redireccion'])) {
            $this->set_redireccion($peticion['redireccion']);
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

            case 'listar':
                return $this->listarHojasServicio($peticion['usuario']);

            case 'tipos_disponibles':
                return $this->obtenerTiposDisponibles();

            case 'tomar_hoja':
                return $this->tomarHojaServicio();

            case 'registrar_detalles':
                return [
                    'resultado' => $this->registrarDetalles() ? 'success' : 'error',
                    'mensaje' => $this->registrarDetalles() ? 'Detalles registrados' : 'Error al registrar detalles'
                ];

            case 'actualizar':
                return $this->actualizarHojaServicio($peticion['usuario']);

            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}