<?php
declare(strict_types=1);
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
require_once "model/dependencia.php";



final class DependenciaTest extends TestCase
{
    private Dependencia $Tdependencia;


    #[ExpectException(ValueError::class)]

    public function setUp(): void
    {
        $this->Tdependencia = new Dependencia();
    }

    #[DataProvider('provideDatos')]
    public function testRegistrarDependencia(string $id, string $nombre, string $id_ente): void
    {

        $this->Tdependencia->set_id($id);
        $this->Tdependencia->set_nombre($nombre);
        $this->Tdependencia->set_id_ente($id_ente);
        $resultado = $this->Tdependencia->Transaccion(['peticion' => 'registrar']);

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
            $this->fail('Fallo en Registrar Dependencia');
        }
    }
    public static function provideDatos(): array
    {
        return [
            'caso básico' => ['GUARDPAR2025201014043321', 'Seguridad', 'PARQU0122025201014043321'],
        ];
    }
    public function testConsultarDependencia()
    {

        $resultado = $this->Tdependencia->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarDependencia()
    {

        $this->Tdependencia->set_id("GUARDPAR2025201014043321");
        $this->Tdependencia->set_nombre('Seguridad del Parque');
        $this->Tdependencia->set_id_ente('PARQU0122025201014043321');
        $resultado = $this->Tdependencia->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Modificar Dependencia');
        }
    }

    public function testEliminarDependencia()
    {
        $this->Tdependencia->set_id("GUARDPAR2025201014043321");
        $resultado = $this->Tdependencia->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Eliminar Dependencia');
        }
    }
}



?>