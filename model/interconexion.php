<?php

require_once "model/conexion.php";

class interconexion extends Conexion {

    private $conex;
    private $id_interconexion;
    private $codigo_switch;
    private $codigo_patch_panel;
    private $puerto_switch;
    private $puerto_patch_panel;

    public function __construct() {

        $this->id_interconexion = "";
        $this->codigo_switch = "";
        $this->codigo_patch_panel = "";
        $this->puerto_switch = "";
        $this->puerto_patch_panel = "";
        
    }

    public function get_id_interconexion() {
        return $this->id_interconexion;
    }
    public function set_id_interconexion($id_interconexion) {
        $this->id_interconexion = $id_interconexion;
    }

    public function get_codigo_switch() {
        return $this->codigo_switch;
    }
    public function set_codigo_switch($codigo_switch) {
        $this->codigo_switch = $codigo_switch;
    }

    public function get_codigo_patch_panel() {
        return $this->codigo_patch_panel;
    }
    public function set_codigo_patch_panel($codigo_patch_panel) {
        $this->codigo_patch_panel = $codigo_patch_panel;
    }

    public function get_puerto_switch() {
        return $this->puerto_switch;
    }
    public function set_puerto_switch($puerto_switch) {
        $this->puerto_switch = $puerto_switch;
    }

    public function get_puerto_patch_panel() {
        return $this->puerto_patch_panel;
    }
    public function set_puerto_patch_panel($puerto_patch_panel) {
        $this->puerto_patch_panel = $puerto_patch_panel;
    }

