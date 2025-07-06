<?php

require_once "model/conexion.php";

class Switch_ extends Conexion {

    private $codigo_bien;
    private $cantidad_puertos;
    private $serial_switch;

    public function __construct() {

        $this->codigo_bien = "";
        $this->cantidad_puertos = "";
        $this->serial_switch = "";

    }


    public function get_codigo_bien() {
        return $this->codigo_bien;
    }
    public function set_codigo_bien($codigo_bien) {
        $this->codigo_bien = $codigo_bien;
    }

    public function get_cantidad_puertos() {
        return $this->cantidad_puertos;
    }
    public function set_cantidad_puertos($cantidad_puertos) {
        $this->cantidad_puertos = $cantidad_puertos;
    }

    public function get_serial_switch() {
        return $this->serial_switch;
    }
    public function set_serial_switch($serial_switch) {
        $this->serial_switch = $serial_switch;
    }

    private function Validar() {

        $dato = [];

        try {

            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT * FROM switch WHERE codigo_bien = :codigo_bien";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_bien", $this->codigo_bien);
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

       $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Registrar() {
        
        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] == 0) {
            try {
                $this->conex->beginTransaction();

                $query = "INSERT INTO switch(codigo_bien, cantidad_puertos, `serial`) 
                        VALUES (:codigo_bien, :cantidad_puertos, :serial_switch)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->bindParam(":cantidad_puertos", $this->cantidad_puertos);
                $stm->bindParam(":serial_switch", $this->serial_switch);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Registró el Switch Exitosamente";

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

    private function Actualizar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {
            try {
                $this->conex->beginTransaction();

                $query = "UPDATE switch SET cantidad_puertos = :cantidad_puertos, `serial` = :serial_switch WHERE codigo_bien = :codigo_bien";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->bindParam(":cantidad_puertos", $this->cantidad_puertos);
                $stm->bindParam(":serial_switch", $this->serial_switch);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Modificaron los datos del Switch Exitosamente";

            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['estado'] = -1;
                $dato['resultado'] = "error";
                $dato['mensaje'] = $e->getMessage();
            }
        } else {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al modificar el registro";
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Eliminar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];
        $bool = $this->Validar();

        if ($bool['bool'] != 0) {

            try {
                $this->conex->beginTransaction();

                $query = "UPDATE bien b
                    JOIN switch s ON s.codigo_bien = b.codigo_bien
                    SET b.estatus = 0
                    WHERE b.codigo_bien = :codigo_bien";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Eliminó el Switch Exitosamente";

            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();
            }

        } else {
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = "Error al eliminar el registro";
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Consultar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];

        try {

            $query = "SELECT s.codigo_bien, s.cantidad_puertos, s.serial
                    FROM switch s
                    JOIN bien b ON s.codigo_bien = b.codigo_bien
                    WHERE b.estatus = 1";

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

    public function ConsultarBien() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        try {
            $query = "SELECT b.codigo_bien, b.descripcion  
                    FROM bien b
                    WHERE b.estatus = 1
                        AND NOT EXISTS (
                            SELECT 1 FROM patch_panel p WHERE p.codigo_bien = b.codigo_bien
                        )
                        AND NOT EXISTS (
                            SELECT 1 FROM switch s WHERE s.codigo_bien = b.codigo_bien
                        )
                        AND NOT EXISTS (
                            SELECT 1 FROM equipo e WHERE e.codigo_bien = b.codigo_bien
                        )";

            $stm = $this->conex->prepare($query);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $result = [];
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $result;
    }

    private function ConsultarEliminadas() {

        $dato = [];

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        try {

            $query = "SELECT s.codigo_bien, s.cantidad_puertos, s.serial
                    FROM switch s
                    JOIN bien b ON s.codigo_bien = b.codigo_bien
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

        
    private function Restaurar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];

        try {

            $this->conex->beginTransaction();

            $query = "UPDATE bien b
                    JOIN switch s ON s.codigo_bien = b.codigo_bien
                    SET b.estatus = 1
                    WHERE b.codigo_bien = :codigo_bien";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_bien", $this->codigo_bien);
            $stm->execute();

            $this->conex->commit();

            $dato['resultado'] = "restaurar";
            $dato['estado'] = 1;
            $dato['mensaje'] = "Se Restauro el Patch Panel exitosamente";

        } catch (PDOException $e) {

            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();

        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function reporteSwitchPanel($id_piso) {
        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

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
        }

        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    public function Transaccion($peticion) {

        switch ($peticion['peticion']) {

            case 'registrar':
                return $this->Registrar();

            case 'consultar':
                return $this->Consultar();

            case 'consultar_eliminadas':
                return $this->ConsultarEliminadas();

            case 'consultar_bien':
                return $this->ConsultarBien();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();

            case 'restaurar':
                return $this->Restaurar();

            case 'validar':
                return $this->Validar();

            case 'reporte_switch_panel':
                return $this->reporteSwitchPanel($peticion['id_piso']);

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }

}

?>