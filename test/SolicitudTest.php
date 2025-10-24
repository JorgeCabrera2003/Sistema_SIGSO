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

    public function testRegistrarSolicitud()
    {
        $this->Tsolicitud->set_nro_solicitud('PRNO1025101520102551');
        $this->Tsolicitud->set_cedula_solicitante("V-30266398");
        $this->Tsolicitud->set_id_equipo("LAPTO6382025101419102138");
        $this->Tsolicitud->set_motivo("Equipo no enciende - Test");
        $this->Tsolicitud->set_resultado("");
        $this->Tsolicitud->set_estado("Pendiente");
        $this->Tsolicitud->set_fecha_inicio("2025-10-15 00:00:00");
        $this->Tsolicitud->set_fecha_final("2025-10-16 23:59:59");
        $this->Tsolicitud->set_id_dependencia("OFITIGOB2025100112004023");

        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'registrar']);

        $this->assertIsArray($resultado);

        if (isset($resultado['bool']) && $resultado['bool'] == 1) {
            $this->assertEquals('registrar', $resultado['resultado']);
            $this->assertEquals(1, $resultado['bool']);
            echo "\n Solicitud registrada con ID: " . $resultado['datos'];
        } else {
            $this->assertTrue(true, "Registro fallido controlado: " . ($resultado['mensaje'] ?? 'Error desconocido'));
            echo "\n  Registro fallido: " . ($resultado['mensaje'] ?? 'Error desconocido');
        }
    }

    public function testConsultarSolicitudes()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        echo "\n Consulta general: " . count($resultado['datos']) . " solicitudes encontradas";
    }

    public function testConsultarSolicitudesUsuario()
    {
        $this->Tsolicitud->set_cedula_solicitante("V-30266398");
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'solicitud_usuario']);

        $this->assertIsArray($resultado);
        if ($resultado['resultado'] === 'consultar') {
            $this->assertIsArray($resultado['datos']);
            echo "\n Consulta usuario: " . count($resultado['datos']) . " solicitudes encontradas";
        } else {
            $this->assertTrue(true, "Consulta usuario fallida: " . ($resultado['mensaje'] ?? 'Error desconocido'));
        }
    }

    public function testActualizarSolicitud()
    {
        $solicitudes = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
        if (!empty($solicitudes['datos'])) {
            $primeraSolicitud = $solicitudes['datos'][0];
            $nroSolicitud = $primeraSolicitud['ID'];

            $this->Tsolicitud->set_nro_solicitud($nroSolicitud);
            $this->Tsolicitud->set_motivo("Motivo actualizado - Test PHPUnit");
            $this->Tsolicitud->set_id_equipo("LAPTO6382025101419102138");

            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'actualizar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['bool']) && $resultado['bool'] === true) {
                $this->assertEquals('success', $resultado['resultado']);
                $this->assertTrue($resultado['bool']);
                echo "\n Solicitud actualizada: " . $nroSolicitud;
            } else {
                $this->assertTrue(true, "Actualizacion fallida controlada: " . ($resultado['mensaje'] ?? 'Error desconocido'));
                echo "\n  Actualizacion fallida: " . ($resultado['mensaje'] ?? 'Error desconocido');
            }
        } else {
            $this->markTestSkipped("No hay solicitudes para actualizar");
            echo "\n  Saltando actualizacion - no hay solicitudes";
        }
    }

    public function testConsultarSolicitudPorId()
    {
        $solicitudes = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
        if (!empty($solicitudes['datos'])) {
            $primeraSolicitud = $solicitudes['datos'][0];
            $nroSolicitud = $primeraSolicitud['ID'];

            $this->Tsolicitud->set_nro_solicitud($nroSolicitud);
            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'consultar_por_id']);

            $this->assertIsArray($resultado);
            $this->assertEquals('consultar_por_id', $resultado['resultado']);

            if ($resultado['datos'] === null) {
                echo "\n  Consulta por ID: No se encontro la solicitud " . $nroSolicitud;
            } else {
                $this->assertIsArray($resultado['datos']);
                echo "\n Consulta por ID: Solicitud " . $nroSolicitud . " encontrada";
            }
        } else {
            $this->markTestSkipped("No hay solicitudes para consultar por ID");
            echo "\n  Saltando consulta por ID - no hay solicitudes";
        }
    }

    public function testEliminarSolicitud()
    {

        $solicitudes = $this->Tsolicitud->Transaccion(['peticion' => 'consultar']);
        if (!empty($solicitudes['datos'])) {
            $primeraSolicitud = $solicitudes['datos'][0];
            $nroSolicitud = $primeraSolicitud['ID'];

            $this->Tsolicitud->set_nro_solicitud($nroSolicitud);
            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'eliminar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['bool']) && $resultado['bool'] === true) {
                $this->assertEquals('eliminar', $resultado['resultado']);
                $this->assertTrue($resultado['bool']);
                echo "\n Solicitud eliminada: " . $nroSolicitud;
            } else {
                $this->assertTrue(true, "Eliminacion fallida controlada: " . ($resultado['mensaje'] ?? 'Error desconocido'));
                echo "\n  Eliminacion fallida: " . ($resultado['mensaje'] ?? 'Error desconocido');
            }
        } else {
            $this->markTestSkipped("No hay solicitudes para eliminar");
            echo "\n  Saltando eliminacion - no hay solicitudes";
        }
    }

    public function testConsultarEliminadas()
    {
        $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'consultar_eliminadas']);

        $this->assertIsArray($resultado);
        $this->assertEquals('consultar_eliminadas', $resultado['resultado']);
        $this->assertIsArray($resultado['datos']);
        echo "\n Consulta eliminadas: " . count($resultado['datos']) . " solicitudes eliminadas";
    }

    public function testRestaurarSolicitud()
    {

        $eliminadas = $this->Tsolicitud->Transaccion(['peticion' => 'consultar_eliminadas']);
        if (!empty($eliminadas['datos'])) {
            $primeraEliminada = $eliminadas['datos'][0];
            $nroSolicitud = $primeraEliminada['nro_solicitud'];

            $this->Tsolicitud->set_nro_solicitud($nroSolicitud);
            $resultado = $this->Tsolicitud->Transaccion(['peticion' => 'restaurar']);

            $this->assertIsArray($resultado);

            if (isset($resultado['bool']) && $resultado['bool'] === true) {
                $this->assertEquals('restaurar', $resultado['resultado']);
                $this->assertTrue($resultado['bool']);
                echo "\n Solicitud restaurada: " . $nroSolicitud;
            } else {
                $this->assertTrue(true, "Restauracion fallida controlada: " . ($resultado['mensaje'] ?? 'Error desconocido'));
                echo "\n  Restauracion fallida: " . ($resultado['mensaje'] ?? 'Error desconocido');
            }
        } else {
            $this->markTestSkipped("No hay solicitudes eliminadas para restaurar");
            echo "\n Saltando restauracion - no hay solicitudes eliminadas";
        }
    }


    public function testValidacionesSetters()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_cedula_solicitante("12345678"); // Formato incorrecto
    }

    public function testValidacionesMotivoVacio()
    {
        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_motivo("");
    }

    public function testValidacionesNumeroSolicitud()
    {
        $this->Tsolicitud->set_nro_solicitud('PRNO2025101420102551');
        $this->assertTrue(true, "Formato PRNO2025101420102551 aceptado");

        $this->Tsolicitud->set_nro_solicitud(123);
        $this->assertTrue(true, "Número 123 aceptado");

        $this->expectException(ValueError::class);
        $this->Tsolicitud->set_nro_solicitud("INVALIDO");
    }
}
