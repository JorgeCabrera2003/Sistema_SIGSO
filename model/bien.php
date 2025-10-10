<?php
require_once "model/conexion.php";
require_once "model/empleado.php";
require_once "model/oficina.php";
require_once "model/marca.php";
require_once "model/categoria.php";
class Bien extends Conexion
{
    private $id_equipo;
    private $codigo_bien;
    private $id_tipo_equipo;
    private $id_tipo_servicio; // Nuevo campo para el tipo de servicio
    private $serial;
    private $modelo;
    private $estado;
    private $estatus;
    private $fecha_instalacion;
    private $fecha_compra;
    private $garantia;
    private $observaciones;
    private $empleado;
    private $oficina;
    private $marca;
    private $tipo_equipo;
    private $id_categoria;
    private $categoria;

    public function __construct()
    {
        $this->id_equipo = 0;
        $this->codigo_bien = NULL;
        $this->id_tipo_equipo = NULL;
        $this->id_tipo_servicio = NULL;
        $this->serial = "";
        $this->modelo = "";
        $this->estado = "";
        $this->estatus = 0;
        $this->fecha_instalacion = NULL;
        $this->fecha_compra = NULL;
        $this->garantia = NULL;
        $this->observaciones = "";
        $this->id_tipo_bien = 0;
    }

    public function set_codigo_bien($codigo_bien)
    {
        $this->codigo_bien = $codigo_bien;
    }

    public function set_id_categoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function set_id_marca($id_marca)
    {
        $this->id_marca = $id_marca;
    }

    public function set_descripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    public function set_cedula_empleado($cedula_empleado)
    {
        $this->cedula_empleado = $cedula_empleado;
    }

    public function set_id_oficina($id_oficina)
    {
        $this->id_oficina = $id_oficina;
    }

    public function set_estatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function get_codigo_bien()
    {
        return $this->codigo_bien;
    }

    public function get_id_categoria()
    {
        return $this->id_categoria;
    }

    public function get_id_marca()
    {
        return $this->id_marca;
    }

    public function get_descripcion()
    {
        return $this->descripcion;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function get_cedula_empleado()
    {
        return $this->cedula_empleado;
    }

    public function get_id_oficina()
    {
        return $this->id_oficina;
    }

    public function get_estatus()
    {
        return $this->estatus;
    }

    private function LlamarEmpleado()
    {
        if ($this->empleado == NULL) {

            $this->empleado = new Empleado();

        }

        return $this->empleado;
    }

    private function DestruirEmpleado()
    {
        $this->empleado = NULL;
    }

    private function LlamarMarca()
    {
        if ($this->marca == NULL) {

            $this->marca = new Marca();

        }

        return $this->marca;
    }

    private function DestruirMarca()
    {
        $this->marca = NULL;
    }

    private function LlamarOficina()
    {
        if ($this->oficina == NULL) {

            $this->oficina = new Oficina();

        }

        return $this->oficina;
    }

    private function DestruirOficina()
    {
        $this->oficina = NULL;
    }

    private function LlamarTipoBien()
    {
        if ($this->categoria == NULL) {

            $this->categoria = new Categoria();

        }

        return $this->categoria;
    }

    private function DestruirTipoBien()
    {
        $this->categoria = NULL;
    }

    private function Validar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();

            $this->conex->beginTransaction();
            $query = "SELECT * FROM bien WHERE codigo_bien = :codigo";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $this->codigo_bien);
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

