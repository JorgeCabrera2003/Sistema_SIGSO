<?php
require_once "model/conexion.php";
require_once "model/ente.php";
class Dependencia extends Conexion
{

    private $id;
    private $nombre;
    private $id_ente;
    private $ente;
    private $conexion;

    public function __construct()
    {
        $this->id = NULL;
        $this->nombre = "";
        $this->id_ente = NULL;
        $this->ente = NULL;
        $this->conexion = NULL;
    }

    public function set_id($id)
    {
        if ($id == NULL || preg_match("/^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/", $id) == 0) {
            throw new ValueError("Id no válido");
        }
        $this->id = $id;
    }

    public function set_nombre($nombre)
    {
        if ($nombre == NULL || preg_match('/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,90}$/', $nombre) == 0) {
            throw new ValueError("Nombre no válido");
        }
        $this->nombre = $nombre;
    }

    public function set_id_ente($id_ente)
    {
        if ($id_ente == NULL || preg_match("/^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/", $id_ente) == 0) {
            throw new ValueError("Id no válido");
        }
        $this->id_ente = $id_ente;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_id_ente()
    {
        return $this->id_ente;
    }


    private function LlamarEnte()
    {
        if ($this->ente == NULL) {

            $this->ente = new Ente();

        }

        return $this->ente;
    }

    private function DestruirEnte()
    {
        $this->ente = NULL;
    }
    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();

            $this->conexion->beginTransaction();
            $query = "SELECT * FROM dependencia WHERE id = :id";

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
        $verificar_ente = NULL;

        if ($bool['bool'] == 0) {
            try {

                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();

                $this->conexion->beginTransaction();
                $this->LlamarEnte()->set_id($this->get_id_ente());
                $verificar_ente = $this->LlamarEnte()->Transaccion(['peticion' => 'validar']);

                if ($verificar_ente['bool'] == 1 && $verificar_ente['arreglo']['estatus'] == 1) {

                    $query = "INSERT INTO dependencia(id, id_ente, nombre)
                VALUES (:id, :id_ente, :nombre)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id", $this->id);
                    $stm->bindParam(":nombre", $this->nombre);
                    $stm->bindParam(":id_ente", $this->id_ente);
                    $stm->execute();
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró la dependencia exitosamente";
                    $this->conexion->commit();
                } else {
                    $this->conexion->rollBack();
                    $dato['resultado'] = "error";
                    $dato['estado'] = -1;
                    $dato['mensaje'] = "No existe el Ente seleccionado";
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
        $this->DestruirEnte();
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
            $this->LlamarEnte()->set_id($this->get_id_ente());
            $verificar_ente = $this->LlamarEnte()->Transaccion(['peticion' => 'validar']);
            if ($verificar_ente['bool'] == 1 && $verificar_ente['arreglo']['estatus'] == 1) {
                $query = "UPDATE dependencia SET nombre = :nombre, id_ente = :id_ente
                WHERE id = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->bindParam(":id_ente", $this->id_ente);
                $stm->execute();
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos de la dependencia exitosamente";
                $this->conexion->commit();

            } else {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No existe el Ente seleccionado";

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

                $query = "UPDATE dependencia SET estatus = 0 WHERE id = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó la dependencia exitosamente";
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
            $query = "SELECT dep.id, dep.id_ente,
            dep.nombre, ente.nombre AS ente
            FROM dependencia dep
            INNER JOIN ente ON dep.id_ente = ente.id
            WHERE dep.estatus = 1";

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

    private function FiltradoDependencia()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT DISTINCT d.id AS id_dependencia, d.nombre AS nombre_dependencia  FROM dependencia d
            INNER JOIN ente e ON e.id = d.id_ente
            INNER JOIN unidad u ON d.id = u.id_dependencia
            WHERE e.id = :id_ente AND d.estatus = 1
            AND u.estatus = 1";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id_ente", $this->id_ente);
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
            $query = "SELECT dep.id, dep.id_ente,
            dep.nombre, ente.nombre AS ente
            FROM dependencia dep
            INNER JOIN ente ON dep.id_ente = ente.id
            WHERE dep.estatus = 0";
            $stm = $this->conexion->prepare($query);
            $stm->execute();

            $dato['resultado'] = "consultar_eliminados";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            $this->conexion->commit();
            $dato['resultado'] = "consultar_eliminados";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
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
            $query = "UPDATE dependencia SET estatus = 1 WHERE id = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Dependecia restaurada exitosamente";

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
    private function ConsultarAreas()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $query = "SELECT id_tipo_servicio, nombre_tipo_servicio AS nombre_servicio FROM tipo_servicio WHERE estatus = 1";
            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar_areas";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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
                $validar = $this->Validar();
                $this->Cerrar_Conexion($this->conexion, $none);
                return $validar;

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminados();

            case 'reactivar':
                return $this->Reactivar();

            case 'consultar_areas':
                return $this->ConsultarAreas();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'filtrar':
                return $this->FiltradoDependencia();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}
