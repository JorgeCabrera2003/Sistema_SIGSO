<?php 

    require_once('model/conexion.php');

    class tecnico extends conexion{

        private $cedula;
        private $nombre;
        private $apellido;
        private $id_cargo;
        private $id_unidad;
        private $telefono;
        private $correo;
        private $id_servicio;

        public function __construct(){
            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
        }

        // Setters
        public function set_cedula($cedula){
            $this->cedula = $cedula;
        }
        public function set_nombre($nombre){
            $this->nombre = $nombre;
        }
        public function set_apellido($apellido){
            $this->apellido = $apellido;
        }
        public function set_id_cargo($id_cargo){
            $this->id_cargo = $id_cargo;
        }
        public function set_id_unidad($id_unidad){
            $this->id_unidad = $id_unidad;
        }
        public function set_telefono($telefono){
            $this->telefono = $telefono;
        }
        public function set_correo($correo){
            $this->correo = $correo;
        }
        public function set_id_servicio($id_servicio){
            $this->id_servicio = $id_servicio;
        }

        // CRUD para técnicos (empleados con cargo técnico)
        public function Registrar(){
            $datos = [];
            try {
                $this->conex->beginTransaction();
                $sql = "INSERT INTO empleado (cedula_empleado, nombre_empleado, apellido_empleado, id_cargo, id_servicio, id_unidad, telefono_empleado, correo_empleado)
                        VALUES (:cedula, :nombre, :apellido, :id_cargo, :id_servicio, :id_unidad, :telefono, :correo)";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':cedula', $this->cedula);
                $stmt->bindParam(':nombre', $this->nombre);
                $stmt->bindParam(':apellido', $this->apellido);
                $stmt->bindParam(':id_cargo', $this->id_cargo);
                $stmt->bindParam(':id_servicio', $this->id_servicio);
                $stmt->bindParam(':id_unidad', $this->id_unidad);
                $stmt->bindParam(':telefono', $this->telefono);
                $stmt->bindParam(':correo', $this->correo);
                $stmt->execute();
                $this->conex->commit();
                $datos['resultado'] = "registrar";
                $datos['mensaje'] = "Se registró el técnico exitosamente";
                $datos['estado'] = 1;
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $datos['resultado'] = "error";
                $datos['mensaje'] = $e->getMessage();
                $datos['estado'] = -1;
            }
            return $datos;
        }

        public function Modificar(){
            $datos = [];
            try {
                $this->conex->beginTransaction();
                $sql = "UPDATE empleado SET nombre_empleado=:nombre, apellido_empleado=:apellido, id_cargo=:id_cargo, id_servicio=:id_servicio, id_unidad=:id_unidad, telefono_empleado=:telefono, correo_empleado=:correo
                        WHERE cedula_empleado=:cedula";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':cedula', $this->cedula);
                $stmt->bindParam(':nombre', $this->nombre);
                $stmt->bindParam(':apellido', $this->apellido);
                $stmt->bindParam(':id_cargo', $this->id_cargo);
                $stmt->bindParam(':id_servicio', $this->id_servicio);
                $stmt->bindParam(':id_unidad', $this->id_unidad);
                $stmt->bindParam(':telefono', $this->telefono);
                $stmt->bindParam(':correo', $this->correo);
                $stmt->execute();
                $this->conex->commit();
                $datos['resultado'] = "modificar";
                $datos['mensaje'] = "Se modificó el técnico exitosamente";
                $datos['estado'] = 1;
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $datos['resultado'] = "error";
                $datos['mensaje'] = $e->getMessage();
                $datos['estado'] = -1;
            }
            return $datos;
        }

        public function Eliminar(){
            $datos = [];
            try {
                $this->conex->beginTransaction();
                $sql = "DELETE FROM empleado WHERE cedula_empleado=:cedula AND id_cargo=:id_cargo";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':cedula', $this->cedula);
                $stmt->bindParam(':id_cargo', $this->id_cargo);
                $stmt->execute();
                $this->conex->commit();
                $datos['resultado'] = "eliminar";
                $datos['mensaje'] = "Se eliminó el técnico exitosamente";
                $datos['estado'] = 1;
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $datos['resultado'] = "error";
                $datos['mensaje'] = $e->getMessage();
                $datos['estado'] = -1;
            }
            return $datos;
        }

        public function Consultar(){
            $datos = [];
            try {
                $this->conex->beginTransaction();
                $sql = "SELECT 
                            e.cedula_empleado AS cedula,
                            e.nombre_empleado AS nombre,
                            e.apellido_empleado AS apellido,
                            e.telefono_empleado AS telefono,
                            e.correo_empleado AS correo,
                            d.nombre AS dependencia,
                            u.nombre_unidad AS unidad,
                            c.nombre_cargo AS cargo,
                            ts.nombre_tipo_servicio AS servicio
                        FROM empleado e
                        LEFT JOIN unidad u ON e.id_unidad = u.id_unidad
                        LEFT JOIN dependencia d ON u.id_dependencia = d.id
                        LEFT JOIN cargo c ON e.id_cargo = c.id_cargo
                        LEFT JOIN tipo_servicio ts ON e.id_servicio = ts.id_tipo_servicio
                        WHERE e.id_cargo = 1";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute();
                $datos['resultado'] = "consultar";
                $datos['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $this->conex->commit();
            } catch (PDOException $e) {
                $this->conex->rollBack();
                $datos['resultado'] = "error";
                $datos['mensaje'] = $e->getMessage();
            }
            return $datos;
        }

        public function Validar(){
            $datos = [];
            try {
                $sql = "SELECT * FROM empleado WHERE cedula_empleado=:cedula AND id_cargo=1";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':cedula', $this->cedula);
                $stmt->execute();
                $datos['bool'] = $stmt->rowCount() > 0 ? 1 : 0;
            } catch (PDOException $e) {
                $datos['error'] = $e->getMessage();
            }
            return $datos;
        }

        // Devuelve el técnico con más servicios realizados en el mes actual
        public function getTecnicoMasEficienteMes() {
            $datos = [];
            try {
                $mesActual = date('m');
                $anioActual = date('Y');
                // Se asume que la tabla hoja_servicio tiene los campos: cedula_tecnico, fecha_inicio
                $sql = "SELECT 
                            e.cedula_empleado AS cedula,
                            CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS nombre_completo,
                            COUNT(hs.codigo_hoja_servicio) AS total_servicios
                        FROM hoja_servicio hs
                        INNER JOIN empleado e ON hs.cedula_tecnico = e.cedula_empleado
                        WHERE MONTH(hs.fecha_inicio) = :mes AND YEAR(hs.fecha_inicio) = :anio
                        GROUP BY hs.cedula_tecnico
                        ORDER BY total_servicios DESC
                        LIMIT 1";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(':mes', $mesActual);
                $stmt->bindParam(':anio', $anioActual);
                $stmt->execute();
                $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $datos = null;
            }
            return $datos;
        }

        public function Transaccion($peticion) {
            switch ($peticion['peticion']) {
                case 'registrar':
                    return $this->Registrar();
                case 'modificar':
                    return $this->Modificar();
                case 'eliminar':
                    return $this->Eliminar();
                case 'consultar':
                    return $this->Consultar();
                case 'validar':
                    return $this->Validar();
                default:
                    return ['resultado' => 'error', 'mensaje' => 'Petición no válida'];
            }
        }
    }
 ?>