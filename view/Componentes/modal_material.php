<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1">
          <div class="col-8">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="100">
              <span id="snombre"></span>
              <label for="nombre" class="form-label">Nombre del Material</label>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <select class="form-select" name="ubicacion" id="ubicacion">
                <option value="default" selected disabled>Seleccione una ubicación</option>
                <?php foreach($oficinas as $oficina): ?>
                  <option value="<?= $oficina['id_oficina'] ?>"><?= $oficina['nombre_oficina'] ?></option>
                <?php endforeach; ?>
              </select>
              <span id="subicacion"></span>
              <label for="ubicacion" class="form-label">Ubicación</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="stock" type="number" id="stock" min="0" max="999999" inputmode="numeric" pattern="\d{1,6}" maxlength="6">
              <span id="sstock"></span>
              <label for="stock" class="form-label">Stock</label>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>