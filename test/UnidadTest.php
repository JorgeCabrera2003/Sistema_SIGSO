<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/unidad.php";

#[ExpectException(ValueError::class)]

final class UnidadTest extends TestCase
{
    private Unidad $Tunidad;

    public function setUp(): void
    {
        $this->Tunidad = new Unidad();
    }

    public function testRegistrarUnidad()
    {

        $this->Tunidad->set_id("UNIDAGOB2025201014043321");
        $this->Tunidad->set_nombre('Unidad de Guardia');
        $this->Tunidad->set_id_dependencia('OFITIGOB2025100112004023');

        $resultado = $this->Tunidad->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            if ($resultado['mensaje'] == "Registro duplicado") {
                $this->assertTrue(true, "No permitir registros duplicados");
            } else {
                $this->assertTrue(false, $resultado['mensaje']);
            }
        } else {
            $this->fail('Fallo en Registrar Unidad');
        }
    }

    public function testConsultarUnidad()
    {

        $resultado = $this->Tunidad->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarUnidad()
    {

        $this->Tunidad->set_id("UNIDAGOB2025201014043321");
        $this->Tunidad->set_nombre('Guardia Forestal');
        $this->Tunidad->set_id_dependencia('OFITIGOB2025100112004023');
        $resultado = $this->Tunidad->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Unidad');
        }
    }

    public function testEliminarUnidad()
    {
        $this->Tunidad->set_id("UNIDAGOB2025201014043321");
        $resultado = $this->Tunidad->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Eliminar Unidad');
        }
    }
}



?>