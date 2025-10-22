<?php
require_once "model/conexion.php";
require_once "model/dependencia.php";
class Unidad extends Conexion
{

    private $id;
    private $id_dependencia;
    private $nombre;
    private $dependencia;
    private $conexion;

    public function __construct()
    {
        $this->id = 0;
        $this->id_dependencia = 0;
        $this->nombre = "";
        $this->dependencia = NULL;
        $this->conexion = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }
    public function set_id_dependencia($id_dependencia)
    {
        $this->id_dependencia = $id_dependencia;
    }

    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_id_dependencia()
    {
        return $this->id_dependencia;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    private function LlamarDependencia()
    {
        if ($this->dependencia == NULL) {

            $this->dependencia = new Dependencia();

        }

        return $this->dependencia;
    }

    private function DestruirDependencia()
    {
        $this->dependencia = NULL;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();

            $this->conexion->beginTransaction();
            $query = "SELECT * FROM unidad WHERE id_unidad = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['arreglo'] = NULL;
                $dato['bool'] = 0;
            }
            $this->conexion->commit();

        } catch (PDOException $e) {
            $this->rollBack();
            $dato['error'] = $e->getMessage();
            $dato['bool'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $bool = $this->Validar();
        $validarDependencia = NULL;

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();

                $this->LlamarDependencia()->set_id($this->get_id_dependencia());
                $validarDependencia = $this->LlamarDependencia()->Transaccion(['peticion' => 'validar']);
                $this->conexion->beginTransaction();
                if ($validarDependencia['bool'] == 1 && $validarDependencia['arreglo']['estatus'] == 1) {
                    $query = "INSERT INTO unidad(id_unidad, id_dependencia, nombre_unidad) VALUES 
                (:id, :id_dependencia, :nombre)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id", $this->id);
                    $stm->bindParam(":nombre", $this->nombre);
                    $stm->bindParam(":id_dependencia", $this->id_dependencia);
                    $stm->execute();
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró el unidad exitosamente";
                    $this->conexion->commit();

                } else {

                    $this->conexion->rollBack();
                    $dato['resultado'] = "error";
                    $dato['estado'] = -1;
                    $dato['mensaje'] = "Error, Dependencia no existe";
                }

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
        $validarDependencia = NULL;

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $this->LlamarDependencia()->set_id($this->get_id_dependencia());
            $validarDependencia = $this->LlamarDependencia()->Transaccion(['peticion' => 'validar']);

            if ($validarDependencia['bool'] == 1 && $validarDependencia['arreglo']['estatus'] == 1) {

                $query = "UPDATE unidad SET nombre_unidad = :nombre, id_dependencia = :id_dependencia WHERE id_unidad = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->bindParam(":id_dependencia", $this->id_dependencia);
                $stm->execute();
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos de la unidad con éxito";
                $this->conexion->commit();
            } else {

                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "Error, Dependencia no existe";

            }
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
                $query = "UPDATE unidad SET estatus = 0 WHERE id_unidad = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el unidad exitosamente";
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
            $query = "SELECT unidad.id_unidad, 
            unidad.nombre_unidad, unidad.estatus,
            CONCAT(ente.nombre, ' - ' , dependencia.nombre) AS dependencia
            FROM unidad
            INNER JOIN dependencia ON unidad.id_dependencia = dependencia.id
            INNER JOIN ente ON dependencia.id_ente = ente.id
            WHERE unidad.estatus = 1";

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
            $query = "SELECT unidad.id_unidad, 
            unidad.nombre_unidad, unidad.estatus,
            CONCAT(ente.nombre, ' - ' , dependencia.nombre) AS dependencia
            FROM unidad
            INNER JOIN dependencia ON unidad.id_dependencia = dependencia.id
            INNER JOIN ente ON dependencia.id_ente = ente.id
            WHERE unidad.estatus = 0";
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
            $query = "UPDATE unidad SET estatus = 1 WHERE id_unidad = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Ente restaurado exitosamente";

        } catch (PDOException $e) {

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

    private function FiltrarUnidad_Dependencia()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM unidad WHERE estatus = 1 AND id_dependencia = :dependencia";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":dependencia", $this->id_dependencia);
            $stm->execute();
            $dato['resultado'] = "filtrar";
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

    public function Transaccion($peticion)
    {

        switch ($peticion['peticion']) {

            case 'registrar':
                return $this->Registrar();

            case 'validar':
                return $this->Validar();

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminados();

            case 'filtrar':
                return $this->FiltrarUnidad_Dependencia();

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
?>