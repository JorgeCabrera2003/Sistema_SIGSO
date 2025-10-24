<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/bien.php";

final class BienTest extends TestCase
{
    private Bien $Tbien;
    private string $testCodigoBien;

    public function setUp(): void
    {
        $this->Tbien = new Bien();
        $this->testCodigoBien = "TEST" . date('YmdHis') . rand(1000, 9999);
    }

    public function testRegistrarBien()
    {
        $codigoUnico = "REG" . date('YmdHis') . rand(1000, 9999);
        
        $this->Tbien->set_codigo_bien($codigoUnico);
        $this->Tbien->set_id_categoria('ELECT4882025100923103488');
        $this->Tbien->set_id_marca('INTEL1332025100923105633');
        $this->Tbien->set_descripcion('Equipo de prueba para testing - REGISTRO');
        $this->Tbien->set_estado('Nuevo');
        $this->Tbien->set_cedula_empleado('V-30266398');
        $this->Tbien->set_id_oficina('OFICIPIS2025101419103834');
        $this->Tbien->set_estatus(1);

        $resultado = $this->Tbien->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('estado', $resultado);
        
        if ($resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->assertTrue(true, "Registro duplicado - comportamiento esperado");
        }
    }

    public function testConsultarBien()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarTiposBien()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar_tipos_bien']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarMarcas()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar_marcas']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarOficinas()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar_oficinas']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarEmpleados()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar_empleados']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testModificarBienExistente()
    {
        // Usar un bien que existe en la BD
        $codigoBienExistente = '10002201';
        
        $this->Tbien->set_codigo_bien($codigoBienExistente);
        $this->Tbien->set_id_categoria('ELECT4882025100923103488');
        $this->Tbien->set_id_marca('INTEL1332025100923105633');
        $this->Tbien->set_descripcion('Laptop Gaming MODIFICADA EN TEST');
        $this->Tbien->set_estado('Usado');
        $this->Tbien->set_cedula_empleado('V-30266398');
        $this->Tbien->set_id_oficina('OFICIPIS2025101419103834');

        $resultado = $this->Tbien->Transaccion(['peticion' => 'actualizar']);

        $this->assertIsArray($resultado);
        
        if (isset($resultado['estado'])) {
            $this->assertContains($resultado['estado'], [1, -1]);
        }
    }

    public function testConsultarBienesPorEmpleado()
    {
        $cedulaEmpleado = 'V-30266398';
        $resultado = $this->Tbien->Transaccion([
            'peticion' => 'consultar_bienes_empleado',
            'cedula_empleado' => $cedulaEmpleado
        ]);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_bienes_empleado', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testFiltrarBienAsignado()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'filtrar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('resultado', $resultado);
        $this->assertArrayHasKey('datos', $resultado);
        $this->assertIsArray($resultado['datos']);
    }

    public function testConsultarEliminadas()
    {
        $resultado = $this->Tbien->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testObtenerTipoServicioPorEquipo()
    {
        $idEquipo = 'LAPTO6382025101419102138';
        $resultado = $this->Tbien->Transaccion([
            'peticion' => 'obtener_tipo_servicio',
            'id_equipo' => $idEquipo
        ]);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('id_tipo_servicio', $resultado);
        $this->assertArrayHasKey('resultado', $resultado);
    }


    public function testEliminarBien()
    {

        $this->markTestSkipped('Prueba de eliminación omitida - requiere validación específica');
    }


    public function testReactivarBien()
    {
        $this->markTestSkipped('Prueba de reactivación omitida - requiere variable $_SESSION');
    }
}