<?php
require_once "model/conexion.php";
require_once "model/hoja_servicio.php";

class Equipo extends Conexion
{
    protected $id_equipo;
    protected $tipo_equipo;
    protected $serial;
    protected $codigo_bien;
    protected $id_unidad;
    protected $id_dependencia;
    protected $hoja_servicio;

    // CONSTANTES DE VALIDACIÓN - UNIFICADAS CON EL CONTROLADOR
    const REGEX_ID_EQUIPO = '/^[a-zA-Z0-9]{1,30}$/'; // CORREGIDO: igual al controlador
    const REGEX_CODIGO_BIEN = '/^[0-9a-zA-Z\-]{1,20}$/';
    const REGEX_SERIAL = '/^[0-9a-zA-ZáéíóúüñÑçÇ.\-\s]{1,45}$/';
    const REGEX_TIPO_EQUIPO = '/^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.]{1,45}$/';
    const REGEX_ID_UNIDAD = '/^[A-Z0-9]{1,30}$/';
    const REGEX_ID_DEPENDENCIA = '/^[A-Z0-9]{1,30}$/';

    public function __construct()
    {
        $this->id_equipo = "";
        $this->tipo_equipo = "";
        $this->serial = "";
        $this->codigo_bien = "";
        $this->id_unidad = "";
        $this->id_dependencia = "";
        $this->hoja_servicio = NULL;
    }

    // SETTERS ACTUALIZADOS
    public function set_id_dependencia($id_dependencia)
    {
        if ($id_dependencia == NULL || !preg_match(self::REGEX_ID_DEPENDENCIA, $id_dependencia)) {
            throw new ValueError("ID de Dependencia no válido. Debe ser alfanumérico de 1-30 caracteres");
        }
        $this->id_dependencia = $id_dependencia;
    }

    public function set_id_equipo($id_equipo)
    {
        if ($id_equipo == NULL || !preg_match(self::REGEX_ID_EQUIPO, $id_equipo)) {
            throw new ValueError("ID de Equipo no válido. Debe ser alfanumérico de 1-30 caracteres. Recibido: " . $id_equipo);
        }
        $this->id_equipo = $id_equipo;
    }

    public function set_tipo_equipo($tipo_equipo)
    {
        $tipo_equipo = trim($tipo_equipo);
        if ($tipo_equipo == NULL || empty($tipo_equipo) || !preg_match(self::REGEX_TIPO_EQUIPO, $tipo_equipo)) {
            throw new ValueError("Tipo de Equipo no válido. Debe tener 1-45 caracteres alfanuméricos");
        }
        $this->tipo_equipo = $tipo_equipo;
    }

    public function set_serial($serial)
    {
        $serial = trim($serial);
        if ($serial == NULL || empty($serial) || !preg_match(self::REGEX_SERIAL, $serial)) {
            throw new ValueError("Serial no válido. Debe tener 1-45 caracteres alfanuméricos");
        }
        $this->serial = $serial;
    }

    public function set_codigo_bien($codigo_bien)
    {
        $codigo_bien = trim($codigo_bien);
        if ($codigo_bien == NULL || empty($codigo_bien) || !preg_match(self::REGEX_CODIGO_BIEN, $codigo_bien)) {
            throw new ValueError("Código de Bien no válido. Debe tener 1-20 caracteres (letras, números y guiones)");
        }
        $this->codigo_bien = $codigo_bien;
    }

    public function set_id_unidad($id_unidad)
    {
        if ($id_unidad == NULL || !preg_match(self::REGEX_ID_UNIDAD, $id_unidad)) {
            throw new ValueError("ID de Unidad no válido. Debe ser alfanumérico de 1-30 caracteres");
        }
        $this->id_unidad = $id_unidad;
    }

    // GETTERS
    public function get_id_equipo()
    {
        return $this->id_equipo;
    }

    public function get_tipo_equipo()
    {
        return $this->tipo_equipo;
    }

