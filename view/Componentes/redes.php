
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
            <div class="col-sm-6">
              <label for="equipo" class="form-label ">Motivo</label>
              <input readonly value="<?php echo $datos_hoja["motivo"] ?>" class="form-control-plaintext" type="text" id="equipo">
            </div>

            <div class="col-12">
              <label for="observacion" class="form-label ">Observación</label>
              <input maxlength="200" value="<?php echo $datos_hoja["observacion"] ?>" required class="form-control" name="observacion" type="text" id="observacion">
            </div>

            <div class="row my-4">

              <div>
              
                <a class="nav-link collapsed"data-bs-toggle="collapse" href="#informacion-nav" role="button" aria-expanded="false" aria-controls="informacion-nav">
                  <span><h5 class="col-sm-12">Información</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="informacion-nav" class="nav-content collapse row " data-bs-parent="#sidebar-nav">
                  
                <div class="col-sm-4">
                  <label for="levantamiento" class="form-label">Levantamiento de información</label>
                  <input type="checkbox" class="form-check-input" name="Levantamiento_de_información" <?php echo (isset($valores["Levantamiento_de_información"]))?"checked":"" ?> id="levantamiento">
                </div>

                <div class="col-sm-4">
                  <label for="mantenimiento" class="form-label">Mantenimiento</label>
                  <input type="checkbox" class="form-check-input" name="Mantenimiento" <?php echo (isset($valores["Mantenimiento"]))?"checked":"" ?> id="mantenimiento">
                </div>

                <div class="col-sm-4">
                  <label for="inventario" class="form-label">Inventario de redes</label>
                  <input type="checkbox" class="form-check-input" name="Inventario_de_redes" <?php echo (isset($valores["Inventario_de_redes"]))?"checked":"" ?> id="inventario">
                </div>

                <div class="col-sm-4">
                  <label for="implantacion" class="form-label">implantación de redes</label>
                  <input type="checkbox" class="form-check-input" name="Implantación_de_redes" <?php echo (isset($valores["Implantación_de_redes"]))?"checked":"" ?> id="implantacion">
                </div>

                <div class="col-sm-4">
                  <label for="Asignacion" class="form-label">Asignación IP</label>
                  <input type="checkbox" class="form-check-input" name="Asignación_IP" <?php echo (isset($valores["Asignación_IP"]))?"checked":"" ?> id="Asignacion">
                </div>

                <div class="col-sm-4">
                  <label for="otro" class="form-label">Otros</label>
                  <input class="form-control" type="text" maxlength="50" name="Otro" value="<?php echo (isset($valores["Otro"]))?$valores["Otro"]:"" ?>" id="otro">
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