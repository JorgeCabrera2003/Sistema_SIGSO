<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/marca.php";

final class MarcaTest extends TestCase
{
    private Marca $Tmarca;

    public function setUp(): void
    {
        $this->Tmarca = new Marca();
    }

    public function testRegistrarMarca()
    {
        $this->Tmarca->set_id("TEST0012025101912552354");
        $this->Tmarca->set_nombre('Marca Test PHPUnit');
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se registró la marca exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            if ($resultado['mensaje'] == "Registro duplicado") {
                $this->assertTrue(true, "No permitir registros duplicados");
            } else {
                $this->assertTrue(false, $resultado['mensaje']);
            }
        } else {
            $this->fail('Fallo en Registrar Marca');
        }
    }

    public function testConsultarMarca()
    {
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        $this->assertNotEmpty($resultado['datos'], 'Debe haber marcas registradas');
    }

    public function testModificarMarca()
    {
        $this->Tmarca->set_id("TEST0012025101912552354");
        $this->Tmarca->set_nombre('Marca Test Modificada');
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se modificaron los datos de la marca con éxito', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Marca: ' . $resultado['mensaje']);
        }
    }

    public function testEliminarMarca()
    {
        $this->Tmarca->set_id("TEST0012025101912552354");
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Se eliminó la marca exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            if ($resultado['mensaje'] == "Error al eliminar el registro - Marca no encontrada") {
                $this->assertTrue(true, "No se puede eliminar una marca que no existe");
            } else {
                $this->fail('Fallo en Eliminar Marca: ' . $resultado['mensaje']);
            }
        }
    }

    public function testConsultarMarcasEliminadas()
    {
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminados', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testReactivarMarca()
    {
        $this->Tmarca->set_id("TEST0012025101912552354");
        $resultado = $this->Tmarca->Transaccion(['peticion' => 'reactivar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('reactivar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
            $this->assertEquals('Marca reactivada exitosamente', $resultado['mensaje']);
        } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {
            $this->fail('Fallo en Reactivar Marca: ' . $resultado['mensaje']);
        }
    }
}