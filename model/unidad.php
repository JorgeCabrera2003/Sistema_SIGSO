<?php
require_once "model/conexion.php";
require_once "model/dependencia.php";
class Unidad extends Conexion
{

    private $id;
    private $id_dependencia;
    private $nombre;
    private $dependencia;

    public function __construct()
    {
        $this->id = 0;
        $this->id_dependencia = 0;
        $this->nombre = "";
        $this->dependencia = NULL;
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

            $this->dependencia = new Ente();

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
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();

            $this->conex->beginTransaction();
            $query = "SELECT * FROM unidad WHERE id_unidad = :id";

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
            $this->rollBack();
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $bool = $this->Validar();
        $validarDependencia = NULL;

        if ($bool['bool'] == 0) {
            try {
                $this->conex = new Conexion("sistema");
                $this->conex = $this->conex->Conex();

                $this->LlamarDependencia()->set_id($this->get_id_dependencia());
                $validarDependencia = $this->LlamarDependencia()->Transaccion(['peticion' => 'validar']);
                $this->conex->beginTransaction();
                if ($validarDependencia['bool'] == 1 && $validarDependencia['arreglo']['estatus'] == 1) {
                    $query = "INSERT INTO unidad(id_unidad, id_dependencia, nombre_unidad) VALUES 
                (NULL, :id_dependencia, :nombre)";

                    $stm = $this->conex->prepare($query);
                    $stm->bindParam(":nombre", $this->nombre);
                    $stm->bindParam(":id_dependencia", $this->id_dependencia);
                    $stm->execute();
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró el unidad exitosamente";
                    $this->conex->commit();

                } else {

                    $this->conex->rollBack();
                    $dato['resultado'] = "error";
                    $dato['estado'] = -1;
                    $dato['mensaje'] = "Error, Dependencia no existe";
                }

            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $this->conex->rollBack();
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
        $validarDependencia = NULL;

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $this->LlamarDependencia()->set_id($this->get_id_dependencia());
            $validarDependencia = $this->LlamarDependencia()->Transaccion(['peticion' => 'validar']);

            if ($validarDependencia['bool'] == 1 && $validarDependencia['arreglo']['estatus'] == 1) {

                $query = "UPDATE unidad SET nombre_unidad = :nombre, id_dependencia = :id_dependencia WHERE id_unidad = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->bindParam(":id_dependencia", $this->id_dependencia);
                $stm->execute();
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos de la unidad con éxito";
                $this->conex->commit();
            } else {

                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "Error, Dependencia no existe";

            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
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
                $this->conex = new Conexion("sistema");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "UPDATE unidad SET estatus = 0 WHERE id_unidad = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el unidad exitosamente";
                $this->conex->commit();
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $this->conex->rollBack();
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
            $this->conex->beginTransaction();
            $query = "SELECT unidad.id_unidad, 
            unidad.nombre_unidad, unidad.estatus,
            CONCAT(ente.nombre, ' - ' , dependencia.nombre) AS dependencia
            FROM unidad
            INNER JOIN dependencia ON unidad.id_dependencia = dependencia.id
            INNER JOIN ente ON dependencia.id_ente = ente.id
            WHERE unidad.estatus = 1";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function FiltrarUnidad_Dependencia()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM unidad WHERE estatus = 1 AND id_dependencia = :dependencia";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":dependencia", $this->id_dependencia);
            $stm->execute();
            $dato['resultado'] = "filtrar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
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

            case 'filtrar':
                return $this->FiltrarUnidad_Dependencia();

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