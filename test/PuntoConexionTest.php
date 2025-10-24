<?php

use PHPUnit\Framework\TestCase;

require_once "model/punto_conexion.php";

class PuntoConexionTest extends TestCase
{
    private $TpuntoConexion;

    protected function setUp(): void
    {
        $this->TpuntoConexion = new punto_conexion();
    }

    public function testRegistrarPuntoConexion()
    {
        $this->TpuntoConexion->set_id_punto_conexion("PC999");
        $this->TpuntoConexion->set_codigo_patch_panel("PP99");
        $this->TpuntoConexion->set_id_equipo("EQ99");
        $this->TpuntoConexion->set_puerto_patch_panel("99");


        $resultado = $this->TpuntoConexion->Transaccion(["peticion" => "registrar"]);

        $this->assertEquals("registrar", $resultado["resultado"]);
        $this->assertEquals(1, $resultado["estado"]);
    }

    public function testConsultarPuntoConexion()
    {
        $resultado = $this->TpuntoConexion->Transaccion(["peticion" => "consultar"]);

        $this->assertEquals("consultar", $resultado["resultado"]);
        $this->assertIsArray($resultado["datos"]);
    }

    public function testActualizarPuntoConexion()
    {
        $this->TpuntoConexion->set_id_punto_conexion("PC001");
        $this->TpuntoConexion->set_codigo_patch_panel("PP02");
        $this->TpuntoConexion->set_id_equipo("EQ02");
        $this->TpuntoConexion->set_puerto_patch_panel("2");

        $resultado = $this->TpuntoConexion->Transaccion(["peticion" => "actualizar"]);

        $this->assertEquals("modificar", $resultado["resultado"]);
        $this->assertEquals(1, $resultado["estado"]);
    }

    public function testEliminarPuntoConexion()
    {
        $this->TpuntoConexion->set_id_punto_conexion("PC001");

        $resultado = $this->TpuntoConexion->Transaccion(["peticion" => "eliminar"]);

        $this->assertEquals("eliminar", $resultado["resultado"], "Fallo en Eliminar Punto de Conexión");
        $this->assertEquals(1, $resultado["estado"]);
    }
}
