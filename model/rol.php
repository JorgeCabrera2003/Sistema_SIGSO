<?php
require_once "model/conexion.php";
class Rol extends Conexion
{

    private $id;
    private $nombre;


    public function __construct()
    {

        $this->conex = new Conexion("usuario");
        $this->conex = $this->conex->Conex();
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
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM rol WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;

            } else {
                $dato['bool'] = 0;
            }
            $this->conex->commit();

        } catch (PDOException $e) {
            $this->conex->rollBack();
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
                $this->conex = new Conexion("usuario");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "INSERT INTO rol (id, nombre) VALUES (NULL, :nombre)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->execute();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la servicio exitosamente";
                $this->conex->commit();
            } catch (PDOException $e) {
                $this->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $this->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Registro duplicado";
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "UPDATE rol SET nombre= :nombre WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->execute();
            $this->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del servicio con éxito";
        } catch (PDOException $e) {
            $this->rollBack();
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $this->conex = new Conexion("usuario");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "UPDATE rol SET estatus = 0 WHERE id = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el servicio exitosamente";
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al eliminar el registro";
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM rol";

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

    public function Transaccion($peticion)
    {

        switch ($peticion['peticion']) {

            case 'registrar':
                return $this->Registrar();

            case 'consultar':
                return $this->Consultar();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }
}
?>