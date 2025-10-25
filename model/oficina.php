<?php
require_once "model/conexion.php";
require_once "model/piso.php";
class Oficina extends Conexion
{
    private $id;
    private $id_piso;
    private $nombre;
    private $estatus;
    private $piso;
    private $conexion;

    public function __construct()
    {
        $this->id = 0;
        $this->id_piso = 0;
        $this->nombre = "";
        $this->estatus = 0;
        $this->piso = NULL;
        $this->conexion = NULL; // Corregido: estaba mal escrito como $this->conexionion
    }

    public function set_id($id)
    {
        if ($id == NULL || !preg_match("/^[A-Z0-9]{3,50}$/", $id)) { // Cambiado de 24 a 50
            throw new ValueError("ID de Oficina no válido. Debe ser alfanumérico entre 3 y 50 caracteres");
        }
        $this->id = $id;
    }

    public function set_id_piso($id_piso)
    {
        if ($id_piso == NULL || !preg_match("/^[A-Z0-9]{3,50}$/", $id_piso)) { // Cambiado de 24 a 50
            throw new ValueError("ID de Piso no válido. Debe ser alfanumérico entre 3 y 50 caracteres");
        }
        $this->id_piso = $id_piso;
    }

    public function set_nombre($nombre)
    {
        if ($nombre == NULL || empty(trim($nombre))) {
            throw new ValueError("El nombre de la oficina no puede estar vacío");
        }

        if (strlen($nombre) > 45) {
            throw new ValueError("El nombre de la oficina no puede exceder 45 caracteres");
        }

        if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/", $nombre)) {
            throw new ValueError("El nombre de la oficina contiene caracteres no válidos");
        }

        $this->nombre = $nombre;
    }

    public function set_estatus($estatus)
    {
        if (!in_array($estatus, [0, 1])) {
            throw new ValueError("El estatus debe ser 0 (inactivo) o 1 (activo)");
        }
        $this->estatus = $estatus;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_id_piso()
    {
        return $this->id_piso;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_estatus()
    {
        return $this->estatus;
    }

    private function LlamarPiso()
    {
        if ($this->piso == NULL) {
            $this->piso = new Piso();
        }
        return $this->piso;
    }

    private function DestruirPiso()
    {
        $this->piso = NULL;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();

            $query = "SELECT * FROM oficina WHERE id_oficina = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $this->conexion->commit();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
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
        $validarPiso = NULL;

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();

                $this->LlamarPiso()->set_id($this->get_id_piso());
                $validarPiso = $this->LlamarPiso()->Transaccion(['peticion' => 'validar']);

                if ($validarPiso['bool'] == 1 && $validarPiso['arreglo']['estatus'] == 1) {
                    $query = "INSERT INTO oficina(id_oficina, id_piso, nombre_oficina, estatus) VALUES 
                            (:id, :id_piso, :nombre, 1)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id", $this->id);
                    $stm->bindParam(":id_piso", $this->id_piso);
                    $stm->bindParam(":nombre", $this->nombre);
                    $stm->execute();

                    $this->conexion->commit();
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró la oficina exitosamente";
                } else {
                    $this->conexion->rollBack();
                    $dato['resultado'] = "error";
                    $dato['estado'] = -1;
                    $dato['mensaje'] = "Error, piso no existe";
                }
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

        $this->DestruirPiso();
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

            $this->LlamarPiso()->set_id($this->get_id_piso());
            $validarPiso = $this->LlamarPiso()->Transaccion(['peticion' => 'validar']);

            if ($validarPiso['bool'] == 1 && $validarPiso['arreglo']['estatus'] == 1) {
                $query = "UPDATE oficina SET id_piso= :id_piso, nombre_oficina= :nombre WHERE id_oficina = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":id_piso", $this->id_piso);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->execute();

                $this->conexion->commit();
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos de la oficina con éxito";
            } else {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "Error, piso no existe";
            }
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->DestruirPiso();
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

                $query = "UPDATE oficina SET estatus = 0 WHERE id_oficina = :id";
                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();

                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó la oficina exitosamente";
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
            $dato['mensaje'] = "Error al eliminar el registro - Oficina no encontrada";
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

            $query = "SELECT o.id_oficina, nombre_oficina, p.tipo_piso, p.nro_piso
                    FROM oficina o 
                    JOIN piso p ON o.id_piso = p.id_piso 
                    WHERE o.estatus = 1
                    ORDER BY p.nro_piso ASC, o.nombre_oficina ASC";

            $stm = $this->conexion->prepare($query);
            $stm->execute();

            $this->conexion->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
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

            $query = "SELECT o.*, p.nro_piso
                    FROM oficina o 
                    JOIN piso p ON o.id_piso = p.id_piso 
                    WHERE o.estatus = 0
                    ORDER BY p.nro_piso ASC, o.nombre_oficina ASC";

            $stm = $this->conexion->prepare($query);
            $stm->execute();

            $this->conexion->commit();
            $dato['resultado'] = "consultar_eliminadas";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
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

            $query = "UPDATE oficina SET estatus = 1 WHERE id_oficina = :id";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            $this->conexion->commit();
            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Oficina restaurada exitosamente";
        } catch (PDOException $e) {
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
                return $this->ConsultarEliminadas();

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
