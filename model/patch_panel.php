<?php

require_once "model/conexion.php";

class patch_panel extends Conexion {

    private $codigo_bien;
    private $tipo_patch_panel;
    private $cantidad_puertos;
    private $serial_patch_panel;

    public function __construct() {

        $this->codigo_bien = "";
        $this->tipo_patch_panel = "";
        $this->cantidad_puertos = "";
        $this->serial_patch_panel = "";

    }


    public function get_codigo_bien() {
        return $this->codigo_bien;
    }
    public function set_codigo_bien($codigo_bien) {
        $this->codigo_bien = $codigo_bien;
    }

    public function get_tipo_patch_panel() {
        return $this->tipo_patch_panel;
    }
    public function set_tipo_patch_panel($tipo_patch_panel) {
        $this->tipo_patch_panel = $tipo_patch_panel;
    }

    public function get_cantidad_puertos() {
        return $this->cantidad_puertos;
    }
    public function set_cantidad_puertos($cantidad_puertos) {
        $this->cantidad_puertos = $cantidad_puertos;
    }

    public function get_serial_patch_panel() {
        return $this->serial_patch_panel;
    }
    public function set_serial_patch_panel($serial_patch_panel) {
        $this->serial_patch_panel = $serial_patch_panel;
    }


    private function Validar() {

        $dato = [];

        try {

            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT * FROM patch_panel WHERE codigo_bien = :codigo_bien";

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

                $query = "INSERT INTO patch_panel(codigo_bien, tipo_patch_panel, cantidad_puertos,  `serial`) VALUES 
                (:codigo_bien, :tipo_patch_panel, :cantidad_puertos, :serial_patch_panel)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->bindParam(":tipo_patch_panel", $this->tipo_patch_panel);
                $stm->bindParam(":cantidad_puertos", $this->cantidad_puertos);
                $stm->bindParam(":serial_patch_panel", $this->serial_patch_panel);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Registro el Patch Panel Exitosamente";

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

                $query = "UPDATE patch_panel SET tipo_patch_panel= :tipo_patch_panel, cantidad_puertos= :cantidad_puertos,  `serial`=:serial_patch_panel WHERE codigo_bien = :codigo_bien";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->bindParam(":tipo_patch_panel", $this->tipo_patch_panel);
                $stm->bindParam(":cantidad_puertos", $this->cantidad_puertos);
                $stm->bindParam(":serial_patch_panel", $this->serial_patch_panel);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Modificaron los datos del Patch Panel Exitosamente";

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
            $dato['mensaje'] = "Error al Modificar el registro";

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
                JOIN patch_panel p ON p.codigo_bien = b.codigo_bien
                SET b.estatus = 0
                WHERE b.codigo_bien = :codigo_bien";
                
                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_bien", $this->codigo_bien);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se Eliminó El Patch Panel Exitosamente";

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

            $query = "SELECT p.codigo_bien, p.tipo_patch_panel, p.cantidad_puertos, p.serial
            FROM patch_panel p
            JOIN bien b ON p.codigo_bien = b.codigo_bien
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

        $dato = [];

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
            
            return $stm->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {

            return [];
        }

    }

    private function ConsultarEliminadas() {

        $dato = [];

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        try {

            $query = "SELECT p.*
                    FROM patch_panel p 
                    JOIN bien b ON p.codigo_bien = b.codigo_bien 
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
                    JOIN patch_panel p ON p.codigo_bien = b.codigo_bien
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

            $dato['resultado'] = "error";
            $dato['estado'] = -1;
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

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }

}

?>