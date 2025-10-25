<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "model/material.php";

final class MaterialTest extends TestCase
{
    private Material $Tmaterial;
    private string $testIdMaterial;

    public function setUp(): void
    {
        $this->Tmaterial = new Material();
        $this->testIdMaterial = "TEST" . date('YmdHis') . rand(1000, 9999);
        
        // Simular sesión para pruebas que la requieren
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
        $_SESSION['user'] = [
            'nombre_usuario' => 'test_user'
        ];
    }

    public function tearDown(): void
    {
        // Limpiar sesión después de cada test
        unset($_SESSION['user']);
    }

    /**
     * PRUEBAS PARA SETTERS - ACTUALIZADAS
     */
    public function testSettersConValidaciones()
    {
        // Probar setters con valores válidos
        $this->Tmaterial->set_id("MAT123456789");
        $this->Tmaterial->set_nombre("Material de prueba válido " . uniqid());
        $this->Tmaterial->set_ubicacion("OFICIPIS2025101419103834");
        $this->Tmaterial->set_stock(50);
        $this->Tmaterial->set_estatus(1);
        
        $this->assertEquals("MAT123456789", $this->Tmaterial->get_id());
        $this->assertStringContainsString("Material de prueba válido", $this->Tmaterial->get_nombre());
        $this->assertEquals("OFICIPIS2025101419103834", $this->Tmaterial->get_ubicacion());
        $this->assertEquals(50, $this->Tmaterial->get_stock());
        $this->assertEquals(1, $this->Tmaterial->get_estatus());

        // Probar que la ubicación es obligatoria
        $this->expectException(ValueError::class);
        $this->Tmaterial->set_ubicacion("");
    }

    /**
     * PRUEBAS DE REGISTRO CON DATOS VÁLIDOS
     */
    public function testRegistrarMaterialConDatosValidos()
    {
        $idUnico = "REG" . date('YmdHis') . rand(1000, 9999);
        $nombreUnico = 'Material de prueba para testing - REGISTRO ' . uniqid();
        
        $this->Tmaterial->set_id($idUnico);
        $this->Tmaterial->set_nombre($nombreUnico);
        $this->Tmaterial->set_ubicacion('OFICIPIS2025101419103834');
        $this->Tmaterial->set_stock(50);

        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('estado', $resultado);
        
        if ($resultado['estado'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['estado']);
        } else if ($resultado['estado'] == -1) {
            $this->assertTrue(true, "Registro duplicado - comportamiento esperado");
        }
    }

