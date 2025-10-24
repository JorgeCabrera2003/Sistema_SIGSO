<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/equipo.php";

final class EquipoTest extends TestCase
{
    private Equipo $Tequipo;

    public function setUp(): void
    {
        $this->Tequipo = new Equipo();
    }

    public function testRegistrarEquipo()
    {
        $this->Tequipo->set_id_equipo("EQP0012025101912552354");
        $this->Tequipo->set_tipo_equipo('Laptop');
        $this->Tequipo->set_serial('SN123456789');
        $this->Tequipo->set_codigo_bien('10002201');
        $this->Tequipo->set_id_unidad('SOPOROFI2025100112003079');
        
        $resultado = $this->Tequipo->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == 0) {
            if ($resultado['mensaje'] == "El equipo que intenta registrar ya existe.") {
                $this->assertTrue(true, "No permitir registros duplicados");
            } else {
                $this->assertTrue(false, $resultado['mensaje']);
            }
        } else {
            $this->fail('Fallo en Registrar Equipo');
        }
    }

    public function testConsultarEquipo()
    {
        $resultado = $this->Tequipo->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarEquipo()
    {
        $this->Tequipo->set_id_equipo("EQP0012025101912552354");
        $this->Tequipo->set_tipo_equipo('Laptop Gaming');
        $this->Tequipo->set_serial('SN123456789');
        $this->Tequipo->set_codigo_bien('10002201');
        $this->Tequipo->set_id_unidad('SOPOROFI2025100112003079');
        
        $resultado = $this->Tequipo->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Equipo: ' . $resultado['mensaje']);
        }
    }

    public function testEliminarEquipo()
    {
        $this->Tequipo->set_id_equipo("EQP0012025101912552354");
        $resultado = $this->Tequipo->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == 0) {
            $this->fail('Fallo en Eliminar Equipo: ' . $resultado['mensaje']);
        }
    }

    public function testConsultarHistorialEquipo()
    {
        $this->Tequipo->set_id_equipo("LAPTO6382025101419102138");
        $resultado = $this->Tequipo->Transaccion(['peticion' => 'detalle']);

        $this->assertIsArray($resultado);
        $this->assertEquals('detalle', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testObtenerTipoServicioPorEquipo()
    {
        $resultado = $this->Tequipo->Transaccion([
            'peticion' => 'obtener_tipo_servicio',
            'id_equipo' => "LAPTO6382025101419102138"
        ]);

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertArrayHasKey('id_tipo_servicio', $resultado);
    }
}
?>