    private function Existe() {

        $dato = [];

        try {

            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT * FROM interconexion 
            WHERE codigo_patch_panel = :codigo_patch_panel 
            AND puerto_patch_panel = :puerto_patch_panel 
            AND codigo_switch = :codigo_switch 
            AND puerto_switch = :puerto_switch";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_patch_panel", $this->codigo_patch_panel);
            $stm->bindParam(":puerto_patch_panel", $this->puerto_patch_panel);
            $stm->bindParam(":codigo_switch", $this->codigo_switch);
            $stm->bindParam(":puerto_switch", $this->puerto_switch);
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
    
    private function Validar_Puerto_Patch_Panel() {

        $dato = [];

        try {

            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT * FROM punto_conexion WHERE codigo_patch_panel = :codigo_patch_panel AND puerto_patch_panel = :puerto_patch_panel";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_patch_panel", $this->codigo_patch_panel);
            $stm->bindParam(":puerto_patch_panel", $this->puerto_patch_panel);
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
    
    private function Validar_Puerto_Switch() {

        $dato = [];

        try {

            $this->conex = new Conexion("sistema");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();
            
            $query = "SELECT * FROM interconexion WHERE codigo_switch = :codigo_switch AND puerto_switch = :puerto_switch";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_switch", $this->codigo_switch);
            $stm->bindParam(":puerto_switch", $this->puerto_switch);
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

        $bool_patch_panel = $this->Validar_Puerto_Patch_Panel();
        $bool_switch = $this->Validar_Puerto_Switch();

        if ($bool_patch_panel['bool'] == 1 && $bool_switch['bool'] == 0) {

            try {

                $this->conex->beginTransaction();

                $query = "INSERT INTO interconexion(codigo_patch_panel, puerto_patch_panel, codigo_switch, puerto_switch) VALUES 
                (:codigo_patch_panel, :puerto_patch_panel, :codigo_switch, :puerto_switch)";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_patch_panel", $this->codigo_patch_panel);
                $stm->bindParam(":puerto_patch_panel", $this->puerto_patch_panel);
                $stm->bindParam(":codigo_switch", $this->codigo_switch);
                $stm->bindParam(":puerto_switch", $this->puerto_switch);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "registrar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se registró la interconexión exitosamente";

            } catch (PDOException $e) {

                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = $e->getMessage();

            }

        } else {
            // Mensajes específicos según el caso
            if ($bool_patch_panel['bool'] == 0 && $bool_switch['bool'] == 1) {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No hay un punto de conexión en este puerto del Patch Panel y el puerto del Switch está ocupado";
            } else if ($bool_patch_panel['bool'] == 0) {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No hay un punto de conexión en este puerto del Patch Panel";
            } else if ($bool_switch['bool'] == 1) {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "El puerto del Switch ya está ocupado";
            } else {
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No se puede registrar la interconexión. Verifique los datos.";
            }
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Actualizar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];

        // Verifica si ya existe otra interconexión con los mismos datos (excepto la actual)
        try {
            $this->conex->beginTransaction();

            $query = "SELECT * FROM interconexion 
                      WHERE codigo_patch_panel = :codigo_patch_panel 
                      AND puerto_patch_panel = :puerto_patch_panel 
                      AND codigo_switch = :codigo_switch 
                      AND puerto_switch = :puerto_switch
                      AND id_interconexion != :id_interconexion";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":codigo_patch_panel", $this->codigo_patch_panel);
            $stm->bindParam(":puerto_patch_panel", $this->puerto_patch_panel);
            $stm->bindParam(":codigo_switch", $this->codigo_switch);
            $stm->bindParam(":puerto_switch", $this->puerto_switch);
            $stm->bindParam(":id_interconexion", $this->id_interconexion);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "Ya existe una interconexión con estos datos.";
            } else {
                // Ahora sí actualiza
                $query = "UPDATE interconexion 
                          SET codigo_patch_panel = :codigo_patch_panel,  
                              puerto_patch_panel = :puerto_patch_panel,  
                              codigo_switch = :codigo_switch,  
                              puerto_switch = :puerto_switch
                          WHERE id_interconexion = :id_interconexion";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":codigo_patch_panel", $this->codigo_patch_panel);
                $stm->bindParam(":puerto_patch_panel", $this->puerto_patch_panel);
                $stm->bindParam(":codigo_switch", $this->codigo_switch);
                $stm->bindParam(":puerto_switch", $this->puerto_switch);
                $stm->bindParam(":id_interconexion", $this->id_interconexion);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se modificó la interconexión exitosamente";
            }

        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['estado'] = -1;
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Eliminar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];

        try {
            $this->conex->beginTransaction();

            // Verifica si existe el registro por ID
            $query = "SELECT * FROM interconexion WHERE id_interconexion = :id_interconexion";
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id_interconexion", $this->id_interconexion);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                // Si existe, elimina
                $query = "DELETE FROM interconexion WHERE id_interconexion = :id_interconexion";
                $stm = $this->conex->prepare($query);
                $stm->bindParam(":id_interconexion", $this->id_interconexion);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "eliminar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se eliminó la Interconexión exitosamente";
            } else {
                $this->conex->rollBack();
                $dato['resultado'] = "error";
                $dato['estado'] = -1;
                $dato['mensaje'] = "No existe la interconexión a eliminar";
            }

        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['estado'] = -1;
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function Consultar() {

        $this->conex = new Conexion("sistema");
        $this->conex = $this->conex->Conex();

        $dato = [];

        try {

            $query = "SELECT 
            id_interconexion, codigo_switch, codigo_patch_panel, puerto_switch, puerto_patch_panel	
            FROM interconexion";

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

    public function Transaccion($peticion) {

        switch ($peticion['peticion']) {

            case 'registrar':
                return $this->Registrar();

            case 'consultar':
                return $this->Consultar();

            case 'actualizar':
                return $this->Actualizar();

            case 'eliminar':
                return $this->Eliminar();
             
            case 'validar_puerto_patch_panel':
                return $this->Validar_Puerto_Patch_Panel();

            case 'validar_puerto_switch':
                return $this->Validar_Puerto_Switch();

            case 'existe':
                return $this->Existe();

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }

}

?>