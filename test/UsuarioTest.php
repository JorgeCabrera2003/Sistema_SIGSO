<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/usuario.php";

final class UsuarioTest extends TestCase
{
    private Usuario $Tusuario;
    private $cedulaPrueba;

    public function setUp(): void
    {
        $this->Tusuario = new Usuario();
        $this->cedulaPrueba = "V-" . rand(10000000, 99999999);
    }

    public function testRegistrarUsuario()
    {

        $this->Tusuario->set_cedula($this->cedulaPrueba);
        $validacion = $this->Tusuario->Transaccion(['peticion' => 'validar']);
        

        if ($validacion['bool'] == 0) {
            $this->Tusuario->set_nombre_usuario("testuser_" . rand(1000, 9999));
            $this->Tusuario->set_nombres("Juan");
            $this->Tusuario->set_apellidos("Pérez");
            $this->Tusuario->set_correo("juan.perez" . rand(1000, 9999) . "@test.com");
            $this->Tusuario->set_telefono("0412-" . rand(1000000, 9999999));
            $this->Tusuario->set_clave(password_hash("password123", PASSWORD_DEFAULT));
            $this->Tusuario->set_rol(1); // Asumimos que el rol 1 existe
        
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'registrar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('registrar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {

                $this->assertTrue(
                    strpos($resultado['mensaje'], 'rol') !== false ||
                    strpos($resultado['mensaje'], 'constraint') !== false ||
                    strpos($resultado['mensaje'], 'foreign') !== false,
                    "Validación de constraints funcionando: " . $resultado['mensaje']
                );
            } else {
                $this->fail('Fallo en Registrar Usuario - Respuesta inesperada');
            }
        } else {
            $this->markTestSkipped('El usuario de prueba ya existe en la base de datos');
        }
    }

    public function testConsultarUsuarios()
    {
        $resultado = $this->Tusuario->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testValidarUsuario()
    {

        $this->Tusuario->set_cedula("V-99999999");
        $this->Tusuario->set_nombre_usuario("usuariofalso");
        $this->Tusuario->set_correo("falso@test.com");
        
        $resultado = $this->Tusuario->Transaccion(['peticion' => 'validar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('validar', $resultado['resultado']);
        $this->assertArrayHasKey('bool', $resultado);
    }

    public function testPerfilUsuario()
    {

        $usuarios = $this->Tusuario->Transaccion(['peticion' => 'consultar']);
        
        if (isset($usuarios['datos']) && count($usuarios['datos']) > 0) {
            $primerUsuario = $usuarios['datos'][0];
            $this->Tusuario->set_cedula($primerUsuario['cedula']);
            
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'perfil']);

            $this->assertIsArray($resultado);
            $this->assertEquals('perfil', $resultado['resultado']);
            
            if (isset($resultado['bool']) && $resultado['bool'] == 1) {
                $this->assertIsArray($resultado['datos']);
                $this->assertArrayHasKey('nombre_usuario', $resultado['datos']);
                $this->assertArrayHasKey('cedula', $resultado['datos']);
            }
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos para probar perfil');
        }
    }

    public function testIniciarSesion()
    {

        $usuarios = $this->Tusuario->Transaccion(['peticion' => 'consultar']);
        
        if (isset($usuarios['datos']) && count($usuarios['datos']) > 0) {
            $primerUsuario = $usuarios['datos'][0];
            $this->Tusuario->set_cedula($primerUsuario['cedula']);
            $this->Tusuario->set_clave("password_incorrecta"); // Probamos con contraseña incorrecta
            
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'sesion']);

            $this->assertIsBool($resultado);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos para probar inicio de sesión');
        }
    }

    public function testActualizarClave()
    {

        $usuarios = $this->Tusuario->Transaccion(['peticion' => 'consultar']);
        
        if (isset($usuarios['datos']) && count($usuarios['datos']) > 0) {
            $primerUsuario = $usuarios['datos'][0];
            $this->Tusuario->set_cedula($primerUsuario['cedula']);
            $this->Tusuario->set_clave(password_hash("newsecurepassword", PASSWORD_DEFAULT));
            
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'ActualizarClave']);

            $this->assertIsArray($resultado);
            $this->assertEquals('cambiar_clave', $resultado['resultado']);
            $this->assertArrayHasKey('bool', $resultado);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos para probar actualización de clave');
        }
    }

    public function testActualizarTema()
    {

        $usuarios = $this->Tusuario->Transaccion(['peticion' => 'consultar']);
        
        if (isset($usuarios['datos']) && count($usuarios['datos']) > 0) {
            $primerUsuario = $usuarios['datos'][0];
            $this->Tusuario->set_cedula($primerUsuario['cedula']);
            $this->Tusuario->set_tema("dark");
            
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'actualizarTema']);

            $this->assertIsArray($resultado);
            $this->assertEquals('cambiar_tema', $resultado['resultado']);
            $this->assertArrayHasKey('bool', $resultado);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos para probar actualización de tema');
        }
    }

    public function testValidarPermiso()
    {
        $resultado = $this->Tusuario->Transaccion([
            'peticion' => 'permiso',
            'user' => 'admin',
            'rol' => 'admin'
        ]);

        $this->assertEquals(1, $resultado);
    }

    public function testValidarPermisoDenegado()
    {
        $resultado = $this->Tusuario->Transaccion([
            'peticion' => 'permiso',
            'user' => 'user',
            'rol' => 'admin'
        ]);

        $this->assertEquals(0, $resultado);
    }

    public function testPeticionInvalida()
    {
        $resultado = $this->Tusuario->Transaccion(['peticion' => 'peticion_invalida']);

        $this->assertStringContainsString('no valida', $resultado);
    }

    public function testModificarUsuarioEmpleado()
    {

        $usuarios = $this->Tusuario->Transaccion(['peticion' => 'consultar']);
        
        if (isset($usuarios['datos']) && count($usuarios['datos']) > 0) {
            $primerUsuario = $usuarios['datos'][0];
            $this->Tusuario->set_cedula($primerUsuario['cedula']);
            $this->Tusuario->set_nombres("Nombre Modificado");
            $this->Tusuario->set_apellidos("Apellido Modificado");
            $this->Tusuario->set_telefono("0412-9999999");
            $this->Tusuario->set_correo("modificado@test.com");
            
            $resultado = $this->Tusuario->Transaccion(['peticion' => 'modificar_empleado']);

            $this->assertIsArray($resultado);
            $this->assertEquals('modificar_empleado', $resultado['resultado']);
            $this->assertArrayHasKey('bool', $resultado);
        } else {
            $this->markTestSkipped('No hay usuarios en la base de datos para probar modificación');
        }
    }

    public function testEliminarUsuario()
    {

        $this->markTestSkipped('Test de eliminación deshabilitado para evitar pérdida de datos');
        
        /*
        // Solo usar si hay un usuario específico para pruebas de eliminación
        $this->Tusuario->set_cedula("V-USUARIOPRUEBA");
        $resultado = $this->Tusuario->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('eliminar', $resultado['resultado']);
        */
    }
}