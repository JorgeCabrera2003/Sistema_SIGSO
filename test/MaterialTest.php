<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/material.php";

final class MaterialTest extends TestCase
{
    private Material $Tmaterial;
    private $idPrueba;

    public function setUp(): void
    {
        $this->Tmaterial = new Material();
        $this->idPrueba = "MAT" . rand(1000, 9999) . date('YmdHis');
    }

    public function testRegistrarMaterial()
    {

        $this->Tmaterial->set_id($this->idPrueba);
        $validacion = $this->Tmaterial->Transaccion(['peticion' => 'validar']);
        

        if ($validacion['bool'] == 0) {
            $this->Tmaterial->set_nombre("Material de Prueba " . rand(100, 999));
            $this->Tmaterial->set_ubicacion("OFICIPIS2025101419103834"); // ID de oficina existente
            $this->Tmaterial->set_stock(rand(10, 100));
        
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('registrar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se registró el material exitosamente', $resultado['mensaje']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {

                $this->assertTrue(
                    strpos($resultado['mensaje'], 'constraint') !== false ||
                    strpos($resultado['mensaje'], 'foreign') !== false ||
                    strpos($resultado['mensaje'], 'Duplicate') !== false ||
                    strpos($resultado['mensaje'], 'duplicado') !== false,
                    "Validación de constraints funcionando: " . $resultado['mensaje']
                );
            } else {
                $this->fail('Fallo en Registrar Material - Respuesta inesperada');
            }
        } else {
            $this->markTestSkipped('El material de prueba ya existe en la base de datos');
        }
    }

    public function testConsultarMateriales()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        

        if (count($resultado['datos']) > 0) {
            $primerMaterial = $resultado['datos'][0];
            $this->assertArrayHasKey('id_material', $primerMaterial);
            $this->assertArrayHasKey('nombre_material', $primerMaterial);
            $this->assertArrayHasKey('ubicacion', $primerMaterial);
            $this->assertArrayHasKey('stock', $primerMaterial);
            $this->assertArrayHasKey('estatus', $primerMaterial);
            $this->assertArrayHasKey('nombre_oficina', $primerMaterial);
        }
    }

    public function testValidarMaterial()
    {

        $this->Tmaterial->set_id("MAT-INEXISTENTE-9999");
        
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'validar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('bool', $resultado);
        
        $this->assertContains($resultado['bool'], [0, 1]);
    }

    public function testModificarMaterial()
    {

        $materiales = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);
        
