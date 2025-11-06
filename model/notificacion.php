<?php
require_once "model/conexion.php";
class Notificacion extends Conexion
{
    private $id;
    private $usuario;
    private $modulo;
    private $mensaje;
    private $fecha;
    private $hora;
    private $estado;

    public function __construct(){
        $this->conex = new Conexion("usuario");
        $this->conex = $this->conex->Conex();
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function set_usuario($usuario){
        $this->usuario = $usuario;
    }

    public function set_modulo($modulo){
        $this->modulo = $modulo;
    }

    public function set_mensaje($mensaje){
        $this->mensaje = $mensaje;
    }

    public function set_fecha($fecha){
        $this->fecha = $fecha;
    }

    public function set_hora($hora){
        $this->hora = $hora;
    }

    public function set_estado($estado){
        $this->estado = $estado;
    }

    public function get_id(){
        return $this->id;
    }

    public function get_usuario(){
        return $this->usuario;
    }

    public function get_modulo(){
        return $this->modulo;
    }

    public function get_mensaje(){
        return $this->mensaje;
    }

    public function get_fecha(){
        return $this->fecha;
    }

    public function get_hora(){
        return $this->hora;
    }

    public function get_estado(){
        return $this->estado;
    }

    private function Registrar(){
        $dato = [];
        try {
            $this->conex->beginTransaction();
            $query = "INSERT INTO notificacion (usuario, modulo, mensaje, fecha, hora, estado)
                      VALUES (:usuario, :modulo, :mensaje, :fecha, :hora, 'Nuevo')";
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":usuario", $this->usuario);
            $stm->bindParam(":modulo", $this->modulo);
            $stm->bindParam(":mensaje", $this->mensaje);
            $stm->bindParam(":fecha", $this->fecha);
            $stm->bindParam(":hora", $this->hora);
            $stm->execute();
            $dato['resultado'] = "registrar";
            $dato['mensaje'] = "Notificación registrada con éxito";
            $dato['id'] = $this->conex->lastInsertId();

            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function Consultar(){
        $dato = [];
        try {
            $this->conex->beginTransaction();
            $query = "SELECT * FROM notificacion WHERE usuario = :usuario ORDER BY id DESC";
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":usuario", $this->usuario);
            $stm->execute();
            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function MarcarLeido(){
        $dato = [];
        try {
            $this->conex->beginTransaction();
            $query = "UPDATE notificacion SET estado = 'Leído' WHERE id = :id AND usuario = :usuario";
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":id", $this->id);
            $stm->bindParam(":usuario", $this->usuario);
            $stm->execute();
            $dato['resultado'] = "actualizar";
            $dato['mensaje'] = "Notificación marcada como leída";
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function MarcarTodasLeidas(){
        $dato = [];
        try {
            $this->conex->beginTransaction();
            $query = "UPDATE notificacion SET estado = 'Leído' WHERE usuario = :usuario AND estado = 'Nuevo'";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":usuario", $this->usuario);
            $stm->execute();
            $dato['resultado'] = "actualizar";
            $dato['mensaje'] = "Todas las notificaciones fueron marcadas como leídas";
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    private function ContarNuevas(){
        $dato = [];
        try {
            $this->conex->beginTransaction();
            $query = "SELECT COUNT(*) as total FROM notificacion WHERE usuario = :usuario AND estado = 'Nuevo'";
            
            $stm = $this->conex->prepare($query);
            $stm->bindParam(":usuario", $this->usuario);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $dato['resultado'] = "contar";
            $dato['total'] = $result['total'];
            $this->conex->commit();
        } catch (PDOException $e) {
            $this->conex->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }
        $this->Cerrar_Conexion($this->conex, $stm);
        return $dato;
    }

    public function Transaccion($peticion){
        switch($peticion['peticion']){
            case 'registrar':
                return $this->Registrar();
            case 'consultar':
                return $this->Consultar();
            case 'marcar_leido':
                return $this->MarcarLeido();
            case 'marcar_todas':
                return $this->MarcarTodasLeidas();
            case 'contar_nuevas':
                return $this->ContarNuevas();
            default:
                return ["resultado" => "error", "mensaje" => "Operación no válida"];
        }
    }
}
?>