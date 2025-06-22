<?php
require_once "model/conexion.php";
require_once "config/modulo.php";
class Modulo_Sistema extends Conexion
{

    private $id;
    private $modulo;


    public function __construct()
    {
        $this->id = 0;
        $this->modulo = "";
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_modulo($modulo)
    {
        $this->modulo = $modulo;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_modulo()
    {
        return $this->modulo;
    }


    private function Comprobar_Modulos()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM modulo";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "comprobacion";

            if ($stm->rowCount() == count(modulos)) {
                $dato['bool'] = true;
            } else {
                $dato['mensaje'] = "Error al comprobar módulos";
                $dato['bool'] = false;
            }

        } catch (PDOException $e) {
            $dato['bool'] = false;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Reestablecer_Modulos()
    {
        $dato = [];

        $stmR = NULL;
        $stmM = NULL;


        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();


            foreach (modulos as $key) {
                $this->set_id($key['id']);
                $this->set_modulo($key['modulo']);
                $busqueda = $this->Validar(true);

                if ($busqueda['bool'] == 0) {
                    $queryRegistrar = "INSERT INTO modulo (id_modulo, nombre_modulo) VALUES (:id, :modulo)";

                    $stmR = $this->conex->prepare($queryRegistrar);
                    $stmR->bindParam(":id", $this->id);
                    $stmR->bindParam(":modulo", $this->modulo);
                    $stmR->execute();
                } else {
                    $queryModificar = "UPDATE modulo SET nombre_modulo = :modulo WHERE id_modulo = :id";

                    $stmM = $this->conex->prepare($queryModificar);
                    $stmM->bindParam(":id", $this->id);
                    $stmM->bindParam(":modulo", $this->modulo);
                    $stmM->execute();
                }
            }

            $this->conex->commit();
            $dato['resultado'] = "cargar";
            $dato['estado'] = 1;

        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Validar($transaccionActiva = false)
    {
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM modulo WHERE id_modulo = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            if (!$transaccionActiva) {
                $this->conex->commit();
            }

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }

        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
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
                $this->conex = new Conexion("usuario");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "INSERT INTO modulo (id, modulo) VALUES 
            (NULL, :modulo)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":modulo", $this->modulo);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el módulo exitosamente";
            } catch (PDOException $e) {
                $this->conex->rollBack();
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
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "UPDATE modulo SET modulo= :modulo WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":modulo", $this->modulo);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del módulo con éxito";
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
                $this->conex = new Conexion("usuario");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();
                $query = "UPDATE modulo SET estatus = 0 WHERE id = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el módulo exitosamente";
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
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM modulo";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
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

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'comprobar':
                return $this->Comprobar_Modulos();

            case 'cargar':
                return $this->Reestablecer_Modulos();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }
}
?>