    public function get_serial()
    {
        return $this->serial;
    }

    public function get_codigo_bien()
    {
        return $this->codigo_bien;
    }

    public function get_id_unidad()
    {
        return $this->id_unidad;
    }

    // MÉTODOS DE HOJA DE SERVICIO
    private function LlamarHojaServicio()
    {
        if ($this->hoja_servicio == NULL) {
            $this->hoja_servicio = new DetalleMaterial();
        }
        return $this->hoja_servicio;
    }

    private function DestruirHojaServicio()
    {
        $this->hoja_servicio = NULL;
    }

    // VALIDACIÓN COMPLETA ANTES DE OPERACIONES
    private function Validar($esActualizacion = false)
    {
        $dato = ['bool' => 0, 'mensaje' => ''];

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            // Validar que no exista otro equipo con el mismo serial activo
            $query_serial = "SELECT id_equipo FROM equipo WHERE serial = :serial AND estatus = 1";
            $params_serial = [":serial" => $this->serial];

            if ($esActualizacion) {
                $query_serial .= " AND id_equipo != :id_equipo";
                $params_serial[":id_equipo"] = $this->id_equipo;
            }

            $stm_serial = $con->prepare($query_serial);
            foreach ($params_serial as $key => $value) {
                $stm_serial->bindValue($key, $value);
            }
            $stm_serial->execute();

            if ($stm_serial->rowCount() > 0) {
                $con->rollBack();
                $dato['bool'] = 1;
                $dato['mensaje'] = "Ya existe un equipo activo con el mismo serial";
                return $dato;
            }

            // Validar que el código de bien exista y esté activo
            $query_bien = "SELECT codigo_bien FROM bien WHERE codigo_bien = :codigo_bien AND estatus = 1";
            $stm_bien = $con->prepare($query_bien);
            $stm_bien->bindParam(":codigo_bien", $this->codigo_bien);
            $stm_bien->execute();

            if ($stm_bien->rowCount() == 0) {
                $con->rollBack();
                $dato['bool'] = 1;
                $dato['mensaje'] = "El código de bien no existe o está inactivo";
                return $dato;
            }

            // Validar que la unidad exista y esté activa
            $query_unidad = "SELECT id_unidad FROM unidad WHERE id_unidad = :id_unidad AND estatus = 1";
            $stm_unidad = $con->prepare($query_unidad);
            $stm_unidad->bindParam(":id_unidad", $this->id_unidad);
            $stm_unidad->execute();

            if ($stm_unidad->rowCount() == 0) {
                $con->rollBack();
                $dato['bool'] = 1;
                $dato['mensaje'] = "La unidad seleccionada no existe o está inactiva";
                return $dato;
            }

            $con->commit();
            $dato['bool'] = 0;
        } catch (PDOException $e) {
            if (isset($con) && $con->inTransaction()) {
                $con->rollBack();
            }
            $dato['bool'] = -1;
            $dato['mensaje'] = "Error en validación: " . $e->getMessage();
        } finally {
            if (isset($stm_serial)) $stm_serial = null;
            if (isset($stm_bien)) $stm_bien = null;
            if (isset($stm_unidad)) $stm_unidad = null;
            if (isset($con)) $con = null;
        }

