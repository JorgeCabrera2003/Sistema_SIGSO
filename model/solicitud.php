<?php

require_once('model/conexion.php');

class Solicitud extends Conexion
{
    // Atributos
    private $nro_solicitud;
    private $cedula_solicitante;
    private $id_equipo;
    private $motivo;
    private $resultado;
    private $estado;
    private $fecha;


    public function __construct()
    {
        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();
    }

    public function set_fecha_inicio($fecha_inicio)
    {
        $this->fecha = $fecha_inicio;
    }

    public function set_fecha_final($fecha_final)
    {
        $this->fecha = $fecha_final;
    }

    public function set_nro_solicitud($nro_solicitud)
    {
        $this->nro_solicitud = $nro_solicitud;
    }

    public function set_cedula_solicitante($cedula_solicitante)
    {
        $this->cedula_solicitante = $cedula_solicitante;
    }

    public function set_motivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function set_id_equipo($id_equipo)
    {
        $this->id_equipo = $id_equipo;
    }

    public function get_id_equipo()
    {
        return $this->id_equipo;
    }

    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    public function get_estado()
    {
        return $this->estado;
    }

    public function set_resultado($resultado)
    {
        $this->resultado = $resultado;
    }

    private function CrearSolicitud()
    {
        $datos = [];

        try {
            $this->conex->beginTransaction();

            $sql = "INSERT INTO solicitud(cedula_solicitante, motivo, id_equipo, fecha_solicitud, estado_solicitud)
            VALUES (:solicitante, :motivo, :equipo, current_timestamp(), 'En Proceso')";

            $solicitar = $this->conex->prepare($sql);
            $solicitar->bindParam(':solicitante', $this->cedula_solicitante);
            $solicitar->bindParam(':equipo', $this->id_equipo);
            $solicitar->bindParam(':motivo', $this->motivo);

            $solicitar->execute();
            $this->Cerrar_Conexion($none, $solicitar);

            $nro = $this->conex->prepare("SELECT * FROM solicitud ORDER BY nro_solicitud DESC LIMIT 1;");
            $nro->execute();

            $datos['resultado'] = 'registrar';
            $datos['datos'] = $nro->fetchAll(PDO::FETCH_ASSOC)[0]["nro_solicitud"];
            $datos['bool'] = 1;
            $this->conex->commit();
        } catch (PDOException $e) {
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
            $datos['bool'] = -1;
            $this->conex->rollBack();
        }

        $this->Cerrar_Conexion($this->conex, $nro);
        return $datos;
    }

