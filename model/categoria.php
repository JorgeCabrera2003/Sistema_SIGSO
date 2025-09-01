<?php
require_once "model/conexion.php";
require_once "model/tipo_servicio.php";
class Categoria extends Conexion
{
    private $id;
    private $nombre;
    private $id_tipo_servicio;
    private $estatus;
    private $conexion;
    private $tipo_servicio;

    public function __construct()
    {
        $this->id = 0;
        $this->nombre = "";
        $this->estatus = 0;
        $this->conexion = NULL;
        $this->id_tipo_servicio = NULL;
        $this->tipo_servicio = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_servicio($tipo_servicio)
    {
        $this->id_tipo_servicio = $tipo_servicio;
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

    public function get_id_servicio()
    {
        return $this->id_tipo_servicio;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_estatus()
    {
        return $this->estatus;
    }

    private function LlamarTipoServicio()
    {
        if ($this->tipo_servicio == NULL) {
            $this->tipo_servicio = new TipoServicio();
        }
        return $this->tipo_servicio;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $query = "SELECT * FROM categoria WHERE id_categoria = :id";
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
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $bool = $this->Validar();
        $boolServicio = NULL;

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();

                $this->LlamarTipoServicio()->set_codigo($this->get_id_servicio());
                $boolServicio = $this->LlamarTipoServicio()->Transaccion(['peticion' => 'validar']);

                if ($boolServicio['bool'] == 1) {
                    $dato['servicio_asignado'] = 1;
                } else {
                    $this->set_id_servicio(NULL);
                    $dato['servicio_asignado'] = 0;
                }

                $query = "INSERT INTO categoria(id_categoria, nombre_categoria, id_tipo_servicio, estatus) VALUES 
                (:id, :nombre, :tipo_servicio, 1)";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->bindParam(":tipo_servicio", $this->id_tipo_servicio);
                $stm->execute();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la categoria exitosamente";
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
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];
        $boolServicio = NULL;

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();

            $this->LlamarTipoServicio()->set_codigo($this->get_id_servicio());
            $boolServicio = $this->LlamarTipoServicio()->Transaccion(['peticion' => 'validar']);

            if ($boolServicio['bool'] == 1) {
                $dato['servicio_asignado'] = 1;
            } else {
                $this->set_id_servicio(NULL);
                $dato['servicio_asignado'] = 0;
            }
            $query = "UPDATE categoria SET nombre_categoria = :nombre, id_tipo_servicio = :tipo_servicio WHERE id_categoria = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->bindParam(":tipo_servicio", $this->id_tipo_servicio);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos dla categoria con éxito";
        } catch (PDOException $e) {
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
                $query = "UPDATE categoria SET estatus = 0 WHERE id_categoria = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó la categoria exitosamente";
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
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $query = "SELECT c.id_categoria, c.nombre_categoria, c.id_tipo_servicio, c.id_tipo_servicio, ts.nombre_tipo_servicio AS servicio
            FROM categoria c 
            LEFT JOIN tipo_servicio ts ON ts.id_tipo_servicio = c.id_tipo_servicio
            WHERE c.estatus = 1";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ConsultarEliminadas()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM categoria WHERE estatus = 0";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar_eliminadas";
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
            $query = "UPDATE categoria SET estatus = 1 WHERE id_categoria = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $dato['resultado'] = "restaurar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "categoria restaurado exitosamente";
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
                $dato = $this->Validar();
                $this->conexion = NULL;
                return $dato;

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();

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