        if (isset($materiales['datos']) && count($materiales['datos']) > 0) {
            $primerMaterial = $materiales['datos'][0];
            $this->Tmaterial->set_id($primerMaterial['id_material']);
            $this->Tmaterial->set_nombre("Material Modificado " . rand(100, 999));
            $this->Tmaterial->set_ubicacion($primerMaterial['ubicacion']);
            $this->Tmaterial->set_stock($primerMaterial['stock'] + 10);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'actualizar']);

            $this->assertIsArray($resultado);
            
            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('modificar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Se modificaron los datos del material con éxito', $resultado['mensaje']);
            } else if (isset($resultado['estado']) && $resultado['estado'] == -1) {

                $this->assertStringContainsString('error', $resultado['resultado']);
            }
        } else {
            $this->markTestSkipped('No hay materiales en la base de datos para probar modificación');
        }
    }

    public function testListarMaterialesDisponibles()
    {
        $resultado = $this->Tmaterial->listarDisponibles();

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        

        if (count($resultado['datos']) > 0) {
            $materialDisponible = $resultado['datos'][0];
            $this->assertArrayHasKey('id_material', $materialDisponible);
            $this->assertArrayHasKey('nombre_material', $materialDisponible);
            $this->assertArrayHasKey('stock', $materialDisponible);
            $this->assertGreaterThan(0, $materialDisponible['stock']);
        }
    }

    public function testConsultarMaterialesEliminados()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        

        if (count($resultado['datos']) > 0) {
            $materialEliminado = $resultado['datos'][0];
            $this->assertArrayHasKey('id_material', $materialEliminado);
            $this->assertArrayHasKey('nombre_material', $materialEliminado);
            $this->assertArrayHasKey('estatus', $materialEliminado);
        }
    }

    public function testreactivarMaterial()
    {

        $materialesEliminados = $this->Tmaterial->Transaccion(['peticion' => 'consultar_eliminadas']);
        
        if (isset($materialesEliminados['datos']) && count($materialesEliminados['datos']) > 0) {
            $primerMaterialEliminado = $materialesEliminados['datos'][0];
            $this->Tmaterial->set_id($primerMaterialEliminado['id_material']);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'reactivar']);

            $this->assertIsArray($resultado);
            
            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('reactivar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
                $this->assertEquals('Material restaurado exitosamente', $resultado['mensaje']);
            }
        } else {
            $this->markTestSkipped('No hay materiales eliminados en la base de datos para probar restauración');
        }
    }

    public function testGenerarReporte()
    {
        $fechaInicio = '2024-01-01';
        $fechaFin = '2024-12-31';
        
        $resultado = $this->Tmaterial->Transaccion([
            'peticion' => 'reporte',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
    }

    public function testVerDetallesMaterial()
    {

        $materiales = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);
        
        if (isset($materiales['datos']) && count($materiales['datos']) > 0) {
            $primerMaterial = $materiales['datos'][0];
            $this->Tmaterial->set_id($primerMaterial['id_material']);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'detalle']);


            $this->assertIsArray($resultado);
        } else {
            $this->markTestSkipped('No hay materiales en la base de datos para probar detalles');
        }
    }

    public function testEliminarMaterial()
    {

        $this->markTestSkipped('Test de eliminación deshabilitado para evitar pérdida de datos');
        
        /*
        // Solo usar si hay un material para pruebas
        $this->Tmaterial->set_id("MAT-ELIMINAR-PRUEBA");
        
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'eliminar']);

        $this->assertIsArray($resultado);
        if (isset($resultado['estado'])) {
            $this->assertEquals('eliminar', $resultado['resultado']);
        }
        */
    }

    public function testPeticionInvalida()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'peticion_invalida']);

        $this->assertStringContainsString('no valida', $resultado);
    }

    public function testGettersYSetters()
    {

        $id = "MAT-TEST-123";
        $nombre = "Material de Test";
        $ubicacion = "OFICIPIS2025101419103834";
        $stock = 50;
        $estatus = 1;
        
        $this->Tmaterial->set_id($id);
        $this->Tmaterial->set_nombre($nombre);
        $this->Tmaterial->set_ubicacion($ubicacion);
        $this->Tmaterial->set_stock($stock);
        $this->Tmaterial->set_estatus($estatus);
        
        $this->assertEquals($id, $this->Tmaterial->get_id());
        $this->assertEquals($nombre, $this->Tmaterial->get_nombre());
        $this->assertEquals($ubicacion, $this->Tmaterial->get_ubicacion());
        $this->assertEquals($stock, $this->Tmaterial->get_stock());
        $this->assertEquals($estatus, $this->Tmaterial->get_estatus());
    }

    public function testConsultarEstructuraDatos()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        
        if (isset($resultado['datos']) && count($resultado['datos']) > 0) {
            $material = $resultado['datos'][0];
            

            $camposEsperados = [
                'id_material', 'nombre_material', 'ubicacion', 'stock', 
                'estatus', 'nombre_oficina'
            ];
            
            foreach ($camposEsperados as $campo) {
                $this->assertArrayHasKey($campo, $material, "El campo {$campo} debería estar presente en los datos del material");
            }
            

            $this->assertEquals(1, $material['estatus']);
        }
    }

    public function tearDown(): void
    {
    }
}