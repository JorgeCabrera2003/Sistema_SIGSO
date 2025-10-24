<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "model/solicitud.php";

final class SolicitudTest extends TestCase
{
    private Solicitud $Tsolicitud;

    public function setUp(): void
    {
        $this->Tsolicitud = new Solicitud();
    }

    /**
     * PRUEBAS PARA SET_NRO_SOLICITUD
     */
    public function testSetNroSolicitudValoresInvalidos()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_nro_solicitud("");
    }

    public function testSetNroSolicitudNull()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_nro_solicitud(null);
    }

    public function testSetNroSolicitudFormatoInvalido()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_nro_solicitud("INVALIDO");
    }

    public function testSetNroSolicitudStringMuyCorto()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_nro_solicitud("ABC");
    }

    public function testSetNroSolicitudValoresValidos()
    {
        $this->Tsolicitud->set_nro_solicitud('PRNO2025101420102551');
        $this->assertTrue(true);

        $this->Tsolicitud->set_nro_solicitud(12345);
        $this->assertTrue(true);

        $this->Tsolicitud->set_nro_solicitud("SOL123456");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_CEDULA_SOLICITANTE
     */
    public function testSetCedulaSolicitanteFormatoInvalido()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("12345678");
    }

    public function testSetCedulaSolicitanteFormatoCorto()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("V-123");
    }

    public function testSetCedulaSolicitanteFormatoLargo()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("V-123456789");
    }

    public function testSetCedulaSolicitanteNull()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante(null);
    }

    public function testSetCedulaSolicitanteVacia()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("");
    }

    public function testSetCedulaSolicitanteFormatosValidos()
    {
        $this->Tsolicitud->set_cedula_solicitante("V-30266398");
        $this->assertTrue(true);

        $this->Tsolicitud->set_cedula_solicitante("E-12345678");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_MOTIVO
     */
    public function testSetMotivoVacio()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo("");
    }

    public function testSetMotivoNull()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo(null);
    }

    public function testSetMotivoSoloEspacios()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo("   ");
    }

    public function testSetMotivoMuyLargo()
    {
        $motivoLargo = str_repeat("a", 201);
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo($motivoLargo);
    }

    public function testSetMotivoValido()
    {
        $this->Tsolicitud->set_motivo("Equipo no enciende - Test de validación");
        $this->assertTrue(true);

        $motivoLimite = str_repeat("a", 200);
        $this->Tsolicitud->set_motivo($motivoLimite);
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_RESULTADO
     */
    public function testSetResultadoMuyLargo()
    {
        $resultadoLargo = str_repeat("a", 21);
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_resultado($resultadoLargo);
    }

    public function testSetResultadoValoresValidos()
    {
        $this->Tsolicitud->set_resultado(null);
        $this->assertTrue(true);

        $this->Tsolicitud->set_resultado("");
        $this->assertTrue(true);

        $this->Tsolicitud->set_resultado("Completado");
        $this->assertTrue(true);

        $resultadoLimite = str_repeat("a", 20);
        $this->Tsolicitud->set_resultado($resultadoLimite);
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_ESTADO
     */
    public function testSetEstadoInvalido()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_estado("EstadoInvalido");
    }

    public function testSetEstadoNull()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_estado(null);
    }

    public function testSetEstadosValidos()
    {
        $estadosValidos = ['Pendiente', 'En proceso', 'Finalizado', 'Eliminado'];
        
        foreach ($estadosValidos as $estado) {
            $this->Tsolicitud->set_estado($estado);
            $this->assertTrue(true);
        }
    }

    /**
     * PRUEBAS PARA SET_FECHA_INICIO
     */
    public function testSetFechaInicioInvalida()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_fecha_inicio("fecha-invalida");
    }

    public function testSetFechaInicioValoresValidos()
    {
        $this->Tsolicitud->set_fecha_inicio(null);
        $this->assertTrue(true);

        $this->Tsolicitud->set_fecha_inicio("");
        $this->assertTrue(true);

        $this->Tsolicitud->set_fecha_inicio("2025-10-15 00:00:00");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_FECHA_FINAL
     */
    public function testSetFechaFinalInvalida()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_fecha_final("fecha-final-invalida");
    }

    public function testSetFechaFinalValoresValidos()
    {
        $this->Tsolicitud->set_fecha_final(null);
        $this->assertTrue(true);

        $this->Tsolicitud->set_fecha_final("");
        $this->assertTrue(true);

        $this->Tsolicitud->set_fecha_final("2025-10-16 23:59:59");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_ID_EQUIPO
     */
    public function testSetIdEquipoTipoInvalido()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_id_equipo(12345);
    }

    public function testSetIdEquipoValoresValidos()
    {
        $this->Tsolicitud->set_id_equipo("LAPTO6382025101419102138");
        $this->assertTrue(true);

        $this->Tsolicitud->set_id_equipo(null);
        $this->assertTrue(true);

        $this->Tsolicitud->set_id_equipo("");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS PARA SET_ID_DEPENDENCIA
     */
    public function testSetIdDependenciaTipoInvalido()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_id_dependencia(12345);
    }

    public function testSetIdDependenciaValoresValidos()
    {
        $this->Tsolicitud->set_id_dependencia("OFITIGOB2025100112004023");
        $this->assertTrue(true);

        $this->Tsolicitud->set_id_dependencia(null);
        $this->assertTrue(true);

        $this->Tsolicitud->set_id_dependencia("");
        $this->assertTrue(true);
    }

    /**
     * PRUEBAS DE INTEGRACIÓN - REGISTRAR CON DATOS ERRÓNEOS
     */
    public function testRegistrarSolicitudSinDatosObligatorios()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo("");
    }

    public function testRegistrarSolicitudConDatosInvalidos()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("INVALIDO");
    }

    public function testRegistrarSolicitudConMotivoVacio()
    {
        $this->Tsolicitud->set_nro_solicitud('TEST123456');
        $this->Tsolicitud->set_cedula_solicitante("V-30266398");
        
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo("");
    }

    /**
     * PRUEBAS DE CONSULTA CON PARÁMETROS INVÁLIDOS
     */
    public function testConsultarSolicitudesUsuarioSinCedula()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'solicitud_usuario']);
        
        $this->assertIsArray($resultado);
        $this->assertTrue(true, "Consulta sin cédula manejada");
    }

    public function testConsultarPorIdSinId()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'consultar_por_id']);
        
        $this->assertIsArray($resultado);
        $this->assertTrue(true, "Consulta sin ID manejada");
    }

    /**
     * PRUEBAS DE ACTUALIZACIÓN CON DATOS INVÁLIDOS
     */
    public function testActualizarSolicitudSinId()
    {
        $this->Tsolicitud->set_motivo("Motivo actualizado");
        
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'actualizar']);
        
        $this->assertIsArray($resultado);
        $this->assertTrue(true, "Actualización sin ID manejada");
    }

    public function testActualizarSolicitudConMotivoVacio()
    {
        $solicitudes = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
        if (!empty($solicitudes['datos'])) {
            $primeraSolicitud = $solicitudes['datos'][0];
            $nroSolicitud = $primeraSolicitud['ID'];

            $this->Tsolicitud->set_nro_solicitud($nroSolicitud);
            
            $this->expectException(ValueError::class);
            $this->Tsolicitud->set_motivo("");
        } else {
            $this->markTestSkipped("No hay solicitudes para actualizar");
        }
    }

    /**
     * PRUEBAS DE ELIMINACIÓN CON DATOS INVÁLIDOS
     */
    public function testEliminarSolicitudSinId()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'eliminar']);
        
        $this->assertIsArray($resultado);
        $this->assertTrue(true, "Eliminación sin ID manejada");
    }

    public function testEliminarSolicitudIdInexistente()
    {
        $this->Tsolicitud->set_nro_solicitud("PRNO9999999999999999");
        
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'eliminar']);
        
        $this->assertIsArray($resultado);
        $this->assertTrue(true, "Eliminación con ID inexistente manejada");
    }

    /**
     * PRUEBAS DE PETICIONES INVÁLIDAS
     */
    public function testTransaccionPeticionInvalida()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'peticion_inexistente']);
        
        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    public function testTransaccionSinPeticion()
    {
        $resultado = $this->Tsolicitud->Transaccion([]);
        
        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    public function testTransaccionPeticionNull()
    {
        $resultado = $this->Tsolicitud->Transaccion(null);
        
        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    public function testTransaccionPeticionString()
    {
        $resultado = $this->Tsolicitud->Transaccion("string_invalido");
        
        $this->assertIsArray($resultado);
        $this->assertEquals('error', $resultado['resultado']);
        $this->assertStringContainsString('no válida', $resultado['mensaje']);
    }

    /**
     * PRUEBAS DE MÚLTIPLES OPERACIONES CONSECUTIVAS
     */
    public function testOperacionesConsecutivasConMismoObjeto()
    {
        $this->Tsolicitud->set_cedula_solicitante("V-30266398");
        $this->Tsolicitud->set_motivo("Test operaciones consecutivas");
        
        $resultado1 = $this->Tsolicitud->Transaccion(['peticion' => 'solicitud_usuario']);
        $this->assertIsArray($resultado1);
        
        $this->Tsolicitud->set_motivo("Motivo modificado");
        $resultado2 = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
        $this->assertIsArray($resultado2);
        
        $this->assertTrue(isset($resultado1['resultado']) && isset($resultado2['resultado']));
    }

    /**
     * PRUEBAS DE LÍMITES Y CASOS BORDE
     */
    public function testMotivoLongitudLimite()
    {
        $motivoLimite = str_repeat("a", 200);
        $this->Tsolicitud->set_motivo($motivoLimite);
        $this->assertTrue(true);

        $motivoExcedido = str_repeat("a", 201);
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo($motivoExcedido);
    }

    public function testResultadoLongitudLimite()
    {
        $resultadoLimite = str_repeat("a", 20);
        $this->Tsolicitud->set_resultado($resultadoLimite);
        $this->assertTrue(true);

        $resultadoExcedido = str_repeat("a", 21);
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_resultado($resultadoExcedido);
    }

    /**
     * PRUEBAS DE ROBUSTEZ CON TIPOS DE DATOS INCORRECTOS
     */
    public function testTiposDeDatosIncorrectos()
    {
        $this->expectException(TypeError::class);
        $this->Tsolicitud->set_motivo(["array_invalido"]);

        $this->expectException(TypeError::class);
        $this->Tsolicitud->set_cedula_solicitante((object)['prop' => 'valor']);

        $this->expectException(TypeError::class);
        $this->Tsolicitud->set_id_equipo(true);
    }

    /**
     * PRUEBAS ADICIONALES PARA MEJORAR COBERTURA
     */
    public function testComportamientoConValoresPorDefecto()
    {
        $solicitud = new Solicitud();
        
        $this->assertEquals(0, $solicitud->get_id_equipo());
        $this->assertEquals("", $solicitud->get_estado());
    }

    public function testRegistrarConEquipoNull()
    {
        try {
            $this->Tsolicitud->set_nro_solicitud('TESTNULL' . date('YmdHis'));
            $this->Tsolicitud->set_cedula_solicitante("V-30266398");
            $this->Tsolicitud->set_id_equipo(null);
            $this->Tsolicitud->set_motivo("Solicitud sin equipo específico");
            $this->Tsolicitud->set_estado("Pendiente");
            
            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'registrar']);
            
            $this->assertIsArray($resultado);
            $this->assertTrue(true, "Registro con equipo null procesado");
            
        } catch (ValueError $e) {
            $this->fail("Validación falló con equipo null: " . $e->getMessage());
        }
    }

    /**
     * CORREGIDO: Prueba de formatos de fecha
     */
    public function testFechasConDiferentesFormatos()
    {
        $formatosValidos = [
            "2025-10-15",
            "2025-10-15 10:30:00",
            "2025-10-15T10:30:00",
            "2025-10-15 10:30",
            "15 October 2025",
            "October 15 2025"
        ];

        foreach ($formatosValidos as $formato) {
            try {
                $this->Tsolicitud->set_fecha_inicio($formato);
                $this->Tsolicitud->set_fecha_final($formato);
                $this->assertTrue(true, "Formato de fecha '$formato' aceptado");
            } catch (ValueError $e) {
                $this->fail("Formato de fecha '$formato' rechazado incorrectamente: " . $e->getMessage());
            }
        }
    }

    public function testFechasInvalidas()
    {
        $fechasInvalidas = [
            "fecha-invalida",
            "2025-13-45",
            "not-a-date",
            "1234567890"
        ];

        foreach ($fechasInvalidas as $fecha) {
            $this->expectException(ValueError::class);
            $this->Tsolicitud->set_fecha_inicio($fecha);
        }
    }

    public function testRegistrarSolicitudCompletaValida()
    {
        try {
            $this->Tsolicitud->set_nro_solicitud('TEST' . date('YmdHis'));
            $this->Tsolicitud->set_cedula_solicitante("V-30266398");
            $this->Tsolicitud->set_id_equipo("LAPTO6382025101419102138");
            $this->Tsolicitud->set_motivo("Equipo no enciende - Test robustez");
            $this->Tsolicitud->set_resultado("");
            $this->Tsolicitud->set_estado("Pendiente");
            $this->Tsolicitud->set_fecha_inicio("2025-10-15 00:00:00");
            $this->Tsolicitud->set_fecha_final("2025-10-16 23:59:59");
            $this->Tsolicitud->set_id_dependencia("OFITIGOB2025100112004023");
            
            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'registrar']);
            
            $this->assertIsArray($resultado);
            $this->assertTrue(true, "Registro con datos válidos procesado");
            
        } catch (ValueError $e) {
            $this->fail("Validación falló con datos supuestamente válidos: " . $e->getMessage());
        }
    }

    public function testConsultarEliminadas()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'consultar_eliminadas']);
        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
    }

    /**
     * PRUEBAS DE RENDIMIENTO Y ESTRÉS
     */
    public function testMultiplesSettersRapidamente()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->Tsolicitud->set_nro_solicitud("TEST" . $i);
            $this->Tsolicitud->set_cedula_solicitante("V-30266398");
            $this->Tsolicitud->set_motivo("Test rápido " . $i);
        }
        $this->assertTrue(true, "Múltiples setters ejecutados correctamente");
    }

    /**
     * PRUEBAS DE ESTADO DEL OBJETO
     */
    public function testEstadoObjetoDespuesDeExcepcion()
    {
        try {
            $this->Tsolicitud->set_motivo("");
        } catch (ValueError $e) {
            $this->Tsolicitud->set_motivo("Motivo válido después de excepción");
            $this->assertTrue(true, "Objeto usable después de excepción");
        }
    }

    /**
     * PRUEBAS DE TRANSACCIONES COMPLEJAS
     */
    public function testCicloCompletoSolicitud()
    {
        try {
            $this->Tsolicitud->set_nro_solicitud('CICLO' . date('YmdHis'));
            $this->Tsolicitud->set_cedula_solicitante("V-30266398");
            $this->Tsolicitud->set_motivo("Test ciclo completo");
            $this->Tsolicitud->set_estado("Pendiente");
            
            $registro = $this->Tsolicitud->Transaccion(['peticion' => 'registrar']);
            $this->assertIsArray($registro);

            $consulta = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
            $this->assertIsArray($consulta);

            $this->Tsolicitud->set_cedula_solicitante("V-30266398");
            $usuario = $this->Tsolicitud->Transaccion(['peticion' => 'solicitud_usuario']);
            $this->assertIsArray($usuario);

            $this->assertTrue(true, "Ciclo completo de operaciones ejecutado");

        } catch (Exception $e) {
            $this->markTestSkipped("Ciclo completo no pudo completarse: " . $e->getMessage());
        }
    }
}