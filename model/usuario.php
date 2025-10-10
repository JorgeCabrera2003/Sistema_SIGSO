<?php
require_once('model/conexion.php');

class Usuario extends Conexion
{

    private $cedula;
    private $nombre_usuario;
    private $nombres;
    private $apellidos;
    private $correo;
    private $telefono;
    private $clave;
    private $tipo;
    private $rol;
    private $foto;
    private $tema;
    private $conexion;

    public function __construct()
    {
        $this->cedula = "";
        $this->nombre_usuario = NULL;
        $this->nombres = "";
        $this->apellidos = "";
        $this->correo = NULL;
        $this->telefono = "";
        $this->clave = "";
        $this->tipo = "";
        $this->rol = NULL;
        $this->foto = "";
        $this->tema = "";
        $this->conexion = NULL;
    }

    public function set_cedula($cedula)
    {
        $this->cedula = $cedula;
    }
    public function set_nombre_usuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function set_nombres($nombres)
    {
        $this->nombres = $nombres;
    }

    public function set_apellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function set_tipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function set_clave($stmtrase)
    {
        $this->clave = $stmtrase;
    }

    public function set_rol($rol)
    {
        $this->rol = $rol;
    }

    public function get_nombre_usuario()
    {
        return $this->nombre_usuario;
    }

    public function get_nombres()
    {
        return $this->nombres;
    }

    public function get_apellidos()
    {
        return $this->apellidos;
    }

    public function set_foto($foto)
    {
        $this->foto = $foto;
    }

    public function get_foto()
    {
        return $this->foto;
    }
    public function set_correo($correo)
    {
        $this->correo = $correo;
    }
    public function get_correo()
    {
        return $this->correo;
    }
    public function set_telefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function get_telefono()
    {
        return $this->telefono;
    }

    public function set_tema($tema)
    {
        $this->tema = $tema;
    }
    public function get_tema()
    {
        return $this->tema;
    }

    private function ValidarPermiso($usuario, $permitidos)
    {

        if ($permitidos == $usuario) {
            $bool = 1;
        } else {
            $bool = 0;
        }
        return $bool;
    }

