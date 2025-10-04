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
          <div class="col-md-8">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="90">
              <span id="snombre"></span>
              <label for="nombre" class="form-label">Nombre del Ente *</label>
            </div>
          </div>
          
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="responsable" type="text" id="responsable" maxlength="65">
              <span id="sresponsable"></span>
              <label for="responsable" class="form-label">Nombre del Responsable *</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="telefono" type="text" id="telefono" maxlength="14">
              <span id="stelefono"></span>
              <label for="telefono" class="form-label">Teléfono *</label>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-3">
            <div class="form-floating mb-3 mt-4">
              <select class="form-select" name="tipo_ente" id="tipo_ente">
                <option value="default">Seleccione Tipo</option>
                <option value="Interno">Interno</option>
                <option value="Externo">Externo</option>
              </select>
              <span id="stipo_ente"></span>
              <label for="tipo_ente" class="form-label">Tipo de Ente *</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-floating mb-3 mt-4">
              <textarea placeholder="" class="form-control" name="direccion" id="direccion"
                maxlength="200"></textarea>
              <span id="sdireccion"></span>
              <label for="direccion" class="form-label">Dirección del Ente *</label>
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