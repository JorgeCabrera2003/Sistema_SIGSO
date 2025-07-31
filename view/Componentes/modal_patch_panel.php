<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close" title="Cerrar Modal"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1"></div>
        <div class="row justify-content-center">
          <div class="col-5">
            <div class="mb-3">
              <select class="form-select w-100" name="codigo_bien" id="codigo_bien"
                title="Seleccionar el Código del Bien del Patch Panel" style="min-width: 100%">
                <option selected value="default" disabled>Seleciones un Código de Bien</option>
                <?php foreach ($bien['datos'] as $bien): ?>
                  <option value="<?= $bien['codigo_bien'] ?>">
                    <?= $bien['codigo_bien'] . " - " .  $bien['descripcion'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span id="scodigo_bien"></span>
            </div>
          </div>

          <div class="col-5">
            <div class="form-floating mb-3 ">
              <input placeholder="" class="form-control" name="serial_patch_panel" type="text" id="serial_patch_panel" title="Ingresar Serial del Patch Panel" maxlength="45">
              <span id="sserial_patch_panel"></span>
              <label for="serial_patch_panel" class="form-label">Serial del Patch Panel</label>

            </div>
          </div>

        </div>

        <div class="row justify-content-center">

          <div class="col-5">
            <div class="form-floating mb-3 ">

              <select class="form-select" name="cantidad_puertos" id="cantidad_puertos" title="Ingresar Cantidad de Puertos del Patch Panel">
                <option selected value="default" disabled>Seleccione la Cantidad de Puertos</option>
                <option value="8">8</option>
                <option value="12">12</option>
                <option value="16">16</option>
                <option value="24">24</option>
                <option value="32">32</option>
                <option value="48">48</option>
                <option value="96">96</option>
              </select>
              <span id="scantidad_puertos"></span>
              <label for="cantidad_puertos" class="form-label">Cantidad Puertos</label>

            </div>
          </div>


          <div class="col-6">
            <div class="form-floating mb-3">

              <select class="form-select" name="tipo_patch_panel" id="tipo_patch_panel" maxlength="45" title="Ingresar Tipo de Patch Panel">

                <option selected value="default" disabled>Seleccione el Tipo de Patch Panel</option>

                <option value="Red">Patch Panel de Red</option>
                <option value="Telefonía">Patch Panel de Telefonía</option>

              </select>

              <span id="stipo_patch_panel"></span>
              <label for="tipo_patch_panel" class="form-label">Tipo de Patch Panel</label>

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