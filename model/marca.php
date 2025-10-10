<?php
require_once "model/conexion.php";
class Marca extends Conexion
{

    private $id;
    private $nombre;
    private $conexion;

    public function __construct()
    {

        $this->id = 0;
        $this->nombre = "";
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

    public function get_id()
    {
        return $this->id;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }


    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            // NO iniciar transacción aquí, solo es una consulta
            $query = "SELECT * FROM marca WHERE id_marca = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
        } catch (PDOException $e) {
            $dato['bool'] = -1;
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
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

                $query = "INSERT INTO marca(id_marca, nombre_marca) VALUES (:id, :nombre)";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->execute();

                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la marca exitosamente";
                $this->conexion->commit();
            } catch (PDOException $e) {
                if ($this->conexion->inTransaction()) {
                    $this->conexion->rollBack();
                }
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

            $query = "UPDATE marca SET nombre_marca = :nombre WHERE id_marca = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->execute();

            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos de la marca con éxito";
            $this->conexion->commit();
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
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
                $this->conexion->beginTransaction(); // Mover beginTransaction aquí

                $query = "UPDATE marca SET estatus = 0 WHERE id_marca = :id";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();

                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó la marca exitosamente";
                $this->conexion->commit();
            } catch (PDOException $e) {
                // Solo hacer rollback si hay una transacción activa
                if ($this->conexion->inTransaction()) {
                    $this->conexion->rollBack();
                }
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al eliminar el registro - Marca no encontrada";
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
            $query = "SELECT * FROM marca WHERE estatus = 1";

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

            $query = "SELECT * FROM marca WHERE estatus = 0";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar_eliminados";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();

            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Reactivar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "UPDATE marca SET estatus = 1 WHERE id_marca = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Marca reactivada exitosamente";

            $this->conexion->commit();
        } catch (PDOException $e) {
            // Solo hacer rollback si hay una transacción activa
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
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

            case 'consultar_eliminadas':
                return $this->ConsultarEliminados();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'reactivar':
                return $this->Reactivar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}
