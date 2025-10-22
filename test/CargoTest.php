<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/cargo.php";

#[ExpectException(ValueError::class)]

final class CargoTest extends TestCase
{
    private Cargo $Tcargo;

    public function setUp(): void
    {
        $this->Tcargo = new Cargo();
    }

    public function testRegistrarCargo()
    {

        $this->Tcargo->set_id("INFOR0312025201014043321");
        $this->Tcargo->set_nombre('Informante');
        $resultado = $this->Tcargo->Transaccion(['peticion' => 'registrar']);

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
            $this->fail('Fallo en Registrar Cargo');
        }
    }

    public function testConsultarCargo()
    {

        $resultado = $this->Tcargo->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarCargo()
    {

        $this->Tcargo->set_id("INFOR0312025201014043321");
        $this->Tcargo->set_nombre('Informador');
        $resultado = $this->Tcargo->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Cargo');
        }
    }

    public function testEliminarCargo()
    {
        $this->Tcargo->set_id("INFOR0312025201014043321");
        $resultado = $this->Tcargo->Transaccion(['peticion' => 'eliminar']);

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