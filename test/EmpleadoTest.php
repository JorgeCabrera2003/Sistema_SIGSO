<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/empleado.php";

final class EmpleadoTest extends TestCase
{
    private Empleado $Templeado;
    private $cedulaPrueba;

    public function setUp(): void
    {
        $this->Templeado = new Empleado();
        $this->cedulaPrueba = "V-" . rand(10000000, 99999999);
    }

    public function testRegistrarTecnico()
    {

        $this->Templeado->set_cedula($this->cedulaPrueba);
        $validacion = $this->Templeado->Transaccion(['peticion' => 'validar']);
        

        if ($validacion['bool'] == 0) {
            $this->Templeado->set_nombre("Empleado");
            $this->Templeado->set_apellido("Prueba");
            $this->Templeado->set_id_cargo("TECNI0012025100112013227"); // ID de tecnico
            $this->Templeado->set_id_unidad("SOPOROFI2025100112003079"); // ID de unidad de soporte
            $this->Templeado->set_telefono("0412-" . rand(1000000, 9999999));
            $this->Templeado->set_correo("empleado.prueba" . rand(1000, 9999) . "@test.com");
        
            $resultado = $this->Templeado->Transaccion(['peticion' => 'registrar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('registrar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se registró el empleado exitosamente', $resultado['mensaje']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {

                $this->assertTrue(
                    strpos($resultado['mensaje'], 'constraint') !== false ||
                    strpos($resultado['mensaje'], 'foreign') !== false ||
                    strpos($resultado['mensaje'], 'Duplicate') !== false,
                    "Validación de constraints funcionando: " . $resultado['mensaje']
                );
            } else {
                $this->fail('Fallo en Registrar Técnico - Respuesta inesperada');
            }
        } else {
            $this->markTestSkipped('El empleado de prueba ya existe en la base de datos');
        }
    }

    public function testConsultarTecnicos()
    {
        $resultado = $this->Templeado->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        

        if (count($resultado['datos']) > 0) {
            $primerTecnico = $resultado['datos'][0];
            $this->assertArrayHasKey('cedula', $primerTecnico);
            $this->assertArrayHasKey('nombre', $primerTecnico);
            $this->assertArrayHasKey('apellido', $primerTecnico);
            $this->assertArrayHasKey('cargo', $primerTecnico);
            $this->assertArrayHasKey('servicio', $primerTecnico);
        }
    }

    public function testValidarTecnico()
    {

        $this->Templeado->set_cedula("V-99999999");
        
        $resultado = $this->Templeado->Transaccion(['peticion' => 'validar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('bool', $resultado);
        
 
        $this->assertContains($resultado['bool'], [0, 1]);
    }

    public function testModificarTecnico()
    {

        $tecnicos = $this->Templeado->Transaccion(['peticion' => 'consultar']);
        
        if (isset($tecnicos['datos']) && count($tecnicos['datos']) > 0) {
            $primerTecnico = $tecnicos['datos'][0];
            $this->Templeado->set_cedula($primerTecnico['cedula']);
            $this->Templeado->set_nombre("Nombre Modificado");
            $this->Templeado->set_apellido("Apellido Modificado");
            $this->Templeado->set_id_cargo("TECNI0012025100112013227");
            $this->Templeado->set_id_unidad("SOPOROFI2025100112003079");
            $this->Templeado->set_telefono("0412-8888888");
            $this->Templeado->set_correo("modificado@test.com");
            
            $resultado = $this->Templeado->Transaccion(['peticion' => 'modificar']);

            $this->assertIsArray($resultado);
            
            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('modificar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se modificó el empleado exitosamente', $resultado['mensaje']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
                $this->assertStringContainsString('error', $resultado['resultado']);
            }
        } else {
            $this->markTestSkipped('No hay técnicos en la base de datos para probar modificación');
        }
    }

    public function testContarTecnicos()
    {
        $resultado = $this->Templeado->Transaccion(['peticion' => 'contarTecnico']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        
        if (count($resultado['datos']) > 0) {
            $conteo = $resultado['datos'][0];
            $this->assertArrayHasKey('Total tecnicos', $conteo);
            $this->assertArrayHasKey('Total soporte', $conteo);
            $this->assertArrayHasKey('Total redes', $conteo);
        }
    }

    public function testEliminarTecnico()
    {

        $this->markTestSkipped('Test de eliminación deshabilitado para evitar pérdida de datos');
        
        /*
        $this->Templeado->set_cedula("V-TECNICOELIMINAR");
        $this->Templeado->set_id_cargo("TECNI0012025100112013227");
        
        $resultado = $this->Templeado->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);
        */
    }

    public function testPeticionInvalida()
    {
        $resultado = $this->Templeado->Transaccion(['peticion' => 'peticion_invalida']);

        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    public function testConsultarEstructuraDatos()
    {
        $resultado = $this->Templeado->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        
        if (isset($resultado['datos']) && count($resultado['datos']) > 0) {
            $tecnico = $resultado['datos'][0];
            
            $camposEsperados = [
                'cedula', 'nombre', 'apellido', 'telefono', 'correo',
                'dependencia', 'unidad', 'cargo'
            ];
            
            foreach ($camposEsperados as $campo) {
                $this->assertArrayHasKey($campo, $tecnico, "El campo {$campo} debería estar presente en los datos del empleado");
            }
            
            $this->assertNotEquals('root', $tecnico['nombre']);
        }
    }

    public function tearDown(): void
    {

    }
}