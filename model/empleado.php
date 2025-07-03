<?php
require_once('model/conexion.php');

class Empleado extends Conexion
{
    private $cedula;
    private $nombre;
    private $apellido;
    private $id_cargo;
    private $id_servicio;
    private $id_unidad;
    private $telefono;
    private $correo;
    private $id_dependencia;

    public function __construct()
    {
        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
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

    public function set_id_servicio($id_servicio)
    {
        $this->id_servicio = $id_servicio;
    }
    public function get_id_servicio()
    {
        return $this->id_servicio;
    }

    public function set_id_unidad($id_unidad)
    {
        $this->id_unidad = $id_unidad;
    }
    public function get_id_unidad()
    {
        return $this->id_unidad;
    }

    private function Registrar()
    {
        $datos = [];
        $bool = $this->Validar();

        if ($bool['bool'] == 0) {
            try {
                $stm = $this->conex->prepare("INSERT INTO empleado 
                (cedula_empleado, nombre_empleado, apellido_empleado, id_cargo, id_servicio, id_unidad, telefono_empleado, correo_empleado) 
                VALUES (:cedula, :nombre, :apellido, :cargo, :servicio, :unidad, :telefono, :correo)");

                $stm->bindParam(':cedula', $this->cedula);
                $stm->bindParam(':nombre', $this->nombre);
                $stm->bindParam(':apellido', $this->apellido);
                $stm->bindParam(':cargo', $this->id_cargo);
                $stm->bindParam(':servicio', $this->id_servicio);
                $stm->bindParam(':unidad', $this->id_unidad);
                $stm->bindParam(':telefono', $this->telefono);
                $stm->bindParam(':correo', $this->correo);

                $stm->execute();
                $this->conex->commit();
                $datos['resultado'] = "registrar";
                $datos['mensaje'] = "Se registró el empleado exitosamente";
                $datos['estado'] = 1;
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $datos['resultado'] = "error";
                $datos['estado'] = -1;
                $datos['mensaje'] = $e->getMessage();
            }
        } else {
            $datos['resultado'] = "error";
            $datos['mensaje'] = "Error: registro duplicado";
            $datos['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function Consultar($filtro = NULL)
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();
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
            LEFT JOIN tipo_servicio AS ts ON e.id_servicio = ts.id_tipo_servicio";
            if ($filtro && isset($filtro['cedula'])) {
                $query .= " WHERE e.cedula_empleado = :cedula";
                $stm = $this->conex->prepare($query);
                $stm->bindParam(':cedula', $filtro['cedula']);
            } else {
                $stm = $this->conex->prepare($query);
            }

            $stm->execute();
            $datos['resultado'] = "consultar";
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function Empleados_dependencia($dependenciaId)
    {
        $datos = [];
        try {
            $query = "SELECT 
                e.cedula_empleado AS cedula, 
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre 
            FROM empleado AS e
            JOIN unidad AS u ON e.id_unidad = u.id_unidad
            WHERE u.id_dependencia = ?";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(1, $dependenciaId, PDO::PARAM_INT);
            $stm->execute();

            $datos['resultado'] = "success";
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
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
            $this->conex->beginTransaction();
            $query = "SELECT e.cedula_empleado, e.nombre_empleado, e.apellido_empleado 
                     FROM empleado e
                     JOIN unidad u ON e.id_unidad = u.id_unidad
                     WHERE u.id_dependencia = :idDependencia
                     AND e.cedula_empleado IS NOT NULL";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(':idDependencia', $idDependencia);
            $stm->execute();

            $datos['resultado'] = 'consultar_solicitantes';
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function Validar()
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();
            $stm = $this->conex->prepare("SELECT * FROM empleado WHERE cedula_empleado = ?");
            $stm->execute([$this->cedula]);
            $this->conex->commit();
            if ($stm->rowCount() > 0) {
                $datos['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $datos['bool'] = 1;
            } else {
                $datos['bool'] = 0;
            }
        } catch (PDOException $e) {
            $datos['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $datos;
    }

    private function Eliminar()
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();
            $query = "DELETE FROM empleado WHERE cedula_empleado = :cedula";
            $stm = $this->conex->prepare($query);
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
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function Modificar()
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();
            $stm = $this->conex->prepare("UPDATE empleado SET 
                nombre_empleado = :nombre, 
                apellido_empleado = :apellido, 
                id_cargo = :cargo,
                id_servicio = :servicio,
                id_unidad = :unidad,
                telefono_empleado = :telefono, 
                correo_empleado = :correo 
                WHERE cedula_empleado = :cedula");

            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':nombre', $this->nombre);
            $stm->bindParam(':apellido', $this->apellido);
            $stm->bindParam(':cargo', $this->id_cargo);
            $stm->bindParam(':servicio', $this->id_servicio);
            $stm->bindParam(':unidad', $this->id_unidad);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);

            $stm->execute();
            $this->conex->commit();

            if ($stm->rowCount() > 0) {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Se modificó el empleado exitosamente";
                $datos['estado'] = 1;
            } else {
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Error: No se encontró el registro";
                $datos['estado'] = -1;
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos['resultado'] = "error";
            $datos['estado'] = -1;
            $datos['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function consultar_solicitantes()
    {
        try {
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

            $stm = $this->conex->prepare($query);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    private function mis_servicios()
    {
        try {
            $query = "SELECT * FROM solicitud WHERE cedula_solicitante = ?";
            $stm = $this->conex->prepare($query);
            $stm->execute([$this->cedula]);
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Nuevo método para obtener información de técnicos por área
    private function listar_tecnicos()
    {
        try {
            $query = "SELECT 
                e.cedula_empleado, 
                CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre_completo,
                ts.nombre_tipo_servicio
            FROM empleado e
            JOIN tipo_servicio ts ON e.id_servicio = ts.id_tipo_servicio
            WHERE e.id_cargo = 1"; // 1 = Técnico

            $stm = $this->conex->prepare($query);
            $stm->execute();

            return ['resultado' => 'success', 'datos' => $stm->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    // Nuevo método para obtener información de un técnico específico
    private function obtener_tecnico()
    {
        try {
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
        AND e.id_cargo = 1"; // 1 = ID de cargo Técnico

            $stm = $this->conex->prepare($query);
            $stm->bindParam(':cedula', $this->cedula);
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

    /**
     * Devuelve los datos del técnico por cédula (para uso en servicios)
     */
    public function obtenerTecnico($cedula)
    {
        try {
            $sql = "SELECT cedula_empleado, id_cargo, id_servicio FROM empleado WHERE cedula_empleado = :cedula LIMIT 1";
            $stmt = $this->conex->prepare($sql); // <-- usar $this->conex
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function Transaccion($peticion)
    {
        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();
            case 'modificar':
                return $this->Modificar();
            case 'eliminar':
                return $this->Eliminar();
            case 'consultar':
                return $this->Consultar($peticion);
            case 'validar':
                return $this->Validar();
            case 'listar':
                return $this->consultar_solicitantes();
            case 'empleados_dependencia':
                return $this->Empleados_dependencia($peticion['dependenciaId']);
            case 'mis_servicios':
                return $this->mis_servicios();
            case 'listar_tecnicos':
                return $this->listar_tecnicos();
            case 'obtener_tecnico':
                $this->set_cedula($peticion['cedula']);
                return $this->obtener_tecnico();
            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}