    private function Registrar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "INSERT INTO usuario(nombre_usuario, cedula, id_rol, nombres, apellidos, telefono, correo, clave)
            VALUES (:nombre_usuario, :cedula, :rol, :nombres, :apellidos, :telefono, :correo, :clave)";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':nombre_usuario', $this->nombre_usuario);
            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':rol', $this->rol);
            $stm->bindParam(':nombres', $this->nombres);
            $stm->bindParam(':apellidos', $this->apellidos);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);
            $stm->bindParam(':clave', $this->clave);

            $stm->execute();
            $this->conexion->commit();
            $dato['resultado'] = "registrar";
            $dato['mensaje'] = "Se registro un usuario exitosamente";
            $dato['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ModificarUsuario($cambioClave = false)
    {
        $dato = [];
        try {

            if ($cambioClave) {
                $string_clave = "clave = :clave";
            } else {
                $string_clave = "";
            }
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "UPDATE usuario SET 
                nombre_usuario = nombre_usuario, id_rol = :rol,
                nombres = :nombres, apellidos = :apellidos, telefono = :telefono,
                correo = :correo, " . $string_clave . "
                WHERE nombre_usuario = :nombre_usuario OR cedula = :cedula";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':nombre_usuario', $this->nombre_usuario);
            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':rol', $this->rol);
            $stm->bindParam(':nombres', $this->nombres);
            $stm->bindParam(':apellidos', $this->apellidos);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);
            if ($cambioClave) {
                $stm->bindParam(':clave', $this->clave);
            }


            $stm->execute();
            $this->conexion->commit();
            if ($stm->rowCount() > 0) {
                $dato['mensaje'] = "Se modificó el usuario exitosamente";
                $dato['bool'] = 1;
            } else {
                $dato['mensaje'] = "Error al modificar Usuario";
                $dato['bool'] = 0;
            }
            $dato['resultado'] = "modificar";
            $dato['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ModificarUsuario_Empleado()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "UPDATE usuario SET
                nombres = :nombres, apellidos = :apellidos, telefono = :telefono,
                correo = :correo WHERE cedula = :cedula";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':cedula', $this->cedula);
            $stm->bindParam(':nombres', $this->nombres);
            $stm->bindParam(':apellidos', $this->apellidos);
            $stm->bindParam(':telefono', $this->telefono);
            $stm->bindParam(':correo', $this->correo);

            $stm->execute();
            $this->conexion->commit();
            if ($stm->rowCount() > 0) {
                $dato['mensaje'] = "Se modificó el usuario exitosamente";
                $dato['bool'] = 1;
            } else {
                $dato['mensaje'] = "Error al modificar Usuario";
                $dato['bool'] = 0;
            }
            $dato['resultado'] = "modificar_empleado";
            $dato['estado'] = 1;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }
    private function Validar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT * FROM usuario WHERE cedula = :cedula
            OR nombre_usuario = :nombre_usuario OR correo = :correo";
            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':correo', $this->correo);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->bindParam(':nombre_usuario', $this->nombre_usuario);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['datos'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['bool'] = -1;
                $dato['datos'] = NULL;
                $dato['bool'] = 0;
            }
            $dato['resultado'] = "validar";
            $dato['estado'] = 1;
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "registrar";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }

        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function BuscarUsuario_Permiso($parametro = NULL)
    {
        if (isset($parametro['modulo']) && isset($parametro['accion'])) {

            $dato = [];
            try {
                $this->conexion = new Conexion("usuario");
                $this->conexion = $this->conexion->Conex();
                $this->conexion->beginTransaction();
                $stm = $this->conexion->prepare("SELECT * FROM usuario u
                INNER JOIN rol r ON r.id_rol = u.id_rol
                INNER JOIN permiso p ON p.id_rol = r.id_rol
                INNER JOIN modulo m ON m.id_modulo = p.id_modulo
                WHERE m.id_modulo = :id_modulo AND p.accion_permiso = :accion AND p.estado = 1");

                $stm->bindParam(":id_modulo", $parametro['modulo']);
                $stm->bindParam(':accion', $parametro['accion']);
                $stm->execute();

                if ($stm->rowCount() > 0) {
                    $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
                    $dato['bool'] = 1;
                } else {
                    $dato['bool'] = -1;
                    $dato['datos'] = NULL;
                    $dato['bool'] = 0;
                }
                $dato['resultado'] = "validar";
                $this->conexion->commit();
            } catch (PDOException $e) {
                $this->conexion->rollBack();
                $dato['resultado'] = "error";
                $dato['datos'] = NULL;
                $dato['mensaje'] = $e->getMessage();
                $dato['estado'] = -1;
            }
        } else {
            $dato['resultado'] = "error";
            $dato['mensaje'] = 'Parámetros no válidos';
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function IniciarSesion()
    {
        $dato = [];
        $exist = $this->Validar();

        if ($exist['bool'] == 1) {
            if (password_verify($this->clave, $exist['datos']['clave'])) {
                $dato = true;
            } else {
                $dato = false;
            }
        } else {
            $dato = false;
        }
        return $dato;
    }

    private function PerfilUsuario()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $query = "SELECT
                usuario.nombre_usuario,
                usuario.cedula,
                usuario.nombres,
                usuario.apellidos,
                usuario.id_rol,
                rol.nombre_rol AS rol,
                usuario.telefono,
                usuario.correo,
                usuario.clave,
                usuario.foto,
                usuario.tema
                FROM usuario
                INNER JOIN rol ON usuario.id_rol = rol.id_rol
                WHERE usuario.cedula = :cedula";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(':cedula', $this->cedula);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['datos'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['datos'] = NULL;
                $dato['bool'] = 0;
            }
            $dato['resultado'] = "perfil";
            $dato['estado'] = 1;
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function Eliminar()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $this->conexion->beginTransaction();
            $stm = $this->conexion->prepare("UPDATE usuario SET estatus = 0  WHERE cedula = :cedula");
            $stm->bindParam(":cedula", $this->cedula);
            $stm->execute();

            if ($stm->rowCount() > 0) {
                $dato['datos'] = $stm->fetch(PDO::FETCH_ASSOC);
                $dato['bool'] = 1;
            } else {
                $dato['datos'] = NULL;
                $dato['bool'] = 0;
            }
            $dato['resultado'] = "eliminar";
            $dato['mensaje'] = "Se eliminó un usuario exitosamente";
            $dato['estado'] = 1;
            $this->conexion->commit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
            $dato['estado'] = -1;
        }
        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ActualizarClave()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $query = "UPDATE usuario SET clave= :clave WHERE cedula = :cedula";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->bindParam(":clave", $this->clave);
            $stm->execute();
            if ($stm->rowCount()) {
                $dato['bool'] = true;
            } else {
                $dato['bool'] = false;
            }
            $dato['resultado'] = "cambiar_clave";
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ConsultaUsuarios()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $query = "SELECT
            usuario.nombre_usuario,
                usuario.cedula,
                usuario.nombres,
                usuario.apellidos,
                usuario.telefono,
                usuario.correo,
                usuario.foto,
                usuario.tema,
                rol.nombre_rol as rol
            FROM usuario
            INNER JOIN rol ON usuario.id_rol = rol.id_rol
            WHERE usuario.estatus = 1
            ORDER BY usuario.cedula = :cedula
            ";

            $stm = $this->conexion->prepare($query);
            $stm->bindValue(':cedula', $this->cedula);
            $stm->execute();

            $dato['resultado'] = "consultar";
            $dato['datos'] = $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stm);

        return $dato;
    }

    private function ActualizarFoto()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $query = "UPDATE usuario SET foto=? WHERE cedula = ?";

            $stm = $this->conexion->prepare($query);

            if ($stm->execute([$this->foto, $this->cedula])) {
                $dato['estado'] = true;
            } else {
                $dato['estado'] = false;
            }
            $dato['resultado'] = "cambiar_foto";
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    private function ActualizarTema()
    {
        $dato = [];
        try {
            $this->conexion = new Conexion("usuario");
            $this->conexion = $this->conexion->Conex();
            $query = "UPDATE usuario SET tema = :tema WHERE cedula = :cedula";

            $stm = $this->conexion->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
            $stm->bindParam(":tema", $this->tema);
            $stm->execute();

            if ($stm->rowCount()) {
                $dato['bool'] = true;
            } else {
                $dato['bool'] = false;
            }
            $dato['resultado'] = "cambiar_tema";
        } catch (PDOException $e) {
            $dato['resultado'] = "error";
            $dato['mensaje'] = $e->getMessage();
        }

        $this->Cerrar_Conexion($this->conexion, $stm);
        return $dato;
    }

    public function Transaccion($peticion)
    {

        switch ($peticion['peticion']) {
            case 'registrar':

                return $this->Registrar();

            case 'consultar':

                return $this->ConsultaUsuarios();

            case 'modificar':

                return $this->ModificarUsuario($peticion["clave_bool"]);

            case 'modificar_empleado':

                return $this->ModificarUsuario_Empleado();

            case 'eliminar':

                return $this->Eliminar();

            case 'sesion':

                return $this->IniciarSesion();

            case 'validar':

                return $this->Validar();

            case 'usuario_permiso':

                return $this->BuscarUsuario_Permiso($peticion['parametro']);

            case 'perfil':

                return $this->PerfilUsuario();

            case 'ActualizarClave':

                return $this->ActualizarClave();

            case 'actualizarFoto':

                return $this->ActualizarFoto();

            case 'permiso':
                return $this->ValidarPermiso($peticion['user'], $peticion['rol']);

            case 'actualizarTema':
                return $this->ActualizarTema();

            default:
                return "error " . $peticion['peticion'] . " no valida";
        }
    }
}
