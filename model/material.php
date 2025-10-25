<?php
require_once "model/conexion.php";
require_once "model/detalles_material.php";
class Material extends Conexion
{
    private $id;
    private $nombre;
    private $ubicacion;
    private $stock;
    private $estatus;
    private $detalles_material;
    private $conex;

    public function __construct()
    {
        $this->id = "";
        $this->nombre = "";
        $this->ubicacion = "";
        $this->stock = 0;
        $this->estatus = 1;
        $this->detalles_material = NULL;
        $this->conex = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function set_ubicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function set_stock($stock)
    {
        $this->stock = $stock;
    }

    public function set_estatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_ubicacion()
    {
        return $this->ubicacion;
    }

    public function get_stock()
    {
        return $this->stock;
    }

    public function get_estatus()
    {
        return $this->estatus;
    }

    private function LlamarDetallesMaterial()
    {
        if ($this->detalles_material == NULL) {
            $this->detalles_material = new DetalleMaterial();
        }
        return $this->detalles_material;
    }

    private function DestruirDetallesMaterial()
    {
        $this->detalles_material = NULL;
    }

    public function listarDisponibles()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $query = "SELECT id_material, nombre_material, stock 
                 FROM material 
                 WHERE estatus = 1 AND stock > 0
                 ORDER BY nombre_material";

            $stm = $this->conex->prepare($query);
            $stm->execute();

            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM material WHERE id_material = :id";

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
                $query = "INSERT INTO material(id_material, ubicacion, nombre_material, stock, estatus) VALUES 
                (:id, :ubicacion, :nombre, :stock, 1)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":nombre", $this->nombre);
                $stm->bindParam(":ubicacion", $this->ubicacion);
                $stm->bindParam(":stock", $this->stock);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el material exitosamente";
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
            $query = "UPDATE material SET nombre_material = :nombre, ubicacion = :ubicacion, stock = :stock
            WHERE id_material = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":nombre", $this->nombre);
            $stm->bindParam(":ubicacion", $this->ubicacion);
            $stm->bindParam(":stock", $this->stock);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del material con éxito";
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

    private function Eliminar()
    {
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $this->conex = new Conexion("sistema");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "UPDATE material SET estatus = 0 WHERE id_material = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el material exitosamente";
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
            $query = "SELECT m.*, o.nombre_oficina FROM material m 
                     LEFT JOIN oficina o ON m.ubicacion = o.id_oficina 
                     WHERE m.estatus = 1";

            $stm = $this->conex->prepare($query);
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

    private function ConsultarEliminadas()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT m.*, o.nombre_oficina FROM material m 
                     LEFT JOIN oficina o ON m.ubicacion = o.id_oficina 
                     WHERE m.estatus = 0";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "consultar_eliminadas";
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

    private function reactivar()
    {
        $dato = [];
        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "UPDATE material SET estatus = 1 WHERE id_material = :id_material";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_material", $this->id);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Material restaurado exitosamente";

            // Bitacora condicional para evitar errores en testing
            if (isset($_SESSION['user']['nombre_usuario']) && function_exists('Bitacora')) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró el material ID: " . $this->id;
                Bitacora($msg, "Material");
            }
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function reporte($fechaInicio, $fechaFin)
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT m.*, o.nombre_oficina 
                      FROM material m 
                      LEFT JOIN oficina o ON m.ubicacion = o.id_oficina 
                      WHERE m.estatus = 1";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();

            $dato['resultado'] = "success";
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

    private function VerDetalles()
    {
        $this->LlamarDetallesMaterial()->set_id_material($this->get_id());
        return $this->LlamarDetallesMaterial()->Transaccion(['peticion' => 'consultar']);
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

            case 'eliminar':
                return $this->Eliminar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();

            case 'reactivar':
                return $this->reactivar();

            case 'detalle':
                return $this->VerDetalles();

            case 'reporte':
                $fechaInicio = $peticion['fecha_inicio'] ?? null;
                $fechaFin = $peticion['fecha_fin'] ?? null;
                return $this->reporte($fechaInicio, $fechaFin);

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}