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
                    'id_movimiento_material' => filter_var($item['id_movimiento_material'] ?? null, FILTER_VALIDATE_INT),
                    'id_material' => filter_var($item['id_material'] ?? null, FILTER_VALIDATE_INT),
                    'cantidad' => filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT)
                ];
            }, $detalles));
        }
    }

    private function contarNumeroHoja()
    {
        try {
            $sql = "SELECT * FROM `filtrado_hoja`";

                $stmt = $this->conex->prepare($sql);

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
            CONCAT(COALESCE(tec.nombre_empleado, ''), ' ', COALESCE(tec.apellido_empleado, '')) AS nombre_tecnico,
            hs.fecha_resultado,
            hs.resultado_hoja_servicio,
            hs.observacion,
            hs.estatus,
            s.motivo,
            s.fecha_solicitud,
            s.estado_solicitud,
            CONCAT(COALESCE(sol.nombre_empleado, ''), ' ', COALESCE(sol.apellido_empleado, '')) AS nombre_solicitante,
            COALESCE(sol.telefono_empleado, 'N/A') AS telefono_empleado,
            COALESCE(sol.correo_empleado, 'N/A') AS correo_empleado,
            COALESCE(u.nombre_unidad, 'N/A') AS nombre_unidad,
            COALESCE(d.nombre, 'N/A') AS nombre_dependencia,
            COALESCE(e.tipo_equipo, 'N/A') AS tipo_equipo,
            COALESCE(e.serial, 'N/A') AS serial,
            COALESCE(b.codigo_bien, 'N/A') AS codigo_bien,
            COALESCE(m.nombre_marca, 'N/A') AS nombre_marca,
            COALESCE(b.descripcion, 'N/A') AS descripcion
        FROM hoja_servicio hs
        JOIN solicitud s ON hs.nro_solicitud = s.nro_solicitud
        JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
        JOIN empleado sol ON s.cedula_solicitante = sol.cedula_empleado
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
            error_log('Error en obtenerHojaServicio: ' . $e->getMessage());
            return ['resultado' => 'error', 'mensaje' => 'Error al consultar hoja de servicio: ' . $e->getMessage()];
        }
    }

    /**
     * Finaliza una hoja de servicio
     */
    private function finalizarHojaServicio()
{
    if (!$this->codigo_hoja_servicio || !$this->cedula_tecnico || !$this->resultado_hoja_servicio) {
        return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para finalizar hoja. Se requiere un resultado.'];
    }

    // Obtener usuario autenticado para validar permisos
    $usuario = isset($_SESSION['user']) ? $_SESSION['user'] : (isset($this->usuario) ? $this->usuario : []);

    try {
        $this->conex->beginTransaction();

        // 1. Verificar que la hoja existe y está activa
        $sqlVerificar = "SELECT estatus, nro_solicitud, cedula_tecnico FROM hoja_servicio 
                        WHERE codigo_hoja_servicio = :codigo";
        $stmtVerificar = $this->conex->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
        $stmtVerificar->execute();

        $hoja = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if (!$hoja) {
            throw new Exception("La hoja de servicio no existe");
        }

        if ($hoja['estatus'] !== 'A') {
            throw new Exception("La hoja de servicio ya está " . 
                ($hoja['estatus'] == 'I' ? 'finalizada' : 'eliminada'));
        }

        // 2. Validar que el técnico sea el asignado o superusuario
        $esSuperusuario = isset($usuario['id_rol']) && $usuario['id_rol'] == 1;
        if ($hoja['cedula_tecnico'] !== $this->cedula_tecnico && !$esSuperusuario) {
            throw new Exception("No tiene permisos para finalizar esta hoja de servicio");
        }

        // 3. Validar que el resultado no esté vacío
        if (empty($this->resultado_hoja_servicio)) {
            throw new Exception("Debe especificar un resultado para finalizar la hoja");
        }

        // 4. Actualizar la hoja de servicio
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

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la hoja de servicio");
        }

        // 5. Actualizar estado de la solicitud si todas las hojas están finalizadas
        $this->nro_solicitud = $hoja['nro_solicitud'];
        
        // Verificar si quedan hojas activas para esta solicitud
        $sqlHojasActivas = "SELECT COUNT(*) as pendientes 
                           FROM hoja_servicio 
                           WHERE nro_solicitud = :nro 
                           AND estatus = 'A'";
        
        $stmtHojas = $this->conex->prepare($sqlHojasActivas);
        $stmtHojas->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
        $stmtHojas->execute();
        $result = $stmtHojas->fetch(PDO::FETCH_ASSOC);

        if ($result['pendientes'] == 0) {
            // Actualizar estado de la solicitud a "Finalizado"
            $sqlActualizarSolicitud = "UPDATE solicitud 
                                      SET estado_solicitud = 'Finalizado',
                                          resultado_solicitud = 'Completado'
                                      WHERE nro_solicitud = :nro";
            
            $stmtActualizar = $this->conex->prepare($sqlActualizarSolicitud);
            $stmtActualizar->bindParam(':nro', $this->nro_solicitud, PDO::PARAM_INT);
            
            if (!$stmtActualizar->execute()) {
                throw new Exception("Error al actualizar estado de la solicitud");
            }
        }

        $this->conex->commit();

        return [
            'resultado' => 'success',
            'mensaje' => 'Hoja de servicio finalizada exitosamente',
            'codigo' => $this->codigo_hoja_servicio
        ];

    } catch (PDOException $e) {
        $this->conex->rollBack();
        error_log('Error en finalizarHojaServicio: ' . $e->getMessage());
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

            if ($usuario['id_rol'] != 1) { // No es superusuario
                // Obtener información del técnico usando nombres calificados de BD
                $sqlTecnico = "SELECT e.cedula_empleado, e.id_cargo, e.id_servicio 
                  FROM sigso_sistema.empleado e
                  JOIN sigso_usuario.usuario u ON e.cedula_empleado = u.cedula
                  WHERE u.nombre_usuario = :usuario";

                $stmtTecnico = $this->conex->prepare($sqlTecnico);
                $stmtTecnico->bindParam(':usuario', $usuario['nombre_usuario']);
                $stmtTecnico->execute();
                $tecnico = $stmtTecnico->fetch(PDO::FETCH_ASSOC);

                if ($tecnico) {
                    // Técnicos ven solo hojas de su área o que han tomado
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

    /**
     * Asigna una hoja de servicio a un tecnico
     */
    private function tomarHojaServicio()
    {
        if (!$this->codigo_hoja_servicio || !$this->cedula_tecnico) {
            return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para tomar hoja'];
        }

        try {
            $this->conex->beginTransaction();

            // Verificar que la hoja no esté ya asignada
            $sqlVerificar = "SELECT cedula_tecnico FROM hoja_servicio 
                        WHERE codigo_hoja_servicio = :codigo 
                        AND (cedula_tecnico IS NULL OR cedula_tecnico = '')";

            $stmtVerificar = $this->conex->prepare($sqlVerificar);
            $stmtVerificar->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtVerificar->execute();

            if ($stmtVerificar->rowCount() == 0) {
                throw new Exception("La hoja ya está asignada a otro técnico");
            }

            // Asignar la hoja al técnico
            $sql = "UPDATE hoja_servicio 
                SET cedula_tecnico = :tecnico
                WHERE codigo_hoja_servicio = :codigo";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':tecnico', $this->cedula_tecnico);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Obtener el número de solicitud para actualizar estado
                $sqlSolicitud = "SELECT nro_solicitud FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
                $stmtSolicitud = $this->conex->prepare($sqlSolicitud);
                $stmtSolicitud->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
                $stmtSolicitud->execute();
                $this->nro_solicitud = $stmtSolicitud->fetchColumn();

                // Actualizar estado de la solicitud a "En proceso"
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
            $sql = "SELECT 
                        componente, 
                        detalle, 
                        id_movimiento_material,
                        (SELECT id_material FROM movimiento_materiales WHERE id_movimiento_material = dh.id_movimiento_material) AS id_material,
                        (SELECT cantidad FROM movimiento_materiales WHERE id_movimiento_material = dh.id_movimiento_material) AS cantidad
                    FROM detalle_hoja dh
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
        if (!$this->codigo_hoja_servicio) {
            return false;
        }

        try {
            // Eliminar detalles existentes
            $sqlDelete = "DELETE FROM detalle_hoja WHERE codigo_hoja_servicio = :codigo";
            $stmtDelete = $this->conex->prepare($sqlDelete);
            $stmtDelete->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Si no hay detalles, retornar éxito
            if (empty($this->detalles)) {
                return true;
            }

            // Preparar consultas
            $sqlInsert = "INSERT INTO detalle_hoja 
                     (codigo_hoja_servicio, componente, detalle, id_movimiento_material) 
                     VALUES (:codigo, :componente, :detalle, :id_movimiento)";

            $sqlMovimiento = "INSERT INTO movimiento_materiales 
                         (id_material, accion, cantidad, descripcion)
                         VALUES (:id_material, 'salida', :cantidad, :descripcion)";

            $stmtInsert = $this->conex->prepare($sqlInsert);
            $stmtMovimiento = $this->conex->prepare($sqlMovimiento);

            foreach ($this->detalles as $detalle) {
                $idMovimiento = null;

                // Si hay material asociado, registrar el movimiento
                if (!empty($detalle['id_material']) && !empty($detalle['cantidad'])) {
                    // Verificar stock disponible
                    $sqlStock = "SELECT stock FROM material WHERE id_material = :id AND estatus = 1";
                    $stmtStock = $this->conex->prepare($sqlStock);
                    $stmtStock->bindParam(':id', $detalle['id_material'], PDO::PARAM_INT);
                    $stmtStock->execute();
                    $stock = $stmtStock->fetchColumn();

                    if ($stock < $detalle['cantidad']) {
                        throw new Exception("Stock insuficiente para el material ID: " . $detalle['id_material']);
                    }

                    // Registrar movimiento
                    $stmtMovimiento->bindValue(':id_material', $detalle['id_material'], PDO::PARAM_INT);
                    $stmtMovimiento->bindValue(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $stmtMovimiento->bindValue(':descripcion', "Uso en servicio #" . $this->codigo_hoja_servicio);
                    $stmtMovimiento->execute();
                    $idMovimiento = $this->conex->lastInsertId();

                    // Actualizar stock
                    $sqlUpdateStock = "UPDATE material SET stock = stock - :cantidad 
                                  WHERE id_material = :id AND estatus = 1";
                    $stmtUpdate = $this->conex->prepare($sqlUpdateStock);
                    $stmtUpdate->bindValue(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $stmtUpdate->bindValue(':id', $detalle['id_material'], PDO::PARAM_INT);
                    $stmtUpdate->execute();
                }

                // Registrar detalle con referencia al movimiento (siempre que tenga componente)
                if (!empty($detalle['componente'])) {
                    $stmtInsert->bindValue(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
                    $stmtInsert->bindValue(':componente', $detalle['componente']);
                    $stmtInsert->bindValue(':detalle', $detalle['detalle'] ?? '');
                    $stmtInsert->bindValue(':id_movimiento', $idMovimiento, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log('Error en registrarDetalles: ' . $e->getMessage());
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
     * Verifica si una hoja puede ser tomada por un técnico
     */
    private function verificarHojaParaTomar($codigoHoja, $idServicioTecnico)
    {
        if (!$codigoHoja || !$idServicioTecnico) {
            return ['resultado' => 'error', 'mensaje' => 'Datos incompletos para verificar hoja'];
        }

        try {
            $sql = "SELECT hs.codigo_hoja_servicio, ts.id_tipo_servicio 
                FROM hoja_servicio hs
                JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                WHERE hs.codigo_hoja_servicio = :codigo
                AND (hs.cedula_tecnico IS NULL OR hs.cedula_tecnico = '')
                AND ts.id_tipo_servicio = :id_servicio";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $codigoHoja, PDO::PARAM_INT);
            $stmt->bindParam(':id_servicio', $idServicioTecnico, PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hoja) {
                return ['resultado' => 'success', 'datos' => $hoja];
            } else {
                return ['resultado' => 'error', 'mensaje' => 'No se puede tomar esta hoja de servicio'];
            }
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al verificar hoja: ' . $e->getMessage()];
        }
    }

    public function consultarPorSolicitud($nroSolicitud) {
    try {
        $sql = "SELECT codigo_hoja_servicio FROM hoja_servicio 
                WHERE nro_solicitud = :nro AND estatus = 'A' LIMIT 1";
        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':nro', $nroSolicitud, PDO::PARAM_INT);
        $stmt->execute();
        
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($datos) {
            return ['resultado' => 'success', 'datos' => $datos];
        } else {
            return ['resultado' => 'error', 'mensaje' => 'No se encontró hoja de servicio activa'];
        }
    } catch (PDOException $e) {
        return ['resultado' => 'error', 'mensaje' => 'Error al consultar hoja: ' . $e->getMessage()];
    }
}

    /**
     * Actualiza una hoja de servicio existente
     */
    private function actualizarHojaServicio($usuario = null)
    {
        // Asegurar que $usuario tenga valor
        if (!$usuario && isset($_SESSION['user'])) {
            $usuario = $_SESSION['user'];
        }

        if (!$this->codigo_hoja_servicio) {
            return ['resultado' => 'error', 'mensaje' => 'Código de hoja no especificado'];
        }

        try {
            $this->conex->beginTransaction();

            // Obtener datos actuales de la hoja
            $sql = "SELECT * FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmt->execute();
            $hoja = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hoja) {
                $this->conex->rollBack();
                return ['resultado' => 'error', 'mensaje' => 'Hoja de servicio no encontrada'];
            }

            // Permisos: solo superusuario o técnico asignado
            $esSuperusuario = isset($usuario['id_rol']);
            $esTecnicoAsignado = isset($usuario['cedula']) && $hoja['cedula_tecnico'] == $usuario['cedula'];

            if (!$esSuperusuario && !$esTecnicoAsignado) {
                $this->conex->rollBack();
                return ['resultado' => 'error', 'mensaje' => 'No tiene permisos para actualizar esta hoja'];
            }

            // Construir consulta dinámica
            $campos = [];
            $params = [':codigo' => $this->codigo_hoja_servicio];

            // Solo superusuario puede cambiar tipo de servicio
            if ($esSuperusuario && $this->id_tipo_servicio) {
                $campos[] = "id_tipo_servicio = :id_tipo_servicio";
                $params[':id_tipo_servicio'] = $this->id_tipo_servicio;
            }

            if ($this->resultado_hoja_servicio !== null) {
                $campos[] = "resultado_hoja_servicio = :resultado";
                $params[':resultado'] = $this->resultado_hoja_servicio;
            }

            if ($this->observacion !== null) {
                $campos[] = "observacion = :observacion";
                $params[':observacion'] = $this->observacion;
            }

            // Si hay campos para actualizar
            if (!empty($campos)) {
                $sqlUpdate = "UPDATE hoja_servicio SET " . implode(', ', $campos) . " WHERE codigo_hoja_servicio = :codigo";
                $stmtUpdate = $this->conex->prepare($sqlUpdate);
                foreach ($params as $key => $value) {
                    $stmtUpdate->bindValue($key, $value);
                }
                $stmtUpdate->execute();
            }

            // Si hay detalles, actualizarlos
            $actualizarDetalles = !empty($this->detalles);
            if ($actualizarDetalles) {
                if (!$this->registrarDetalles()) {
                    $this->conex->rollBack();
                    return ['resultado' => 'error', 'mensaje' => 'Error al actualizar detalles técnicos'];
                }
            }

            // Si no hay campos ni detalles, no hay nada que actualizar
            if (empty($campos) && !$actualizarDetalles) {
                $this->conex->rollBack();
                return ['resultado' => 'error', 'mensaje' => 'No hay datos para actualizar'];
            }

            $this->conex->commit();
            return ['resultado' => 'success', 'mensaje' => 'Hoja de servicio actualizada correctamente'];
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()];
        }
    }

    /**
     * Consulta solo los detalles técnicos de una hoja
     */
    private function consultarSoloDetalles()
    {
        if (!$this->codigo_hoja_servicio) return [];
        try {
            $sql = "SELECT componente, detalle, id_movimiento_material, 
                        (SELECT id_material FROM movimiento_materiales WHERE id_movimiento_material = dh.id_movimiento_material) AS id_material,
                        (SELECT cantidad FROM movimiento_materiales WHERE id_movimiento_material = dh.id_movimiento_material) AS cantidad
                    FROM detalle_hoja dh
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
     * Genera datos para el reporte PDF de hojas de servicio
     */
    public function reporteHojasServicio($fecha_inicio = null, $fecha_fin = null, $id_tipo_servicio = null)
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
                        hs.resultado_hoja_servicio,
                        hs.observacion,
                        hs.estatus
                    FROM hoja_servicio hs
                    JOIN solicitud s ON hs.nro_solicitud = s.nro_solicitud
                    JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                    JOIN empleado sol ON s.cedula_solicitante = sol.cedula_empleado
                    LEFT JOIN equipo e ON s.id_equipo = e.id_equipo
                    LEFT JOIN bien b ON e.codigo_bien = b.codigo_bien
                    LEFT JOIN marca m ON b.id_marca = m.id_marca
                    WHERE 1=1";

            $params = [];

            if ($fecha_inicio) {
                $sql .= " AND s.fecha_solicitud >= :fecha_inicio";
                $params[':fecha_inicio'] = $fecha_inicio;
            }
            if ($fecha_fin) {
                $sql .= " AND s.fecha_solicitud <= :fecha_fin";
                $params[':fecha_fin'] = $fecha_fin;
            }
            if ($id_tipo_servicio) {
                $sql .= " AND hs.id_tipo_servicio = :id_tipo_servicio";
                $params[':id_tipo_servicio'] = $id_tipo_servicio;
            }

            $sql .= " ORDER BY hs.codigo_hoja_servicio DESC";

            $stmt = $this->conex->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Elimina (marca como eliminada) una hoja de servicio
     */
    private function eliminarHojaServicio()
    {
        if (!$this->codigo_hoja_servicio) {
            return ['resultado' => 'error', 'mensaje' => 'Código de hoja no especificado'];
        }
        try {
            $this->conex->beginTransaction();

            // Verificar existencia
            $sql = "SELECT estatus FROM hoja_servicio WHERE codigo_hoja_servicio = :codigo";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo_hoja_servicio);
            $stmt->execute();
            $estatusActual = $stmt->fetchColumn();

            if ($estatusActual === false) {
                $this->conex->rollBack();
                return ['resultado' => 'error', 'mensaje' => 'Hoja de servicio no encontrada'];
            }
            if ($estatusActual === 'E') {
                $this->conex->rollBack();
                return ['resultado' => 'error', 'mensaje' => 'La hoja de servicio ya está eliminada'];
            }

            // Marcar como eliminada
            $sqlUpdate = "UPDATE hoja_servicio SET estatus = 'E' WHERE codigo_hoja_servicio = :codigo";
            $stmtUpdate = $this->conex->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':codigo', $this->codigo_hoja_servicio, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->conex->commit();
            return [
                'resultado' => 'success',
                'mensaje' => 'Hoja de servicio eliminada correctamente',
                'codigo' => $this->codigo_hoja_servicio // <-- Agregado
            ];
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return ['resultado' => 'error', 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()];
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

            case 'verificar_hoja_tomar':
                return $this->verificarHojaParaTomar(
                    $peticion['codigo_hoja_servicio'],
                    $peticion['id_servicio']
                );

            case 'registrar_detalles':
                return [
                    'resultado' => $this->registrarDetalles() ? 'success' : 'error',
                    'mensaje' => $this->registrarDetalles() ? 'Detalles registrados' : 'Error al registrar detalles'
                ];

            case 'actualizar':
                // Si no se pasa usuario, usar el de sesión
                $usuario = isset($peticion['usuario']) ? $peticion['usuario'] : (isset($_SESSION['user']) ? $_SESSION['user'] : []);
                return $this->actualizarHojaServicio($usuario);

            case 'consultar_detalles':
                return $this->consultarSoloDetalles();

            case 'eliminar':
                return $this->eliminarHojaServicio();

            case 'contar':
                return $this->contarNumeroHoja();

            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }

}