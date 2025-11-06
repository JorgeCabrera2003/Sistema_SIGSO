<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once "model/tipo_servicio.php";
final class TipoServicioTest extends TestCase
{
    private TipoServicio $Ttiposervicio;
    public function setUp(): void
    {
        $this->Ttiposervicio = new TipoServicio();
    }

    public function testRegistrar()
    {
        $this->Ttiposervicio->set_codigo('PUBLI6752025102221401345');
        $this->Ttiposervicio->set_nombre('Publicidad');
        $this->Ttiposervicio->set_encargado('V-30266398');

        $resultado = $this->Ttiposervicio->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['estado']) && $resultado['estado'] == 1) {
            $this->assertTrue(true);
            $this->assertEquals('registrar', $resultado['resultado']);
            echo 'Se registró correctamente';
        } else if ($resultado['estado'] == -1) {
            if ($resultado['mensaje'] == 'Registro duplicado') {
                $this->assertTrue(true);
                echo 'Se encontró un registro repetido';
            } else {
                $this->fail('Fallo al realizar Registro');
            }
        } else {
            $this->fail('Fallo al realizar Registro');
        }
    }

   public function testConsulta()
    {
        $resultado = $this->Ttiposervicio->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        var_dump($resultado['datos']);
    }
}

?>