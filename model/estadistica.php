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

    // Método para obtener patch panels con sus puertos
    public function obtenerPatchPanels($id_piso)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            // Cambia la consulta: busca patch panels cuyo bien está en una oficina que pertenece al piso solicitado
            $query = "SELECT 
                        pp.codigo_bien,
                        b.descripcion as nombre,
                        pp.serial,
                        pp.cantidad_puertos,
                        pp.tipo_patch_panel,
                        m.nombre_marca,
                        b.descripcion,
                        o.nombre_oficina,
                        p.tipo_piso,
                        p.nro_piso,
                        (SELECT COUNT(*) FROM punto_conexion pc WHERE pc.codigo_patch_panel = pp.codigo_bien) as puertos_ocupados
                      FROM patch_panel pp
                      JOIN bien b ON pp.codigo_bien = b.codigo_bien
                      LEFT JOIN marca m ON b.id_marca = m.id_marca
                      LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                      LEFT JOIN piso p ON o.id_piso = p.id_piso
                      WHERE o.id_piso = :id_piso AND b.estatus = 1";
            // Ahora filtra por el piso de la oficina asociada al bien

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $id_piso, PDO::PARAM_INT);
            $stm->execute();

            $patchPanels = $stm->fetchAll(PDO::FETCH_ASSOC);

            // Obtener información de los puertos para cada patch panel
            foreach ($patchPanels as &$panel) {
                $panel['puertos'] = $this->obtenerPuertosPatchPanel($panel['codigo_bien']);
            }

            $dato['resultado'] = "success";
            $dato['datos'] = $patchPanels;
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

    // Método para obtener los puertos de un patch panel
    private function obtenerPuertosPatchPanel($codigo_patch_panel)
    {
        $stm = null;
        $puertos = [];
        
        try {
            $this->conex = $this->Conex();
            
            // Primero crear array con todos los puertos posibles
            $queryInfo = "SELECT cantidad_puertos FROM patch_panel WHERE codigo_bien = :codigo";
            $stm = $this->conex->prepare($queryInfo);
            $stm->bindParam(":codigo", $codigo_patch_panel, PDO::PARAM_STR);
            $stm->execute();
            $info = $stm->fetch(PDO::FETCH_ASSOC);
            
            $cantidadPuertos = $info['cantidad_puertos'] ?? 0;
            
            // Obtener puertos ocupados
            $queryOcupados = "SELECT 
                                pc.puerto_patch_panel as numero,
                                e.tipo_equipo as equipo_nombre,
                                CONCAT(emp.nombre_empleado, ' ', emp.apellido_empleado) as empleado_nombre,
                                o.nombre_oficina as oficina_nombre,
                                1 as ocupado,
                                0 as danado
                              FROM punto_conexion pc
                              LEFT JOIN equipo e ON pc.id_equipo = e.id_equipo
                              LEFT JOIN bien b ON e.codigo_bien = b.codigo_bien
                              LEFT JOIN empleado emp ON b.cedula_empleado = emp.cedula_empleado
                              LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                              WHERE pc.codigo_patch_panel = :codigo";
                              
            $stm = $this->conex->prepare($queryOcupados);
            $stm->bindParam(":codigo", $codigo_patch_panel, PDO::PARAM_STR);
            $stm->execute();
            $ocupados = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            // Crear array completo de puertos
            for ($i = 1; $i <= $cantidadPuertos; $i++) {
                $encontrado = false;
                
                foreach ($ocupados as $ocupado) {
                    if ($ocupado['numero'] == $i) {
                        $puertos[] = $ocupado;
                        $encontrado = true;
                        break;
                    }
                }
                
                if (!$encontrado) {
                    $puertos[] = [
                        'numero' => $i,
                        'ocupado' => 0,
                        'danado' => 0,
                        'equipo_nombre' => null,
                        'empleado_nombre' => null,
                        'oficina_nombre' => null
                    ];
                }
            }
            
        } catch (PDOException $e) {
            error_log("Error obteniendo puertos: " . $e->getMessage());
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
        }
        
        return $puertos;
    }

    // Método para obtener switches con sus puertos
    public function obtenerSwitches($id_piso)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            // Cambia la consulta: busca switches cuyo bien está en una oficina que pertenece al piso solicitado
            $query = "SELECT 
                        s.codigo_bien,
                        b.descripcion as nombre,
                        s.serial,
                        s.cantidad_puertos,
                        m.nombre_marca,
                        b.descripcion,
                        o.nombre_oficina,
                        p.tipo_piso,
                        p.nro_piso,
                        (SELECT COUNT(*) FROM interconexion i WHERE i.codigo_switch = s.codigo_bien) as puertos_ocupados
                      FROM switch s
                      JOIN bien b ON s.codigo_bien = b.codigo_bien
                      LEFT JOIN marca m ON b.id_marca = m.id_marca
                      LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                      LEFT JOIN piso p ON o.id_piso = p.id_piso
                      WHERE o.id_piso = :id_piso AND b.estatus = 1";
            // Ahora filtra por el piso de la oficina asociada al bien

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $id_piso, PDO::PARAM_INT);
            $stm->execute();

            $switches = $stm->fetchAll(PDO::FETCH_ASSOC);

            // Obtener información de los puertos para cada switch
            foreach ($switches as &$switch) {
                $switch['puertos'] = $this->obtenerPuertosSwitch($switch['codigo_bien']);
            }
            
            $dato['resultado'] = "success";
            $dato['datos'] = $switches;
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

    // Método para obtener los puertos de un switch
    private function obtenerPuertosSwitch($codigo_switch)
    {
        $stm = null;
        $puertos = [];
        
        try {
            $this->conex = $this->Conex();
            
            // Obtener cantidad total de puertos del switch
            $queryInfo = "SELECT cantidad_puertos FROM switch WHERE codigo_bien = :codigo";
            $stm = $this->conex->prepare($queryInfo);
            $stm->bindParam(":codigo", $codigo_switch, PDO::PARAM_STR);
            $stm->execute();
            $info = $stm->fetch(PDO::FETCH_ASSOC);
            
            $cantidadPuertos = $info['cantidad_puertos'] ?? 0;
            
            // Obtener puertos ocupados
            $queryOcupados = "SELECT 
                                i.puerto_switch as numero,
                                pp.tipo_patch_panel as equipo_nombre,
                                'Conexión a Patch Panel' as empleado_nombre,
                                p.nro_piso as oficina_nombre,
                                1 as ocupado,
                                0 as danado
                              FROM interconexion i
                              LEFT JOIN patch_panel pp ON i.codigo_patch_panel = pp.codigo_bien
                              LEFT JOIN bien b ON pp.codigo_bien = b.codigo_bien
                              LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                              LEFT JOIN piso p ON o.id_piso = p.id_piso
                              WHERE i.codigo_switch = :codigo";
                              
            $stm = $this->conex->prepare($queryOcupados);
            $stm->bindParam(":codigo", $codigo_switch, PDO::PARAM_STR);
            $stm->execute();
            $ocupados = $stm->fetchAll(PDO::FETCH_ASSOC);
            
            // Crear array completo de puertos (igual que patch panel)
            for ($i = 1; $i <= $cantidadPuertos; $i++) {
                $encontrado = false;
                foreach ($ocupados as $ocupado) {
                    if ($ocupado['numero'] == $i) {
                        $puertos[] = $ocupado;
                        $encontrado = true;
                        break;
                    }
                }
                if (!$encontrado) {
                    $puertos[] = [
                        'numero' => $i,
                        'ocupado' => 0,
                        'danado' => 0,
                        'equipo_nombre' => null,
                        'empleado_nombre' => null,
                        'oficina_nombre' => null
                    ];
                }
            }
            
        } catch (PDOException $e) {
            error_log("Error obteniendo puertos switch: " . $e->getMessage());
        } finally {
            if ($stm !== null) {
                $stm->closeCursor();
            }
        }
        
        return $puertos;
    }

    // Método para obtener detalles de un puerto específico
    public function obtenerDetallesPuerto($codigo_dispositivo, $numero_puerto, $tipo)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];

        try {
            if ($tipo === 'patch') {
                $query = "SELECT 
                            pc.puerto_patch_panel as numero,
                            pp.tipo_patch_panel as dispositivo_nombre,
                            pp.serial as dispositivo_serial,
                            e.tipo_equipo as equipo_nombre,
                            e.serial as equipo_serial,
                            CONCAT(emp.nombre_empleado, ' ', emp.apellido_empleado) as empleado_nombre,
                            emp.cedula_empleado as empleado_cedula,
                            emp.correo_empleado as empleado_correo,
                            o.nombre_oficina as oficina_nombre,
                            p.nro_piso as piso_nombre,
                            1 as ocupado,
                            0 as danado,
                            1 as con_equipo
                          FROM punto_conexion pc
                          JOIN patch_panel pp ON pc.codigo_patch_panel = pp.codigo_bien
                          LEFT JOIN equipo e ON pc.id_equipo = e.id_equipo
                          LEFT JOIN bien b ON e.codigo_bien = b.codigo_bien
                          LEFT JOIN empleado emp ON b.cedula_empleado = emp.cedula_empleado
                          LEFT JOIN oficina o ON b.id_oficina = o.id_oficina
                          LEFT JOIN piso p ON o.id_piso = p.id_piso
                          WHERE pc.codigo_patch_panel = :codigo AND pc.puerto_patch_panel = :puerto";
            } else {
                $query = "SELECT 
                            i.puerto_switch as numero,
                            s.serial as dispositivo_serial,
                            'Switch' as dispositivo_nombre,
                            pp.tipo_patch_panel as equipo_nombre,
                            pp.serial as equipo_serial,
                            'Sistema' as empleado_nombre,
                            'N/A' as empleado_cedula,
                            'N/A' as empleado_correo,
                            p.nro_piso as oficina_nombre,
                            p.nro_piso as piso_nombre,
                            1 as ocupado,
                            0 as danado,
                            1 as con_equipo
                          FROM interconexion i
                          JOIN switch s ON i.codigo_switch = s.codigo_bien
                          LEFT JOIN patch_panel pp ON i.codigo_patch_panel = pp.codigo_bien
                          LEFT JOIN piso p ON s.id_piso = p.id_piso
                          WHERE i.codigo_switch = :codigo AND i.puerto_switch = :puerto";
            }
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo", $codigo_dispositivo, PDO::PARAM_STR);
            $stm->bindParam(":puerto", $numero_puerto, PDO::PARAM_INT);
            $stm->execute();
            
            $detalles = $stm->fetch(PDO::FETCH_ASSOC);
            
            if ($detalles) {
                $dato['resultado'] = "success";
                $dato['datos'] = $detalles;
            } else {
                // Si no hay detalles, es un puerto disponible
                if ($tipo === 'patch') {
                    $queryInfo = "SELECT 
                                    cantidad_puertos,
                                    tipo_patch_panel as dispositivo_nombre,
                                    serial as dispositivo_serial
                                  FROM patch_panel 
                                  WHERE codigo_bien = :codigo";
                } else {
                    $queryInfo = "SELECT 
                                    cantidad_puertos,
                                    serial as dispositivo_serial,
                                    'Switch' as dispositivo_nombre
                                  FROM switch 
                                  WHERE codigo_bien = :codigo";
                }
                
                $stm = $this->conex->prepare($queryInfo);
                $stm->bindParam(":codigo", $codigo_dispositivo, PDO::PARAM_STR);
                $stm->execute();
                $info = $stm->fetch(PDO::FETCH_ASSOC);
                
                $dato['resultado'] = "success";
                $dato['datos'] = [
                    'numero' => $numero_puerto,
                    'dispositivo_nombre' => $info['dispositivo_nombre'] ?? 'N/A',
                    'dispositivo_serial' => $info['dispositivo_serial'] ?? 'N/A',
                    'ocupado' => 0,
                    'danado' => 0,
                    'con_equipo' => 0
                ];
            }
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

    // Reporte 1: Eficiencia por Técnico
    public function reporteEficienciaTecnicos($filtros = [])
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "CALL sp_reporte_rendimiento_tecnicos(:cedula_tecnico, :id_tipo_servicio, :fecha_inicio, :fecha_fin)";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":cedula_tecnico", $filtros['cedula_tecnico'], PDO::PARAM_STR);
            $stm->bindParam(":id_tipo_servicio", $filtros['id_tipo_servicio'], PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $filtros['fecha_inicio'], PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $filtros['fecha_fin'], PDO::PARAM_STR);
            $stm->execute();
            $dato['resultado'] = "reporte_eficiencia_tecnicos";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 2: Tiempos de Respuesta
    public function reporteTiemposRespuesta($filtros = [])
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT ts.nombre_tipo_servicio, 
                             AVG(TIMESTAMPDIFF(HOUR, s.fecha_solicitud, hs.fecha_resultado)) AS tiempo_promedio_horas,
                             COUNT(hs.codigo_hoja_servicio) AS total_hojas
                      FROM hoja_servicio hs
                      JOIN tipo_servicio ts ON hs.id_tipo_servicio = ts.id_tipo_servicio
                      JOIN solicitud s ON hs.nro_solicitud = s.nro_solicitud
                      WHERE hs.estatus = 'I'
                        AND (:id_tipo_servicio IS NULL OR hs.id_tipo_servicio = :id_tipo_servicio)
                        AND (:fecha_inicio IS NULL OR s.fecha_solicitud >= :fecha_inicio)
                        AND (:fecha_fin IS NULL OR s.fecha_solicitud <= :fecha_fin)
                      GROUP BY ts.nombre_tipo_servicio";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_tipo_servicio", $filtros['id_tipo_servicio'], PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $filtros['fecha_inicio'], PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $filtros['fecha_fin'], PDO::PARAM_STR);
            $stm->execute();
            $dato['resultado'] = "reporte_tiempos_respuesta";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 3: Utilización de Materiales
    public function reporteUtilizacionMateriales($filtros = [])
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            // Asegurar que los filtros sean null si no existen
            $id_material = isset($filtros['id_material']) ? $filtros['id_material'] : null;
            $id_oficina = isset($filtros['id_oficina']) ? $filtros['id_oficina'] : null;
            $fecha_inicio = isset($filtros['fecha_inicio']) ? $filtros['fecha_inicio'] : null;
            $fecha_fin = isset($filtros['fecha_fin']) ? $filtros['fecha_fin'] : null;

            $query = "CALL sp_reporte_materiales_utilizados(:id_material, :id_oficina, :fecha_inicio, :fecha_fin)";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_material", $id_material, PDO::PARAM_INT);
            $stm->bindParam(":id_oficina", $id_oficina, PDO::PARAM_INT);
            $stm->bindParam(":fecha_inicio", $fecha_inicio, PDO::PARAM_STR);
            $stm->bindParam(":fecha_fin", $fecha_fin, PDO::PARAM_STR);
            $stm->execute();
            $dato['resultado'] = "reporte_utilizacion_materiales";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 4: Estado de Equipos
    public function reporteEstadoEquipos($filtros = [])
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT b.descripcion, b.estado, m.nombre_marca, COUNT(*) as cantidad
                      FROM bien b
                      LEFT JOIN marca m ON b.id_marca = m.id_marca
                      WHERE (:estado IS NULL OR b.estado = :estado)
                      GROUP BY b.descripcion, b.estado, m.nombre_marca
                      ORDER BY cantidad DESC";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":estado", $filtros['estado'], PDO::PARAM_STR);
            $stm->execute();
            $dato['resultado'] = "reporte_estado_equipos";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 5: Estado de Infraestructura de Red
    public function reporteEstadoInfraestructura($id_piso)
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "CALL sp_reporte_patch_panel_por_nro(:id_piso)";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_piso", $id_piso, PDO::PARAM_INT);
            $stm->execute();
            $dato['resultado'] = "reporte_estado_infraestructura";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 6: Tendencias y Predicción (Solicitudes por mes)
    public function reporteTendenciasSolicitudes()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT DATE_FORMAT(fecha_solicitud, '%Y-%m') as mes, COUNT(*) as total
                      FROM solicitud
                      WHERE estatus = 1
                      GROUP BY mes
                      ORDER BY mes DESC";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "reporte_tendencias_solicitudes";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 7: Satisfacción y Calidad (Reincidencia de problemas)
    public function reporteReincidenciaProblemas()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT motivo, COUNT(*) as veces_reportado
                      FROM solicitud
                      WHERE estatus = 1
                      GROUP BY motivo
                      HAVING veces_reportado > 1
                      ORDER BY veces_reportado DESC";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "reporte_reincidencia_problemas";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 8: KPIs Ejecutivos
    public function reporteKPIs()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM solicitud WHERE estatus = 1) as total_solicitudes,
                        (SELECT COUNT(*) FROM hoja_servicio WHERE estatus = 'I') as hojas_finalizadas,
                        (SELECT COUNT(*) FROM hoja_servicio WHERE estatus = 'A') as hojas_activas,
                        (SELECT COUNT(*) FROM bien WHERE estado = 'Dañado') as equipos_danados,
                        (SELECT COUNT(*) FROM material WHERE stock < 10) as materiales_bajo_stock";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "reporte_kpis";
            $dato['datos'] = $stm->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
            $this->conex = null;
        }
        return $dato;
    }

    // Reporte 9: Carga de Trabajo por Técnico
    public function reporteCargaTrabajoTecnicos()
    {
        $stm = null;
        $this->conex = $this->Conex();
        $dato = [];
        try {
            $query = "SELECT e.cedula_empleado, CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) as nombre,
                             COUNT(hs.codigo_hoja_servicio) as hojas_asignadas,
                             SUM(hs.estatus = 'I') as hojas_finalizadas,
                             SUM(hs.estatus = 'A') as hojas_activas
                      FROM empleado e
                      LEFT JOIN hoja_servicio hs ON hs.cedula_tecnico = e.cedula_empleado
                      WHERE e.id_cargo = 1 AND e.estatus = 1
                      GROUP BY e.cedula_empleado, nombre
                      ORDER BY hojas_asignadas DESC";
            $stm = $this->conex->prepare($query);
            $stm->execute();
            $dato['resultado'] = "reporte_carga_trabajo";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        } finally {
            if ($stm !== null) $stm->closeCursor();
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
                
            case 'obtener_patch_panels':
                return $this->obtenerPatchPanels($peticion['id_piso']);
                
            case 'obtener_switches':
                return $this->obtenerSwitches($peticion['id_piso']);
                
            case 'detalles_puerto':
                return $this->obtenerDetallesPuerto(
                    $peticion['codigo_dispositivo'],
                    $peticion['numero_puerto'],
                    $peticion['tipo']
                );
                
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
                
            case 'reporte_eficiencia_tecnicos':
                return $this->reporteEficienciaTecnicos($peticion);
            case 'reporte_tiempos_respuesta':
                return $this->reporteTiemposRespuesta($peticion);
            case 'reporte_utilizacion_materiales':
                return $this->reporteUtilizacionMateriales($peticion);
            case 'reporte_estado_equipos':
                return $this->reporteEstadoEquipos($peticion);
            case 'reporte_estado_infraestructura':
                return $this->reporteEstadoInfraestructura($peticion['id_piso']);
            case 'reporte_tendencias_solicitudes':
                return $this->reporteTendenciasSolicitudes();
            case 'reporte_reincidencia_problemas':
                return $this->reporteReincidenciaProblemas();
            case 'reporte_kpis':
                return $this->reporteKPIs();
            case 'reporte_carga_trabajo':
                return $this->reporteCargaTrabajoTecnicos();
                
            default:
                return ['resultado' => 'error', 'mensaje' => 'Operación no válida: ' . $peticion['peticion']];
        }
    }
}