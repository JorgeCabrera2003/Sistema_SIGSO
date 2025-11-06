<?php
require_once('model/conexion.php');

class Empleado extends Conexion
{
    protected $cedula;
    protected $nombre;
    protected $apellido;
    protected $id_cargo;
    protected $id_unidad;
    protected $telefono;
    protected $correo;
    protected $id_dependencia;
    private $conexion;

    public function __construct()
    {
        $this->cedula = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->id_cargo = NULL;
        $this->id_unidad = NULL;
        $this->telefono = "";
        $this->correo = "";
        $this->id_dependencia = NULL;
        $this->conexion = NULL;
    }

    // Setters y Getters
    public function set_id_dependencia($id_dependencia)
    {
        $this->id_dependencia = $id_dependencia;
    }
    public function get_id_dependencia()
    {
        return $this->id_dependencia;
    }
    public function set_cedula($cedula)
    {
        $this->cedula = $cedula;
    }
    public function get_cedula()
    {
        return $this->cedula;
    }

    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }

    public function set_apellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function get_apellido()
    {
        return $this->apellido;
    }

    public function set_telefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function get_telefono()
    {
        return $this->telefono;
    }

    public function set_correo($correo)
    {
        $this->correo = $correo;
    }
    public function get_correo()
    {
        return $this->correo;
    }

    public function set_id_cargo($id_cargo)
    {
        $this->id_cargo = $id_cargo;
    }
    public function get_id_cargo()
    {
        return $this->id_cargo;
    }

    public function set_id_unidad($id_unidad)
    {
        $this->id_unidad = $id_unidad;
    }
    public function get_id_unidad()
    {
        return $this->id_unidad;
    }

    // === VALIDACIÓN DE EXISTENCIA ===
    private function Validar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $stm = $this->conexion->prepare("SELECT * FROM empleado WHERE cedula_empleado = :cedula");
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();
            $this->conexion->commit();

            if ($stm->rowCount() > 0) {
                $datos['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $datos['bool'] = 1;
            } else {
                $datos['bool'] = 0;
            }
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['bool'] = -1;
            $datos['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === REGISTRAR EMPLEADO ===
    private function Registrar()
    {
        $datos = [];
        $bool = $this->Validar();

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();

                $stm = $this->conexion->prepare("INSERT INTO empleado 
                (cedula_empleado, nombre_empleado, apellido_empleado, id_cargo, id_unidad, telefono_empleado, correo_empleado) 
                VALUES (:cedula, :nombre, :apellido, :cargo, :unidad, :telefono, :correo)");

                $stm->bindParam(':cedula', $this->cedula);
                $stm->bindParam(':nombre', $this->nombre);
                $stm->bindParam(':apellido', $this->apellido);
                $stm->bindParam(':cargo', $this->id_cargo);
                $stm->bindParam(':unidad', $this->id_unidad);
                $stm->bindParam(':telefono', $this->telefono);
                $stm->bindParam(':correo', $this->correo);

                $stm->execute();
                $this->conexion->commit();

                $datos['resultado'] = "registrar";
                $datos['mensaje'] = "Se registró el empleado exitosamente";
                $datos['estado'] = 1;
            } catch (PDOException $e) {
                if ($this->conexion->inTransaction()) {
                    $this->conexion->rollBack();
                }
                $datos['resultado'] = "error";
                $datos['estado'] = -1;
                $datos['mensaje'] = $e->getMessage();
            }
        } else {
            $datos['resultado'] = "error";
            $datos['mensaje'] = "Error: registro duplicado";
            $datos['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === CONSULTAR EMPLEADOS ACTIVOS ===
    private function Consultar($filtro = NULL)
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
                e.cedula_empleado AS cedula,
                e.nombre_empleado AS nombre,
                e.apellido_empleado AS apellido,
                e.telefono_empleado AS telefono,
                e.correo_empleado AS correo,
                u.nombre_unidad AS unidad,
                CONCAT(et.nombre,' - ',d.nombre) AS dependencia,
                c.nombre_cargo AS cargo,
                ts.nombre_tipo_servicio AS servicio
            FROM empleado AS e
            LEFT JOIN unidad AS u ON e.id_unidad = u.id_unidad
            LEFT JOIN dependencia AS d ON u.id_dependencia = d.id
            LEFT JOIN ente AS et ON d.id_ente = et.id
            LEFT JOIN cargo AS c ON e.id_cargo = c.id_cargo
            LEFT JOIN tipo_servicio AS ts ON e.id_servicio = ts.id_tipo_servicio
            WHERE e.nombre_empleado != 'root' AND e.estatus = 1";

            // Filtro por cédula
            if ($filtro && isset($filtro['cedula'])) {
                $query .= " AND e.cedula_empleado = :cedula";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(':cedula', $filtro['cedula']);
            } else if ($filtro && isset($filtro['rol']) && $filtro['rol'] == 'tecnico') {
                $query .= " AND e.id_cargo = 'TECNI0012025100112013227'";
                $stm = $this->conexion->prepare($query);
            } else {
                $stm = $this->conexion->prepare($query);
            }

            $stm->execute();
            $datos['resultado'] = "consultar";
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === CONSULTAR EMPLEADOS ELIMINADOS ===
    private function ConsultarEliminadas()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
                e.cedula_empleado AS cedula,
                e.nombre_empleado AS nombre,
                e.apellido_empleado AS apellido,
                e.telefono_empleado AS telefono,
                e.correo_empleado AS correo,
                u.nombre_unidad AS unidad,
                CONCAT(et.nombre,' - ',d.nombre) AS dependencia,
                c.nombre_cargo AS cargo,
                ts.nombre_tipo_servicio AS servicio
            FROM empleado AS e
            LEFT JOIN unidad AS u ON e.id_unidad = u.id_unidad
            LEFT JOIN dependencia AS d ON u.id_dependencia = d.id
            LEFT JOIN ente AS et ON d.id_ente = et.id
            LEFT JOIN cargo AS c ON e.id_cargo = c.id_cargo
            LEFT JOIN tipo_servicio AS ts ON e.id_servicio = ts.id_tipo_servicio
            WHERE e.nombre_empleado != 'root' AND e.estatus = 0";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $this->conexion->commit();

            $datos['resultado'] = "consultar_eliminadas";
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === ACTUALIZAR EMPLEADO ===
    private function Actualizar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "UPDATE empleado SET nombre_empleado = :nombre, apellido_empleado = :apellido, 
                id_cargo = :cargo, id_unidad = :unidad, telefono_empleado = :telefono, correo_empleado = :correo 
                WHERE cedula_empleado = :cedula";

            $stm = $this->conexion->prepare($query);

            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':apellido', $this->apellido);
            $stm->bindParam(':cargo', $this->id_cargo);
            $stm->bindParam(':unidad', $this->id_unidad);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);

            $stm->execute();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Se modificó el empleado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Error: No se encontró el registro";
                $datos['estado'] = -1;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === ELIMINAR EMPLEADO (BORRADO LÓGICO) ===
    private function Eliminar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "UPDATE empleado SET estatus = 0 WHERE cedula_empleado = :cedula";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "eliminar";
                $datos['mensaje'] = "Se eliminó el empleado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "eliminar";
                $datos['mensaje'] = "Error: No se encontró el registro";
                $datos['estado'] = -1;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === REACTIVAR EMPLEADO ===
    private function Reactivar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "UPDATE empleado SET estatus = 1 WHERE cedula_empleado = :cedula";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "reactivar";
                $datos['mensaje'] = "Empleado reactivado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "reactivar";
                $datos['mensaje'] = "Error: No se encontró el empleado para reactivar";
                $datos['estado'] = -1;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === REACTIVAR USUARIO ===
    private function ReactivarUsuario()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "UPDATE usuario SET estatus = 1 WHERE cedula = :cedula";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "reactivar_usuario";
                $datos['mensaje'] = "Usuario reactivado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "reactivar_usuario";
                $datos['mensaje'] = "No se encontró el usuario para reactivar";
                $datos['estado'] = 0;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
            $datos['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    // === REACTIVAR EMPLEADO Y USUARIO ===
    private function ReactivarCompleto()
    {
        $datos = [];

        // Reactivar empleado
        $resultadoEmpleado = $this->Reactivar();

        if ($resultadoEmpleado['estado'] == 1) {
            // Reactivar usuario
            $resultadoUsuario = $this->ReactivarUsuario();

            if ($resultadoUsuario['estado'] == 1) {
                $datos['resultado'] = "reactivar";
                $datos['mensaje'] = "Empleado y usuario reactivados exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "reactivar";
                $datos['mensaje'] = "Empleado reactivado pero no se pudo reactivar el usuario";
                $datos['estado'] = 1; // Aún así consideramos éxito porque el empleado se reactivó
            }
        } else {
            $datos = $resultadoEmpleado; // Retorna el error del empleado
        }

        return $datos;
    }

    // === MÉTODOS AUXILIARES EXISTENTES ===
    private function Empleados_dependencia($dependenciaId)
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
                e.cedula_empleado AS cedula, 
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre 
            FROM empleado AS e
            JOIN unidad AS u ON e.id_unidad = u.id_unidad
            WHERE u.id_dependencia = ?";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(1, $dependenciaId, PDO::PARAM_INT);
            $stm->execute();

            $datos['resultado'] = "success";
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        return $datos;
    }

    public function empleadosPorDependencia($idDependencia)
    {
        $datos = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT e.cedula_empleado, e.nombre_empleado, e.apellido_empleado 
                     FROM empleado e
                     JOIN unidad u ON e.id_unidad = u.id_unidad
                     WHERE u.id_dependencia = :idDependencia
                     AND e.cedula_empleado IS NOT NULL";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':idDependencia', $idDependencia);
            $stm->execute();

            $datos['resultado'] = 'consultar_solicitantes';
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    private function ModificarDatosPersonales()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $stm = $this->conexion->prepare("UPDATE empleado SET 
                nombre_empleado = :nombre, 
                apellido_empleado = :apellido, 
                telefono_empleado = :telefono, 
                correo_empleado = :correo 
                WHERE cedula_empleado = :cedula");

            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':apellido', $this->apellido);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);

            $stm->execute();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Se modificó el empleado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Error: No se encontró el registro";
                $datos['estado'] = -1;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $datos;
    }

    private function consultar_solicitantes()
    {
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
                e.cedula_empleado AS cedula, 
                e.nombre_empleado AS nombre, 
                e.apellido_empleado AS apellido, 
                e.telefono_empleado AS telefono, 
                e.correo_empleado AS correo, 
                u.nombre_unidad AS unidad, 
                d.nombre AS dependencia
            FROM empleado AS e
            JOIN unidad AS u ON e.id_unidad = u.id_unidad
            JOIN dependencia AS d ON u.id_dependencia = d.id";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $this->conexion->commit();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return [];
        }
    }

    private function mis_servicios()
    {
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT * FROM solicitud WHERE cedula_solicitante = ?";
            $stm = $this->conexion->prepare($query);
            $stm->execute([$this->cedula]);
            $this->conexion->commit();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return [];
        }
    }

    private function listar_tecnicos()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT 
                e.cedula_empleado, 
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre_completo,
                ts.nombre_tipo_servicio
            FROM empleado e
            LEFT JOIN tipo_servicio ts ON e.id_servicio = ts.id_tipo_servicio
            WHERE (e.id_cargo = 'TECNI0012025100112013227' OR e.id_cargo = 'ENCAR0022025100112011321') AND e.nombre_empleado != 'root'";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
            $datos['resultado'] = "listar_tecnicos";
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }
        return $datos;
    }

    private function obtener_tecnico()
    {
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
            e.cedula_empleado,
            e.nombre_empleado,
            e.apellido_empleado,
            e.id_cargo,
            e.id_servicio,
            e.id_unidad,
            e.telefono_empleado,
            e.correo_empleado,
            ts.nombre_tipo_servicio,
            c.nombre_cargo
        FROM empleado e
        LEFT JOIN tipo_servicio ts ON e.id_servicio = ts.id_tipo_servicio
        LEFT JOIN cargo c ON e.id_cargo = c.id_cargo
        WHERE e.cedula_empleado = :cedula
        AND e.id_cargo = 'TECNI0012025100112013227'";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':cedula', $this->cedula);
            $stm->execute();
            $this->conexion->commit();
            $datos = $stm->fetch(PDO::FETCH_ASSOC);

            if ($datos) {
                return ['resultado' => 'success', 'datos' => $datos];
            } else {
                return ['resultado' => 'error', 'mensaje' => 'Técnico no encontrado'];
            }
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    public function obtenerTecnico($cedula)
    {
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT cedula_empleado, id_cargo, id_servicio FROM empleado WHERE cedula_empleado = :cedula LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            $this->conexion->commit();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return null;
        }
    }

    private function contarEmpleados()
    {
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM filtrado_empleado";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $datos = $stm->fetch(PDO::FETCH_ASSOC);
            if ($datos) {
                return ['resultado' => 'success', 'datos' => $datos];
            } else {
                return ['resultado' => 'error', 'mensaje' => 'Técnico no encontrado'];
            }
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    private function tecnicosPorAreaRendimiento($area_id)
    {
        $datos = ['resultado' => 'error', 'mensaje' => '', 'datos' => []];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $sql = "
                SELECT 
                    e.cedula_empleado, 
                    CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre,
                    COALESCE(hs.cant_hojas, 0) AS hojas_mes
                FROM empleado e
                LEFT JOIN (
                    SELECT 
                        cedula_tecnico, 
                        COUNT(*) AS cant_hojas
                    FROM hoja_servicio
                    WHERE estatus = 'A'
                    GROUP BY cedula_tecnico
                ) hs ON hs.cedula_tecnico = e.cedula_empleado
                WHERE e.id_servicio = :area_id
                    AND e.id_cargo = 'TECNI0012025100112013227'
                    AND e.estatus = 1
                ORDER BY hojas_mes ASC, nombre ASC
            ";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
            $stmt->execute();
            $datos['resultado'] = 'success';
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stmt);
        return $datos;
    }

    private function ConsultarPorCedula()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT 
                    e.id_ente,
                    d.id AS id_dependencia, 
                    u.id_unidad,
                    ent.nombre AS nombre_ente,
                    d.nombre AS nombre_dependencia,
                    u.nombre_unidad AS nombre_unidad
                  FROM empleado emp
                  INNER JOIN unidad u ON emp.id_unidad = u.id_unidad
                  INNER JOIN dependencia d ON u.id_dependencia = d.id
                  INNER JOIN ente ent ON d.id_ente = ent.id
                  WHERE emp.cedula = :cedula AND emp.estatus = 1";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();

            $dato['resultado'] = "consultar_por_cedula";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    // === TRANSACCIÓN PRINCIPAL ===
    public function Transaccion($peticion)
    {
        // Validar formato de petición
        if (!is_array($peticion) || !isset($peticion["peticion"])) {
            return "Operacion: peticion no valida";
        }

        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();
            case 'modificar':
                return $this->Actualizar();
            case 'modificar_datos_personal':
                return $this->ModificarDatosPersonales();
            case 'eliminar':
                return $this->Eliminar();
            case 'consultar':
                return $this->Consultar($peticion);
            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();
            case 'reactivar':
                return $this->ReactivarCompleto(); // Usa la función que reactiva ambos
            case 'reactivar_empleado':
                return $this->Reactivar();
            case 'reactivar_usuario':
                return $this->ReactivarUsuario();
            case 'validar':
                return $this->Validar();
            case 'listar':
                return $this->consultar_solicitantes();
            case 'consultar_por_cedula':
                return $this->ConsultarPorCedula();
            case 'empleados_dependencia':
                return $this->Empleados_dependencia($peticion['dependenciaId']);
            case 'mis_servicios':
                return $this->mis_servicios();
            case 'listar_tecnicos':
                return $this->listar_tecnicos();
            case 'obtener_tecnico':
                $this->set_cedula($peticion['cedula']);
                return $this->obtener_tecnico();
            case 'contar_empleados':
                return $this->contarEmpleados();
            case 'tecnicos_por_area_rendimiento':
                return $this->tecnicosPorAreaRendimiento($peticion['area_id']);
            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}