        return $dato;
    }

    // REGISTRAR EQUIPO
    private function Registrar()
    {
        $dato = $this->Validar(false);
        if ($dato['bool'] != 0) {
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => $dato['mensaje']
            ];
        }

        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "INSERT INTO equipo (id_equipo, tipo_equipo, serial, codigo_bien, id_unidad, estatus) 
                     VALUES (:id_equipo, :tipo_equipo, :serial, :codigo_bien, :id_unidad, 1)";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->bindParam(':tipo_equipo', $this->tipo_equipo);
            $stm->bindParam(':serial', $this->serial);
            $stm->bindParam(':codigo_bien', $this->codigo_bien);
            $stm->bindParam(':id_unidad', $this->id_unidad);

            $stm->execute();
            $con->commit();

            return [
                'estado' => 1,
                'resultado' => "registrar",
                'mensaje' => "Equipo registrado exitosamente."
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => "Error al registrar equipo: " . $e->getMessage()
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // ACTUALIZAR EQUIPO
    private function Actualizar()
    {
        $dato = $this->Validar(true);
        if ($dato['bool'] != 0) {
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => $dato['mensaje']
            ];
        }

        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "UPDATE equipo SET tipo_equipo = :tipo_equipo, serial = :serial, 
                     codigo_bien = :codigo_bien, id_unidad = :id_unidad 
                     WHERE id_equipo = :id_equipo AND estatus = 1";

            $stm = $con->prepare($query);
            $stm->bindParam(":id_equipo", $this->id_equipo);
            $stm->bindParam(":tipo_equipo", $this->tipo_equipo);
            $stm->bindParam(":serial", $this->serial);
            $stm->bindParam(":codigo_bien", $this->codigo_bien);
            $stm->bindParam(":id_unidad", $this->id_unidad);

            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se encontró el equipo o ya fue eliminado");
            }

            $con->commit();

            return [
                'resultado' => "modificar",
                'estado' => 1,
                'mensaje' => "Equipo modificado exitosamente"
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => "Error al modificar equipo: " . $e->getMessage()
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // ELIMINAR EQUIPO (ELIMINACIÓN LÓGICA)
    private function Eliminar()
    {
        $con = null;
        $stm_check = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            // Verificar si el equipo tiene solicitudes activas
            $query_check = "SELECT COUNT(*) as count FROM solicitud 
                           WHERE id_equipo = :id_equipo AND estado_solicitud IN ('Pendiente', 'En proceso')";
            $stm_check = $con->prepare($query_check);
            $stm_check->bindParam(':id_equipo', $this->id_equipo);
            $stm_check->execute();
            $result = $stm_check->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // Cambiar el código de resultado para indicar que es un warning
                return [
                    'estado' => 2, // Código especial para warning
                    'resultado' => "warning",
                    'mensaje' => "No se puede eliminar el equipo porque tiene solicitudes activas"
                ];
            }

            // Verificar si el equipo está en uso en punto_conexion
            $query_punto = "SELECT COUNT(*) as count FROM punto_conexion WHERE id_equipo = :id_equipo";
            $stm_punto = $con->prepare($query_punto);
            $stm_punto->bindParam(':id_equipo', $this->id_equipo);
            $stm_punto->execute();
            $result_punto = $stm_punto->fetch(PDO::FETCH_ASSOC);

            if ($result_punto['count'] > 0) {
                return [
                    'estado' => 2, // Código especial para warning
                    'resultado' => "warning",
                    'mensaje' => "No se puede eliminar el equipo porque está en uso en la red"
                ];
            }

            $query = "UPDATE equipo SET estatus = 0 WHERE id_equipo = :id_equipo";
            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se encontró el equipo");
            }

            $con->commit();

            return [
                'estado' => 1,
                'resultado' => "eliminar",
                'mensaje' => "Equipo eliminado exitosamente."
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => "Error al eliminar equipo: " . $e->getMessage()
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
            if ($stm_check) $stm_check = null;
        }
    }

    // CONSULTAR EQUIPOS ACTIVOS
    private function Consultar()
    {
        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "SELECT 
                        e.id_equipo,
                        e.tipo_equipo,
                        e.serial,
                        e.codigo_bien,
                        u.nombre_unidad, 
                        CONCAT(et.nombre,' - ', d.nombre) AS dependencia,
                        CASE WHEN pc.id_equipo IS NOT NULL THEN 1 ELSE 0 END AS ocupado,
                        b.descripcion as descripcion_bien,
                        m.nombre_marca,
                        o.nombre_oficina
                    FROM equipo e 
                    JOIN unidad u ON e.id_unidad = u.id_unidad
                    JOIN dependencia d ON u.id_dependencia = d.id
                    JOIN ente et ON d.id_ente = et.id
                    JOIN bien b ON e.codigo_bien = b.codigo_bien
                    LEFT JOIN marca m ON b.id_marca = m.id_marca
                    LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                    LEFT JOIN punto_conexion pc ON pc.id_equipo = e.id_equipo
                    WHERE u.estatus = 1 AND e.estatus = 1
                    ORDER BY e.tipo_equipo, e.serial";

            $stm = $con->prepare($query);
            $stm->execute();
            $con->commit();

            return [
                'resultado' => "consultar",
                'datos' => $stm->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'resultado' => "error",
                'mensaje' => "Error al consultar equipos: " . $e->getMessage(),
                'datos' => []
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // CONSULTAR EQUIPOS ELIMINADOS
    private function ConsultarEliminadas()
    {
        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "SELECT 
                        e.id_equipo,
                        e.tipo_equipo,
                        e.serial,
                        e.codigo_bien,
                        u.nombre_unidad, 
                        CONCAT(et.nombre,' - ', d.nombre) AS dependencia,
                        b.descripcion as descripcion_bien,
                        m.nombre_marca,
                        o.nombre_oficina
                    FROM equipo e 
                    JOIN unidad u ON e.id_unidad = u.id_unidad
                    JOIN dependencia d ON u.id_dependencia = d.id
                    JOIN ente et ON d.id_ente = et.id
                    JOIN bien b ON e.codigo_bien = b.codigo_bien
                    LEFT JOIN marca m ON b.id_marca = m.id_marca
                    LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                    WHERE e.estatus = 0
                    ORDER BY e.serial DESC";

            $stm = $con->prepare($query);
            $stm->execute();
            $con->commit();

            return [
                'resultado' => "consultar_eliminadas",
                'datos' => $stm->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'resultado' => "error",
                'mensaje' => "Error al consultar equipos eliminados: " . $e->getMessage(),
                'datos' => []
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // reactivar EQUIPO ELIMINADO
    private function reactivar()
    {
        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            // Verificar que el equipo existe y está eliminado
            $query_check = "SELECT id_equipo FROM equipo WHERE id_equipo = :id_equipo AND estatus = 0";
            $stm_check = $con->prepare($query_check);
            $stm_check->bindParam(':id_equipo', $this->id_equipo);
            $stm_check->execute();

            if ($stm_check->rowCount() == 0) {
                throw new PDOException("No se encontró el equipo eliminado");
            }

            // Verificar que no exista otro equipo activo con el mismo serial
            $query_serial = "SELECT e.id_equipo FROM equipo e 
                            WHERE e.serial = (SELECT serial FROM equipo WHERE id_equipo = :id_equipo) 
                            AND e.estatus = 1";
            $stm_serial = $con->prepare($query_serial);
            $stm_serial->bindParam(':id_equipo', $this->id_equipo);
            $stm_serial->execute();

            if ($stm_serial->rowCount() > 0) {
                throw new PDOException("Ya existe un equipo activo con el mismo serial");
            }

            $query = "UPDATE equipo SET estatus = 1 WHERE id_equipo = :id_equipo";
            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se pudo reactivar el equipo");
            }

            $con->commit();

            return [
                'estado' => 1,
                'resultado' => "reactivar",
                'mensaje' => "Equipo restaurado exitosamente."
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'estado' => 0,
                'resultado' => "error",
                'mensaje' => "Error al reactivar equipo: " . $e->getMessage()
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // DETALLE DE EQUIPO
    private function Detalle()
    {
        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "SELECT 
                        e.id_equipo,
                        e.tipo_equipo,
                        e.serial,
                        e.codigo_bien,
                        e.id_unidad,
                        u.nombre_unidad,
                        u.id_dependencia,
                        d.nombre as nombre_dependencia,
                        et.nombre as nombre_ente,
                        b.descripcion,
                        m.nombre_marca,
                        o.nombre_oficina,
                        CASE WHEN pc.id_equipo IS NOT NULL THEN 1 ELSE 0 END AS ocupado
                    FROM equipo e 
                    JOIN unidad u ON e.id_unidad = u.id_unidad
                    JOIN dependencia d ON u.id_dependencia = d.id
                    JOIN ente et ON d.id_ente = et.id
                    JOIN bien b ON e.codigo_bien = b.codigo_bien
                    LEFT JOIN marca m ON b.id_marca = m.id_marca
                    LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                    LEFT JOIN punto_conexion pc ON pc.id_equipo = e.id_equipo
                    WHERE e.id_equipo = :id_equipo AND e.estatus = 1";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->execute();
            $con->commit();

            $resultado = $stm->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return [
                    'resultado' => "error",
                    'mensaje' => "Equipo no encontrado"
                ];
            }

            return [
                'resultado' => "detalle",
                'datos' => $resultado
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'resultado' => "error",
                'mensaje' => "Error al obtener detalle: " . $e->getMessage()
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // HISTORIAL DE EQUIPO
    private function Historial()
    {
        $con = null;
        $stm = null;

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            // Consulta para obtener el historial completo del equipo
            $query = "SELECT 
                        'Solicitud' as tipo,
                        s.id_solicitud as id,
                        s.fecha_solicitud as fecha,
                        s.descripcion_falla as descripcion,
                        s.estado_solicitud as estado,
                        CONCAT(us.nombre, ' ', us.apellido) as usuario,
                        NULL as tecnico
                    FROM solicitud s
                    JOIN usuario us ON s.id_usuario = us.id_usuario
                    WHERE s.id_equipo = :id_equipo
                    
                    UNION ALL
                    
                    SELECT 
                        'Mantenimiento' as tipo,
                        m.id_mantenimiento as id,
                        m.fecha_mantenimiento as fecha,
                        m.descripcion_trabajo as descripcion,
                        m.estado_mantenimiento as estado,
                        CONCAT(ut.nombre, ' ', ut.apellido) as usuario,
                        CONCAT(ut.nombre, ' ', ut.apellido) as tecnico
                    FROM mantenimiento m
                    JOIN usuario ut ON m.id_tecnico = ut.id_usuario
                    WHERE m.id_equipo = :id_equipo
                    
                    ORDER BY fecha DESC";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->execute();
            $con->commit();

            return [
                'resultado' => "historial",
                'datos' => $stm->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            if ($con && $con->inTransaction()) {
                $con->rollBack();
            }
            return [
                'resultado' => "error",
                'mensaje' => "Error al obtener historial: " . $e->getMessage(),
                'datos' => []
            ];
        } finally {
            $this->Cerrar_Conexion($con, $stm);
        }
    }

    // MÉTODO PÚBLICO PRINCIPAL - ACCEDE A TODOS LOS MÉTODOS PRIVADOS
    public function Transaccion($peticion)
    {
        $resultado = ['estado' => 0, 'mensaje' => 'Petición no válida'];

        try {
            switch ($peticion["peticion"]) {
                case "registrar":
                    $resultado = $this->Registrar();
                    break;

                case "actualizar":
                    $resultado = $this->Actualizar();
                    break;

                case "eliminar":
                    $resultado = $this->Eliminar();
                    break;

                case "consultar":
                    $resultado = $this->Consultar();
                    break;

                case "consultar_eliminadas":
                    $resultado = $this->ConsultarEliminadas();
                    break;

                case "reactivar":
                    $resultado = $this->reactivar();
                    break;

                case "detalle":
                    $resultado = $this->Detalle();
                    break;

                case "historial":
                    $resultado = $this->Historial();
                    break;

                default:
                    $resultado = ['estado' => 0, 'mensaje' => 'Petición no reconocida'];
                    break;
            }
        } catch (Exception $e) {
            $resultado = [
                'estado' => 0,
                'mensaje' => 'Error en transacción: ' . $e->getMessage()
            ];
        }

        return $resultado;
    }
}
