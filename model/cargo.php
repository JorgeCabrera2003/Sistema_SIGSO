<?php
require_once "model/conexion.php";
class Cargo extends Conexion
{
    private $id;
    private $nombre;
    private $estatus;
    private $conexion;

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->estatus = 0;
        $this->conexionion = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function set_estatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_estatus()
    {
        return $this->estatus;
    }

    private function Validar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();


            $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
            $query = "SELECT * FROM cargo WHERE id_cargo = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['bool'] = -1;
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();


                $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
                $query = "INSERT INTO cargo(id_cargo, nombre_cargo, estatus) VALUES (:id, :nombre, 1)";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el cargo exitosamente";
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Registro duplicado";
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();


            $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
            $query = "UPDATE cargo SET nombre_cargo = :nombre WHERE id_cargo = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->execute();
            $this->conexion->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del cargo con éxito";
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();


                $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
                $query = "UPDATE cargo SET estatus = 0 WHERE id_cargo = :id";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el cargo exitosamente";
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al eliminar el registro";
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();


            $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
            $query = "SELECT * FROM cargo WHERE estatus = 1 ORDER BY nombre_cargo ASC";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar";
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

    private function ConsultarEliminados()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();


            $this->conexion->beginTransaction();
>>>>>>> d0463428e5bec6df44e7151e49495ddb3836bde6
            $query = "SELECT * FROM cargo WHERE estatus = 0 ORDER BY nombre_cargo ASC";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar_eliminados";
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

    private function Restaurar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $query = "UPDATE cargo SET estatus = 1 WHERE id_cargo = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $dato['resultado'] = "restaurar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Cargo restaurado exitosamente";
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    public function Transaccion($peticion)
    {
        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminados':
                return $this->ConsultarEliminados();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'restaurar':
                return $this->Restaurar();

            default:
                return "Operación: " . $peticion['peticion'] . " no válida";
        }
    }
}
?>