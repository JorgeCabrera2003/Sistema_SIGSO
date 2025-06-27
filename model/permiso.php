<?php
require_once "model/conexion.php";
require_once "model/modulo_sistema.php";
class Permiso extends Conexion
{

    private $id;
    private $id_rol;
    private $modulo;
    private $accion;
    private $estado;
    private $modulo_sistema;


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

    private function Instancia_ModuloSistema()
    {
        $this->modulo_sistema = new Modulo_Sistema();
    }
    private function objetoToArray($objeto)
    {
        if (is_object($objeto)) {

            $objeto = (array) $objeto;

            foreach ($objeto as &$valor) {

                if (is_object($valor)) {
                    $valor = $this->objetoToArray($valor);
                } elseif (is_array($valor)) {
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


    private function FiltrarPermiso($filtro = "modulo_nombre")
    {
        if ($filtro == "modulo_nombre") {
            $columna = "nombre_modulo";
        } else {
            $columna = "id_modulo";
        }
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT p.id_permiso, p.id_rol, p.id_modulo, p.accion_permiso, p.estado, m.nombre_modulo
            FROM permiso AS p 
            INNER JOIN modulo AS m ON p.id_modulo = m.id_modulo
            WHERE p.id_rol = :rol";


            $stm = $this->conex->prepare($query);
            $stm->bindParam(':rol', $this->id_rol);
            $stm->execute();
            $this->conex->commit();
            $resultadoQuery = $stm->fetchAll(PDO::FETCH_ASSOC);
            $permisos = [];

            foreach ($resultadoQuery as $fila) {
                $modulo = $fila[$columna];
                $accion = $fila['accion_permiso'];
                $estado = $fila['estado'];

                if (!isset($permisos[$modulo])) {
                    $permisos[$modulo] = [];
                }
                $permisos[$modulo][$accion] = [
                    'estado' => $estado,
                    'id' => $fila['id_permiso']
                ];
            }
            $dato['resultado'] = "traer_permiso";
            $dato['permiso'] = $permisos;
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }
    private function CargarPermiso($datos)
    {
        $dato = [];
        $permisos = $this->objetoToArray($datos);
        $this->Instancia_ModuloSistema();
        $boolPermiso = $this->modulo_sistema->Transaccion(['peticion' => 'comprobar']);

        $query = "INSERT INTO permiso (id_permiso, id_rol, id_modulo, accion_permiso, estado)
                VALUES (:id_permiso, :rol, :modulo, :accion, :estado)
                ON DUPLICATE KEY UPDATE estado = VALUES(estado)";

        $this->conex->beginTransaction();
        if ($boolPermiso['bool'] == true) {
            try {
                $stm = $this->conex->prepare($query);
                foreach ($permisos as $key) {
                    foreach ($key['permisos'] as $accion) {
                        $params[] = [
                            'id_permiso' => $accion['id'],
                            'rol' => $this->id_rol,
                            'modulo' => $key['modulo'],
                            'accion' => $accion['accion'],
                            'estado' => $accion['estado']
                        ];
                    }
                }

                foreach ($params as $param) {
                    $stm->bindParam(":id_permiso", $param['id_permiso']);
                    $stm->bindParam(":rol", $param['rol']);
                    $stm->bindParam(":modulo", $param['modulo']);
                    $stm->bindParam(":accion", $param['accion']);
                    $stm->bindParam(":estado", $param['estado']);
                    $stm->execute();
                }
                $this->conex->commit();
                $dato['mensaje'] = 'Se cargaron los permisos';
                $dato['resultado'] = 'permiso';
                $dato['estado'] = 1;
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['resultado'] = 'error_permiso';
                $dato['mensaje'] = $e->getMessage();
                $dato['estado'] = -1;
            }
        } else {
            $this->conex->rollBack();
            $dato['resultado'] = 'error_modulo';
            $dato['mensaje'] = $boolPermiso['mensaje'];
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }
    private function Validar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT * FROM permiso WHERE id = :id";

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
                $query = "INSERT INTO permiso (id, modulo) VALUES 
            (NULL, :modulo)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":modulo", $this->modulo);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la permisos exitosamente";
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

        try {
            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "UPDATE permiso SET modulo = :modulo WHERE id = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":modulo", $this->modulo);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los permisos con éxito";
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
                $query = "UPDATE permiso SET estatus = 0 WHERE id = :id";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el permisos exitosamente";
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
            $dato['mensaje'] = "Error al eliminar el permiso";
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
            $query = "SELECT * FROM permiso WHERE estatus = 1";

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

            case 'cargar_permiso':
                return $this->CargarPermiso($peticion["permisos"]);

            case 'filtrar_permiso':
                return $this->FiltrarPermiso($peticion["parametro"]);

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }
}