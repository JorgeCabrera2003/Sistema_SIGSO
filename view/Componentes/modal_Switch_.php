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
          <div class="col-6">
            <div class="mb-3"> <!-- class="form-floating mb-3" -->
              
            <label for="codigo_bien">Código de Bien</label>
              <select class="form-select" name="codigo_bien" id="codigo_bien" title="Seleccionar el Código del Bien del Switch">
                <option selected value="default" disabled>Seleccione un Código de Bien</option>
                <?php foreach ($bien as $bien): ?>
                  <option value="<?= $bien['codigo_bien'] ?>">
                    <?= $bien['codigo_bien'] . " - " .  $bien['descripcion'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span id="scodigo_bien"></span>
              
            </div>
          </div>

          <div class="col-4">
            <div class="form-floating mb-3">
                <select class="form-select" name="cantidad_puertos" id="cantidad_puertos" title="Ingresar Cantidad de Puertos del Switch">

                    <option selected value="default" disabled>Seleccione la Cantidad de Puertos</option>

                    <option value="8">8</option>
                    <option value="10">10</option>
                    <option value="16">16</option>
                    <option value="24">24</option>
                    <option value="28">28</option>
                    <option value="48">48</option>
                    <option value="52">52</option>
                    
                </select>
                <span id="scantidad_puertos"></span>
                <label for="cantidad_puertos" class="form-label">Cantidad de Puertos</label>
            </div>
        </div>

        </div>
        <div class="row justify-content-center">

          <div class="col-8">
            <div class="form-floating mb-3">
              <input placeholder="" class="form-control" name="serial_switch" type="text" id="serial_switch" maxlength="45" title="Ingresar Serial del Switch">
              <span id="sserial_switch"></span>
              <label for="serial_switch" class="form-label">Serial del Switch</label>
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