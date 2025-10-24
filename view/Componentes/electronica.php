
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
 
          <!-- cargo,condicion laboral, Departamento/Dependencia(unidad o area),  -->

          <form method="post" class="row g-3 needs-validation">
            <div class="col col-sm-2">
              <label for="nro_solicitud" class="form-label ">Nro Solicitud</label>
              <input readonly value="<?php echo $datos_hoja["nro"] ?>" class="form-control-plaintext" type="text" id="nro_solicitud">
            </div>
            <div class="col col-sm-2">
              <label for="nro_hoja" class="form-label ">Nro Hoja</label>
              <input readonly value="<?php echo $datos_hoja["hoja"] ?>" class="form-control-plaintext" type="text" name="nro_hoja" id="nro_hoja">
            </div>
            <div class="col col-sm-2">
              <label for="Fecha" class="form-label ">Fecha</label>
              <input readonly type="date" value="<?php echo $datos_hoja["fecha"] ?>" class="form-control-plaintext" type="text" id="Fecha">
            </div>
            <div class="col col-sm-2">
              <label for="equipo" class="form-label ">Solicitante</label>
              <input readonly value="<?php echo $datos_hoja["solicitante"] ?>" class="form-control-plaintext" type="text" id="equipo">
            </div>
            <div class="col col-sm-3">
              <label for="equipo" class="form-label ">Equipo/Marca</label>
              <input readonly value="<?php echo $datos_hoja["tipo"]."/".$datos_hoja["marca"] ?>" class="form-control-plaintext" type="text" id="equipo">
            </div>
            <div class="col col-sm-3">
              <label for="equipo" class="form-label ">Serial/Nro Bien</label>
              <input readonly value="<?php echo $datos_hoja["serial"]."/".$datos_hoja["nro_bien"] ?>" class="form-control-plaintext" type="text" id="equipo">
            </div>
            <div class="col-sm-6">
              <label for="equipo" class="form-label ">Motivo</label>
              <input readonly value="<?php echo $datos_hoja["motivo"] ?>" class="form-control-plaintext" type="text" id="equipo">
            </div>

            <div class="col-12">
              <label for="observacion" class="form-label ">Observación</label>
              <input value="<?php echo $datos_hoja["observacion"] ?>" class="form-control" name="observacion" type="text" id="observacion" maxlength="300">
            </div>

            <div class="row my-4">

              <div>
              
                <a class="nav-link collapsed"data-bs-toggle="collapse" href="#componentes-nav" role="button" aria-expanded="false" aria-controls="componentes-nav">
                  </i><span><h5>Remplazar Componentes</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="componentes-nav" class="nav-content collapse row " data-bs-parent="#sidebar-nav">
                  
                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_resistencia" <?php echo (isset($valores["Cambio_resistencia"]))?"checked":"" ?>>
                    <span>Resistencia</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_condensador" <?php echo (isset($valores["Cambio_condensador"]))?"checked":"" ?>>
                    <span>Condensadores</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_transistores" <?php echo (isset($valores["Cambio_transistores"]))?"checked":"" ?>>
                    <span>Transistores</span>
                  
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_Diodo" <?php echo (isset($valores["Cambio_Diodo"]))?"checked":"" ?>>
                    <span>Diodo</span>
                    
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_fusible" <?php echo (isset($valores["Cambio_fusible"]))?"checked":"" ?>>
                    <span>Fusible</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_integrados" <?php echo (isset($valores["Cambio_integrados"]))?"checked":"" ?>>
                    <span>Integrados</span>
                  </div>
                  
                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_bobinas" <?php echo (isset($valores["Cambio_bobinas"]))?"checked":"" ?>>
                    <span>Bobinas</span>
                  </div>
                  
                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_pulsadores" <?php echo (isset($valores["Cambio_pulsadores"]))?"checked":"" ?>>
                    <span>Pulsadores</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_transformadores" <?php echo (isset($valores["Cambio_transformadores"]))?"checked":"" ?>>
                    <span>Transformadores</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_optoacoplador" <?php echo (isset($valores["Cambio_optoacoplador"]))?"checked":"" ?>>
                    <span>Optoacoplador</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_led" <?php echo (isset($valores["Cambio_led"]))?"checked":"" ?>>
                    <span>Led</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_motor" <?php echo (isset($valores["Cambio_motor"]))?"checked":"" ?>>
                    <span>Motor</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_fancooler" <?php echo (isset($valores["Cambio_fancooler"]))?"checked":"" ?>>
                    <span>Fan cooler</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="Cambio_cornetas" <?php echo (isset($valores["Cambio_cornetas"]))?"checked":"" ?>>
                    <span>Cornetas</span>
                  </div>

                  <div class="col-sm-3">
                    <span>Otros</span>
                    <input class="form-control col-sm-5" type="text" name="Cambio_otros" value="<?php echo (isset($valores["Cambio_otros"]))?$valores["Cambio_otros"]:"" ?>" maxlength="50">
                  </div>
                  
                </ul>
              </div>

              <div>
              
                <a class="nav-link collapsed"data-bs-toggle="collapse" href="#servicio-nav" role="button" aria-expanded="false" aria-controls="servicio-nav">
                  </i><span><h5>Servicio Prestado</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="servicio-nav" class="nav-content collapse row " data-bs-parent="#sidebar-nav">
                  
                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="servicio_revición" <?php echo (isset($valores["servicio_revición"]))?"checked":"" ?>>
                    <span>Revición</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="servicio_mantenimiento_preventivo" <?php echo (isset($valores["servicio_mantenimiento_preventivo"]))?"checked":"" ?>>
                    <span>Mantenimiento Preventivo</span>
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="servicio_mantenimiento_correctivo" <?php echo (isset($valores["servicio_mantenimiento_correctivo"]))?"checked":"" ?>>
                    <span>Mantenimiento Correctivo</span>
                  
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="servicio_reparación" <?php echo (isset($valores["servicio_reparación"]))?"checked":"" ?>>
                    <span>Reparacion</span>
                    
                  </div>

                  <div class="col-sm-3">
                    <input type="checkbox" class="form-check-input" name="servicio_instalación_de_equipos" <?php echo (isset($valores["servicio_instalación_de_equipos"]))?"checked":"" ?>>
                    <span>Instalación de equipos</span>
                  </div>

                  <div class="col-sm-3">
                    <span>Otros</span>
                    <input class="form-control col-sm-7" type="text" name="servicio_otro" value="<?php echo (isset($valores["servicio_otro"]))?$valores["servicio_otro"]:"" ?>" maxlength="50">
                  </div>
                  
                </ul>
              </div>

              <div class="col-sm-12">
                  <span><h5>Resultado</h5></span>
                  
                  <div class="col-sm-3">
                    <input required type="radio" class="form-check-input" name="resultado" value="Buen funcionamiento" <?php echo ($datos_hoja["resultado"]=="Buen funcionamiento")?"checked":"" ?>>
                    <span>Buen Funcionamiento</span>
                  </div>

                  <div class="col-sm-3">
                    <input required type="radio" class="form-check-input" name="resultado" value="Operativo" <?php echo ($datos_hoja["resultado"]=="Operativo")?"checked":"" ?>>
                    <span>Operativo</span>
                  </div>

                  <div class="col-sm-3">
                    <input required type="radio" class="form-check-input" name="resultado" value="Sin funcionar" <?php echo ($datos_hoja["resultado"]=="Sin funcionar")?"checked":"" ?>>
                    <span>Sin Funcionar</span>
                  </div>
              </div>

            <div class="row">
            <?php if ($datos_hoja["estatus"]=="A") { ?>
            
            <div class="col-sm-2 mt-sm-5">
              <input type="submit" class="btn btn-success w-100" name="accion" value="Actualizar">
            </div>
            <div class="col-sm-2 mt-sm-5">
              <input type="submit" class="btn btn-danger w-100" required name="accion" value="Finalizar">
            </div>
            <?php } ?>
            <?php if ($datos_hoja["estatus"]!="A") { ?>
            <div class="col-sm-2 mt-sm-5">
              <input type="submit" formtarget="_blank" class="btn btn-primary w-100" name="accion" value="Reporte">
            </div>
            <?php }
             if ($datos_hoja["estatus"]=="A") { ?>
            <div class="col-sm-2 mt-sm-5">
              <input type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#cambioarea" value="Cambiar Area">
            </div>
            <?php } ?>
          <?php if ($datos_hoja["estatus"]=="A" or $datos_hoja["estatus_s"]!="Finalizado") { ?>
          <div class="modal fade" id="cambioarea" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cambio de Area</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row g-3 align-items-center">
                      <div class="col-auto"><label for="nro_hoja" class="form-label ">Nro Hoja</label></div>
                      <div class="col-auto"><input readonly value="<?php echo $datos_hoja["hoja"] ?>" class="form-control-plaintext" type="text" name="nro_hoja" id="nro_hoja"></div>
                    </div>
                    <label for="area" class="form-label">Area</label>
                    <select class="col-sm-3 form-control" placeholder="Selecione area destino" name="area" id="area">
                      <option selecte hidden value="">Seleccione una opcion</option>
                      <?php foreach ($areas as $area) echo '<option value="'.$area["codigo"].'">'.$area["nombre"].'</option>'?>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" name="accion" value="Cambiar">
                  </div>
                </div>
              </div>
            
          </div>
          <?php } ?>
        </div>
      </div>
      </form>
    </div>

  </div>
</section>