    private function SolicitudUsuario()
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();
            $query = "SELECT o.nro_solicitud AS ID,
            s.nombre_empleado AS Tecnico,
            o.motivo AS Motivo,
            e.tipo_equipo AS Equipo,
            o.fecha_solicitud AS Inicio,
            o.estado_solicitud AS Estatus, 
            o.resultado_solicitud AS Resultado
            FROM solicitud AS o
               LEFT JOIN
               empleado AS s
               ON o.cedula_solicitante = s.cedula_empleado
               LEFT JOIN
               equipo AS e
               ON o.id_equipo = e.id_equipo
               WHERE o.cedula_solicitante = :cedula";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(':cedula', $this->cedula_solicitante);
            $stm->execute();
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $datos['resultado'] = 'consultar';
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }
    private function ValidarSolicitud()
    {
        $datos = [];
        try {
            $this->conex->beginTransaction();

            $query = "SELECT * FROM solicitud WHERE nro_solicitud= :nro_solicitud";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":nro_solicitud", $this->nro_solicitud);
            $stm->execute();
            $datos["respuesta"] = "validar";
            $datos["datos"] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $datos["resultado"] = "error";
            $datos["mensaje"] = $e->getMessage();
        }
        $this->Cerrar_Conexion($none, $stm);
        return $datos;
    }

    private function ActualizarSolicitud()
    {
        $sql = "UPDATE solicitud SET motivo = :motivo, id_equipo = :equipo, estado_solicitud = 'En Proceso'
        WHERE nro_solicitud = :nro";

        $solicitar = $this->conex->prepare($sql);
        $solicitar->bindValue(':nro', $this->nro_solicitud);
        $solicitar->bindParam(':equipo', $this->id_equipo);
        $solicitar->bindParam(':motivo', $this->motivo);

        $bool = $solicitar->execute();
        $this->Cerrar_Conexion($this->conex, $solicitar);
        return $bool;
    }
    private function EliminarSolicitud()
    {
        $datos = [];

        try {
            $sql = "DELETE FROM solicitud WHERE nro_solicitud=?";
            $stm = $this->conex->prepare($sql);

            $bool = $stm->execute([$this->nro_solicitud]);
            $datos['resultado'] = 'eliminar';
            if ($bool > 0) {
                $datos['mensaje'] = 'Se eliminó exitosamente';
            } else {
                $datos['mensaje'] = 'No se logró la eliminación';
            }
        } catch (PDOException $e) {
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function Solicitar()
    {

        $query = "SELECT COUNT(*) AS total FROM solicitud WHERE cedula_solicitante = :cedula AND motivo = :motivo
        ORDER BY fecha DESC LIMIT 1";

        $stm = $this->conex->prepare($query);
        $stm->bindParam(':cedula', $this->cedula_solicitante);
        $stm->bindParam(':motivo', $this->motivo);

        $stm->execute();
        $resultado = $stm->fetch();

        if ($resultado["total"] > 0) {
        } else {
            $sql = "INSERT INTO solicitud(cedula_solicitante,motivo,fecha) VALUES (:solicitante,:motivo,current_timestamp())";
            $solicitar = $this->conex->prepare($sql);
            $solicitar->bindValue(':solicitante', $this->cedula_solicitante);
            $solicitar->bindParam(':motivo', $this->motivo);

            $solicitar->execute();
            $this->Cerrar_Conexion($none, $solicitar);
        }

        $datos = $this->UltimaSolicitud();

        $html["lo0la"] = "lola";
        $html["html"] = "<tr class='odd'><td class='sorting_1'>" . $datos["ID"] . "</td><td>" . $datos["Motivo"] . "</td><td>" . $datos["Inicio"] . "</td><td>" . $datos["Estatus"] . "</td><td>" . $datos["Resultado"] . "</td><td></td></tr>";
        $this->Cerrar_Conexion($this->conex, $stm);
        return $html;
    }

    private function Finalizar()
    {
        $sql = "UPDATE solicitud SET resultado_solicitud = :resultado, estado_solicitud ='Finalizado' WHERE nro_solicitud=:nro";
        $solicitar = $this->conex->prepare($sql);
        $solicitar->bindValue(':nro', $this->nro_solicitud);
        $solicitar->bindParam(':resultado', $this->resultado);

        $bool = $solicitar->execute();
        $this->Cerrar_Conexion($this->conex, $solicitar);
        return $bool;
    }


    private function ConsultaServicios()
    {

        $query = "SELECT * FROM servicio";

        $stm = $this->conex->prepare($query);

        $stm->execute();
        $datos = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->Cerrar_Conexion($this->conex, $stm);
        return $datos;
    }

    private function UltimaSolicitud()
    {
        $query = "SELECT o.nro_solicitud AS ID, s.nombre AS Tecnico, o.motivo AS Motivo, e.tipo AS Equipo, o.fecha AS Inicio, o.estatus AS Estatus, o.resultado AS Resultado
            FROM solicitud AS o
            LEFT JOIN
            empleado AS s ON o.cedula_solicitante = s.cedula
            LEFT JOIN
            equipo AS e ON o.id_equipo = e.id_equipo
            WHERE o.cedula_solicitante = :cedula
            ORDER BY o.fecha DESC LIMIT 1";

        $stm = $this->conex->prepare($query);

        $stm->bindParam(':cedula', $this->cedula_solicitante);

        $stm->execute();

        return $stm->fetch();
    }

    private function Servicios()
    {
        $datos = [];

        try {
            $this->conex->beginTransaction();

            $query = "SELECT o.nro_solicitud AS ID,
        s1.nombre_empleado AS Solicitante,
        o.cedula_solicitante AS Cedula,
        o.motivo AS Motivo,
        e.tipo_equipo AS Equipo,
        o.fecha_solicitud AS Inicio,
        o.estado_solicitud AS Estado,
        o.resultado_solicitud AS Resultado
        FROM solicitud AS o
        LEFT JOIN empleado AS s1 ON o.cedula_solicitante = s1.cedula_empleado
        LEFT JOIN equipo AS e ON o.id_equipo = e.id_equipo
        WHERE o.estatus = 1
        ORDER BY Inicio DESC;";

            $stm = $this->conex->prepare($query);
            $stm->execute();

            $datos['resultado'] = 'consultar';
            $datos['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $datos['resultado'] = 'error';
            $datos['mensaje'] = $e->getMessage();;
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $datos;
    }

    private function ConsultaReporte()
    {
        $query = "SELECT o.nro_solicitud AS ID,
        s1.nombre AS Solicitante,
        o.cedula_solicitante AS Cedula,
        o.motivo AS Motivo,
        e.tipo_equipo AS Equipo,
        o.fecha AS Inicio,
        o.estatus AS Estatus,
        o.resultado AS Resultado
        FROM solicitud AS o
        LEFT JOIN empleado AS s1 ON o.cedula_solicitante = s1.cedula
        LEFT JOIN equipo AS e ON o.id_equipo = e.id_equipo
        WHERE o.fecha BETWEEN :inicio AND :final
        ORDER BY Inicio DESC;";

        $stm = $this->conex->prepare($query);

        $stm->bindParam(':inicio', $this->fecha);
        $stm->bindParam(':final', $this->$this->fecha);

        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Transaccion($peticion)
    {
        switch ($peticion["peticion"]) {

            case "registrar":
                return $this->CrearSolicitud();

            case "solicitud_usuario":
                return $this->SolicitudUsuario();

            case "actualizar":
                return $this->ActualizarSolicitud();

            case "eliminar":
                return $this->EliminarSolicitud();

            case "finalizar":
                return $this->Finalizar();

            case "consultar_servicio":
                return $this->Servicios();

            default:
                $error = "Operación no Válida";
                return $error;
        }
    }
}
