<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/ente.php";

#[ExpectException(ValueError::class)]

final class EnteTest extends TestCase
{
    private Ente $Tente;

    public function setUp(): void
    {
        $this->Tente = new Ente();
    }

    public function testRegistrarEnte()
    {

        $this->Tente->set_id("PARQU0122025201014043321");
        $this->Tente->set_nombre('Parque del Este');
        $this->Tente->set_telefono('0424-9883212');
        $this->Tente->set_direccion('Avenida Los Horcones');
        $this->Tente->set_responsable('Antonia de Gil');
        $resultado = $this->Tente->Transaccion(['peticion' => 'registrar']);

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
            $this->fail('Fallo en Registrar Ente');
        }
    }

    public function testConsultarEnte()
    {

        $resultado = $this->Tente->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarEnte()
    {

        $this->Tente->set_id("PARQU0122025201014043321");
        $this->Tente->set_nombre('Parque del Oeste');
        $this->Tente->set_telefono('0424-9883212');
        $this->Tente->set_direccion('Avenida Los Horcones');
        $this->Tente->set_responsable('Antonia de Gil');
        $resultado = $this->Tente->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            if ($resultado['mensaje'] == "Ya hay un piso con este mismo número") {
                $this->assertTrue(true, "No permitir pisos repetidos");
            } else {
                $this->fail('Fallo en Modificar Ente');
            }
        }
    }

    public function testEliminarEnte()
    {
        $this->Tente->set_id("PARQU0122025201014043321");
        $resultado = $this->Tente->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Eliminar Ente');
        }
    }
}



?>