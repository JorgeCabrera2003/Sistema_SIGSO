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

    public function __construct()
    {
        $this->id_equipo = "";
        $this->tipo_equipo = "";
        $this->serial = 0;
        $this->codigo_bien = 0;
        $this->id_unidad = 0;
        $this->id_dependencia = 0;
        $this->hoja_servicio = NULL;
    }

    public function set_id_dependencia($id_dependencia)
    {
        $this->id_dependencia = $id_dependencia;
    }
    public function get_id_dependencia()
    {
        return $this->id_dependencia;
    }

    public function set_id_equipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function set_tipo_equipo($tipo_equipo)
    {
        $this->tipo_equipo = $tipo_equipo;
    }

    public function set_serial($serial)
    {
        $this->serial = $serial;
    }

    public function set_codigo_bien($codigo_bien)
    {
        $this->codigo_bien = $codigo_bien;
    }


    public function set_id_unidad($id_unidad)
    {
        $this->id_unidad = $id_unidad;
    }

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
    private function Validar()
    {
        $dato = [];

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();
            // Cambiar la validación: evitar duplicados por serial o por código_bien
            $query = "SELECT * FROM equipo WHERE serial = :serial OR (codigo_bien = :codigo_bien AND estatus = 1)";
            $stm = $con->prepare($query);
            $stm->bindParam(":serial", $this->serial);
            $stm->bindParam(":codigo_bien", $this->codigo_bien);
            $stm->execute();
            $con->commit();
            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
        } catch (PDOException $e) {
            $con->rollBack();
            $dato['bool'] = -1;
            $dato['mensaje'] = "Error: " . $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = $this->Validar();
        if ($dato['bool'] == 1) {
            $dato['estado'] = 0;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "El equipo que intenta registrar ya existe.";
            return $dato;
        }
        if ($dato['bool'] == -1) {
            $dato['estado'] = 0;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "Error en la base de datos: " . $dato['mensaje'];
            return $dato;
        }

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "INSERT INTO equipo (id_equipo,tipo_equipo, serial, codigo_bien, id_unidad, estatus) VALUES (:id_equipo, :tipo_equipo, :serial, :codigo_bien, :id_unidad, 1)";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);
            $stm->bindParam(':tipo_equipo', $this->tipo_equipo);
            $stm->bindParam(':serial', $this->serial);
            $stm->bindParam(':codigo_bien', $this->codigo_bien);
            $stm->bindParam(':id_unidad', $this->id_unidad);

            $stm->execute();
            $con->commit();
            $dato['estado'] = 1;
            $dato['resultado'] = "registrar";
            $dato['mensaje'] = "Equipo registrado exitosamente.";
        } catch (PDOException $e) {
            $con->rollBack();
            $dato['estado'] = 0;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "Error: " . $e->getMessage();
        }
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();
            $query = "UPDATE equipo SET tipo_equipo= :tipo_equipo, serial= :serial, codigo_bien= :codigo_bien, 
                     id_unidad= :id_unidad WHERE id_equipo = :id";

            $stm = $con->prepare($query);
            $stm->bindParam(":id", $this->id_equipo);
            $stm->bindParam(":tipo_equipo", $this->tipo_equipo);
            $stm->bindParam(":serial", $this->serial);
            $stm->bindParam(":codigo_bien", $this->codigo_bien);
            $stm->bindParam(":id_unidad", $this->id_unidad);
            $stm->execute();
            $con->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del equipo con éxito";
        } catch (PDOException $e) {
            $con->rollBack();
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "Error: " . $e->getMessage();
        }
        $this->Cerrar_Conexion($con, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $dato = [];
        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "UPDATE equipo SET estatus = 0 WHERE id_equipo = :id_equipo";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);

            $stm->execute();
            $con->commit();
            $dato['estado'] = 1;
            $dato['resultado'] = "eliminar";
            $dato['mensaje'] = "Equipo eliminado exitosamente.";
        } catch (PDOException $e) {
            $con->rollBack();
            $dato['estado'] = 0;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "Error: " . $e->getMessage();
        }
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            // Mejorar la consulta: incluir si el equipo está ocupado (tiene punto de conexión)
            $query = "SELECT e.*, 
                         u.nombre_unidad, 
                         CONCAT(et.nombre,' - ', d.nombre) AS dependencia,
                         CASE WHEN pc.id_equipo IS NOT NULL THEN 1 ELSE 0 END AS ocupado
                  FROM equipo e 
                  JOIN unidad u ON e.id_unidad = u.id_unidad
                  JOIN dependencia d ON u.id_dependencia = d.id
                  JOIN ente et ON d.id_ente = et.id
                  LEFT JOIN punto_conexion pc ON pc.id_equipo = e.id_equipo
                  WHERE u.estatus = 1 AND e.estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function ConsultarEliminadas()
    {
        $dato = [];
        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "SELECT e.*, u.nombre_unidad 
                     FROM equipo e 
                     JOIN unidad u ON e.id_unidad = u.id_unidad 
                     WHERE u.estatus = 0 OR e.estatus = 0";

            $stm = $con->prepare($query);
            $stm->execute();
            $con->commit();
            $dato['resultado'] = 'consultar_eliminadas';
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = 'error';
            $dato['mensaje'] = $e->getMessage();
            $dato['datos'] = [];
        }
        return $dato;
    }

    private function Restaurar()
    {
        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "UPDATE equipo SET estatus = 1 WHERE id_equipo = :id_equipo";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_equipo', $this->id_equipo);

            $stm->execute();
            $con->commit();
            $dato['estado'] = 1;
            $dato['resultado'] = "restaurar";
            $dato['mensaje'] = "Equipo restaurado exitosamente.";
        } catch (PDOException $e) {
            $con->rollBack();
            $dato['estado'] = 0;
            $dato['resultado'] = "error";
            $dato['mensaje'] = "Error: " . $e->getMessage();
        }
        return $dato;
    }

    private function equiposPorDependencia($idDependencia)
    {
        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $query = "SELECT e.id_equipo, e.tipo_equipo, e.serial, e.codigo_bien, b.descripcion
                      FROM equipo e
                      INNER JOIN bien b ON e.codigo_bien = b.codigo_bien
                      WHERE b.id_dependencia = :id_dependencia AND e.estatus = 1 AND b.estatus = 1";

            $stm = $con->prepare($query);
            $stm->bindParam(':id_dependencia', $idDependencia);
            $stm->execute();
            $con->commit();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    private function equiposPorEmpleado($cedula_empleado, $nro_solicitud = null)
    {
        $datos = [];
        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();

            $sql = "SELECT e.id_equipo, e.tipo_equipo, e.serial, e.codigo_bien, b.descripcion
                    FROM equipo e
                    INNER JOIN bien b ON e.codigo_bien = b.codigo_bien
                    LEFT JOIN solicitud s ON e.id_equipo = s.id_equipo AND s.estado_solicitud IN ('Pendiente', 'En proceso')
                    WHERE b.cedula_empleado = :cedula
                    AND e.estatus = 1
                    AND b.estatus = 1
                    AND (s.id_equipo IS NULL";

            if ($nro_solicitud !== null) {
                $sql .= " OR s.nro_solicitud = :nro_solicitud";
            }
            $sql .= ")";

            $stm = $con->prepare($sql);
            $stm->bindParam(':cedula', $cedula_empleado);
            if ($nro_solicitud !== null) {
                $stm->bindParam(':nro_solicitud', $nro_solicitud);
            }

            $stm->execute();
            $con->commit();
            $datos = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $con->rollBack();
            echo "Error: " . $e->getMessage();
        }
        return $datos;
    }

    public function HistorialEquipo()
    {
        $datos = [];

        try {
            $con = new Conexion("sistema");
            $con = $con->Conex();
            $con->beginTransaction();
            $query = "SELECT e.id_equipo, e.serial, e.tipo_equipo, hs.id_tipo_servicio, s.nro_solicitud,
            s.motivo, hs.observacion, hs.resultado_hoja_servicio, ts.nombre_tipo_servicio,
            hs.codigo_hoja_servicio, CONCAT(emp.nombre_empleado, ' ', emp.apellido_empleado) AS empleado
                 FROM equipo e
                 INNER JOIN solicitud s ON e.id_equipo = s.id_equipo
                 INNER JOIN hoja_servicio hs ON s.nro_solicitud = hs.nro_solicitud
                 INNER JOIN tipo_servicio ts ON ts.id_tipo_servicio = hs.id_tipo_servicio
                 INNER JOIN empleado emp ON emp.cedula_empleado = s.cedula_solicitante
                 WHERE e.id_equipo = :id
                 AND e.estatus = 1";

            $stm = $con->prepare($query);
            $stm->bindParam(':id', $this->id_equipo);
            $stm->execute();

            $datos['resultado'] = 'detalle';
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $con->commit();
        } catch (PDOException $e) {
            $con->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = "Error: " . $e->getMessage();
        }

        $this->Cerrar_Conexion($con, $stm);
        return $datos;
    }


    public function obtenerTipoServicioPorEquipo($id_equipo)
    {
        if (empty($id_equipo)) return 1; // Default a Soporte Técnico

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();

            // Obtener el tipo de servicio asociado al bien del equipo
            $sql = "SELECT COALESCE(b.id_tipo_servicio, c.id_tipo_servicio, 1) as id_tipo_servicio 
                FROM equipo e
                JOIN bien b ON e.codigo_bien = b.codigo_bien
                LEFT JOIN categoria c ON b.id_categoria = c.id_categoria
                WHERE e.id_equipo = :id_equipo
                AND e.estatus = 1
                LIMIT 1";

            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['id_tipo_servicio'] ?? 1; // Default a Soporte Técnico si no hay asociación
        } catch (PDOException $e) {
            error_log("Error al obtener tipo de servicio: " . $e->getMessage());
            return 1; // Default a Soporte Técnico en caso de error
        }
    }

    private function obtenerTipoServicio($idEquipo)
    {
        try {
            $conexion = new Conexion("sistema");
            $conexion = $conexion->Conex();

            $sql = "SELECT COALESCE(c.id_tipo_servicio, 1) as id_tipo_servicio 
                FROM equipo e
                JOIN bien b ON e.codigo_bien = b.codigo_bien
                LEFT JOIN categoria c ON b.id_categoria = c.id_categoria
                WHERE e.id_equipo = :id_equipo
                LIMIT 1";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_equipo', $idEquipo, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'resultado' => 'success',
                'id_tipo_servicio' => $resultado['id_tipo_servicio'] ?? 1 // Default a Soporte Técnico
            ];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    public function Transaccion($peticion)
    {
        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'restaurar':
                return $this->Restaurar();

            case 'detalle':
                return $this->HistorialEquipo();

            case 'equipos_por_dependencia':
                return $this->Restaurar();

            case 'equipos_por_empleado':
                $nro_solicitud = isset($peticion['nro_solicitud']) ? $peticion['nro_solicitud'] : null;
                return $this->equiposPorEmpleado($peticion['cedula_empleado'], $nro_solicitud);
            case 'obtener_tipo_servicio':
                return $this->obtenerTipoServicio($peticion['id_equipo']);

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}
