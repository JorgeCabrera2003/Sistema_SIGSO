<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/piso.php";

#[ExpectException(ValueError::class)]

final class PisoTest extends TestCase
{
    private Piso $Tpiso;

    public function setUp(): void
    {
        $this->Tpiso = new Piso();
    }

    public function testRegistrarPiso()
    {

        $this->Tpiso->set_id("SOTAN0012025101912552354");
        $this->Tpiso->set_tipo('Sótano');
        $this->Tpiso->set_nro_piso(2);
        $resultado = $this->Tpiso->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->assertEquals('error', $resultado['resultado']);
            $this->assertEquals('Registro duplicado', $resultado['mensaje']);
        } else {
            $this->fail('Fallo en Registrar Piso');
        }
    }

    public function testConsultarPiso()
    {

        $resultado = $this->Tpiso->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarPiso()
    {

        $this->Tpiso->set_id("SOTAN0012025101912552354");
        $this->Tpiso->set_tipo('Sótano');
        $this->Tpiso->set_nro_piso(3);
        $resultado = $this->Tpiso->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Piso');
        }

    }

    public function testEliminarPiso()
    {
        $this->Tpiso->set_id("SOTAN0012025101912552354");
        $resultado = $this->Tpiso->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Eliminar Piso');
        }
    }
}



?>