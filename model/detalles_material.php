<?php
require_once "model/conexion.php";
class DetalleMaterial extends Conexion
{
    private $id;
    private $id_material;
    private $accion;
    private $cantidad;
    private $descripcion;
    private $conex;

    public function __construct()
    {
        $this->id = "";
        $this->id_material = "";
        $this->accion = "";
        $this->cantidad = 0;
        $this->descripcion = "";
        $this->conex = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_material($id_material)
    {
        $this->id_material = $id_material;
    }

    public function set_accion($accion)
    {
        $this->accion = $accion;
    }

    public function set_cantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function set_descripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_id_material()
    {
        return $this->id_material;
    }

    public function get_accion()
    {
        return $this->accion;
    }

    public function get_cantidad()
    {
        return $this->cantidad;
    }

    public function get_descripcion()
    {
        return $this->descripcion;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM movimiento_materiales WHERE id_movimiento_material = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $this->conex->commit();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['bool'] = -1;
            $dato['error'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
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
                $this->conex->beginTransaction();
                $query = "INSERT INTO movimiento_materiales(id_movimiento_material, id_material, accion, cantidad, descripcion)
                VALUES (:id, :id_material, :accion, :cantidad, :descripcion)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":id_material", $this->id_material);
                $stm->bindParam(":accion", $this->accion);
                $stm->bindParam(":cantidad", $this->cantidad);
                $stm->bindParam(":descripcion", $this->descripcion);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el detalle del material exitosamente";
            } catch (PDOException $e) {
                if ($this->conex->inTransaction()) {
                    $this->conex->rollBack();
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
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "UPDATE movimiento_materiales SET id_material = :id_material, 
            accion = :accion, cantidad = :cantidad, descripcion = :descripcion WHERE id_movimiento_material = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":id_material", $this->id_material);
            $stm->bindParam(":accion", $this->accion);
            $stm->bindParam(":cantidad", $this->cantidad);
            $stm->bindParam(":descripcion", $this->descripcion);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del detalle del material con éxito";
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
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
            $query = "SELECT mm.id_movimiento_material, m.nombre_material, mm.accion, mm.cantidad, mm.descripcion
            FROM movimiento_materiales AS mm
            INNER JOIN material AS m ON mm.id_material = m.id_material
            WHERE m.id_material = :id_material";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_material", $this->id_material);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    public function Transaccion($peticion)
    {
        if (!is_array($peticion) || !isset($peticion["peticion"])) {
            return "Operacion: peticion no valida";
        }

        switch ($peticion['peticion']) {
            case 'registrar':
                return $this->Registrar();

            case 'validar':
                return $this->Validar();

            case 'consultar':
                return $this->Consultar();

            case 'actualizar':
                return $this->Actualizar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}