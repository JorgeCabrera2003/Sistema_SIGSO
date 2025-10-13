<?php
require_once('model/empleado.php');
require_once('config/cargo.php');

class Tecnico extends Empleado
{

    private $grado_experiencia;
    private $id_servicio;
    private $conexion;

    public function __construct()
    {
        $this->cedula = "";
        $this->nombre = "";
        $this->id_cargo = NULL;
        $this->id_unidad = NULL;
        $this->telefono = "";
        $this->correo = "";
        $this->grado_experiencia = 0;
        $this->id_servicio = 0;
        $this->conexion = NULL;
    }

    public function set_id_servicio($id_servicio)
    {
        $this->id_servicio = $id_servicio;
    }
        public function set_grado_experiencia($grado_experiencia)
    {
        $this->grado_experiencia = $grado_experiencia;
    }
    public function get_grado_experiencia()
    {
        return $this->grado_experiencia;
    }
    public function get_id_servicio()
    {
        return $this->id_servicio;
    }

    // CRUD para técnicos (empleados con cargo técnico)
    public function Registrar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "INSERT INTO empleado (cedula_empleado, nombre_empleado, apellido_empleado, id_cargo, id_servicio, grado_experiencia_empleado, id_unidad, telefono_empleado, correo_empleado)
                        VALUES (:cedula, :nombre, :apellido, :id_cargo, :id_servicio, :grado_experiencia, :id_unidad, :telefono, :correo)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':id_cargo', $this->id_cargo);
            $stmt->bindParam(':grado_experiencia', $this->grado_experiencia);
            $stmt->bindParam(':id_servicio', $this->id_servicio);
            $stmt->bindParam(':id_unidad', $this->id_unidad);
            $stmt->bindParam(':telefono', $this->telefono);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->execute();
            $this->conexion->commit();
            $datos['resultado'] = "registrar";
            $datos['mensaje'] = "Se registró el técnico exitosamente";
            $datos['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
            $datos['estado'] = -1;
        }
        return $datos;
    }

    public function Modificar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "UPDATE empleado SET nombre_empleado=:nombre, apellido_empleado=:apellido, 
                    id_cargo=:id_cargo, id_servicio=:id_servicio, grado_experiencia_empleado = :grado_experiencia, 
                    id_unidad=:id_unidad, telefono_empleado=:telefono, correo_empleado=:correo
                    WHERE cedula_empleado=:cedula";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':apellido', $this->apellido);
            $stmt->bindParam(':id_cargo', $this->id_cargo);
            $stmt->bindParam(':id_servicio', $this->id_servicio);
            $stmt->bindParam(':grado_experiencia', $this->grado_experiencia);
            $stmt->bindParam(':id_unidad', $this->id_unidad);
            $stmt->bindParam(':telefono', $this->telefono);
            $stmt->bindParam(':correo', $this->correo);
            $stmt->execute();
            $this->conexion->commit();
            $datos['resultado'] = "modificar";
            $datos['mensaje'] = "Se modificó el técnico exitosamente";
            $datos['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
            $datos['estado'] = -1;
        }
        return $datos;
    }

    public function Eliminar()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "UPDATE FROM empleado WHERE cedula_empleado=:cedula AND id_cargo=:id_cargo";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->bindParam(':id_cargo', $this->id_cargo);
            $stmt->execute();
            $this->conexion->commit();
            $datos['resultado'] = "eliminar";
            $datos['mensaje'] = "Se eliminó el técnico exitosamente";
            $datos['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
            $datos['estado'] = -1;
        }
        return $datos;
    }

    public function Consultar()
    {
        $datos = [];
        $id_cargoTecnico = Ccargo[0]['id'];
        $id_cargoEncargado = Ccargo[1]['id'];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT 
                            e.cedula_empleado AS cedula,
                            e.nombre_empleado AS nombre,
                            e.apellido_empleado AS apellido,
                            e.telefono_empleado AS telefono,
                            e.correo_empleado AS correo,
                            d.nombre AS dependencia,
                            u.nombre_unidad AS unidad,
                            c.nombre_cargo AS cargo,
                            ts.nombre_tipo_servicio AS servicio
                        FROM empleado e
                        LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                        LEFT JOIN dependencia d ON u.id_dependencia = d.id
                        LEFT JOIN cargo c ON e.id_cargo = c.id_cargo
                        LEFT JOIN tipo_servicio ts ON e.id_servicio = ts.id_tipo_servicio
                        WHERE (e.id_cargo = :id_cargoTecnico || e.id_cargo = :id_cargoEncargado) AND e.nombre_empleado != 'root'";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_cargoTecnico', $id_cargoTecnico);
            $stmt->bindParam(':id_cargoEncargado', $id_cargoEncargado);
            $stmt->execute();
            $datos['resultado'] = "consultar";
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        return $datos;
    }

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
            $datos['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $datos;
    }

    public function contarTecnico()
    {
        $datos = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $sql = "SELECT * FROM filtrado_tecnico";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $datos['resultado'] = "consultar";
            $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $datos['resultado'] = "error";
            $datos['mensaje'] = $e->getMessage();
        }
        return $datos;
    }

    public function Transaccion($peticion)
    {
        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();
            case 'modificar':
                return $this->Modificar();
            case 'eliminar':
                return parent::Transaccion(['peticion' => 'eliminar']);
            case 'consultar':
                return $this->Consultar();
            case 'validar':
                return $this->Validar();
            case 'contarTecnico':
                return $this->contarTecnico();
            default:
                return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
        }
    }
}
?>