<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
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
              <input value="<?php echo $datos_hoja["observacion"] ?>" maxlength="200" required class="form-control" name="observacion" type="text" id="observacion">
            </div>

                <div class="row my-4">
                  <div>
                    
                  </div>

                  <div>
                  
                    <a class="nav-link collapsed"data-bs-toggle="collapse" href="#servicios-nav" role="button" aria-expanded="false" aria-controls="servicios-nav">
                      <span><h5 class="col-sm-12">Componentes Internos</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="servicios-nav" class="nav-content collapse row " data-bs-parent="#sidebar-nav">
                      
                      <div class="col-sm-3">
                        <span>Memoria RAM</span>
                        <input class="form-control" maxlength="50" name="componente_RAM" value="<?php echo (isset($valores["componente_RAM"]))?$valores["componente_RAM"]:"" ?>">
                      </div>

                      <div class="col-sm-3">
                        <span>Disco Duro</span>
                        <input class="form-control" maxlength="50" name="componente_disco_duro" value="<?php echo (isset($valores["componente_disco_duro"]))?$valores["componente_disco_duro"]:"" ?>">
                      </div>

                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="componente_tarjeta_madre" <?php echo (isset($valores["componente_tarjeta_madre"]))?"checked":"" ?>>
                        <span>Tarjeta Madre</span>
                      </div>

                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="componente_fuente_de_poder" <?php echo (isset($valores["componente_fuente_de_poder"]))?"checked":"" ?>>
                        <span>Fuente de poder</span>
                      </div>

                      <div class="col-sm-3">
                        <span>Tarjeta de Red</span>
                        <input class="form-control" maxlength="50" name="componente_tarjeta_de_red" value="<?php echo (isset($valores["componente_tarjeta_de_red"]))?$valores["componente_tarjeta_de_red"]:"" ?>">
                      </div>

                      <div class="col-sm-3">
                        <span>Tarjeta de video</span>
                        <input class="form-control" maxlength="50" name="componente_tarjeta_de_video" value="<?php echo (isset($valores["componente_tarjeta_de_video"]))?$valores["componente_tarjeta_de_video"]:"" ?>">
                      </div>
                      
                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="componente_procesador" <?php echo (isset($valores["componente_procesador"]))?"checked":"" ?>>
                        <span>Procesador</span>
                      </div>
                      
                      <div class="col-sm-3">
                        <span>Otro</span>
                        <input class="form-control" maxlength="50" type="text" name="componente_otro" value="<?php echo (isset($valores["componente_otro"]))?$valores["componente_otro"]:"" ?>" maxlength="50">
                      </div>
                      
                    </ul>
                  </div>

                  <div>
                  
                    <a class="nav-link collapsed"data-bs-toggle="collapse" href="#servicio-nav" role="button" aria-expanded="false" aria-controls="servicio-nav">
                      <span><h5 class="col-sm-12">Servicio Prestado</h5></span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="servicio-nav" class="nav-content collapse row w-100" data-bs-parent="#sidebar-nav">
                      
                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="servicio_revisión" <?php echo (isset($valores["servicio_revisión"]))?"checked":"" ?>>
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
                        <input type="checkbox" class="form-check-input" name="servicio_instalación_de_equipo" <?php echo (isset($valores["servicio_instalación_de_equipo"]))?"checked":"" ?>>
                        <span>Instalación de Equipo</span>
                        
                      </div>

                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="servicio_instalación_de_software" <?php echo (isset($valores["servicio_instalación_de_software"]))?"checked":"" ?>>
                        <span>Instalación de software</span>
                      </div>

                      <div class="col-sm-3">
                        <input type="checkbox" class="form-check-input" name="servicio_instalación_de_hardware" <?php echo (isset($valores["servicio_instalación_de_hardware"]))?"checked":"" ?>>
                        <span>Instalacion de Hardware</span>
                      </div>
                      
                      <div class="col-sm-3">
                        <span>Otro</span>
                        <input class="form-control" maxlength="50" type="text" name="servicio_otro" value="<?php echo (isset($valores["servicio_otro"]))?$valores["servicio_otro"]:"" ?>">
                      </div>
                      
                      <div class="col-sm-3">
                        <label class="form-label">Respaldo</label>
                        <div>
                          <input type="radio" class="form-check-input" name="servicio_respaldo" value="Si" id="Respaldos" <?php echo (isset($valores["servicio_respaldo"]) and $valores["servicio_respaldo"]=="Si")?"checked":"" ?>>si
                          <input type="radio" class="form-check-input" name="servicio_respaldo" value="No" id="Respaldon" <?php echo (isset($valores["servicio_respaldo"]) and $valores["servicio_respaldo"]=="No")?"checked":"" ?>>no
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <label class="form-label" for="office">Instalación de Office</label>
                        <select class="form-control" name="servicio_instalación_de_Office" id="office">
                          <option value="">Seleccione la version</option>
                          <option value="2007" <?php echo (isset($valores["servicio_instalación_de_Office"]) and $valores["servicio_instalación_de_Office"]=="2007")?"selected":"" ?>>2007</option>
                          <option value="2010" <?php echo (isset($valores["servicio_instalación_de_Office"]) and $valores["servicio_instalación_de_Office"]=="2010")?"selected":"" ?>>2010</option>
                          <option value="2013" <?php echo (isset($valores["servicio_instalación_de_Office"]) and $valores["servicio_instalación_de_Office"]=="2013")?"selected":"" ?>>2013</option>
                          <option value="2016" <?php echo (isset($valores["servicio_instalación_de_Office"]) and $valores["servicio_instalación_de_Office"]=="2016")?"selected":"" ?>>2016</option>
                          <option value="2019" <?php echo (isset($valores["servicio_instalación_de_Office"]) and $valores["servicio_instalación_de_Office"]=="2019")?"selected":"" ?>>2019</option>
                        </select>
                        <br>
                      </div>

                      <div class="col-sm-4">
                        <label class="form-label" for="navegador">Instalación de Navegador</label>
                        <select class="form-control" name="servicio_instalación_de_navegador" id="navegador">
                          <option value="">Seleccione el navegador</option>
                          <option value="Chrome" <?php echo (isset($valores["servicio_instalación_de_navegador"]) and $valores["servicio_instalación_de_navegador"]=="Chrome")?"selected":"" ?>>Chrome</option>
                          <option value="Edge" <?php echo (isset($valores["servicio_instalación_de_navegador"]) and $valores["servicio_instalación_de_navegador"]=="Edge")?"selected":"" ?>>Edge</option>
                          <option value="Mozilla" <?php echo (isset($valores["servicio_instalación_de_navegador"]) and $valores["servicio_instalación_de_navegador"]=="Mozilla")?"selected":"" ?>>Mozilla</option>
                        </select>
                      </div>

                      <div class="col-sm-4">
                        <label class="form-label" for="sistema">Instalación de Sistema Operativo</label>
                        <select class="form-control" name="servicio_instalación_de_SO" id="sistema">
                          <option value="">Seleccione el Sistema</option>
                          <option value="Windows 7" <?php echo (isset($valores["servicio_instalación_de_SO"]) and $valores["servicio_instalación_de_SO"]=="Windows 7")?"selected":"" ?>>Windows 7</option>
                          <option value="Windows 10" <?php echo (isset($valores["servicio_instalación_de_SO"]) and $valores["servicio_instalación_de_SO"]=="Windows 10")?"selected":"" ?>>Windows 10</option>
                          <option value="Ubuntu" <?php echo (isset($valores["servicio_instalación_de_SO"]) and $valores["servicio_instalación_de_SO"]=="Ubuntu")?"selected":"" ?>>Ubuntu</option>
                          <option value="Otro" <?php echo (isset($valores["servicio_instalación_de_SO"]) and $valores["servicio_instalación_de_SO"]=="Otro")?"selected":"" ?>>Otro</option>
                        </select>
                      </div>

                      <div class="col-sm-8">
                        <label class="form-label" for="impresora">Instalación de Impresora</label>
                        <div class="input-group">
                          <select class="form-control" name="servicio_instalación_de_impresora" id="impresora">
                            <option value="" >Seleccione el Tipo</option>
                            <option value="Red" <?php echo (isset($valores["servicio_instalación_de_impresora"]) and $valores["servicio_instalación_de_impresora"]=="Red")?"selected":"" ?>>Red</option>
                            <option value="Local" <?php echo (isset($valores["servicio_instalación_de_impresora"]) and $valores["servicio_instalación_de_impresora"]=="Local")?"selected":"" ?>>Local</option>
                          </select>
                          <input class="form-control" maxlength="15" type="text" name="servicio_IP_impresora" placeholder="IP de la impresora" value="<?php echo (isset($valores["servicio_IP_impresora"]))?$valores["servicio_IP_impresora"]:"" ?>">
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <label class="form-label">Versión del sitema</label>
                        <div>
                          <input type="checkbox" class="form-check-input" name="servicio_versión_sistema" value="32 bits" <?php echo (isset($valores["servicio_versión_sistema"]) and $valores["servicio_versión_sistema"]=="32 bits")?"checked":"" ?>>32
                          <input type="checkbox" class="form-check-input" name="servicio_versión_sistema" value="64 bits" <?php echo (isset($valores["servicio_versión_sistema"]) and $valores["servicio_versión_sistema"]=="64 bits")?"checked":"" ?>>64
                        </div>
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