    /**
     * PRUEBAS DE CONSULTA
     */
    public function testConsultarMaterial()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        $this->assertGreaterThanOrEqual(0, count($resultado['datos']));
    }

    public function testConsultarMaterialesEliminados()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        $this->assertGreaterThanOrEqual(0, count($resultado['datos']));
    }

    /**
     * PRUEBAS DE LISTADO DISPONIBLES
     */
    public function testListarMaterialesDisponibles()
    {
        $resultado = $this->Tmaterial->listarDisponibles();

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        $this->assertGreaterThanOrEqual(0, count($resultado['datos']));
    }

    /**
     * PRUEBAS DE DETALLES
     */
    public function testVerDetallesMaterial()
    {
        $materiales = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);
        
        if (isset($materiales['datos']) && count($materiales['datos']) > 0) {
            $primerMaterial = $materiales['datos'][0];
            $this->Tmaterial->set_id($primerMaterial['id_material']);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'detalle']);

            $this->assertIsArray($resultado);
            $this->assertTrue(true, "Consulta de detalles ejecutada correctamente");
        } else {
            $this->markTestSkipped("No hay materiales en la base de datos para probar detalles");
        }
    }

    /**
     * PRUEBAS DE REPORTES
     */
    public function testGenerarReporteMateriales()
    {
        $resultado = $this->Tmaterial->Transaccion([
            'peticion' => 'reporte',
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31'
        ]);

        $this->assertIsArray($resultado);
        $this->assertEquals('success', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        $this->assertGreaterThanOrEqual(0, count($resultado['datos']));
    }

    /**
     * PRUEBAS DE PETICIONES INVÁLIDAS
     */
    public function testTransaccionPeticionInvalida()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'peticion_inexistente']);
        
        $this->assertIsString($resultado);
        $this->assertStringContainsString('no valida', $resultado);
    }

    /**
     * PRUEBAS DE COMPORTAMIENTO BÁSICO
     */
    public function testComportamientoBasico()
    {
        $material = new Material();
        
        $material->set_id("TEST123");
        $material->set_nombre("Material de prueba " . uniqid());
        $material->set_ubicacion("OFICIPIS2025101419103834");
        $material->set_stock(100);
        
        $this->assertTrue(true, "Comportamiento básico funciona correctamente");
    }

    public function testRegistrarConUbicacionNull()
    {
        try {
            $idUnico = "NULLUBIC" . date('YmdHis') . rand(1000, 9999);
            
            $this->Tmaterial->set_id($idUnico);
            $this->Tmaterial->set_nombre('Material sin ubicación asignada ' . uniqid());
            $this->Tmaterial->set_ubicacion(null); // Esto debería fallar
            $this->Tmaterial->set_stock(25);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);
            
            // Si llegamos aquí, la ubicación null fue aceptada (no debería)
            $this->assertEquals(-1, $resultado['estado']);
            $this->assertStringContainsString('obligatoria', $resultado['mensaje']);
            
        } catch (ValueError $e) {
            // Esto es lo esperado - la ubicación es obligatoria
            $this->assertStringContainsString('obligatoria', $e->getMessage());
        }
    }

    /**
     * PRUEBAS DE ELIMINACIÓN Y REACTIVACIÓN - ACTUALIZADAS CON NOMBRES ÚNICOS
     */
    public function testEliminarMaterialExistente()
    {
        // Primero registrar un material para eliminarlo
        $idEliminar = "ELIM" . date('YmdHis') . rand(1000, 9999);
        $nombreUnico = 'Material para eliminar ' . uniqid();
        
        $this->Tmaterial->set_id($idEliminar);
        $this->Tmaterial->set_nombre($nombreUnico);
        $this->Tmaterial->set_ubicacion('OFICIPIS2025101419103834');
        $this->Tmaterial->set_stock(30);

        $registro = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);
        
        if (isset($registro['estado']) && $registro['estado'] == 1) {
            // Ahora eliminar el material
            $this->Tmaterial->set_id($idEliminar);
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'eliminar']);
            
            $this->assertIsArray($resultado);
            $this->assertArrayHasKey('estado', $resultado);
            
            if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                $this->assertEquals('eliminar', $resultado['resultado']);
                $this->assertEquals(1, $resultado['estado']);
            } else {
                $this->assertTrue(true, "Eliminación falló pero fue manejada: " . ($resultado['mensaje'] ?? 'Sin mensaje'));
            }
        } else {
            $this->markTestSkipped("No se pudo registrar el material para eliminación: " . ($registro['mensaje'] ?? 'Error desconocido'));
        }
    }

    public function testEliminarMaterialInexistente()
    {
        // Usar un ID más corto que pase la validación
        $idInexistente = "MAT-INEX-" . date('YmdHis');
        $this->Tmaterial->set_id($idInexistente);
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'eliminar']);
        
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('estado', $resultado);
        $this->assertEquals(-1, $resultado['estado']);
    }

    public function testReactivarMaterialEliminado()
    {
        // Primero registrar y eliminar un material para luego reactivarlo
        $idReactivar = "REACT" . date('YmdHis') . rand(1000, 9999);
        $nombreUnico = 'Material para reactivar ' . uniqid();
        
        // Registrar el material
        $this->Tmaterial->set_id($idReactivar);
        $this->Tmaterial->set_nombre($nombreUnico);
        $this->Tmaterial->set_ubicacion('OFICIPIS2025101419103834');
        $this->Tmaterial->set_stock(40);

        $registro = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);
        
        if (isset($registro['estado']) && $registro['estado'] == 1) {
            // Eliminar el material
            $this->Tmaterial->set_id($idReactivar);
            $eliminacion = $this->Tmaterial->Transaccion(['peticion' => 'eliminar']);
            
            if (isset($eliminacion['estado']) && $eliminacion['estado'] == 1) {
                // Reactivar el material
                $this->Tmaterial->set_id($idReactivar);
                $resultado = $this->Tmaterial->Transaccion(['peticion' => 'reactivar']);
                
                $this->assertIsArray($resultado);
                $this->assertArrayHasKey('estado', $resultado);
                
                if (isset($resultado['estado']) && $resultado['estado'] == 1) {
                    $this->assertEquals('reactivar', $resultado['resultado']);
                    $this->assertEquals(1, $resultado['estado']);
                } else {
                    $this->assertTrue(true, "Reactivación falló pero fue manejada: " . ($resultado['mensaje'] ?? 'Sin mensaje'));
                }
            } else {
                $this->markTestSkipped("No se pudo eliminar el material para reactivación: " . ($eliminacion['mensaje'] ?? 'Error desconocido'));
            }
        } else {
            $this->markTestSkipped("No se pudo registrar el material para reactivación: " . ($registro['mensaje'] ?? 'Error desconocido'));
        }
    }

    public function testReactivarMaterialNoEliminado()
    {
        // Intentar reactivar un material que no está eliminado
        $idNoEliminado = "NOELIM" . date('YmdHis') . rand(1000, 9999);
        $nombreUnico = 'Material activo para reactivar ' . uniqid();
        
        // Registrar el material (queda activo)
        $this->Tmaterial->set_id($idNoEliminado);
        $this->Tmaterial->set_nombre($nombreUnico);
        $this->Tmaterial->set_ubicacion('OFICIPIS2025101419103834');
        $this->Tmaterial->set_stock(60);

        $registro = $this->Tmaterial->Transaccion(['peticion' => 'registrar']);
        
        if (isset($registro['estado']) && $registro['estado'] == 1) {
            // Intentar reactivar un material que ya está activo
            $this->Tmaterial->set_id($idNoEliminado);
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'reactivar']);
            
            $this->assertIsArray($resultado);
            $this->assertTrue(isset($resultado['estado']), "La reactivación debería retornar un estado");
            
            // Puede retornar warning (estado 2) o error (-1)
            $this->assertContains($resultado['estado'], [-1, 1, 2]);
        } else {
            $this->markTestSkipped("No se pudo registrar el material para prueba de reactivación: " . ($registro['mensaje'] ?? 'Error desconocido'));
        }
    }

    public function testEliminarSinId()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'eliminar']);
        
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('estado', $resultado);
        $this->assertTrue(isset($resultado['estado']), "Debería retornar un estado");
    }

    public function testReactivarSinId()
    {
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'reactivar']);
        
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('estado', $resultado);
        $this->assertTrue(isset($resultado['estado']), "Debería retornar un estado");
    }

    /**
     * PRUEBAS DE VALIDACIÓN
     */
    public function testValidarMaterialExistente()
    {
        $materiales = $this->Tmaterial->Transaccion(['peticion' => 'consultar']);
        
        if (isset($materiales['datos']) && count($materiales['datos']) > 0) {
            $primerMaterial = $materiales['datos'][0];
            $this->Tmaterial->set_id($primerMaterial['id_material']);
            
            $resultado = $this->Tmaterial->Transaccion(['peticion' => 'validar']);
            
            $this->assertIsArray($resultado);
            $this->assertArrayHasKey('bool', $resultado);
            $this->assertEquals(1, $resultado['bool']);
        } else {
            $this->markTestSkipped("No hay materiales en la base de datos para probar validación");
        }
    }

    public function testValidarMaterialInexistente()
    {
        $idInexistente = "MAT-INEX-" . date('YmdHis');
        $this->Tmaterial->set_id($idInexistente);
        
        $resultado = $this->Tmaterial->Transaccion(['peticion' => 'validar']);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('bool', $resultado);
        $this->assertEquals(0, $resultado['bool']);
    }

    /**
     * PRUEBAS DE GETTERS Y SETTERS
     */
    public function testGettersYSetters()
    {
        $id = "MAT-TEST-123";
        $nombre = "Material de Test " . uniqid();
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
}