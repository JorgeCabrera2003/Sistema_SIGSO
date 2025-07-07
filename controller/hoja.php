<?php 
	if (!$_SESSION) {
		echo'<script>window.location="?page=login"</script>';
		$msg["danger"] = "Sesion Finalizada.";
	}
	
	ob_start();

	require_once "model/usuario.php";
	$usuario = new Usuario();
	require_once "model/usuario.php";
	$usuario = new Usuario();
	if(!$usuario->Transaccion([
		'peticion' => 'permiso',
		'user' => $_SESSION['user']['rol'],
		'rol' => ["Super usuario", "Técnico"]
	])) {
		echo '<script>window.location="?page=404"</script>';
	}
		echo'<script>window.location="?page=404"</script>';

	if (is_file("view/".$page.".php")) {
		require_once "model/hoja_servicio.php";
		$hoja=new hoja;
		$hoja->set_cod_hoja($_POST["nro_hoja"]);
		$datos_hoja = $hoja->DatosHoja();

		$hoja->set_nro_solicitud($datos_hoja['nro']);
		$areas = $hoja->AreaDisponible();
		$aux = $hoja->ConsultarDetalles();
		foreach ($aux as $detalle) {
			$valores[$detalle["componente"]]=$detalle["detalle"];
		}

		$titulo = "Hoja de ".$datos_hoja['tipo_s'];
		$css = ["alert"];
		$usuario->set_cedula($_SESSION['user']['cedula']);
		
		$datos = $_SESSION['user'];
		$datos = $datos + $usuario->Transaccion(['peticion' => 'perfil']);
		if ($datos["rol"] == "Técnico") {
			require_once "model/tecnico.php";
			$tec = new tecnico();
			$tec->set_cedula($_SESSION['user']["cedula"]);
			$datos = $datos + $tec->tipo();
		}elseif ($datos["rol"] == "Super usuario") {
			$datos = $datos + ["tipo"=>"super"];
		}

		if (isset($_POST["accion"])) {
			switch ($_POST["accion"]) {
				case 'Actualizar':
					$hoja->set_cod_hoja($_POST["nro_hoja"]);
					$hoja->set_observacion($_POST["observacion"]);
					$hoja->set_resultado($_POST["resultado"]);
					$hoja->ActualizarHoja();
					$excluir=["accion","nro_hoja","observacion","resultado","area"];
					$actualizar=[];
					foreach ($_POST as $indice => $valor) {
						if (!in_array($indice, $excluir)) {
							if ($valor!="") {
								if($valor=="on")
									$valor="";
								$actualizar[] = [$indice,$valor];
							}
						}
					}
					$hoja->set_tipo_servicio($datos_hoja['tipo_s']);
					$hoja->LimpiarDetalles();
					$hoja->set_detalles($actualizar);
					$hoja->llenar_detalles();
					echo'<script>window.location="?page=servicios"</script>';

					break;
				case 'Finalizar':
					$hoja->set_cod_hoja($_POST["nro_hoja"]);
					$hoja->set_observacion($_POST["observacion"]);
					$hoja->set_resultado($_POST["resultado"]);
					$hoja->ActualizarHoja();
					$excluir=["accion","nro_hoja","observacion","resultado","area"];
					$actualizar=[];
					foreach ($_POST as $indice => $valor) {
						if (!in_array($indice, $excluir)) {
							if ($valor!="") {
								if($valor=="on")
									$valor="";
								$actualizar[] = [$indice,$valor];
							}
						}
					}
					$hoja->set_tipo_servicio($datos_hoja['tipo_s']);
					$hoja->LimpiarDetalles();
					$hoja->set_detalles($actualizar);
					$hoja->llenar_detalles();
					$hoja->set_cedula_tecnico($_SESSION['user']['cedula']);
					$hoja->finalizar();
					require_once "model/solicitud.php";
					$soli=new solicitud();
					$soli->set_resultado($_POST["resultado"]);
					$soli->set_nro_solicitud($datos_hoja["nro"]);
					$soli->finalizar();
					echo'<script>window.location="?page=servicios"</script>';
				case 'Reporte':
					require_once "model/reporte.php";
					$reporte = new reporte();
					ob_clean();
					$reporte->hoja_servicio($datos_hoja,$valores);
					break;
				case 'Cambiar':
					$hoja->set_cod_hoja($_POST["nro_hoja"]);
					$hoja->set_observacion($_POST["observacion"]);
					$hoja->set_resultado($_POST["resultado"]);
					$hoja->ActualizarHoja();
					$excluir=["accion","nro_hoja","observacion","resultado","area"];
					$actualizar=[];
					foreach ($_POST as $indice => $valor) {
						if (!in_array($indice, $excluir)) {
							if ($valor!="") {
								if($valor=="on")
									$valor="";
								$actualizar[] = [$indice,$valor];
							}
						}
					}
					$hoja->set_tipo_servicio($datos_hoja['tipo_s']);
					$hoja->LimpiarDetalles();
					$hoja->set_detalles($actualizar);
					$hoja->llenar_detalles();
					ob_clean();
					$hoja->set_cedula_tecnico($_SESSION['user']['cedula']);
					$hoja->finalizar();
					if (isset($_POST["area"])) {				
						$hoja->set_tipo_servicio($_POST["area"]);
						$hoja->nueva_hoja();
						$msg["success"]="Solicitud Procesada";

						echo'<script>window.location="?page=servicios"</script>';
					} else {
						$msg["warning"]="Area no seleccionada";
					}
					break;
			}
		}

		require_once "view/$page.php";
	} else {
		require_once "view/404.php";
	}
 ?>