<?php
require_once('model/conexion.php');

class recuperar extends Conexion {

    private $conex;
    
    private $cedula;
    private $nombre;
    private $apellido;
    private $nombre_usuario;
    private $correo;
    private $clave;
    
    
    public function __construct() {

        $this->cedula = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->nombre_usuario = "";
        $this->correo = "";
        $this->clave = "";

    }

    public function get_cedula() {
        return $this->cedula;
    }
    public function set_cedula($cedula) {
        $this->cedula = $cedula;
    }

    public function get_nombre() {
        return $this->nombre;
    }
    public function set_nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function get_apellido() {
        return $this->apellido;
    }
    public function set_apellido($apellido) {
        $this->apellido = $apellido;
    }

    public function get_nombre_usuario() {
        return $this->nombre_usuario;
    }
    public function set_nombre_usuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function get_correo() {
        return $this->correo;
    }
    public function set_correo($correo) {
        $this->correo = $correo;
    }

    public function get_clave() {
        return $this->clave;
    }
    public function set_clave($clave) {
        $this->clave = $clave;
    }

    private function Consultar_Usuario() {

        $dato = [];

        try {

            $this->conex = new Conexion("usuario");
            $this->conex = $this->conex->Conex();
            $this->conex->beginTransaction();

            $query = "SELECT * FROM usuario WHERE cedula = :cedula AND estatus = '1'";

            $stm = $this->conex->prepare($query);
            $stm->bindParam(":cedula", $this->cedula);
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

    private function Actualizar_clave() {

        $this->conex = new Conexion("usuario");
        $this->conex = $this->conex->Conex();
       
            try {

                $this->conex->beginTransaction();

                $query = "UPDATE usuario SET clave= :clave WHERE cedula = :cedula";

                $stm = $this->conex->prepare($query);
                $stm->bindParam(":clave", $this->clave);
                $stm->bindParam(":cedula", $this->cedula);
                $stm->execute();

                $this->conex->commit();

                $dato['resultado'] = "modificar";
                $dato['estado'] = 1;
                $dato['mensaje'] = "Se cambio la Contraseña Exitosamente";

            } catch (PDOException $e) {
                $this->conex->rollBack();
                $dato['estado'] = -1;
                $dato['resultado'] = "error";
                $dato['mensaje'] = $e->getMessage();

            }

        $this->Cerrar_Conexion($this->conex, $stm);

        return $dato;
    }

    private function EnviarCorreo($correo, $nombre, $codigo) {

        require_once __DIR__ . "/../vendor/autoload.php";
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        $mail->addEmbeddedImage(__DIR__ . '/../assets/img/OFITIC.png', 'logo_ofitic');


        $mail_body = '
            <body style="margin: 0; font-family: Arial, sans-serif; background-color: #e0e0e0; color: #333;">
            <!-- Franja superior -->
            <div style="background-color: #7c1d21; padding: 15px;">
                <div style="text-align: right;">
                 <img style="width: 30%;" class="img-logo" src="cid:logo_ofitic">
                </div>
            </div>

            <!-- Contenido principal -->
            <div style="padding: 20px;">
                <p style="margin-top: 0;">Hola, <strong>'.$nombre.'</strong>:</p>

                <p>Has solicitado un código para cambiar tu contraseña en el sistema de <strong>SIGSO</strong>.</p>

                <p>Tu código de verificación es:</p>

                <p style="font-size: 24px; font-weight: bold; color:rgb(0, 0, 0);">'.$codigo.'</p>

                <p>Ingresa este código en el formulario para continuar con el proceso.</p>

                <p>Si no solicitaste este cambio, puedes ignorar este mensaje.<br>
                Por favor, no respondas a este correo.</p>

                <p>Saludos,<br><br>
                <strong>Sistema SIGSO - OFITIC</strong></p>
            </div>

            </body>

        ';

        try {

            $mail->SMTPOptions = ['socket' => ['bindto' => '0.0.0.0:0']];
            $mail->Timeout = 30;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sigso.sistema@gmail.com';
            $mail->Password = 'nxmg qbxg ecfb vnrm';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('sigso.sistema@gmail.com', 'OFITIC');
            $mail->addAddress($correo, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Código para Cambiar Contraseña';
            $mail->Body    = $mail_body;

            $mail->send();
            return ['estado' => 1, 'mensaje' => 'Código enviado correctamente'];

        } catch (Exception $e) {

            return ['estado' => 0, 'mensaje' => 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo];

        }
    }


    public function Transaccion($peticion) {

        switch ($peticion['peticion']) {

            case 'consultar':
                return $this->Consultar_Usuario();

            case 'actualizar':
                return $this->Actualizar_clave();

            case 'enviar_correo':
                return $this->EnviarCorreo($peticion['correo'], $peticion['nombre'], $peticion['codigo']);

            default:
                return "Operacion: " . $peticion['peticion'] . " no valida";

        }

    }
}
?>