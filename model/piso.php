<?php
require_once "model/conexion.php";
class Piso extends Conexion
{

    private $id;
    private $id_edificio;
    private $tipo;
    private $nro_piso;

    public function __construct()
    {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
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

    public function get_id_edificio()
    {
        return $this->id_edificio;
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
            $query = "SELECT * FROM piso WHERE id_piso = :id_piso";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $this->id);
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
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $bool = $this->Validar();
        $dato = [];

        if ($bool['bool'] == 0) {
            try {
                $query = "INSERT INTO piso(tipo_piso, nro_piso) 
                VALUES (:tipo_piso, :nro_piso)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":tipo_piso", $this->tipo);
                $stm->bindParam(":nro_piso", $this->nro_piso);
                $stm->execute();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registro con éxito";
            } catch (PDOException $e) {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
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
            $query = "UPDATE piso SET tipo_piso= :tipo_piso,
                nro_piso= :nro_piso WHERE id_piso= :id_piso";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $this->id);
            $stm->bindParam(":tipo_piso", $this->tipo);
            $stm->bindParam(":nro_piso", $this->nro_piso);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se actualizó el registro con éxito";
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $bool = $this->Validar();
        $dato = [];

        if ($bool['bool'] == 1) {
            try {
                $query = "UPDATE piso SET estatus = 0 WHERE id_piso= :id_piso";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id_piso", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el registro con éxito";
            } catch (PDOException $e) {
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
            $query = "SELECT piso.id_piso, piso.tipo_piso, piso.nro_piso,
                  CONCAT(piso.tipo_piso, ' ', piso.nro_piso) AS nombre_piso
            FROM piso
            WHERE piso.estatus = 1
            ORDER BY piso.tipo_piso ASC, piso.nro_piso ASC";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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

            case 'validar':
                $json = $this->Validar();
                $this->Cerrar_Conexion($this->conex, $none);
                return $json;

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