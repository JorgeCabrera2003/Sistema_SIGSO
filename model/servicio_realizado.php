<?php
require_once "model/conexion.php";
require_once "model/servicio_prestado.php";
class ServicioRealizado extends Conexion
{

    private $id;
    private $id_hoja_servicio;
    private $id_servicio_prestado;
    private $observacion;
    private $servicio_prestado;
    private $conexion;


    public function __construct()
    {
        $this->id = 0;
        $this->id_hoja_servicio = 0;
        $this->observacion = "";
        $this->id_servicio_prestado = 0;
        $this->servicio_prestado = NULL;
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

    public function set_observacion($observacion)
    {
        $this->observacion = $observacion;
    }
    public function set_id_servicio_prestado($id_servicio_prestado)
    {
        $this->id_servicio_prestado = $id_servicio_prestado;
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

    public function get_id_servicio_prestado()
    {
        return $this->id_servicio_prestado;
    }

    private function LlamarServicioPrestado()
    {
        if ($this->servicio_prestado == NULL) {
            $this->servicio_prestado = new ServicioPrestado();
        }
        return $this->servicio_prestado;
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
            $query = "SELECT * FROM servicio_prestado WHERE id = :id";

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

    private function CargarServicios($servicio = NULL)
    {
        $dato = [];
        $dato['total_errores'] = 0;

        if ($servicio == NULL || $servicio == []) {
            $dato['resultado'] = "error";
            $dato['estado'] = "vacio";
            $dato['total_errores'] = 0;
            $dato['mensaje'] = "Conjunto de servicios vacíos";
        } else {
            if (is_array($servicio)) {
                try {
                    $this->conexion = new Conexion("sistema");
                    $this->conexion = $this->conexion->Conex();
                    $this->conexion->beginTransaction();
                    foreach ($servicio as $key) {
                        if (preg_match("/^[A-Z0-9]{1,2}[A-Z0-9]{1,2}[0-9]{4}[0-9]{8}$/", $key['id']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else if (preg_match("/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,30}$/", $key['nombre']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else if (preg_match("/^[0-9]{1}$/", $key['estado']) == 0) {
                            $dato['total_errores'] = $dato['total_errores'] + 1;

                        } else {
                            $this->set_id($key['id']);
                            $this->set_id_hoja_servicio($key['hoja_servicio']);
                            $this->set_id_servicio_prestado($key['servicio_prestado']);
                            $this->set_observacion($key['observacion']);

                            $bool = $this->Validar();
                            if ($bool['bool'] == 0) {
                                $this->Registrar();
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
        $this->LlamarServicioPrestado()->set_id($this->get_id_servicio_prestado());
        $boolServicio = $this->LlamarServicioPrestado()->Transaccion(['peticion' => 'validar']);
        if ($bool['bool'] == 0) {
            if ($boolServicio['bool'] == 1) {
                try {
                    if ($this->conexion === NULL) {
                        $this->conexion = new Conexion("sistema");
                        $this->conexion = $this->conexion->Conex();
                        $this->conexion->beginTransaction();
                        $transaccion = true;
                    }
                    $query = "INSERT INTO servicio_realizado(id_servicio_realizado, id_servicio_prestado, id_hoja_servicio, observacion)
                    VALUES (:id_servicio_realizado, :id_servicio_prestado, :id_hoja_servicio, :observacion)";

                    $stm = $this->conexion->prepare($query);
                    $stm->bindParam(":id_servicio_realizado", $this->id);
                    $stm->bindParam(":id_servicio_prestado", $this->id_servicio_prestado);
                    $stm->bindParam(":id_hoja_servicio", $this->id_hoja_servicio);
                    $stm->bindParam(":observacion", $this->observacion);
                    $stm->execute();
                    if ($transaccion) {
                        $this->conexion->commit();
                    }
                    $dato['resultado'] = "registrar";
                    $dato['estado'] = 1;
                    $dato['mensaje'] = "Se registró el servicio prestado exitosamente";
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
                $dato['mensaje'] = "No existe el Tipo de Servicio";
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
        $this->LlamarServicioPrestado()->set_id($this->get_id_servicio_prestado());
        $boolServicio = $this->LlamarServicioPrestado()->Transaccion(['peticion' => 'validar']);
        if ($boolServicio['bool'] == 1) {

            try {
                if ($this->conexion === NULL) {
                    $this->conexion = new Conexion("sistema");
                    $this->conexion = $this->conexion->Conex();
                    $this->conexion->beginTransaction();
                    $transaccion = true;
                }
                $query = "UPDATE servicio_realizado SET observacion = :observacion
                WHERE id_servicio_realizado = :id_servicio_realizado";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id_servicio_realizado", $this->id);
                $stm->bindParam(":observacion", $this->id_servicio_prestado);
                $stm->execute();
                if ($transaccion) {
                    $this->conexion->commit();
                }
                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificaron los datos del servicio prestado con éxito";
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
                $query = "DELETE FROM servicio_realizado WHERE id_servicio_realizado= :id";

                $stm = $this->conexion->prepare($query);
                $stm->bindParam(":id", $this->id);
                $stm->execute();
                $this->conexion->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el servicio prestado exitosamente";
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
            $query = "SELECT sp.id, sp.id_tipo_servicio, tp.nombre_tipo_servicio, sp.nombre, sp.bool_texto FROM servicio_prestado AS sp 
            INNER JOIN tipo_servicio as tp ON tp.id_tipo_servicio = sp.id_tipo_servicio
            WHERE tp.id_tipo_servicio = :servicio";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':servicio', $this->id_hoja_servicio);
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

            case 'cargar':
                return $this->CargarServicios($peticion['servicios']);

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

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }
}