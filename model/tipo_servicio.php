<?php
require_once "model/conexion.php";
class TipoServicio extends Conexion
{

    private $id_tipo_servicio;
    private $nombre_tipo_servicio;


    public function __construct()
    {
        $this->id_tipo_servicio = 0;
        $this->nombre_tipo_servicio = "";
    }

    public function set_codigo($codigo)
    {
        $this->id_tipo_servicio = $codigo;
    }

    public function set_nombre($nombre)
    {
        $this->nombre_tipo_servicio = $nombre;
    }

    public function get_codigo()
    {
        return $this->id_tipo_servicio;
    }

    public function get_nombre()
    {
        return $this->nombre_tipo_servicio;
    }


    private function Validar()
    {
        $dato = [];

        try {
             $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
            $query = "SELECT * FROM tipo_servicio WHERE id_tipo_servicio = :codigo";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $this->id_tipo_servicio);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
        } catch (PDOException $e) {
            $dato['error'] = $e->getMessage();
            $dato['bool'] = -1;
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
                $this->conex = new Conexion("sistema");
                $this->conex = $this->conex->Conex();
                $query = "INSERT INTO tipo_servicio (nombre_tipo_servicio, estatus) VALUES (:nombre, 1)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":nombre", $this->nombre_tipo_servicio);
                $stm->execute();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el servicio exitosamente";
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
             $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
            $query = "UPDATE tipo_servicio SET nombre_tipo_servicio = :nombre WHERE id_tipo_servicio = :codigo";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $this->id_tipo_servicio);
            $stm->bindParam(":nombre", $this->nombre_tipo_servicio);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del servicio con éxito";
        } catch (PDOException $e) {
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Eliminar()
    {
         $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $query = "UPDATE tipo_servicio SET estatus = 0 WHERE id_tipo_servicio = :codigo";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo", $this->id_tipo_servicio);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el servicio exitosamente";
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
             $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
            $query = "SELECT id_tipo_servicio, nombre_tipo_servicio 
                 FROM tipo_servicio 
                 WHERE estatus = 1
                 ORDER BY nombre_tipo_servicio";

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
