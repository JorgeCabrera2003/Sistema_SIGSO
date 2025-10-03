<?php
require_once "model/conexion.php";
require_once "model/componente.php";
class ComponenteAtendido extends Conexion
{
    private $id;
    private $id_hoja_servicio;
    private $id_componente;
    private $estado;
    private $observacion;
    private $componente;
    private $conexion;


    public function __construct()
    {
        $this->id = 0;
        $this->id_hoja_servicio = 0;
        $this->observacion = "";
        $this->estado = 0;
        $this->id_componente = 0;
        $this->tipo_servicio = NULL;
        $this->conexion = NULL;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function set_id_hoja_servicio($id_hoja_servicio)
    {
        $this->id_hoja_servicio = $id_hoja_servicio;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    public function set_observacion($observacion)
    {
        $this->observacion = $observacion;
    }
    public function set_id_componente($id_componente)
    {
        $this->id_componente = $id_componente;
    }

    public function get_id()
    {
        return $this->id;
    }
    public function get_id_hoja_servicio()
    {
        return $this->id_hoja_servicio;
    }
    public function get_observacion()
    {
        return $this->observacion;
    }

    public function get_id_componente()
    {
        return $this->id_componente;
    }

    public function get_estado()
    {
        return $this->estado;
    }
    private function LlamarComponente()
    {
        if ($this->componente == NULL) {
            $this->componente = new Componente();
        }
        return $this->componente;
    }

    private function Validar()
    {
        $dato = [];
        $transaccion = false;
        try {
            if ($this->conexion === NULL) {
                $this->conexion = new Conexion("sistema");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $transaccion = true;
            }
            $query = "SELECT * FROM componente_atendido WHERE id = :id";

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
            $dato['error'] = $e->getMessage();
            $dato['bool'] = -1;
        }
        if ($transaccion) {
            $this->Cerrar_Conexion($none, $stm);
        }
        return $dato;
    }

    private function CargarComponentes($arrayComponente = NULL)
    {
        $dato = [];
        $dato['total_errores'] = 0;

        if ($arrayComponente == NULL || $arrayComponente == []) {
            $dato['resultado'] = "error";
            $dato['estado'] = "vacio";
            $dato['total_errores'] = 0;
            $dato['mensaje'] = "Conjunto de componentes vacíos";
        } else {
            if (is_array($arrayComponente)) {
                try {
                    $this->conexion = new Conexion("sistema");
                    $this->conexion = $this->conexion->Conex();
                    $this->conexion->beginTransaction();
                    foreach ($arrayComponente as $key) {
                        if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $key['id_atendido']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $key['id_check']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else if ($key['observacion'] != NULL && preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,30}$/", $key['observacion']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else if (preg_match("/^[0-9]{1}$/", $key['estado']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else {
                            $this->set_id($key['id_atendido']);
                            $this->set_id_componente($key['id_check']);
                            $this->set_estado($key['estado']);
                            $this->set_observacion($key['observacion']);

                            $bool = $this->Validar();
                            if ($bool['bool'] == 0) {
                                $dato['msg_registrar'] = $this->Registrar();
                            } else if ($bool['bool'] == 1) {
                                $this->Actualizar();
                            } else {
                                $dato['total_errores'] = $dato['total_errores'] + 1;
                            }
                        }
                    }
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
                $dato['mensaje'] = "Registro duplicado";
            }
        }
        return $dato;
    }
    private function Registrar()
    {
        $dato = [];
        $boolServicio = [];
        $bool = $this->Validar();
        $transaccion = false;
        $this->LlamarComponente()->set_id($this->get_id_componente());
        $boolServicio = $this->LlamarComponente()->Transaccion(['peticion' => 'validar']);
        if ($bool['bool'] == 0) {
            if ($boolServicio['bool'] == 1) {
                try {
                    if ($this->conexion === NULL) {
                        $this->conexion = new Conexion("sistema");
                        $this->conexion = $this->conexion->Conex();
                        $this->conexion->beginTransaction();
                        $transaccion = true;
                    }
                    $query = "INSERT INTO componente_atendido(id, id_componente, id_hoja_servicio, estado, observacion)
                    VALUES (:id, :id_componente, :id_hoja_servicio, :estado, :observacion)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id", $this->id);
                    $stm->bindParam(":id_componente", $this->id_componente);
                    $stm->bindParam(":id_hoja_servicio", $this->id_hoja_servicio);
                    $stm->bindParam(":estado", $this->estado);
                    $stm->bindParam(":observacion", $this->observacion);
                    $stm->execute();
                    if ($transaccion) {
                        $this->conexion->commit();
                    }
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró el componente atendido exitosamente";
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
            } else {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No existe el Componente";
            }

        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Registro duplicado";
        }

        return $dato;
    }

    private function Actualizar()
    {
        $dato = [];
        $transaccion = false;
        $boolServicio = [];
        $this->LlamarComponente()->set_id($this->get_id_componente());
        $boolServicio = $this->LlamarComponente()->Transaccion(['peticion' => 'validar']);
        if ($boolServicio['bool'] == 1) {

            try {
                if ($this->conexion === NULL) {
                    $this->conexion = new Conexion("sistema");
                    $this->conexion = $this->conexion->Conex();
                    $this->conexion->beginTransaction();
                    $transaccion = true;
                }
                $query = "UPDATE componente_atendido SET observacion = :observacion, estado = :estado WHERE id = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->bindParam(":estado", $this->estado);
                $stm->bindParam(":observacion", $this->observacion);

                $stm->execute();
                if ($transaccion) {
                    $this->conexion->commit();
                }
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos del componente atendido exitosamente";
            } catch (PDOException $e) {
                if ($transaccion) {
                    $this->conexion->rollBack();
                }
                $dato['estado'] = -1;
                $dato['resultado'] = "error";
                $dato['mensaje'] = $e->getMessage();
            }
            if ($transaccion) {
                $this->Cerrar_Conexion($this->conexion, $stm);
            }
        } else {

        }

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
                $query = "UPDATE componente SET estatus = 0 WHERE id = :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el compenente atendido exitosamente";
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
            $query = "SELECT sp.id, sp.id_tipo_servicio, tp.nombre_tipo_servicio, sp.nombre, sp.bool_texto FROM componente AS sp 
            INNER JOIN tipo_servicio as tp ON tp.id_tipo_servicio = sp.id_tipo_servicio
            WHERE tp.id_tipo_servicio = :servicio";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':servicio', $this->id_componente);
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

    private function FiltrarComponenteAtendido()
    {
        $dato = [];

        try {
            $this->conexion = new Conexion("sistema");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT id AS id_atendido, id_componente AS clave, id_hoja_servicio, estado, observacion FROM componente_atendido
            WHERE id_hoja_servicio = :id_hoja_servicio";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':id_hoja_servicio', $this->id_hoja_servicio);
            $stm->execute();
            if($stm->rowCount() > 0){
                $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $dato['datos'] = NULL;
            }
            $this->conexion->commit();

            $dato['resultado'] = "consultar";
            
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

            case 'cargar':
                return $this->CargarComponentes($peticion['componentes']);

            case 'consultar':
                return $this->Consultar();

            case 'validar':
                $datos = $this->Validar();
                $this->conexion = NULL;
                return $datos;

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();
            
            case 'filtrar':
                return $this->FiltrarComponenteAtendido();
            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}