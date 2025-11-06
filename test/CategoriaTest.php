<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/categoria.php";

final class CategoriaTest extends TestCase
{
    private Categoria $Tcategoria;


    #[ExpectException(ValueError::class)]

    public function setUp(): void
    {
        $this->Tcategoria = new Categoria();
    }

    public function testRegistrarCategoria(): void
    {
        $this->Tcategoria->set_id('ELECT9912025201014043321');
        $this->Tcategoria->set_nombre('Electrodomesticos');
        $this->Tcategoria->set_id_servicio(NULL);

        $resultado = $this->Tcategoria->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
            echo "Se Registró correctamente";
        } else if ($resultado['estado'] == -1) {
            if ($resultado['mensaje'] == "Registro duplicado") {
                $this->assertTrue(true, "No permitir registros duplicados");
                echo "Registro duplicado";
            } else {
                $this->assertTrue(false, $resultado['mensaje']);
            }
        } else {
            $this->fail('Fallo en Registrar Categoria');
        }
    }

    public function testConsultarCategoria()
    {

        $resultado = $this->Tcategoria->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarCategoria()
    {

        $this->Tcategoria->set_id('ELECT9912025201014043321');
        $this->Tcategoria->set_nombre('Electrodomestico');
        $this->Tcategoria->set_id_servicio(NULL);
        $resultado = $this->Tcategoria->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('modificar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            if ($resultado['mensaje'] == 'No existe el Ente seleccionado') {
                $this->assertTrue(true, "No existe el Ente seleccionado");
            } else {
                $this->fail('Fallo en Modificar Categoria');
            }
        }
    }

    public function testEliminarCategoria()
    {
        $this->Tcategoria->set_id('ELECT9912025201014043321');
        $resultado = $this->Tcategoria->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertEquals('eliminar', $resultado['resultado']);
            $this->assertEquals('1', $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->fail('Fallo en Eliminar Categoria');
        }
    }
}



?>