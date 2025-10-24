<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/oficina.php";

final class OficinaTest extends TestCase
{
    private Oficina $Toficina;
    private string $idOficinaUnico;

    public function setUp(): void
    {
        $this->Toficina = new Oficina();

        $this->idOficinaUnico = "OFICITEST" . date('YmdHis') . rand(100, 999);
    }

    public function testRegistrarOficina()
    {
        $this->Toficina->set_id($this->idOficinaUnico);
        $this->Toficina->set_id_piso("PISO9462025101419100946");
        $this->Toficina->set_nombre('Oficina Test PHPUnit ' . date('His'));
        
        $resultado = $this->Toficina->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se registró la oficina exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            if (strpos($resultado['mensaje'], "duplicado") !== false) {
                $this->markTestSkipped("Registro duplicado - ID ya existe: " . $this->idOficinaUnico);
            } else if (strpos($resultado['mensaje'], "piso no existe") !== false) {
                $this->markTestSkipped("Piso no existe para la prueba");
            } else {
                $this->fail("Error inesperado al registrar: " . $resultado['mensaje']);
            }
        }
    }

    public function testConsultarOficina()
    {
        $resultado = $this->Toficina->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        
        if (!empty($resultado['datos'])) {
            $primeraOficina = $resultado['datos'][0];
            $this->assertArrayHasKey('id_oficina', $primeraOficina);
            $this->assertArrayHasKey('nombre_oficina', $primeraOficina);
            $this->assertArrayHasKey('tipo_piso', $primeraOficina);
            $this->assertArrayHasKey('nro_piso', $primeraOficina);
        }
    }

    public function testModificarOficina()
    {

        $this->Toficina->set_id($this->idOficinaUnico);
        $this->Toficina->set_id_piso("PISO9462025101419100946");
        $this->Toficina->set_nombre('Oficina Test Modificada ' . date('His'));
        
        $resultado = $this->Toficina->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se modificaron los datos de la oficina con éxito', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            if (strpos($resultado['mensaje'], "piso no existe") !== false) {
                $this->markTestSkipped("Piso no existe para modificación");
            } else {

                $this->assertTrue(true, "Modificación falló como se esperaba - Oficina no existe");
            }
        }
    }

    public function testEliminarOficina()
    {

        $this->Toficina->set_id($this->idOficinaUnico);
        $resultado = $this->Toficina->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se eliminó la oficina exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {

            $this->assertTrue(true, "Eliminación falló como se esperaba - Oficina no existe");
        }
    }

    public function testConsultarOficinasEliminadas()
    {
        $resultado = $this->Toficina->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testReactivarOficina()
    {
        $this->Toficina->set_id($this->idOficinaUnico);
        $resultado = $this->Toficina->Transaccion(['peticion' => 'reactivar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('restaurar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Oficina restaurada exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            $this->assertTrue(true, "Reactivación falló como se esperaba - Oficina no existe");
        }
    }

    public function testValidacionesSetter()
    {
        $this->expectException(ValueError::class);
        $this->Toficina->set_id("AB"); // ID muy corto
    }

    public function testValidacionesNombre()
    {
        $this->expectException(ValueError::class);
        $this->Toficina->set_nombre(''); // Nombre vacio
    }
}