        if ($bool['bool'] == 0) {
            try {
                $this->conex = new Conexion("sistema");
                $this->conex = $this->conex->Conex();
                $this->conex->beginTransaction();

                $query = "INSERT INTO bien(codigo_bien, id_categoria, id_marca, descripcion, estado, cedula_empleado, id_oficina, estatus) VALUES 
                (:codigo, :categoria, :marca, :descripcion, :estado, :empleado, :oficina, 1)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo", $this->codigo_bien);
                $stm->bindParam(":categoria", $this->id_categoria);
                $stm->bindParam(":marca", $this->id_marca);
                $stm->bindParam(":descripcion", $this->descripcion);
                $stm->bindParam(":estado", $this->estado);
                $stm->bindParam(":empleado", $this->cedula_empleado);
                $stm->bindParam(":oficina", $this->id_oficina);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró el bien exitosamente";
            } catch (PDOException $e) {
                if ($this->conex->beginTransaction()) {
                    $this->conex->rollBack();
                }
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            // No hay transacción activa aquí, solo retorna error
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

            $query = "UPDATE bien SET id_categoria=:categoria, id_marca=:marca, descripcion=:descripcion, 
                     estado=:estado, cedula_empleado=:empleado, id_oficina=:oficina 
                     WHERE codigo_bien = :codigo";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $this->codigo_bien);
            $stm->bindParam(":categoria", $this->id_categoria);
            $stm->bindParam(":marca", $this->id_marca);
            $stm->bindParam(":descripcion", $this->descripcion);
            $stm->bindParam(":estado", $this->estado);
            $stm->bindParam(":empleado", $this->cedula_empleado);
            $stm->bindParam(":oficina", $this->id_oficina);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se modificaron los datos del bien con éxito";
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

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
        $this->conex->beginTransaction();

        if ($bool['bool'] != 0) {
            try {
                $query = "UPDATE bien SET estatus = 0 WHERE codigo_bien = :codigo";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo", $this->codigo_bien);
                $stm->execute();
                $this->conex->commit();
                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó el bien exitosamente";
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

    private function FiltrarBienAsignado()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT b.codigo_bien, CONCAT(tb.nombre_categoria, ' ', m.nombre_marca) AS nombre_bien
            FROM bien b
            LEFT JOIN categoria tb ON b.id_categoria = tb.id_categoria
            LEFT JOIN marca m ON b.id_marca = m.id_marca
            WHERE b.estatus = 1
            AND b.codigo_bien NOT IN (
            SELECT e.codigo_bien FROM equipo e 
            WHERE e.codigo_bien IS NOT NULL
            AND e.estatus = 1)";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $this->conex->commit();
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $dato['resultado'] = "filtrar_bien";
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['datos'] = [];
        }
        return $dato;
    }

    private function Consultar()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT b.*, tb.nombre_categoria, m.nombre_marca, o.nombre_oficina, 
                     CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS empleado
                     FROM bien b 
                     LEFT JOIN categoria tb ON b.id_categoria = tb.id_categoria
                     LEFT JOIN marca m ON b.id_marca = m.id_marca
                     LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                     LEFT JOIN empleado e ON b.cedula_empleado = e.cedula_empleado
                     WHERE b.estatus = 1";

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

    private function ConsultarTiposBien()
    {
        return $this->LlamarTipoBien()->Transaccion(['peticion' => 'consultar']);
    }

    private function ConsultarMarcas()
    {
        return $this->LlamarMarca()->Transaccion(['peticion' => 'consultar']);
    }

    private function ConsultarOficinas()
    {
        return $this->LlamarOficina()->Transaccion(['peticion' => 'consultar']);
    }

    private function ConsultarEmpleados()
    {
        return $this->LlamarEmpleado()->Transaccion(['peticion' => 'consultar']);
    }

    private function ConsultarEliminadas()
    {
        $dato = [];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT b.*, tb.nombre_categoria, m.nombre_marca
                     FROM bien b 
                     LEFT JOIN categoria tb ON b.id_categoria = tb.id_categoria
                     LEFT JOIN marca m ON b.id_marca = m.id_marca
                     WHERE b.estatus = 0";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "consultar_eliminadas";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Reactivar()
    {
        $dato = [];
        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "UPDATE bien SET estatus = 1 WHERE codigo_bien = :codigo";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $this->codigo_bien);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "reactivar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Bien restaurado exitosamente";

            $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró el bien Código: " . $this->codigo_bien;
            Bitacora($msg, "Bien");
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }
    private function ConsultarPorEmpleado($cedula_empleado)
    {
        $dato = [];
        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            $query = "SELECT b.codigo_bien, b.descripcion, tb.nombre_categoria, m.nombre_marca
                      FROM bien b
                      LEFT JOIN categoria tb ON b.id_categoria = tb.id_categoria
                      LEFT JOIN marca m ON b.id_marca = m.id_marca
                      WHERE b.estatus = 1 AND b.cedula_empleado = :cedula_empleado";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":cedula_empleado", $cedula_empleado);
            $stm->execute();
            $this->conex->commit();
            $dato['resultado'] = "consultar_bienes_empleado";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (!isset($dato['datos']) || !is_array($dato['datos'])) {
                $dato['datos'] = [];
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['datos'] = [];
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    public function obtenerTipoServicioPorEquipo($idEquipo)
    {
        $dato = [];
        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT id_tipo_servicio FROM equipo WHERE id_equipo = :id_equipo AND estatus = 1";
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_equipo", $idEquipo);
            $stm->execute();
            $this->conex->commit();
            
            $resultado = $stm->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                $dato['id_tipo_servicio'] = $resultado['id_tipo_servicio'] ?? 1; // Default a Soporte Técnico
                $dato['resultado'] = 'success';
            } else {
                $dato['id_tipo_servicio'] = 1; // Default si no encuentra
                $dato['resultado'] = 'warning';
                $dato['mensaje'] = 'Equipo no encontrado, usando valor por defecto';
            }
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['id_tipo_servicio'] = 1; // Default en caso de error
            $dato['resultado'] = 'error';
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

            case 'filtrar':
                return $this->FiltrarBienAsignado();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();

            case 'consultar_tipos_bien':
                return $this->ConsultarTiposBien();

            case 'consultar_marcas':
                return $this->ConsultarMarcas();

            case 'consultar_oficinas':
                return $this->ConsultarOficinas();

            case 'consultar_empleados':
                return $this->ConsultarEmpleados();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'reactivar':
                return $this->Reactivar();

            case 'consultar_bienes_empleado':
                return $this->ConsultarPorEmpleado($peticion['cedula_empleado']);

            case 'obtener_tipo_servicio':
                return $this->obtenerTipoServicioPorEquipo($peticion['id_equipo']);
            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";
        }
    }

}
?>