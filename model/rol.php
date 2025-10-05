<?php
require_once "model/conexion.php";

class Rol extends Conexion
{

    private $id;
    private $nombre;
    private $conexion;
    private $permiso;

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->conexion = NULL;
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

    public function ObjPermiso(Permiso &$permiso)
    {
        $this->permiso = $permiso;
    }

    private function FiltrarPermiso($parametro = "nombre_modulo")
    {
        $this->permiso->set_id_rol($this->get_id());

        return $this->permiso->Transaccion(['peticion' => 'filtrar_permiso', 'parametro' => $parametro]);
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM rol WHERE id_rol = :id";

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
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function UltimoID()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT MAX(id_rol) AS ultimo_id FROM rol";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $fila = $stm->fetch(PDO::FETCH_ASSOC);
            $dato['id_rol'] = $fila['ultimo_id'];
            $dato['estado'] = 1;
            $this->conexion->commit();

        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $validar = $this->Validar();
        if ($validar['bool'] == 0) {
            try {
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "INSERT INTO rol (id_rol, nombre_rol) VALUES (:id, :nombre)";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->execute();

                $this->conexion->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['id_rol'] = $this->conexion->lastInsertId();
                $dato['mensaje'] = "Se registró el rol exitosamente";
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $this->conexion->rollBack();
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
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "UPDATE rol SET nombre_rol = :nombre WHERE id_rol = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->execute();
            $this->conexion->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del rol con éxito";

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
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "UPDATE rol SET estatus = 0 WHERE id_rol = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el rol exitosamente";
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
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM rol WHERE estatus = 1";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $this->conexion->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
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

            case 'ultimo_id':
                return $this->UltimoID();

            case 'filtrar_permiso':
                return $this->FiltrarPermiso($peticion['parametro']);

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