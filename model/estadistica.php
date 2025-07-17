<?php
require_once "model/conexion.php";

class reporte extends Conexion
{
    private $conex;

    public function __construct()
    {
        parent::__construct("sistema"); // Llama al constructor de la clase padre Conexion
    }

    // Método para contar empleados
    public function contarEmpleados()
    {
        $stm = null;
        try {
            $this->conex = $this->Conex();
            $query = "SELECT * FROM filtrado_empleado";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $datos = $stm->fetch(PDO::FETCH_ASSOC);
            
            if ($datos) {
                return ['resultado' => 'success', 'datos' => $datos];
            } else {
                return ['resultado' => 'error', 'mensaje' => 'No se encontraron datos de empleados'];
            }
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }
    }

    // Método para contar hojas de servicio
    public function contarNumeroHoja()
    {
        $stm = null;
        try {
            $this->conex = $this->Conex();
            $query = "SELECT * FROM filtrado_hoja";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $datos = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            return ['resultado' => 'success', 'datos' => $datos];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }
    }

    // Método para contar técnicos
    public function contarTecnicos()
    {
        $stm = null;
        try {
            $this->conex = $this->Conex();
            $query = "SELECT * FROM filtrado_tecnico";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $datos = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            return ['resultado' => 'success', 'datos' => $datos];
        } catch (PDOException $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }
    }

    // Método para reporte de patch panel por número de piso
    public function reportePatchPanelPorNro($id_piso)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "CALL sp_reporte_patch_panel_por_nro(:id_piso)";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $id_piso, PDO::PARAM_INT);
            $stm->execute();
            
            $dato['resultado'] = "reporte_patch_panel";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para reporte de switches
    public function reporteSwitchPanel($id_piso)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "CALL sp_reporte_switches(:id_piso)";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $id_piso, PDO::PARAM_INT);
            $stm->execute();
            
            $dato['resultado'] = "reporte_switch_panel";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para consultar pisos
    public function consultarPisos()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT * FROM piso WHERE estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para listar tipos de bien
    public function listarTiposBien()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT * FROM categoria WHERE estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para listar oficinas
    public function listarOficinas()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT * FROM oficina WHERE estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para listar técnicos
    public function listarTecnicos()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT e.cedula_empleado, CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre 
                      FROM empleado e 
                      WHERE e.id_cargo = 1 AND e.estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para listar tipos de servicio
    public function listarTiposServicio()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT * FROM tipo_servicio WHERE estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para listar materiales
    public function listarMateriales()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "SELECT * FROM material WHERE estatus = 1";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            
            $dato['resultado'] = "success";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para reporte de bienes
    public function reporteBienes($filtros)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "CALL sp_reporte_bienes(:id_tipo_bien, :estado, :id_oficina, :fecha_inicio, :fecha_fin)";
            $stm = $this->conex->prepare($query);
            
            $stm->bindParam(":id_tipo_bien", $filtros['id_tipo_bien'], PDO::PARAM_INT);
            $stm->bindParam(":estado", $filtros['estado'], PDO::PARAM_STR);
            $stm->bindParam(":id_oficina", $filtros['id_oficina'], PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $filtros['fecha_inicio'], PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $filtros['fecha_fin'], PDO::PARAM_STR);
            
            $stm->execute();
            
            $dato['resultado'] = "reporte_bienes";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para reporte de solicitudes
    public function reporteSolicitudes($filtros)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "CALL sp_reporte_solicitudes_atendidas(:cedula_tecnico, :id_tipo_servicio, :fecha_inicio, :fecha_fin, :estatus)";
            $stm = $this->conex->prepare($query);
            
            $stm->bindParam(":cedula_tecnico", $filtros['cedula_tecnico'], PDO::PARAM_STR);
            $stm->bindParam(":id_tipo_servicio", $filtros['id_tipo_servicio'], PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $filtros['fecha_inicio'], PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $filtros['fecha_fin'], PDO::PARAM_STR);
            $stm->bindParam(":estatus", $filtros['estatus'], PDO::PARAM_STR);
            
            $stm->execute();
            
            $dato['resultado'] = "reporte_solicitudes";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método para reporte de materiales
    public function reporteMateriales($filtros)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            $query = "CALL sp_reporte_materiales_utilizados(:id_material, :id_oficina, :fecha_inicio, :fecha_fin)";
            $stm = $this->conex->prepare($query);
            
            $stm->bindParam(":id_material", $filtros['id_material'], PDO::PARAM_INT);
            $stm->bindParam(":id_oficina", $filtros['id_oficina'], PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $filtros['fecha_inicio'], PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $filtros['fecha_fin'], PDO::PARAM_STR);
            
            $stm->execute();
            
            $dato['resultado'] = "reporte_materiales";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
            $this->conex = null;
        }

        return $dato;
    }

    // Método principal de transacción que enruta todas las peticiones
    public function Transaccion($peticion)
    {
        switch ($peticion['peticion']) {
            case 'contar_empleados':
                return $this->contarEmpleados();
                
            case 'contar':
                return $this->contarNumeroHoja();
                
            case 'contar_tecnicos':
                return $this->contarTecnicos();
                
            case 'reporte_patch_panel':
                return $this->reportePatchPanelPorNro($peticion['id_piso']);
                
            case 'reporte_switch_panel':
                return $this->reporteSwitchPanel($peticion['id_piso']);
                
            case 'consultar_pisos':
                return $this->consultarPisos();
                
            case 'listar_tipos_bien':
                return $this->listarTiposBien();
                
            case 'listar_oficinas':
                return $this->listarOficinas();
                
            case 'listar_tecnicos':
                return $this->listarTecnicos();
                
            case 'listar_tipos_servicio':
                return $this->listarTiposServicio();
                
            case 'listar_materiales':
                return $this->listarMateriales();
                
            case 'reporte_bienes':
                return $this->reporteBienes($peticion);
                
            case 'reporte_solicitudes':
                return $this->reporteSolicitudes($peticion);
                
            case 'reporte_materiales':
                return $this->reporteMateriales($peticion);
                
            default:
                return ['resultado' => 'error', 'mensaje' => 'Operación no válida: ' . $peticion['peticion']];
        }
    }
}