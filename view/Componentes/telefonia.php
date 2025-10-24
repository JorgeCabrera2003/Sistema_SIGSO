
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
              <input maxlength="200" value="<?php echo $datos_hoja["observacion"] ?>" class="form-control" name="observacion" type="text" id="observacion">
            </div>

            <div class="row my-4">


              <div>
              
                <a class="nav-link collapsed"data-bs-toggle="collapse" href="#informacion-nav" role="button" aria-expanded="false" aria-controls="informacion-nav">
                  <span><h5 class="col-sm-12">Información</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="informacion-nav" class="nav-content collapse row " data-bs-parent="#sidebar-nav">

                  <div class="col-6">
                    <label class="form-label" for="preventivo">Mantenimiento Preventivo</label>
                    <select class="form-control" name="Mantenimiento_preventivo_de" id="preventivo">
                      <option value="">Selecione la opción</option>
                      <option value="Equipos telefónicos y centrales" <?php echo (isset($valores["Mantenimiento_preventivo_de"]) and $valores["Mantenimiento_preventivo_de"]=="Equipos telefónicos y centrales")?"selected":"" ?>>Equipos telefónicos y centrales</option>
                      <option value="Equipos de telecomunicación" <?php echo (isset($valores["Mantenimiento_preventivo_de"]) and $valores["Mantenimiento_preventivo_de"]=="Equipos de telecomunicación")?"selected":"" ?>>Equipos de telecomunicación</option>
                      <option value="Equipos celulares" <?php echo (isset($valores["Mantenimiento_preventivo_de"]) and $valores["Mantenimiento_preventivo_de"]=="Equipos celulares")?"selected":"" ?>>Equipos celulares</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label class="form-label" for="correctivo">Mantenimiento Correctivo</label>
                    <select class="form-control" name="Mantenimiento_correctivo_de" id="correctivo">
                      <option value="">Selecione la opción</option>
                      <option value="equipos telefónicos" <?php echo (isset($valores["Mantenimiento_correctivo_de"]) and $valores["Mantenimiento_correctivo_de"]=="equipos telefónicos")?"selected":"" ?>>Equipos telefónicos</option>
                      <option value="Equipos de telecomunicación" <?php echo (isset($valores["Mantenimiento_correctivo_de"]) and $valores["Mantenimiento_correctivo_de"]=="Equipos de telecomunicación")?"selected":"" ?>>Equipos de telecomunicación</option>
                      <option value="Equipos celulares" <?php echo (isset($valores["Mantenimiento_correctivo_de"]) and $valores["Mantenimiento_correctivo_de"]=="Equipos celulares")?"selected":"" ?>>Equipos celulares</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label class="form-label" for="programacion">Programación de </label>
                    <select class="form-control" name="Programación_de" id="programacion">
                      <option value="">Selecione la opción</option>
                      <option value="Equipos telefónicos y centrales" <?php echo (isset($valores["Programación_de"]) and $valores["Programación_de"]=="Equipos telefónicos y centrales")?"selected":"" ?>>Equipos telefónicos y centrales</option>
                      <option value="Equipos de telecomunicación" <?php echo (isset($valores["Programación_de"]) and $valores["Programación_de"]=="Equipos de telecomunicación")?"selected":"" ?>>Equipos de telecomunicación</option>
                      <option value="Equipos celulares" <?php echo (isset($valores["Programación_de"]) and $valores["Programación_de"]=="Equipos celulares")?"selected":"" ?>>Equipos celulares</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label class="form-label" for="instalacion">Instalación y activación de:</label>
                    <select class="form-control" name="Instalación_y_activación_de" id="instalacion">
                      <option value="">Selecione la opción</option>
                      <option value="Equipos telefónicos" <?php echo ($valores["Instalación_y_activación_de"]=="Equipos telefónicos")?"selected":"" ?>>Equipos telefónicos</option>
                      <option value="Equipos de telecomunicación" <?php echo ($valores["Instalación_y_activación_de"]=="Equipos de telecomunicación")?"selected":"" ?>>Equipos de telecomunicación</option>
                      <option value="Equipos celulares" <?php echo ($valores["Instalación_y_activación_de"]=="Equipos celulares")?"selected":"" ?>>Equipos celulares</option>
                    </select>
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