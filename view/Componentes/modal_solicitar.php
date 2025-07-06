<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-floating mb-3 mt-4">
          <input placeholder="" class="form-control" name="motivo" type="text"
            id="motivo" maxlength="200">
          <span id="smotivo"></span>
          <label for="motivo" class="form-label">Describa su Solicitud</label>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" id="select_equipos" name="id_equipo" style="width:100%">
            <option value="">Seleccione un equipo</option>
          </select>
          <label for="select_equipos" class="form-label">Equipos del Solicitante</label>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="solicitar" name="solicitud" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div>