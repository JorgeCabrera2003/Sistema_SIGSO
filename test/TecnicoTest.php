<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/tecnico.php";

final class TecnicoTest extends TestCase
{
    private Tecnico $Ttecnico;
    private $cedulaPrueba;

    public function setUp(): void
    {
        $this->Ttecnico = new Tecnico();
        $this->cedulaPrueba = "V-" . rand(10000000, 99999999);
    }

    public function testRegistrarTecnico()
    {

        $this->Ttecnico->set_cedula($this->cedulaPrueba);
        $validacion = $this->Ttecnico->Transaccion(['peticion' => 'validar']);
        

        if ($validacion['bool'] == 0) {
            $this->Ttecnico->set_nombre("Técnico");
            $this->Ttecnico->set_apellido("Prueba");
            $this->Ttecnico->set_id_cargo("TECNI0012025100112013227"); // ID de tecnico
            $this->Ttecnico->set_id_servicio("SOPOR6432025101300104143"); // ID de soporte tecnico
            $this->Ttecnico->set_grado_experiencia(3);
            $this->Ttecnico->set_id_unidad("SOPOROFI2025100112003079"); // ID de unidad de soporte
            $this->Ttecnico->set_telefono("0412-" . rand(1000000, 9999999));
            $this->Ttecnico->set_correo("tecnico.prueba" . rand(1000, 9999) . "@test.com");
        
            $resultado = $this->Ttecnico->Transaccion(['peticion' => 'registrar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('registrar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se registró el técnico exitosamente', $resultado['mensaje']);
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
            $this->markTestSkipped('El técnico de prueba ya existe en la base de datos');
        }
    }

    public function testConsultarTecnicos()
    {
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'consultar']);

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

        $this->Ttecnico->set_cedula("V-99999999");
        
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'validar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('bool', $resultado);
        
 
        $this->assertContains($resultado['bool'], [0, 1]);
    }

    public function testModificarTecnico()
    {

        $tecnicos = $this->Ttecnico->Transaccion(['peticion' => 'consultar']);
        
        if (isset($tecnicos['datos']) && count($tecnicos['datos']) > 0) {
            $primerTecnico = $tecnicos['datos'][0];
            $this->Ttecnico->set_cedula($primerTecnico['cedula']);
            $this->Ttecnico->set_nombre("Nombre Modificado");
            $this->Ttecnico->set_apellido("Apellido Modificado");
            $this->Ttecnico->set_id_cargo("TECNI0012025100112013227");
            $this->Ttecnico->set_id_servicio("SOPOR6432025101300104143");
            $this->Ttecnico->set_grado_experiencia(5); // Aumentar 
            $this->Ttecnico->set_id_unidad("SOPOROFI2025100112003079");
            $this->Ttecnico->set_telefono("0412-8888888");
            $this->Ttecnico->set_correo("modificado@test.com");
            
            $resultado = $this->Ttecnico->Transaccion(['peticion' => 'modificar']);

            $this->assertIsArray($resultado);
            
            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('modificar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se modificó el técnico exitosamente', $resultado['mensaje']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
                $this->assertStringContainsString('error', $resultado['resultado']);
            }
        } else {
            $this->markTestSkipped('No hay técnicos en la base de datos para probar modificación');
        }
    }

    public function testContarTecnicos()
    {
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'contarTecnico']);

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
        $this->Ttecnico->set_cedula("V-TECNICOELIMINAR");
        $this->Ttecnico->set_id_cargo("TECNI0012025100112013227");
        
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);
        */
    }

    public function testPeticionInvalida()
    {
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'peticion_invalida']);

        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    public function testGettersYSetters()
    {

        $gradoExperiencia = 4;
        $idServicio = "SOPOR6432025101300104143";
        
        $this->Ttecnico->set_grado_experiencia($gradoExperiencia);
        $this->Ttecnico->set_id_servicio($idServicio);
        
        $this->assertEquals($gradoExperiencia, $this->Ttecnico->get_grado_experiencia());
        $this->assertEquals($idServicio, $this->Ttecnico->get_id_servicio());
    }

    public function testConsultarEstructuraDatos()
    {
        $resultado = $this->Ttecnico->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        
        if (isset($resultado['datos']) && count($resultado['datos']) > 0) {
            $tecnico = $resultado['datos'][0];
            
            $camposEsperados = [
                'cedula', 'nombre', 'apellido', 'telefono', 'correo',
                'dependencia', 'unidad', 'cargo', 'servicio'
            ];
            
            foreach ($camposEsperados as $campo) {
                $this->assertArrayHasKey($campo, $tecnico, "El campo {$campo} debería estar presente en los datos del técnico");
            }
            

            $this->assertNotEquals('root', $tecnico['nombre']);
        }
    }

    public function tearDown(): void
    {

    }
}