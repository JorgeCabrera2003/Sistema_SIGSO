<?php
require_once "model/conexion.php";
class Piso extends Conexion
{

    private $id;
    private $tipo;
    private $nro_piso;
    private $conexion;

    public function __construct()
    {
        $this->id = 0;
        $this->tipo = "";
        $this->nro_piso = "";
        $this->conexion = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_edificio($id_edificio)
    {
        $this->id_edificio = $id_edificio;
    }

    public function set_tipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function set_nro_piso($nro_piso)
    {
        $this->nro_piso = $nro_piso;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_tipo()
    {
        return $this->tipo;
    }

    public function get_nro_piso()
    {
        return $this->nro_piso;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM piso WHERE id_piso = :id_piso";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id_piso", $this->id);
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
        $bool = $this->Validar();
        $dato = [];

        if ($bool['bool'] == 0) {

            $validarNroPiso = $this->ValidarNroPiso();

            if ($validarNroPiso['bool'] == 0) {
                try {
                    $this->conexion = new Conexion("sistema");
                    $this->conexion = $this->conexion->Conex();
                    $this->conexion->beginTransaction();
                    $query = "INSERT INTO piso(id_piso, tipo_piso, nro_piso) 
                VALUES (:id_piso, :tipo_piso, :nro_piso)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id_piso", $this->id);
                    $stm->bindParam(":tipo_piso", $this->tipo);
                    $stm->bindParam(":nro_piso", $this->nro_piso);
                    $stm->execute();
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registro con éxito";
                    $this->conexion->commit();
                } catch (PDOException $e) {
                    $this->conexion->rollBack();
                    $dato['resultado'] = "error";
                    $dato['estado'] = -1;
                    $dato['mensaje'] = $e->getMessage();
                }
            } else {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "Ya hay un piso con este mismo número";
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Registro duplicado";
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ValidarNroPiso()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM piso WHERE tipo_piso = :tipo_piso AND nro_piso = :nro_piso";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":tipo_piso", $this->tipo);
            $stm->bindParam(":nro_piso", $this->nro_piso);
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
            $dato['resultado'] = "error";
            $dato['bool'] = -1;
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];
        $validarNroPiso = $this->ValidarNroPiso();
        if ($validarNroPiso['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "UPDATE piso SET tipo_piso = :tipo_piso,
                nro_piso= :nro_piso WHERE id_piso = :id_piso";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id_piso", $this->id);
                $stm->bindParam(":tipo_piso", $this->tipo);
                $stm->bindParam(":nro_piso", $this->nro_piso);
                $stm->execute();
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se actualizó el registro con éxito";
                $this->conexion->commit();
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Ya hay un piso con este mismo número";
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $bool = $this->Validar();
        $dato = [];

        if ($bool['bool'] == 1) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "UPDATE piso SET estatus = 0 WHERE id_piso= :id_piso";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id_piso", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el registro con éxito";
                $this->conexion->commit();
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
            $query = "SELECT piso.id_piso, piso.tipo_piso, piso.nro_piso,
                  CONCAT(piso.tipo_piso, ' ', piso.nro_piso) AS nombre_piso
            FROM piso
            WHERE piso.estatus = 1
            ORDER BY piso.tipo_piso ASC, piso.nro_piso ASC";

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
            $query = "SELECT * FROM piso WHERE estatus = 0";
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
            $this->conexion->beginTransaction();
            $query = "UPDATE piso SET estatus = 1 WHERE id_piso = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $dato['resultado'] = "restaurar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Piso restaurado exitosamente";
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
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

            case 'validar':
                $json = $this->Validar();
                $this->Cerrar_Conexion($this->conexion, $none);
                return $json;

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminados();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'restaurar':
                return $this->Restaurar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }
    }
}
?>