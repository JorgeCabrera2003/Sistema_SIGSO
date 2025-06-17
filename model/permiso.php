<?php
require_once "model/conexion.php";
class Permiso extends Conexion
{

    private $id;
    private $id_rol;
    private $modulo;
    private $accion;
    private $estado;


    public function __construct()
    {

        $this->conex = new Conexion("usuario");
        $this->conex = $this->conex->Conex();
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_rol($id_rol)
    {
        $this->id_rol = $id_rol;
    }

    public function set_modulo($modulo)
    {
        $this->modulo = $modulo;
    }

    public function set_accion($accion)
    {
        $this->accion = $accion;
    }
    public function set_estado($estado)
    {
        $this->estado = $estado;
    }
    public function get_id()
    {
        return $this->id;
    }

    public function get_id_rol()
    {
        return $this->id_rol;
    }

    public function get_modulo()
    {
        return $this->modulo;
    }


    public function get_accion()
    {
        return $this->accion;
    }

    public function get_estado()
    {
        return $this->estado;
    }

private function objetoToArray($objeto)
{
    if (is_object($objeto)) {
        // Convertir el objeto a array
        $objeto = (array) $objeto;
        
        // Recorrer cada elemento del array
        foreach ($objeto as &$valor) {
            // Si el valor es un objeto, convertirlo recursivamente
            if (is_object($valor)) {
                $valor = $this->objetoToArray($valor);
            }
            // Si el valor es un array, procesar sus elementos
            elseif (is_array($valor)) {
                $valor = array_map(function ($item) {
                    return is_object($item) ? $this->objetoToArray($item) : $item;
                }, $valor);
            }
        }
        return $objeto;
    }
    
    if (is_array($objeto)) {
        return array_map(function ($valor) {
            return is_object($valor) ? $this->objetoToArray($valor) : $valor;
        }, $objeto);
    }
    
    return $objeto;
}


    private function CargarPermiso($datos)
    {
        $dato = [];
        $permisos = $this->objetoToArray($datos);
        print_r($permisos);
        try {
            $this->conex->beginTransaction();
            foreach ($permisos as $key) {
                $query = "SELECT * FROM permiso WHERE (id_rol = :rol AND id_modulo = :modulo) AND accion_permiso = :accion";    
                $this->set_modulo($key['modulo']);
                $stm = $this->conex->prepare($query);
                $stm->bindParam(":rol", $this->id_rol);
                $stm->bindParam(":modulo", $this->modulo);
                foreach ($key['permisos'] as $accion) {
                    $this->set_accion($accion['accion']);
                    $this->set_estado($accion['estado']);

                    $stm->bindParam(":accion", $this->accion);
                    $stm->execute();
                    if ($stm->rowCount() == 0) {
                        $sqlR = "INSERT INTO permiso(id_permiso, id_rol, id_modulo, accion_permiso, estado) VALUES (NULL, :rol, :modulo, :accion, :estado)";

                        $registrar = $this->conex->prepare($sqlR);
                        $registrar->bindParam(":rol", $this->id_rol);
                        $registrar->bindParam(":modulo", $this->modulo);
                        $registrar->bindParam(":accion", $this->accion);
                        $registrar->bindParam(":estado", $this->estado);

                        $registrar->execute();
                    } else {
                        $sqlM = "UPDATE permiso SET estado = :estado WHERE (id_rol = :rol AND id_modulo = :modulo) AND accion_permiso = :accion";
                        $modificar = $this->conex->prepare($sqlM);

                        $modificar->bindParam(":rol", $this->id_rol);
                        $modificar->bindParam(":modulo", $this->modulo);
                        $modificar->bindParam(":accion", $this->accion);
                        $modificar->bindParam(":estado", $this->estado);
                        $modificar->execute();
                    }
                }
            }
            $dato['estado'] = 1;
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['estado'] = -1;
            $dato['error'] = $e->getMessage();

        }
        print_r($this->get_id_rol());
        print_r($dato);
        $this->Cerrar_Conexion($none, $stm);
        return $dato;
    }
    private function Validar()
    {
        $dato = [];

        try {
            $query = "SELECT * FROM permiso WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['arreglo'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
            }

        } catch (PDOException $e) {
            $dato['error'] = $e->getMessage();
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
                $query = "INSERT INTO permiso (id, modulo) VALUES 
            (NULL, :modulo)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":modulo", $this->modulo);
                $stm->execute();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la permisos exitosamente";
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
            $query = "UPDATE permiso SET modulo = :modulo WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":modulo", $this->modulo);
            $stm->execute();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los permisos con éxito";
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
        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $query = "UPDATE permiso SET estatus = 0 WHERE id = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el permisos exitosamente";
            } catch (PDOException $e) {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al eliminar el permiso";
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $query = "SELECT * FROM permiso WHERE estatus = 1";

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

            case 'cargar_permiso':
                return $this->CargarPermiso($peticion["permisos"]);

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }
}