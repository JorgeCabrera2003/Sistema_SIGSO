<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close" title="Cerrar Modal"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1">

        </div>
        <div class="row justify-content-center">

          <div class="col-5 d-none" >
            <div class="form-floating mb-3 ">

              <input placeholder="" class="form-control" name="id_punto_conexion" type="text" id="id_punto_conexion" title="Id del Puerto de  Conexión" >
              <span id="sid_punto_conexion"></span>
              <label for="id_punto_conexion" class="form-label">ID id de Punto de Conexión</label>

            </div>
          </div>

          <div class="col-6" id="">
            <div class="form-floating mb-3">

              <select class="form-select" name="id_equipo" id="id_equipo" title="Seleccionar el Equipo">
                <option selected value="default" disabled>Seleciona un Equipo</option>
                 
                <?php foreach ($equipos as $equipo): ?>
                      <option value="<?= $equipo['id_equipo'] ?>">
                       <?= $equipo['id_equipo'] . ' - ' . $equipo['tipo_equipo']?>
                      </option>
                 <?php endforeach; ?>

              </select>

              <span id="sid_equipo"></span>
              <label for="id_equipo">Equipo</label>
            </div>
          </div>
                

          <div class="col-6" id="">
            <div class="form-floating mb-3">

              <select class="form-select" name="codigo_patch_panel" id="codigo_patch_panel" title="Seleccionar el Patch Panel">
                <option selected value="default" disabled>Seleciona un Patch Panel</option>
                 
                <?php foreach ($patch_panels as $patch_panel): ?>
                      <option value="<?= $patch_panel['codigo_bien'] ?>" data-cantidad="<?= $patch_panel['cantidad_puertos'] ?>">
    <?= $patch_panel['codigo_bien'] . ' - ' . $patch_panel['cantidad_puertos'] . ' Puertos - ' . $patch_panel['tipo_patch_panel']?>
</option>
                 <?php endforeach; ?>

              </select>

              <span id="scodigo_patch_panel"></span>
              <label for="codigo_patch_panel">Patch Panel</label>
            </div>
          </div>
          
                  

          <div class="col-6">
            <div class="form-floating mb-3 ">

              <select class="form-select" name="puerto_patch_panel" id="puerto_patch_panel" title="Seleccionar Puerto del Patch Panel">
                <option selected value="default" disabled>Selecciona un Puerto</option>
                <!-- Opciones dinámicas -->
              </select>
              <span id="spuerto_patch_panel"></span>
              <label for="puerto_patch_panel" class="form-label">Nro. de Puerto</label>

            </div>
          </div>

        </div>
        
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" title="Cerrar Modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>