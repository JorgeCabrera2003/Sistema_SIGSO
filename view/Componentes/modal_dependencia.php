<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1"></div>
        <div class="row justify-content-center">
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <select class="form-select" name="ente" id="ente" required>
              </select>
              <span id="sente" class="invalid-feedback"></span>
              <label for="ente" class="form-label">Ente</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="65" 
                     placeholder="Ejemplo: Nombre de la Dependencia" required disabled>
              <span id="snombre" class="invalid-feedback"></span>
              <label for="nombre" class="form-label">Nombre de la Dependencia</label>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary" disabled></button>
      </div>
    </div>
  </div>
</div>