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

    // CONSTANTES DE VALIDACIÓN UNIFICADAS
    const REGEX_ID_MATERIAL = '/^[A-Z0-9\-_]{1,50}$/';
    const REGEX_NOMBRE_MATERIAL = '/^[0-9a-zA-ZáéíóúüñÑçÇ\s\-.,()]{1,100}$/';
    const REGEX_ID_OFICINA = '/^[A-Z0-9]{1,30}$/';
    const MIN_STOCK = 0;
    const MAX_STOCK = 999999;

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

    // SETTERS VALIDADOS (MANTENIDOS IGUAL)
    public function set_id($id)
    {
        if ($id === null) {
            $this->id = null;
            return;
        }
        
        $id = trim((string)$id);
        if (empty($id)) {
            throw new ValueError("ID de Material no puede estar vacío");
        }
        
        if (!preg_match(self::REGEX_ID_MATERIAL, $id)) {
            throw new ValueError("ID de Material no válido. Debe ser alfanumérico de 1-50 caracteres. Recibido: " . $id);
        }
        $this->id = $id;
    }

    public function set_nombre($nombre)
    {
        if ($nombre === null) {
            $this->nombre = null;
            return;
        }
        
        $nombre = trim((string)$nombre);
        if (empty($nombre)) {
            throw new ValueError("Nombre de Material no puede estar vacío");
        }
        
        if (!preg_match(self::REGEX_NOMBRE_MATERIAL, $nombre)) {
            throw new ValueError("Nombre de Material no válido. Debe tener 1-100 caracteres alfanuméricos. Recibido: " . $nombre);
        }
        $this->nombre = $nombre;
    }

    public function set_ubicacion($ubicacion)
    {
        // OBLIGATORIO - No puede ser null o vacío
        if ($ubicacion === null || $ubicacion === "") {
            throw new ValueError("La ubicación es obligatoria para el material");
        }
        
        $ubicacion = trim((string)$ubicacion);
        if (empty($ubicacion)) {
            throw new ValueError("La ubicación no puede estar vacía");
        }
        
        if (!preg_match(self::REGEX_ID_OFICINA, $ubicacion)) {
            throw new ValueError("ID de Oficina no válido. Debe ser alfanumérico de 1-30 caracteres. Recibido: " . $ubicacion);
        }
        $this->ubicacion = $ubicacion;
    }

    public function set_stock($stock)
    {
        if ($stock === null) {
            $this->stock = null;
            return;
        }
        
        if (!is_numeric($stock)) {
            throw new ValueError("El stock debe ser un valor numérico. Recibido: " . gettype($stock));
        }
        
        $stock = (int)$stock;
        if ($stock < self::MIN_STOCK || $stock > self::MAX_STOCK) {
            throw new ValueError("Stock no válido. Debe estar entre " . self::MIN_STOCK . " y " . self::MAX_STOCK . ". Recibido: " . $stock);
        }
        $this->stock = $stock;
    }

    public function set_estatus($estatus)
    {
        if ($estatus === null) {
            $this->estatus = null;
            return;
        }
        
        if (!in_array($estatus, [0, 1])) {
            throw new ValueError("Estatus no válido. Debe ser 0 (inactivo) o 1 (activo). Recibido: " . $estatus);
        }
        $this->estatus = $estatus;
    }

    // GETTERS (MANTENIDOS IGUAL)
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
            $this->conex->beginTransaction();

            $query = "SELECT id_material, nombre_material, stock 
                 FROM material 
                 WHERE estatus = 1 AND stock > 0
                 ORDER BY nombre_material";

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
        
        $this->cerrarConexion();
        return $dato;
    }

    // VALIDACIÓN COMPLETA ANTES DE OPERACIONES
    private function Validar($esActualizacion = false)
    {
        $dato = ['bool' => 0, 'mensaje' => ''];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            // Validar que no exista otro material con el mismo nombre activo
            $query_nombre = "SELECT id_material FROM material WHERE nombre_material = :nombre AND estatus = 1";
            $params_nombre = [":nombre" => $this->nombre];

            if ($esActualizacion) {
                $query_nombre .= " AND id_material != :id_material";
                $params_nombre[":id_material"] = $this->id;
            }

            $stm_nombre = $this->conex->prepare($query_nombre);
            foreach ($params_nombre as $key => $value) {
                $stm_nombre->bindValue($key, $value);
            }
            $stm_nombre->execute();

            if ($stm_nombre->rowCount() > 0) {
                $this->conex->rollBack();
                $dato['bool'] = 1;
                $dato['mensaje'] = "Ya existe un material activo con el mismo nombre";
                return $dato;
            }

            // Validar que la oficina exista y esté activa
            $query_oficina = "SELECT id_oficina FROM oficina WHERE id_oficina = :id_oficina AND estatus = 1";
            $stm_oficina = $this->conex->prepare($query_oficina);
            $stm_oficina->bindParam(":id_oficina", $this->ubicacion);
            $stm_oficina->execute();

            if ($stm_oficina->rowCount() == 0) {
                $this->conex->rollBack();
                $dato['bool'] = 1;
                $dato['mensaje'] = "La oficina seleccionada no existe o está inactiva";
                return $dato;
            }

            $this->conex->commit();
            $dato['bool'] = 0;
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['bool'] = -1;
            $dato['mensaje'] = "Error en validación: " . $e->getMessage();
        } finally {
            $this->cerrarConexion();
        }

        return $dato;
    }

    private function Registrar()
    {
        // Validar campos obligatorios antes de la validación completa
        if (empty($this->id)) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => "ID de material es obligatorio"
            ];
        }

        if (empty($this->nombre)) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => "Nombre de material es obligatorio"
            ];
        }

        if (empty($this->ubicacion)) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => "Ubicación es obligatoria"
            ];
        }

        $dato = $this->Validar(false);
        if ($dato['bool'] != 0) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => $dato['mensaje']
            ];
        }

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

            $dato = [
                'resultado' => "registrar",
                'estado' => 1,
                'mensaje' => "Se registró el material exitosamente"
            ];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato = [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => $e->getMessage()
            ];
        }
        $this->cerrarConexion();
        return $dato;
    }

    private function Actualizar()
    {
        if (empty($this->id)) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => "ID de material es obligatorio para actualizar"
            ];
        }

        $dato = $this->Validar(true);
        if ($dato['bool'] != 0) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => $dato['mensaje']
            ];
        }

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

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se encontró el material o ya fue eliminado");
            }

            $this->conex->commit();
            $dato = [
                'resultado' => "modificar",
                'estado' => 1,
                'mensaje' => "Se modificaron los datos del material con éxito"
            ];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato = [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => $e->getMessage()
            ];
        }
        $this->cerrarConexion();
        return $dato;
    }

    private function Eliminar()
    {
        if (empty($this->id)) {
            return [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => "ID de material es obligatorio para eliminar"
            ];
        }

        // Validar que el material existe
        $validacion = $this->ValidarExistencia();
        if ($validacion['bool'] != 1) {
            return [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => "El material no existe o ya fue eliminado"
            ];
        }

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "UPDATE material SET estatus = 0 WHERE id_material = :id";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se pudo eliminar el material");
            }

            $this->conex->commit();
            $dato = [
                'resultado' => "eliminar",
                'estado' => 1,
                'mensaje' => "Se eliminó el material exitosamente"
            ];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato = [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => $e->getMessage()
            ];
        }
        $this->cerrarConexion();
        return $dato;
    }

    private function ValidarExistencia()
    {
        $dato = ['bool' => 0, 'mensaje' => ''];

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT id_material FROM material WHERE id_material = :id AND estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->execute();
            $this->conex->commit();

            if ($stm->rowCount() > 0) {
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = 0;
                $dato['mensaje'] = "Material no encontrado";
            }
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato['bool'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }
        $this->cerrarConexion();
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
        $this->cerrarConexion();
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
        $this->cerrarConexion();
        return $dato;
    }

    private function reactivar()
    {
        if (empty($this->id)) {
            return [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => "ID de material es obligatorio para reactivar"
            ];
        }

        try {
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            // Verificar que el material existe y está eliminado
            $query_check = "SELECT id_material FROM material WHERE id_material = :id_material AND estatus = 0";
            $stm_check = $this->conex->prepare($query_check);
            $stm_check->bindParam(':id_material', $this->id);
            $stm_check->execute();

            if ($stm_check->rowCount() == 0) {
                // Si no está eliminado, verificar si existe activo
                $query_check_active = "SELECT id_material FROM material WHERE id_material = :id_material AND estatus = 1";
                $stm_check_active = $this->conex->prepare($query_check_active);
                $stm_check_active->bindParam(':id_material', $this->id);
                $stm_check_active->execute();
                
                if ($stm_check_active->rowCount() > 0) {
                    return [
                        'resultado' => "warning",
                        'estado' => 2,
                        'mensaje' => "El material ya está activo"
                    ];
                } else {
                    throw new PDOException("No se encontró el material");
                }
            }

            $query = "UPDATE material SET estatus = 1 WHERE id_material = :id_material";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_material", $this->id);
            $stm->execute();

            if ($stm->rowCount() == 0) {
                throw new PDOException("No se pudo reactivar el material");
            }

            $this->conex->commit();
            $dato = [
                'resultado' => "reactivar",
                'estado' => 1,
                'mensaje' => "Material restaurado exitosamente"
            ];

            // Bitacora condicional para evitar errores en testing
            if (isset($_SESSION['user']['nombre_usuario']) && function_exists('Bitacora')) {
                $msg = "(" . $_SESSION['user']['nombre_usuario'] . "), Se restauró el material ID: " . $this->id;
                Bitacora($msg, "Material");
            }
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $dato = [
                'resultado' => "error",
                'estado' => -1,
                'mensaje' => $e->getMessage()
            ];
        }
        $this->cerrarConexion();
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

        $this->cerrarConexion();
        return $dato;
    }

    private function VerDetalles()
    {
        $this->LlamarDetallesMaterial()->set_id_material($this->get_id());
        return $this->LlamarDetallesMaterial()->Transaccion(['peticion' => 'consultar']);
    }

    // MÉTODO PRIVADO PARA CERRAR CONEXIÓN SIN ERRORES DE REFERENCIA
    private function cerrarConexion()
    {
        if ($this->conex !== null) {
            $this->conex = null;
        }
    }

    public function Transaccion($peticion)
    {
        if (!is_array($peticion) || !isset($peticion["peticion"])) {
            return "Operacion: peticion no valida";
        }

        try {
            switch ($peticion['peticion']) {
                case 'registrar':
                    return $this->Registrar();

                case 'validar':
                    return $this->ValidarExistencia();

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
        } catch (ValueError $e) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'estado' => -1,
                'resultado' => "error",
                'mensaje' => "Error en transacción: " . $e->getMessage()
            ];
        }
    }
}