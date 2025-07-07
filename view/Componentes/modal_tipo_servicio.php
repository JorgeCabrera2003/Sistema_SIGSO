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
              <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="200">
              <span id="snombre"></span>
              <label for="nombre" class="form-label">Nombre del Servicio</label>
            </div>
          </div>
          <div class="col-8">
            <div class="form-floating mb-3 mt-4">
              <select class="form-select" name="encargado" id="encargado">
              </select>
              <span id="sencargado"></span>
              <label for="encargado" class="form-label">Seleccione un Encargado</label>
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