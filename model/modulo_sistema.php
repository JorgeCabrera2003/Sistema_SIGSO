<?php
require_once "model/conexion.php";
require_once "config/modulo.php";
class Modulo_Sistema extends Conexion
{

    private $id;
    private $modulo;
    private $conexion;


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
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM modulo";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $this->conexion->commit();
            $validacion = $stm->fetchAll(PDO::FETCH_ASSOC);
            $dato['resultado'] = "comprobar";

            if ($stm->rowCount() == count(modulos)) {



                foreach (modulos as $indice) {
                    foreach ($validacion as $llave) {
                        if (($indice['id'] == $llave['id_modulo']) && ($indice['modulo'] == $llave['nombre_modulo'])) {
                            $dato['bool'] = true;
                            $dato['mensaje'] = "Módulos cumplen con la validación";
                            $dato['icon'] = "success";
                            break;
                        } else {
                            $dato['bool'] = false;
                            $dato['mensaje'] = "Validación fallida";
                            $dato['icon'] = "warning";
                        }
                    }
                }
            } else {
                $dato['mensaje'] = "Error al comprobar módulos";
                $dato['icon'] = "warning";
                $dato['bool'] = false;
            }

        } catch (PDOException $e) {
            $dato['bool'] = false;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Reestablecer_Modulos()
    {
        $dato = [];

        $stmR = NULL;
        $stmM = NULL;


        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $queryRegistrar = "INSERT INTO modulo (id_modulo, nombre_modulo) VALUES (:id, :modulo)";
            $queryModificar = "UPDATE modulo SET nombre_modulo = :modulo WHERE id_modulo = :id";
            $stmR = $this->conexion->prepare($queryRegistrar);
            $stmM = $this->conexion->prepare($queryModificar);
            foreach (modulos as $key) {
                $this->set_id($key['id']);
                $this->set_modulo($key['modulo']);
                $busqueda = $this->Validar();

                if ($busqueda['bool'] == 0) {

                    $stmR->bindParam(":id", $this->id);
                    $stmR->bindParam(":modulo", $this->modulo);
                    $stmR->execute();

                } else {

                    $stmM->bindParam(":id", $this->id);
                    $stmM->bindParam(":modulo", $this->modulo);

                    $stmM->execute();

                }
            }

            $this->conexion->commit();
            $dato['resultado'] = "cargar";
            $dato['mensaje'] = "Módulos cargados correctamente";
            $dato['estado'] = 1;

        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Validar()
    {
        $dato = [];
        $transaccion = false;

        try {

            if ($this->conexion === NULL || !$this->conexion->inTransaction()) {
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $transaccion = true;
            }
            $query = "SELECT * FROM modulo WHERE id_modulo = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            if ($transaccion) {
                $this->conexion->commit();
            }

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }

        } catch (PDOException $e) {
            if ($transaccion) {
                $this->conexion->rollBack();
            }
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        if ($transaccion) {
            $this->Cerrar_Conexion($this->conexion, $stm);
        }
        return $dato;
    }

    private function Registrar()
    {
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] == 0) {
            try {
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "INSERT INTO modulo (id, modulo) VALUES 
            (NULL, :modulo)";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":modulo", $this->modulo);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el módulo exitosamente";
            } catch (PDOException $e) {
                $this->conexion->rollBack();
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

        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "UPDATE modulo SET modulo= :modulo WHERE id = :id";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":modulo", $this->modulo);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del módulo con éxito";
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
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $query = "UPDATE modulo SET estatus = 0 WHERE id = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el módulo exitosamente";
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
            $dato['mensaje'] = "Error al eliminar el registro";
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM modulo";

            $stm = $this->conexion->prepare($query);
            $stm->execute();
            $this->conexion->commit();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
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